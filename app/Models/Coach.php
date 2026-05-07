<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Player;
use Illuminate\Support\Facades\DB;


class Coach extends Model
{
	use SoftDeletes;

	protected $primaryKey = 'id';
	public $incrementing = false;
	protected $keyType = 'string';

	protected $table = 'coaches';

	protected $fillable = [
		'id',
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
		'date_of_joining',
		'coach_contact_no',
		'coach_email',
		'coach_password',
		'coach_image',

	];

	public function getFullNameAttribute()
	{
		return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
	}

	public function getFullAddressAttribute()
	{
		return trim($this->address1 . ' ' . $this->address2 . ' ' . $this->area . ' ' . $this->city . ' ' . $this->pincode);
	}

	public function deptCoaches()
	{
		return $this->hasMany(DepartmentCoach::class, 'coach_id', 'id');
	}

	//one to many relation
	public function player()
	{
		return $this->hasMany(Player::class, 'assign_coach_id', 'id');
	}

	public function attendance()
	{
		return $this->hasMany(Attendance::class, 'player_coaches_id', 'id');
	}


	public function department()
	{
		return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
	}

	public function batch()
	{
		return $this->hasMany(Batch::class, 'coach_id', 'id');
	}

	public function latestBatch()
	{
		return $this->hasMany(Batch::class, 'coach_id', 'id')->latest('created_at');
	}
	public function coachAttendance()
	{
		return $this->hasMany(coachAttendance::class, 'coach_id', 'id');
	}
	// App/Models/Coach.php
	public function coachDept()
	{
		return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
	}



	//call API

	public function getCoachData($id)
	{
		// $coach = parent::with(['department', 'batch'])->find($id);

		$coach = parent::with([
			'department' => function ($query) {
				$query->select('dept_id', 'dept_name');
			},
			'batch' => function ($query) {
				$query->select('id', 'batch_name');
			}
		])->find($id);
		return $coach;
	}

	//************************ function **************************** */
	//coach

	// public function loadCoach()
	// {
	// $dept = Auth::user()->userDept;
	// $result = parent::with('department')
	// ->where('dept_id', $dept->dept_id)
	// ->get();
	// return $result;
	// }
	public function loadCoach($batch_id = null, $coach_id = null)
	{
		$user = Auth::user();
		$deptId = $user->resolveDeptId(); // ✅ correct

		if (!$deptId) {
			throw new \Exception('Please select a department first.');
		}

		$query = static::with('department')
			->where('dept_id', $deptId);

		if (!empty($coach_id)) {
			$query->where('id', $coach_id);
		}

		if (!empty($batch_id)) {
			$query->whereHas('batch', function ($q) use ($batch_id) {
				$q->where('batch_id', $batch_id);
			});
		}

		return $query->get();
	}

	public function loadEditCoach($id)
	{
		$result = parent::find($id);
		return $result;
	}

	public function updateCoach($data, $id)
	{
		return true;
	}

	public function deleteCoach($id)
	{
		$result = parent::destroy($id);
		return $result;
	}

	public function loadCoachAttendance($data)
	{
		$authUser = Auth::user();

		$deptId = $authUser->resolveDeptId(); // ✅ FIX
		$deptName = $authUser->resolveDeptName(); // ✅ FIX

		if (!$deptId || !$deptName) {
			throw new \Exception('Please select a department first.');
		}

		$attendanceTable = $deptName . '_coach_attendance';

		$query = parent::with(['batch'])
			->where('dept_id', $deptId);

		if (!empty($data['search'])) {
			$search = trim($data['search']);
			$query->where(function ($q) use ($search) {
				$q->where('id', 'like', "%{$search}%")
					->orWhere('first_name', 'like', "%{$search}%")
					->orWhere('middle_name', 'like', "%{$search}%")
					->orWhere('last_name', 'like', "%{$search}%")
					->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
					->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
			});
		}

		if (!empty($data['batches'])) {
			$query->whereHas('batch', function ($q) use ($data) {
				$q->where('id', $data['batches']);
			});
		}

		if (!empty($data['coach'])) {
			$query->where('id', $data['coach']);
		}

		if (!empty($data['offset'])) {
			$query->skip((int) $data['offset']);
		}

		$coaches = $query->take(10)->get();

		// Attendance
		$coachIds = $coaches->pluck('id')->toArray();

		$attendanceQuery = DB::table($attendanceTable)
			->whereIn('coach_id', $coachIds)
			->where('dept_id', $deptId);

		if (!empty($data['attendance_date'])) {
			$attendanceQuery->whereDate('attendance_date', $data['attendance_date']);
		}

		$attendanceRecords = $attendanceQuery->get()->groupBy('coach_id');

		$coaches->each(function ($coach) use ($attendanceRecords) {
			$coach->setRelation(
				'coachAttendance',
				$attendanceRecords->get($coach->id, collect())
			);
		});

		return $coaches;
	}


