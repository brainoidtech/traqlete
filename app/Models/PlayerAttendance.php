<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PlayerAttendance extends Model
{
	use HasFactory;
	// protected $table = 'player_attendance';

	protected $fillable = [
		'id',
		'player_id',
		'dept_id',
		'batch_id',
		'attendance_date',
		'in_time',
		'out_time',
		'status',
		'marked_by',
		'remarks'
	];
	public function player()
	{
		return $this->belongsTo(Player::class, 'player_id', 'player_id');
	}

	/**
	 * Returns a model instance scoped to the correct dept table.
	 * e.g. "Badminton" => "badminton_player_attendance"
	 */
	public static function forDept(?string $deptName = null): static
	{
		$instance = new static();

		// Use passed deptName, OR safely resolve from auth user
		$dept = $deptName ?? Auth::user()->resolveDeptName();

		if (!$dept) {
			throw new \Exception('Please select a department first.');
		}

		$instance->table = strtolower(str_replace(' ', '_', $dept)) . '_player_attendance';
		return $instance;
	}
	/**
	 * Returns a query builder scoped to the correct dept table.
	 * Usage: PlayerAttendance::onDept('badminton')->where(...)->get()
	 */
	public static function onDept(?string $deptName = null)
	{
		return static::forDept($deptName)->newQuery();
	}


	public static function resolveTable(int $deptId = null): string
	{
		$authUser = Auth::user();
		$dept     = $deptId
			? \App\Models\Department::find($deptId)
			: $authUser->userDept;
		$game = strtolower($dept->dept_name);
		$game = preg_replace('/[^a-z0-9]/', '_', $game);
		return $game . '_player_attendance';
	}

	public function markAttendance($data)
	{
		$user     = Auth::user();
		$deptName = $user->resolveDeptName();
		$deptId   = $user->resolveDeptId();

		if (!$deptName || !$deptId) {
			throw new \Exception('Please select a department first.');
		}

		$attendanceTable = $deptName . '_player_attendance';
		$attendanceDate  = $data->attendance_date ?? now()->toDateString();
		$punchTime       = $data->time ?? now()->format('H:i:s');

		// ── 1. Player must belong to this department ──────────────────────────────
		$playerExists = DB::table('players')
			->where('player_id', $data->player_id)
			->where('dept_id', $deptId)
			->exists();

		if (!$playerExists) {
			throw new \Exception('This player does not belong to the selected department.');
		}

		// ── 2. Only ONE record allowed per player per day ─────────────────────────
		$existing = DB::table($attendanceTable)
			->where('player_id', $data->player_id)
			->whereDate('attendance_date', $attendanceDate)
			->first();

		if ($existing) {
			// Already checked in but not checked out → allow out_time update
			if (is_null($existing->out_time)) {
				throw new \InvalidArgumentException(
					'Check-in already marked at ' . $existing->in_time 
						// '. Please provide out_time to check out.'
				);
			}

			// Already fully marked → block
			throw new \InvalidArgumentException(
				'Attendance already fully marked for this date (' .
					$existing->in_time . ' - ' . $existing->out_time . ').'
			);
		}

		// ── 3. INSERT new attendance record ───────────────────────────────────────
		$attendance = new PlayerAttendance();
		$attendance->setTable($attendanceTable);
		$attendance->player_id       = $data->player_id;
		$attendance->dept_id         = $deptId;
		$attendance->attendance_date = $attendanceDate;
		$attendance->in_time         = $punchTime;
		$attendance->out_time        = null;
		$attendance->status          = 'IN';
		$attendance->marked_by       = $user->id;
		$attendance->save();

		return ['action' => 'IN', 'data' => $attendance];
	}

	public function loadAttendance($request)
	{
		$query = parent::with('player.dept')->orderBy('attendance_date', 'desc');

		if ($request->filled('from') && $request->filled('to')) {
			$query->whereBetween('attendance_date', [
				$request->from,
				$request->to
			]);
		} else {
			$query->limit(5);
		}

		return $query->get();
	}

	// public function updateAttendance($request)
	// {
	// 	$user = auth()->user();
	// 	$department = strtolower($user->userDept->dept_name);
	// 	$table = str_replace(' ', '_', $department) . '_player_attendance';
	// 	$attendance = DB::table($table)
	// 		->where('id', $request->player_attendance_id)
	// 		->first();
	// 	if (!$attendance) {
	// 		return false;
	// 	}
	// 	$updateData = [];
	// 	if ($request->filled('in_time')) {
	// 		$updateData['in_time'] = $request->in_time;
	// 	}
	// 	if ($request->filled('out_time')) {
	// 		$updateData['out_time'] = $request->out_time;
	// 	}
	// 	if (!empty($updateData)) {
	// 		DB::table($table)
	// 			->where('id', $request->player_attendance_id)
	// 			->update($updateData);
	// 	}
	// 	return DB::table($table)
	// 		->where('id', $request->player_attendance_id)
	// 		->first();
	// }

	public function updateAttendance($request)
	{
		$user     = Auth::user();
		$deptName = $user->resolveDeptName();

		if (!$deptName) {
			throw new \Exception('Please select a department first.');
		}

		$table      = $deptName . '_player_attendance';
		$attendance = DB::table($table)
			->where('id', $request->player_attendance_id)
			->first();

		if (!$attendance) return false;

		$updateData = [];
		if ($request->filled('in_time'))  $updateData['in_time']  = $request->in_time;
		if ($request->filled('out_time')) $updateData['out_time'] = $request->out_time;

		if (!empty($updateData)) {
			DB::table($table)
				->where('id', $request->player_attendance_id)
				->update($updateData);
		}

		return DB::table($table)
			->where('id', $request->player_attendance_id)
			->first();
	}



	// ###############################################################################################################################


	public function getPlayersAttendByDate($date, $deptId)
	{

		$players = Parent::with('player.batch.loadCoach')
			->where('dept_id', $deptId)
			->where('attendance_date', $date)
			->whereRaw('created_at = (
                SELECT MAX(pa2.created_at)
                FROM player_attendance pa2
                WHERE pa2.player_id = player_attendance.player_id
                  AND pa2.attendance_date = player_attendance.attendance_date
            )')
			->orderBy('player_id', 'asc')
			->get();

		return $players;
	}


	public function loadPlayerAttendance($from = null, $to = null, $deptId, $player_id = null)
	{
		$todays_date = Carbon::today();

		//for display on player_profile page filter by date range and player id 
		if ($player_id !== null) {
			$playerData = parent::where('player_id', $player_id)
				->where('dept_id', $deptId)
				->whereBetween('attendance_date', [$from, $to])
				->orderBy('id', 'desc')
				->get()
				->groupBy('attendance_date');

			$from = Carbon::parse($from)->format('Y-m-d');
			$to = Carbon::parse($to)->format('Y-m-d');

			$period = CarbonPeriod::create($from, $to);


			$players = collect();
			foreach ($period as $date) {
				$dateString = $date->format('Y-m-d');

				if (isset($playerData[$dateString])) {
					$players->push(...$playerData[$dateString]);
				} else {
					$players->push((object)[
						'id' => null,
						'player_id' => $player_id,
						'dept_id' => $deptId,
						'attendance_date' => $dateString,
						'in_time' => null,
						'out_time' => null,
					]);
				}
			}
		} else {
			if ($from || $to) {
				$players = Parent::with('player.batch.loadCoach')
					->where('dept_id', $deptId)
					->whereBetween('attendance_date', [$from, $to])
					->whereRaw('created_at = (
                SELECT MAX(pa2.created_at)
                FROM player_attendance pa2
                WHERE pa2.player_id = player_attendance.player_id
                  AND pa2.attendance_date = player_attendance.attendance_date
            )')
					->orderBy('player_id', 'asc')
					->get();
			}
			// } else {
			// $players = parent::with('player.batch.loadCoach')
			// ->where('attendance_date', $todays_date)
			// ->where('dept_id', $deptId)
			// ->whereRaw('created_at = (
			//             SELECT MAX(pa2.created_at)
			//             FROM player_attendance pa2
			//             WHERE pa2.player_id = player_attendance.player_id
			//             AND pa2.dept_id = player_attendance.dept_id
			//             AND pa2.attendance_date = player_attendance.attendance_date
			//             )')->orderBy('player_id', 'asc')->get();
			// }
		}






		return $players;
	}

	public function getPlayerAttendanceById($id, $deptId)
	{
		$todays_date = Carbon::today()->toDateString();


		$data = parent::where('player_id', $id)
			->where('dept_id', $deptId)
			->where('attendance_date', $todays_date)->get();
		return $data;
	}



	public function storeAttendance($data)
	{
		$attend = new PlayerAttendance;
		$attend->player_id = $data['id'];
		$attend->dept_id = $data['dept_id'];
		$attend->in_time = $data['in_time'];
		$attend->out_time = $data['out_time'];
		$attend->attendance_date = $data['date'];
		$attend->marked_by = $data['user_id'];

		$result = $attend->save();
		return $result;
	}


	public function updatePlayerAttend($data, $id)
	{
		$attend = self::find($id);
		if (!$attend): return false;
		endif;

		$attend->in_time = $data['in_time'] ?? null;
		$attend->out_time = $data['out_time'] ?? null;
		$attend->save();
	}

	public function getPlayerAttendanceMonthYear(
		string $player_id,
		int    $month,
		int    $year,
		int    $dept_id,
		string $deptName
	) {
		return static::onDept($deptName)
			->where('player_id', $player_id)
			->where('dept_id', $dept_id)
			->whereMonth('attendance_date', $month)
			->whereYear('attendance_date', $year)
			->orderBy('attendance_date', 'asc')
			->get();
	}
}
