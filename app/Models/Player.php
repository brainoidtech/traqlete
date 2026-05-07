<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\{Coach, DepartmentPlayers, FeesValidity};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Player extends Model
{
	use SoftDeletes;

	protected $table = 'players';
	protected $primaryKey = 'player_id';
	public $incrementing = false;
	protected $keyType = 'string';

	protected $fillable = [
		'player_id',
		'first_name',
		'middle_name',
		'last_name',
		'address1',
		'address2',
		'area',
		'city',
		'pincode',
		'dob',
		'age',
		'gender',
		'dept_id',
		'batch_id',
		'assign_coach_id',
		'date_of_enrollment',
		'player_contact_no',
		'player_email',
		'aadhar_number',
		'passport_number',
		'player_image',
		'hoid',
		'school_name',
		'foot',
		'inch',
		'weight',
		'status',
		'created_at',
		'updated_at',
		'deleted_at',
	];

	// =========================================================
	// RELATIONSHIPS
	// =========================================================

	public function coach()
	{
		return $this->belongsTo(Coach::class, 'assign_coach_id', 'id');
	}

	public function batch()
	{
		return $this->belongsTo(Batch::class, 'batch_id', 'id');
	}

	public function dept()
	{
		return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
	}

	public function parents()
	{
		return $this->hasMany(PlayerParent::class, 'player_id', 'player_id');
	}

	public function deptPlayers()
	{
		return $this->hasMany(DepartmentPlayers::class, 'player_id', 'player_id');
	}

	public function player_qr()
	{
		return $this->hasMany(PlayerQrcode::class, 'player_id', 'player_id');
	}

	// =========================================================
	// FIXED — playerAttendance()
	// Uses resolveDeptName() instead of userDept->dept_name
	// =========================================================
	public function playerAttendance(): HasMany
	{
		$user = Auth::user();
		$game = $user->resolveDeptName();

		if (!$game) {
			throw new \Exception('Please select a department first.');
		}

		$table = $game . '_player_attendance';
		$related = new PlayerAttendance();
		$related->setTable($table);

		return new HasMany(
			$related->newQuery(),
			$this,
			$table . '.player_id',
			'player_id'
		);
	}

	// =========================================================
	// FIXED — playerFeesValidity()
	// Uses resolveDeptName() instead of userDept->dept_name
	// =========================================================
	public function playerFeesValidity(): HasMany
	{
		$user = Auth::user();
		$game = $user->resolveDeptName();

		if (!$game) {
			throw new \Exception('Please select a department first.');
		}

		$feeTable = $game . '_fee_validity';
		$deptPlayerIds = $this->deptPlayers()->pluck('id')->toArray();
		$related = new FeesValidity();
		$related->setTable($feeTable);

		return new HasMany(
			$related->newQuery()->whereIn('dept_player_id', $deptPlayerIds),
			$this,
			$feeTable . '.dept_player_id',
			'player_id'
		);
	}

	// =========================================================
	// FIXED — forDept()
	// Uses resolveDeptName() instead of userDept->dept_name
	// =========================================================
	public static function forDept(?string $deptName = null): static
	{
		$instance = new static();
		$dept = $deptName ?? Auth::user()->resolveDeptName();

		if (!$dept) {
			throw new \Exception('Please select a department first.');
		}

		$instance->table = strtolower(str_replace(' ', '_', $dept)) . '_player_attendance';
		return $instance;
	}

	public static function onDept(?string $deptName = null)
	{
		return static::forDept($deptName)->newQuery();
	}

	// =========================================================
	// FIXED — loadPlayer()
	// Uses resolveDeptName() and resolveDeptId()
	// =========================================================
	public function loadPlayer($batch_id = null, $coach_id = null)
	{
		$user = Auth::user();
		$deptId = $user->resolveDeptId();
		$deptName = $user->resolveDeptName();

		if (!$deptId || !$deptName) {
			throw new \Exception('Please select a department first.');
		}

		$query = static::with('parents', 'coach', 'batch', 'dept', 'deptPlayers')
			->where('dept_id', $deptId);
			
		$query = self::with(['deptPlayers'])
			->where('dept_id', $deptId)
			->where('status', 1);

		if (!empty($batch_id)) {
			$query->where('batch_id', $batch_id);
		}

		if (!empty($coach_id)) {
			$query->where('assign_coach_id', $coach_id);
		}

		$players = $query->get();
		$deptPlayerIds = $players
			->pluck('deptPlayers')
			->flatten()
			->pluck('id')
			->toArray();

		if (!empty($deptPlayerIds)) {
			$fees = \App\Models\FeesValidity::onDept($deptName)
				->whereIn('dept_player_id', $deptPlayerIds)
				->orderByDesc('valid_from')
				->get()
				->groupBy('dept_player_id');

			foreach ($players as $player) {
				foreach ($player->deptPlayers as $deptPlayer) {
					$deptPlayer->setRelation(
						'feeValidity',
						$fees->get($deptPlayer->id, collect())
					);
				}
			}
		}

		return $players;
	}

	// =========================================================
	// FIXED — loadPlayerAttendance()
	// Uses resolveDeptName() and resolveDeptId()
	// =========================================================
	// public function loadPlayerAttendance($data)
	// {
	// 	$attendanceDate = !empty($data['date'])
	// 		? $data['date']
	// 		: Carbon::today()->toDateString();

	// 	$user = Auth::user();
	// 	$deptId = $user->resolveDeptId();
	// 	$deptName = $user->resolveDeptName();

	// 	if (!$deptId || !$deptName) {
	// 		throw new \Exception('Please select a department first.');
	// 	}

	// 	$attendanceTable = $deptName . '_player_attendance';
	// 	$feeTable = $deptName . '_fee_validity';

	// 	$query = self::with(['deptPlayers'])
	// 		->where('dept_id', $deptId);

	// 	if (!empty($data['search'])) {
	// 		$search = $data['search'];
	// 		$query->where(function ($q) use ($search) {
	// 			$q->where('player_id', 'like', "%{$search}%")
	// 				->orWhere('first_name', 'like', "%{$search}%")
	// 				->orWhere('last_name', 'like', "%{$search}%");
	// 		});
	// 	}

	// 	if (!empty($data['player_id'])) {
	// 		$query->where('player_id', $data['player_id']);
	// 	}

	// 	if (!empty($data['batches'])) {
	// 		$query->whereHas('deptPlayers', function ($q) use ($data) {
	// 			$q->where('batch_id', $data['batches']);
	// 		});
	// 	}

	// 	if (!empty($data['coach'])) {
	// 		$query->where('assign_coach_id', $data['coach']);
	// 	}

	// 	if (!empty($data['offset'])) {
	// 		$query->offset((int) $data['offset']);
	// 	}

	// 	$players = $query->limit(10)->get();
	// 	$playerIds = $players->pluck('player_id')->toArray();

	// 	$attendanceRecords = DB::table($attendanceTable)
	// 		->whereIn('player_id', $playerIds)
	// 		->where('dept_id', $deptId)
	// 		->whereDate('attendance_date', $attendanceDate)
	// 		->get()
	// 		->groupBy('player_id');

	// 	$deptPlayerIds = $players
	// 		->pluck('deptPlayers')
	// 		->flatten()
	// 		->pluck('id')
	// 		->toArray();

	// 	$feeRecords = collect();
	// 	if (!empty($deptPlayerIds)) {
	// 		$feeRecords = DB::table($feeTable)
	// 			->whereIn('dept_player_id', $deptPlayerIds)
	// 			->orderByDesc('valid_from')
	// 			->get()
	// 			->groupBy('dept_player_id');
	// 	}

	// 	$players->each(function ($player) use ($attendanceRecords, $feeRecords) {
	// 		$player->setRelation(
	// 			'playerAttendance',
	// 			$attendanceRecords->get($player->player_id, collect())
	// 		);
	// 		$player->deptPlayers->each(function ($deptPlayer) use ($feeRecords) {
	// 			$deptPlayer->setRelation(
	// 				'feeValidity',
	// 				$feeRecords->get($deptPlayer->id, collect())
	// 			);
	// 		});
	// 		$player->setAttribute(
	// 			'player_fees_validity',
	// 			$player->deptPlayers->pluck('feeValidity')->flatten()
	// 		);
	// 	});

	// 	return $players;
	// }
	public function loadPlayerAttendance($data)
	{
		$attendanceDate = !empty($data['date'])
			? $data['date']
			: \Carbon\Carbon::today()->toDateString();

		$user = \Auth::user();
		$deptId = $user->resolveDeptId();
		$deptName = $user->resolveDeptName();

		if (!$deptId || !$deptName) {
			throw new \Exception('Please select a department first.');
		}

		// 🔥 Get coach_id automatically
		$coachId = $user->getCoachIdByDept($deptId);

		$attendanceTable = $deptName . '_player_attendance';
		$feeTable = $deptName . '_fee_validity';

		$query = self::with(['deptPlayers'])
			->where('dept_id', $deptId);

		$query = self::with(['deptPlayers'])
			->where('dept_id', $deptId)
			->where('status', 1);

		// 🔍 Search filter
		if (!empty($data['search'])) {
			$search = $data['search'];
			$query->where(function ($q) use ($search) {
				$q->where('player_id', 'like', "%{$search}%")
					->orWhere('first_name', 'like', "%{$search}%")
					->orWhere('last_name', 'like', "%{$search}%");
			});
		}

		// 🎯 Player filter
		if (!empty($data['player_id'])) {
			$query->where('player_id', $data['player_id']);
		}

		// 📦 Batch filter
		if (!empty($data['batches'])) {
			$query->whereHas('deptPlayers', function ($q) use ($data) {
				$q->where('batch_id', $data['batches']);
			});
		}

		// 🔥 IMPORTANT: Always filter by coach
		if ($coachId) {
			$query->where('assign_coach_id', $coachId);
		}

		// 📄 Pagination
		if (!empty($data['offset'])) {
			$query->offset((int) $data['offset']);
		}

		$players = $query->limit(10)->get();
		$playerIds = $players->pluck('player_id')->toArray();

		// 📅 Attendance records
		$attendanceRecords = \DB::table($attendanceTable)
			->whereIn('player_id', $playerIds)
			->where('dept_id', $deptId)
			->whereDate('attendance_date', $attendanceDate)
			->get()
			->groupBy('player_id');

		// 💰 Fee records
		$deptPlayerIds = $players
			->pluck('deptPlayers')
			->flatten()
			->pluck('id')
			->toArray();

		$feeRecords = collect();
		if (!empty($deptPlayerIds)) {
			$feeRecords = \DB::table($feeTable)
				->whereIn('dept_player_id', $deptPlayerIds)
				->orderByDesc('valid_from')
				->get()
				->groupBy('dept_player_id');
		}

		// 🔗 Attach relations
		$players->each(function ($player) use ($attendanceRecords, $feeRecords) {
			$player->setRelation(
				'playerAttendance',
				$attendanceRecords->get($player->player_id, collect())
			);

			$player->deptPlayers->each(function ($deptPlayer) use ($feeRecords) {
				$deptPlayer->setRelation(
					'feeValidity',
					$feeRecords->get($deptPlayer->id, collect())
				);
			});

			$player->setAttribute(
				'player_fees_validity',
				$player->deptPlayers->pluck('feeValidity')->flatten()
			);
		});

		return $players;
	}

	// =========================================================
	// loadPlayerByIds — no userDept used, already safe
	// =========================================================
	public function loadPlayerByIds($data)
	{
		$dept = \App\Models\Department::find($data->dept_id);
		$attendanceTable = null;

		if ($dept) {
			$deptName = strtolower(str_replace(' ', '_', $dept->dept_name));
			$deptName = preg_replace('/[^a-z0-9_]/', '', $deptName);
			$attendanceTable = $deptName . '_player_attendance';
		}

		$player = self::with([
			'parents',
			'coach',
			'batch',
			'dept',
			'deptPlayers' => function ($q) use ($data) {
				$q->where('dept_id', $data->dept_id)
					->orderBy('id', 'desc')
					->with('dept');
			},
		])
			->where('player_id', $data->player_id)
			->first();

		if ($player) {
			$todayCount = 0;
			if ($attendanceTable) {
				$todayCount = DB::table($attendanceTable)
					->where('player_id', $data->player_id)
					->where('dept_id', $data->dept_id)
					->whereDate('attendance_date', Carbon::today())
					->count();
			}
			$player->today_attendance_count = $todayCount;

			$player->deptPlayers->each(function ($deptPlayer) {
				$dept = $deptPlayer->dept;
				if ($dept) {
					$tableName = strtolower(str_replace(' ', '_', $dept->dept_name)) . '_fee_validity';
					$feeValidity = \App\Models\FeesValidity::query()
						->from($tableName)
						->where('dept_player_id', $deptPlayer->id)
						->orderByDesc('id')
						->get();
					$deptPlayer->setRelation('feeValidity', $feeValidity);
				}
			});
		}

		return $player;
	}

	// =========================================================
	// UNCHANGED METHODS — no userDept logic, already safe
	// =========================================================

	public function getLastIdOfPlayer($dept_id)
	{
		return parent::where('dept_id', $dept_id)
			->select('player_id')
			->latest('created_at')
			->first();
	}

	public function loadEditPlayer($id)
	{
		return parent::find($id);
	}

	public function deletePlayer($id)
	{
		return parent::destroy($id);
	}

	public function getDuplicatePlayer($data)
	{
		return parent::where('first_name', $data['first_name'])
			->where('player_email', $data['player_email'])
			->where('dob', $data['dob'])
			->where('dept_id', $data['dept_id'])
			->where('aadhar_number', $data['aadhar_number'])
			->first();
	}

	public function getFullNameAttribute()
	{
		return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
	}

	public function insertPlayer($data)
	{
		$player = new Player;

		$player->aadhar_number = $data['aadhar_number'];
		$player->first_name = $data['first_name'];
		$player->middle_name = $data['middle_name'];
		$player->last_name = $data['last_name'];
		$player->dob = $data['dob'];
		$player->age = $data['age'];
		$player->player_image = $data['player_image'];
		$player->address1 = $data['address1'];
		$player->address2 = $data['address2'];
		$player->area = $data['area'];
		$player->city = $data['city'];
		$player->pincode = $data['pincode'];
		$player->gender = $data['gender'];
		$player->batch_id = $data['batch_id'];
		$player->player_contact_no = $data['player_contact_no'];
		$player->player_email = $data['player_email'];
		$player->player_id = $data['player_id'];
		$player->dept_id = $data['dept_id'];
		$player->hoid = $data['hoid'];
		$player->school_name = $data['school_name'];
		$player->foot = $data['foot'];
		$player->inch = $data['inch'];
		$player->weight = $data['weight'];
		$player->assign_coach_id = $data['assign_coach_id'];
		$player->status = 1;

		try {
			$player->save();
		} catch (\Exception $e) {
			dd($e->getMessage());
		}
	}

	public function getAllPlayerList($dept_id)
	{
		return parent::with('batch.loadCoach', 'player_qr', 'coach')
			->where('dept_id', $dept_id)
			->get();
	}

	public function getPlayerDataById($id, $dept_id = null)
	{
		$query = parent::with('parents', 'batch.loadCoach', 'dept', 'player_qr')
			->where('player_id', $id);

		if ($dept_id != null) {
			$query->where('dept_id', $dept_id);
		}

		return $query->first();
	}

	public function getPlayerData($data, $deptId = null)
	{
		return parent::where('player_id', $data['id'])->where('dept_id', $deptId)->first();
	}

	public function updatePlayerProfile($data, $player_id)
	{
		$player = parent::findOrFail($player_id);

		$player->first_name = $data['first_name'];
		$player->middle_name = $data['middle_name'];
		$player->last_name = $data['last_name'];
		$player->aadhar_number = $data['aadhar_number'];

		if (isset($data['player_image']) && $data['player_image']) {
			$player->player_image = $data['player_image'];
		}

		$player->dob = $data['dob'];
		$player->age = $data['age'];
		$player->gender = $data['gender'];
		$player->school_name = $data['school_name'];
		$player->address1 = $data['address1'];
		$player->address2 = $data['address2'];
		$player->area = $data['area'];
		$player->city = $data['city'];
		$player->pincode = $data['pincode'];
		$player->player_contact_no = $data['player_contact_no'];
		$player->player_email = $data['player_email'];
		$player->hoid = $data['hoid'];
		$player->foot = $data['foot'];
		$player->inch = $data['inch'];
		$player->weight = $data['weight'];
		$player->status = $data['status'];

		return $player->save();
	}

	public function getAllPlayerFee($deptId)
	{
		return parent::with('deptPlayers.feeValiditys', 'coach')
			->where('dept_id', $deptId)
			->get();
	}

	public function playerFeeById($player_id)
	{
		return parent::with('deptPlayers.feeValidity')
			->where('player_id', $player_id)
			->first();
	}

	public function getExistingPlayer($data)
	{
		return parent::with('parents')
			->where('aadhar_number', $data['document'])
			->first();
	}

	public function updatePlayerCoachId($data)
	{
		$players = parent::where('batch_id', $data['batch_id'])->get();
		foreach ($players as $player) {
			$player->assign_coach_id = $data['head_coach_id'];
			$player->save();
		}
		return true;
	}

	public function getAbsentPlayer($deptId, $presntPlayerId)
	{
		return parent::with('batch.loadCoach', 'coach')
			->where('dept_id', $deptId)
			->whereNotIn('player_id', $presntPlayerId)
			->get();
	}

	public function getPlayerIdAttendance($player_id, $dept_id)
	{
		return parent::with('dept:dept_id,dept_name', 'batch:id,batch_name', 'coach:id,first_name,middle_name,last_name')
			->where('player_id', $player_id)
			->where('dept_id', $dept_id)
			->first();
	}

	public function getPlayerList()
	{
		return parent::with([
			'coach:id,first_name,middle_name,last_name',
			'dept',
			'deptPlayers',
		])
			->select(
				'players.player_id',
				'players.first_name',
				'players.last_name',
				'players.player_contact_no',
				'players.assign_coach_id',
				'players.dept_id',
				'players.status',
				'players.aadhar_number'
			)
			->orderBy('players.first_name', 'asc');
	}

	public function checkPlayerStatus($id)
	{
		return parent::where('player_id', $id)->first();
	}

	// for cron
	public function updatePlayerStatus($ids, $status)
	{
		$query = parent::query();
		if (is_array($ids)) {
			$query->whereIn('player_id', $ids);
		} else {
			$query->where('player_id', $ids);
		}

		$query->update(['status' => $status]);
	}

	public function isPlayerIdExist($id, $dept_id)
	{
		return self::where('player_id', $id)->where('dept_id', $dept_id)->exists();

	}
}
