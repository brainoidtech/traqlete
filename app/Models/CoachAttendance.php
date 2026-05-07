<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CoachAttendance extends Model
{
	use HasFactory;

	protected $table = 'coach_attendance';

	protected $fillable = [
		'id',
		'coach_id',
		'dept_id',
		'batch_id',
		'attendance_date',
		'in_time',
		'out_time',
		'status',
		'marked_by',
		'remarks'
	];


	//relations

	public function coach()
	{
		return $this->belongsTo(Coach::class, 'coach_id', 'id');
	}


	// Coach.php

	public function coachAttendance(): HasMany
	{
		$authUser = Auth::user();
		$game     = strtolower($authUser->userDept->dept_name);
		$game     = preg_replace('/[^a-z0-9]/', '_', $game);
		$table    = $game . '_coach_attendance';

		$related = new CoachAttendance();
		$related->setTable($table);

		return new HasMany(
			$related->newQuery(),
			$this,
			$table . '.coach_id',
			'id'
		);
	}

	/**
	 * Returns a model instance scoped to the correct dept table.
	 * e.g. "Badminton" => "badminton_coach_attendance"
	 */
	public static function forDept(?string $deptName = null): static
	{
		$instance = new static();
		$dept = $deptName ?? Auth::user()->userDept->dept_name;
		$instance->table = strtolower(str_replace(' ', '_', $dept)) . '_coach_attendance';
		return $instance;
	}

	/**
	 * Returns a query builder scoped to the correct dept table.
	 * Usage: CoachAttendance::onDept('badminton')->where(...)->get()
	 */
	public static function onDept(?string $deptName = null)
	{
		$instance = static::forDept($deptName);
		return $instance->newQuery();
	}










	// ###############################################################################################################################

	public function getCoachMonthlyAttendance(string $coach_id, int $month, int $year, string $deptName)
	{
		return static::onDept($deptName)
			->where('coach_id', $coach_id)
			->whereMonth('attendance_date', $month)
			->whereYear('attendance_date', $year)
			->orderBy('attendance_date', 'asc')
			->get();
	}

	public function getCoachesAttendByDate($date, $deptId)
	{
		$coaches = Parent::with('coach.latestBatch')
			->where('dept_id', $deptId)
			->where('attendance_date', $date)
			->whereRaw('created_at = (
                    SELECT MAX(pa2.created_at)
                    FROM coach_attendance pa2
                    WHERE pa2.coach_id = coach_attendance.coach_id
                    AND pa2.attendance_date = coach_attendance.attendance_date
                )')
			->orderBy('coach_id', 'asc')
			->get();
		return $coaches;
	}


	public function loadCoachAttendance($from = null, $to = null, $deptId, $coach_id = null)
	{
		$todays_date = Carbon::today();

		if ($coach_id !== null) {

			$coachData = parent::where('coach_id', $coach_id)
				->whereBetween('attendance_date', [$from, $to])
				->where('dept_id', $deptId)
				->orderBy('id', 'desc')
				->get()
				->groupBy('attendance_date');


			$from = Carbon::parse($from)->format('Y-m-d');
			$to = Carbon::parse($to)->format('Y-m-d');

			$period = CarbonPeriod::create($from, $to);

			$coaches = collect();
			foreach ($period as $date) {
				$dateString = $date->format('Y-m-d');

				if (isset($coachData[$dateString])) {
					$coaches->push(...$coachData[$dateString]);
				} else {
					$coaches->push((object)[
						'id' => null,
						'coach_id' => $coach_id,
						'dept_id' => $deptId,
						'attendance_date' => $dateString,
						'in_time' => null,
						'out_time' => null,
					]);
				}
			}
		} else {

			if ($from && $to) {
				$coaches = Parent::with('coach.latestBatch')
					->where('dept_id', $deptId)
					->whereBetween('attendance_date', [$from, $to])
					->whereRaw('created_at = (
                    SELECT MAX(pa2.created_at)
                    FROM coach_attendance pa2
                    WHERE pa2.coach_id = coach_attendance.coach_id
                    AND pa2.attendance_date = coach_attendance.attendance_date
                )')
					->orderBy('coach_id', 'asc')
					->get();
			} else {

				$coaches = parent::with('coach.latestBatch')
					->where('attendance_date', $todays_date)
					->where('dept_id', $deptId)
					->whereRaw('created_at = (
                    SELECT MAX(pa2.created_at)
                    FROM coach_attendance pa2
                    WHERE pa2.coach_id = coach_attendance.coach_id
                    AND pa2.attendance_date = coach_attendance.attendance_date
                        )')->orderBy('player_id', 'asc')->get();
			}
		}

		return $coaches;
	}

	public function getCoachAttendanceById($id, $deptId)
	{
		$todays_date = Carbon::today()->toDateString();


		$data = parent::where('coach_id', $id)
			->where('dept_id', $deptId)
			->where('attendance_date', $todays_date)->get();
		return $data;
	}

	public function storeCoachAtten($data)
	{
		$attend = new CoachAttendance;
		$attend->coach_id = $data['id'];
		$attend->dept_id = $data['dept_id'];
		$attend->in_time = $data['in_time'];
		$attend->out_time = $data['out_time'];
		$attend->attendance_date = $data['date'];
		$attend->marked_by = $data['user_id'];

		$result = $attend->save();
		return $result;
	}

	public function updateCoachAttend($data, $id)
	{
		$attend = self::find($id);
		if (!$attend): return false;
		endif;

		$attend->in_time = $data['in_time'] ?? null;
		$attend->out_time = $data['out_time'] ?? null;
		$attend->save();
	}

	public function updateCoachAttendance($request)
	{
		$authUser = auth()->user();

		$deptName = $authUser->resolveDeptName(); // ✅ FIX

		if (!$deptName) {
			throw new \Exception('Please select a department first.');
		}

		$table = $deptName . '_coach_attendance';

		$attendance = DB::table($table)
			->where('id', $request->coach_attendance_id)
			->first();

		if (!$attendance) {
			return false;
		}

		$updateData = ['updated_at' => now()];

		if ($request->filled('in_time')) {
			$updateData['in_time'] = $request->in_time;
		}

		if ($request->filled('out_time')) {
			$updateData['out_time'] = $request->out_time;
		}

		DB::table($table)
			->where('id', $request->coach_attendance_id)
			->update($updateData);

		return DB::table($table)
			->where('id', $request->coach_attendance_id)
			->first();
	}

	// public function addCoachAttendance($data, $markedby)
	// {
	// 	$user     = auth()->user();
	// 	$deptName = $user->resolveDeptName();

	// 	if (!$deptName) {
	// 		throw new \Exception('Please select a department first.');
	// 	}

	// 	$table = $deptName . '_coach_attendance';

	// 	$attendance = DB::table($table)
	// 		->where('coach_id', $data['coach_id'])
	// 		->where('attendance_date', $data['attendance_date'])
	// 		->first();

	// 	$isInTime  = !empty($data['in_time']);
	// 	$isOutTime = !empty($data['out_time']);

	// 	if (!$isInTime && !$isOutTime) {
	// 		throw new \InvalidArgumentException('Please provide either in_time or out_time.');
	// 	}


	// 	// ── No record yet → INSERT ──────────────────────────────────────────────
	// 	if (!$attendance) {
	// 		DB::table($table)->insert([
	// 			'coach_id'        => $data['coach_id'],
	// 			'dept_id'         => $user->resolveDeptId(),
	// 			'attendance_date' => $data['attendance_date'],
	// 			'in_time'         => $isInTime  ? $data['in_time']  : null,
	// 			'out_time'        => $isOutTime ? $data['out_time'] : null,
	// 			'marked_by'       => $markedby,
	// 			'created_at'      => now(),
	// 			'updated_at'      => now(),
	// 		]);

	// 		return 'created';
	// 	}

	// 	// ── Record exists → UPDATE only provided fields ─────────────────────────
	// 	$updatePayload = ['updated_at' => now()];

	// 	if ($isInTime) {
	// 		$existingOutTime = $attendance->out_time;
	// 		if ($existingOutTime && strtotime($data['in_time']) >= strtotime($existingOutTime)) {
	// 			throw new \InvalidArgumentException(
	// 				'in_time must be before the existing out_time (' . $existingOutTime . ').'
	// 			);
	// 		}
	// 		$updatePayload['in_time'] = $data['in_time'];
	// 	}

	// 	DB::table($table)
	// 		->where('id', $attendance->id)
	// 		->update($updatePayload);

	// 	return 'updated';
	// }

	public function addCoachAttendance($data, $markedby)
	{
		$user     = auth()->user();
		$deptName = $user->resolveDeptName();

		if (!$deptName) {
			throw new \Exception('Please select a department first.');
		}

		$mainTable = $deptName . '_coach_attendance';
		$tempTable = $deptName . '_temp_coach_attendance';

		$coachId = $data['coach_id'];
		$date    = $data['attendance_date'];

		$isInTime  = !empty($data['in_time']);
		$isOutTime = !empty($data['out_time']);

		if (!$isInTime && !$isOutTime) {
			throw new \InvalidArgumentException('Please provide either in_time or out_time.');
		}

		// Only one action allowed
		if ($isInTime && $isOutTime) {
			throw new \InvalidArgumentException('Please send either in_time or out_time only.');
		}

		// Main Attendance Record
		$attendance = DB::table($mainTable)
			->where('coach_id', $coachId)
			->where('attendance_date', $date)
			->first();

		/**
		 * ============================================================
		 * 1. FIRST IN ENTRY → INSERT INTO MAIN TABLE
		 * ============================================================
		 */
		if (!$attendance && $isInTime) {

			DB::table($mainTable)->insert([
				'coach_id'        => $coachId,
				'dept_id'         => $user->resolveDeptId(),
				'attendance_date' => $date,
				'in_time'         => $data['in_time'],
				'out_time'        => null,
				'marked_by'       => $markedby,
				'created_at'      => now(),
				'updated_at'      => now(),
			]);

			return 'First IN inserted in main table';
		}

		/**
		 * ============================================================
		 * 2. FIRST ENTRY CANNOT BE OUT
		 * ============================================================
		 */
		if (!$attendance && $isOutTime) {
			throw new \InvalidArgumentException('First entry must be IN time.');
		}

		/**
		 * ============================================================
		 * 3. OUT ENTRY → STORE IN TEMP TABLE
		 * ============================================================
		 */
		if ($isOutTime) {

			// OUT must be after main IN time
			if (strtotime($data['out_time']) <= strtotime($attendance->in_time)) {
				throw new \InvalidArgumentException(
					'Check-out time must be after check-in time.'
				);
			}

			DB::table($tempTable)->insert([
				'coach_id'        => $coachId,
				'dept_id'         => $user->resolveDeptId(),
				'attendance_date' => $date,
				'in_time'         => null,
				'out_time'        => $data['out_time'],
				'marked_by'       => $markedby,
				'created_at'      => now(),
				'updated_at'      => now(),
			]);

			return 'OUT stored in temp table';
		}

		/**
		 * ============================================================
		 * 4. NEXT IN ENTRY
		 * ============================================================
		 */
		if ($isInTime) {

			// Get Last OUT from temp
			$lastOut = DB::table($tempTable)
				->where('coach_id', $coachId)
				->where('attendance_date', $date)
				->whereNotNull('out_time')
				->orderBy('id', 'desc')
				->first();

			if (!$lastOut) {
				throw new \InvalidArgumentException(
					'Previous OUT missing. New IN not allowed.'
				);
			}

			$lastOutTime = strtotime($lastOut->out_time);
			$currentIn   = strtotime($data['in_time']);

			// IN must be after OUT
			if ($currentIn <= $lastOutTime) {
				throw new \InvalidArgumentException(
					'Check-in time must be after previous OUT time.'
				);
			}

			$minutes = ($currentIn - $lastOutTime) / 60;

			// Minimum 15 min gap
			if ($minutes < 15) {
				throw new \InvalidArgumentException(
					'Invalid entry: The difference between the last OUT and current IN must be at least 15 minutes.'
				);
			}

			// Store IN in temp table
			DB::table($tempTable)->insert([
				'coach_id'        => $coachId,
				'dept_id'         => $user->resolveDeptId(),
				'attendance_date' => $date,
				'in_time'         => $data['in_time'],
				'out_time'        => null,
				'marked_by'       => $markedby,
				'created_at'      => now(),
				'updated_at'      => now(),
			]);

			// Update main table with latest OUT
			DB::table($mainTable)
				->where('id', $attendance->id)
				->update([
					'out_time'   => $lastOut->out_time,
					'updated_at' => now()
				]);

			return 'IN stored in temp and last OUT updated in main';
		}

		return 'No action';
	}
}