	//################################################################## Coach dashboard function ####################################################################

	public function loadLastCoachId($dept_id)
	{
		$result = parent::where('dept_id', $dept_id)->select('id')->latest('created_at')->first();
		return $result;
	}

	public function getExistingCoach($data)
	{
		$data = parent::where('document', $data['document'])->first();
		return $data;
	}

	public function insertCoach($data)
	{

		$coach = new Coach;

		$coach->id = $data['id'];
		$coach->document = $data['document'];
		$coach->first_name = $data['first_name'];
		$coach->middle_name = $data['middle_name'];
		$coach->last_name = $data['last_name'];
		$coach->address1 = $data['address1'];
		$coach->address2 = $data['address2'];
		$coach->area = $data['area'];
		$coach->city = $data['city'];
		$coach->pincode = $data['pincode'];
		$coach->dob = $data['dob'];
		$coach->age = $data['age'];
		$coach->gender = $data['gender'];
		$coach->dept_id = $data['dept_id'];
		// $coach->batch_id = $data['batch_id'];
		$coach->coach_contact_no = $data['coach_contact_no'];
		$coach->coach_email = $data['coach_email'];
		$coach->password = Hash::make($data['password']);
		$coach->coach_image = $data['coach_image'] ?? null;


		$result = $coach->save();

		$user = new User;
		$user->name = trim($data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name']);
		$user->email = $data['coach_email'];
		$user->contact_number = $data['coach_contact_no'];
		$user->password = Hash::make($data['password']);
		$user->role_id = 3;
		$user->dept_id = $data['dept_id'];
		$user->coach_id = $coach->id;
		$user->document_number = $data['document'];
		$user->save();

		return $result;
	}

	public function getAllCoach($dept_id, $batchId = null, $coachId = null, $status = null)
	{
		$query = parent::with(['latestBatch' => function ($qu) {
			$qu->where('status', 1);
		}])
			->where('dept_id', $dept_id);
		if (!empty($coachId)) {
			$query->where('id', $coachId);
		}
		if (!empty($batchId)) {
			$query->whereHas('latestBatch', function ($q) use ($batchId) {
				$q->where('id', $batchId);
			});
		}
		if (!empty($status)) {
			$query->where('status', $status);
		}
		return $query->orderBy('first_name')->get();
	}


	// public function getAllCoachQuery($dept_id, $batchId = null, $coachId = null, $status = null)
	// {
	// 	$query = parent::with(['latestBatch' => function ($q) {
	// 		$q->where('status', 1);
	// 	}])
	// 		->leftJoin('batches', function ($join) {    
	// 			$join->on('coaches.id', '=', 'batches.coach_id')
	// 				->where('batches.status', 1)
	// 				->whereNull('batches.deleted_at');
	// 		})
	// 		->where('coaches.dept_id', $dept_id)          
	// 		->select('coaches.*', 'batches.batch_name');
	// 	if (!empty($coachId)) {
	// 		$query->where('id', $coachId);
	// 	}

	// 	if (!empty($batchId)) {
	// 		$query->whereHas('latestBatch', function ($q) use ($batchId) {
	// 			$q->where('id', $batchId);
	// 		});
	// 	}

	// 	if (!empty($status)) {
	// 		$query->where('status', $status);
	// 	}

	// 	return $query;
	// 	// return $query->orderBy('first_name');
	// }

	public function getAllCoachQuery($dept_id, $batchId = null, $coachId = null, $status = null)
	{
		$query = parent::with(['latestBatch' => function ($q) use ($batchId) {
			$q->where('status', 1);
			if (!empty($batchId)) {
				$q->where('id', $batchId);
			}
		}])->where('coaches.dept_id', $dept_id);

		if (!empty($coachId)) {
			$query->where('id', $coachId);
		}

		if (!empty($status)) {
			$query->where('status', $status);
		}

		 if ($batchId) {
        $query->whereHas('latestBatch', function ($q) use ($batchId) {
            $q->where('status', 1)->where('id', $batchId);
        });
    }

		return $query;
	}



	public function getCoachProfile($id)
	{
		$profiles = parent::with(['latestBatch' => function ($qu) {
			$qu->where('status', 1);
		}])
			->with('department')->where('id', $id)->first();
		return $profiles;
	}

	public function updateCoachData($data, $id)
	{
		// Find existing coach
		$coach = Coach::findOrFail($id);

		$coach->document = $data['document'];
		$coach->first_name = $data['first_name'];
		$coach->middle_name = $data['middle_name'];
		$coach->last_name = $data['last_name'];
		$coach->address1 = $data['address1'];
		$coach->address2 = $data['address2'];
		$coach->area = $data['area'];
		$coach->city = $data['city'];
		$coach->pincode = $data['pincode'];
		$coach->dob = $data['dob'];
		$coach->age = $data['age'];
		$coach->gender = $data['gender'];
		$coach->dept_id = $data['dept_id'];
		// $coach->batch_id = $data['batch_id'];
		$coach->coach_contact_no = $data['coach_contact_no'];
		$coach->coach_email = $data['coach_email'];

		if (!empty($data['password'])) {
			$coach->password = Hash::make($data['password']);
		}

		if (!empty($data['coach_image'])) {
			$coach->coach_image = $data['coach_image'];
		}

		return $coach->save();
	}

	public function validateCoachEmail($data, $deptId)
	{
		$data = parent::where('coach_email', $data['coach_email'])
			->where('dept_id', $deptId)
			->get();
		return $data;
	}


	public function getCoachByBatch($data)
	{
		return parent::where('id', $data['coach_id'])->first();
	}

	public function getCoachName($data, $deptId)
	{
		return parent::where('id', $data['id'])
			->where('dept_id', $deptId)
			->first();
	}

	//based on department get coach
	public function loadAllCoachByDept($deptId)
	{
		return parent::where('dept_id', $deptId)->orderBy('first_name')->get();
	}

	//absent coach

	public function getAbsentCoach($deptId, $presentCoachIds)
	{
		$query = parent::with('batch')
			->where('dept_id', $deptId);
		if ($presentCoachIds) {
			$query->whereNotIn('id', $presentCoachIds);
		}

		return $query->get();
	}



	public function getCoachList()
	{
		return parent::select(
			'coaches.coach_email',
			'coaches.first_name',
			'coaches.middle_name',
			'coaches.last_name',
			DB::raw('GROUP_CONCAT(DISTINCT coaches.id ORDER BY coaches.id SEPARATOR ",") as coach_ids'),
			DB::raw('GROUP_CONCAT(DISTINCT department.dept_name ORDER BY department.dept_name SEPARATOR ",") as department_names')
		)
			->join('department', 'coaches.dept_id', '=', 'department.dept_id')
			->whereNull('coaches.deleted_at')
			->groupBy(
				'coaches.coach_email',
				'coaches.first_name',
				'coaches.middle_name',
				'coaches.last_name'
			);
	}
	public function getCoachesByDept($deptId)
	{
		$batches = parent::select('id', 'dept_id', 'first_name', 'last_name')->where('dept_id', $deptId)->orderBy('first_name')->get();
		return $batches;
	}

	public function isCoachIdExist($id)
	{
		$data = self::where('id', $id)->exists();
		return $data;
	}
}
