<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
	protected $dept;
	protected $user;
	public function __construct(Department $dept, User $user)
	{
		$this->dept = $dept;
		$this->user = $user;
	}

	public function addDepartment()
	{
		try {
			$data['topmenu'] = 'Department';
			$data['submenu'] = 'Department';
			$data['pagetitle'] = 'Add Department';

			return view('super-admin/addDepartment', ['data' => $data]);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}

	public function storeDepartment(Request $request)
	{

		try {

			//validation 
			$request->validate(
				[
					'dept_name' => 'required',
					'dept_icon' => 'required|image',
					'dept_email' => 'nullable|email|unique:users,email',
					'dept_contact_number' => 'nullable|numeric',
					'code_for_player' => 'required',
					'code_for_coach' => 'required',
					'digit_for_player' => 'required',
					'digit_for_coach' => 'required',
					'dept_admin_name' => 'required',
					'dept_admin_email' => 'required|email',
					'dept_admin_contact' => 'required',
					'dept_admin_password' => 'required',
					'dept_manager_name' => 'required',
					'dept_manager_email' => 'required|email',
					'dept_manager_contact' => 'required',
					'dept_manager_password' => 'required',
					'latitude' => 'required',
					'longitude' => 'required',
					'geofence_radius' => 'required'
				],
				[
					'dept_email.unique' => 'This department email is already registered.',
					'dept_admin_email.unique' => 'This department admin email is already registered.',
				]
			);

			// validation for duplicate dept name 
			$deptName = $request->input('dept_name');
			// $dept_name = strtolower($deptName);
			$dept_name = preg_replace('/\s+/', '_', strtolower($deptName));
			$existDept = $this->dept->isDeptExist($dept_name);
			if ($existDept) {
				return response()->json([
					'status' => false,
					'message' => "{$dept_name} Department already exists. Please enter a different department!",
				]);
			}

			// validation for duplicate code for player and coach 
			$codeForPlayer = $request->input('code_for_player');
			$codeForCoach = $request->input('code_for_coach');

			$CodeExist = $this->dept->getExistingCode([$codeForPlayer, $codeForCoach]);

			if ($CodeExist) {

				$code = [];
				if ($CodeExist->code_for_player === $codeForPlayer) {
					$code[] = $codeForPlayer;
				}
				if ($CodeExist->code_for_coach === $codeForCoach) {
					$code[] = $codeForCoach;
				}


				return response()->json([
					'status' => false,
					'message' => implode(' & ', $code) . " Code already exists. Please enter a different Code!",
				]);
			}




			$data = $request->all();
			// save icon
			if ($request->hasFile('dept_icon')) {

				$dept_icon = $request->file('dept_icon');
				$fileName = time() . '_' . $dept_name . '.' . $dept_icon->getClientOriginalExtension();

				$folder = 'images/' . $dept_name . '/icon';
				if (!file_exists($folder)) {
					mkdir($folder, 0777, true);
				}
				$dept_icon->move($folder, $fileName);
				$imgPath = $folder . '/' . $fileName;
				$data['dept_icon'] = $imgPath;
			}

			$id = $this->dept->insertDept($data);

			$this->user->insertUserbyDept($data, $id);



			//create tables

			$tableName1 = $dept_name . '_player_attendance';
			$tableName2 = $dept_name . '_coach_attendance';

			$tableName3 = $dept_name . '_assessments';
			$tableName4 = $dept_name . '_batch_logs';
			$tableName5 = $dept_name . '_fee_validity';
			$tableName6 = $dept_name . '_player_qrcodes';
			$tableName7 = $dept_name . '_coach_logs';
			$tableName8 = $dept_name . '_documents';
			$tableName9 = $dept_name . '_temp_coach_attendance';



			// if (Schema::hasTable($tableName)) {
			// throw new Exception('Table {$tableName} already exist!');
			// }

			//player attend
			Schema::create($tableName1, function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('player_id');
				$table->integer('dept_id');
				$table->date('attendance_date');
				$table->time('in_time');
				$table->time('out_time')->nullable();
				$table->string('status')->default(1);
				$table->integer('marked_by');
				$table->timestamps();
				$table->softDeletes();
			});

			//coach_attend
			Schema::create($tableName2, function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('coach_id');
				$table->integer('dept_id');
				$table->date('attendance_date');
				$table->time('in_time');
				$table->time('out_time')->nullable();
				$table->string('status')->default(1);
				$table->integer('marked_by');
				$table->timestamps();
				$table->softDeletes();
			});

			//player assessment
			Schema::create($tableName3, function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('dept_player_id');
				$table->date('assessment_date');
				$table->decimal('foot');
				$table->decimal('inch');
				$table->decimal('weight');
				$table->decimal('sprint');
				$table->decimal('standing_broad_jump')->nullable();
				$table->decimal('jump_with_stepping')->nullable();
				$table->decimal('vertical_jump')->nullable();
				$table->string('approved_by');

				$table->timestamps();
				$table->softDeletes();
			});

			// batch log 
			Schema::create($tableName4, function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('batch_id');
				$table->string('coach_id');
				$table->string('player_id');
				$table->date('start_date');
				$table->date('end_date')->nullable();
				$table->integer('status')->nuallable();

				$table->timestamps();
				$table->softDeletes();
			});



			//fee-validity
			Schema::create($tableName5, function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('dept_player_id');
				$table->integer('approved_by');
				$table->date('valid_from');
				$table->date('valid_to');
				$table->string('receipt_no');
				$table->integer('amount');
				$table->date('date');

				$table->timestamps();
				$table->softDeletes();
			});

			//player_qrcodes
			Schema::create($tableName6, function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('dept_id');
				$table->string('player_id');
				$table->string('qr_code');

				$table->timestamps();
				$table->softDeletes();
			});

			Schema::create($tableName7, function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('coach_id');
				$table->string('batch_name');
				$table->date('start_date')->notNull();
				$table->date('end_date')->nullable();
				$table->string('status')->default('current_batch');

				$table->timestamps();
				$table->softDeletes();
			});

			Schema::create($tableName8, function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('player_id');
				$table->string('document_name');
				$table->string('file')->notNull();
				$table->string('description')->nullable();;

				$table->timestamps();
				$table->softDeletes();
			});

			Schema::create($tableName9, function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('coach_id')->nullable();
				$table->integer('dept_id')->nullable();
				$table->date('attendance_date')->nullable();
				$table->time('in_time')->nullable();
				$table->time('out_time')->nullable();
				$table->string('status')->default(1);
				$table->integer('marked_by')->nullable();
				$table->timestamps();
				$table->softDeletes();
			});




			return response()->json([
				'status' => 'success',
				'message' => 'Department created successfully.'
			]);
		} catch (\Exception $e) {

			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}

	public function departmentList()
	{
		try {
			$data['topmenu'] = 'Department';
			$data['submenu'] = 'Department';
			$data['pagetitle'] = 'Department List';

			$department = $this->dept->getAllDept();

			return view('super-admin/departmentList', compact('data', 'department'));
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}




	public function editDepartment($id)
	{
		try {
			$data['topmenu'] = 'Department';
			$data['submenu'] = 'Department';
			$data['pagetitle'] = 'Edit Department';

			$dept = $this->dept->getDeptById($id);
			$userData = $this->user->getUserData($id);
			

			return view('super-admin/editDepartment', compact('data', 'dept', 'userData'));
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}

	public function updateDepartment(Request $request, $id)
	{
		try {
			if (!$id) {
				return response()->json([
					' status' => false,
					'message' => 'Id Not found!',

				]);
			}


			$validator = Validator::make($request->all(), [
				'dept_name' => 'required',
				'dept_icon' => 'nullable|image',
				'dept_email' => 'nullable|email',
				'dept_contact_number' => 'nullable|numeric|digits:10',
				'code_for_player' => 'required',
				'code_for_coach' => 'required',
				'digit_for_player' => 'required',
				'digit_for_coach' => 'required',
				'dept_admin_name' => 'required',
				'dept_admin_email' => 'required|email',
				'dept_admin_contact' => 'required',
				
				'dept_manager_name' => 'required',
				'dept_manager_email' => 'required|email',
				'dept_manager_contact' => 'required',
				
				'latitude' => 'required',
				'longitude' => 'required',
				'geofence_radius' => 'required',
			]);

			if ($validator->fails()) {
				return response()->json([
					'status' => 'error',
					'message' => 'Validation failed',
					'errors' => $validator->errors()
				], 422);
			}


			$newDept = $request->input('dept_name');
			// dd($newDept);
			$newDeptName = strtolower($newDept);

			$oldDept = $this->dept->getDeptById($id);
			$oldDeptName = strtolower($oldDept->dept_name);
			if ($newDeptName !== $oldDeptName) {

				$existDept = $this->dept->isDeptExist($newDeptName, $id);
				if ($existDept) {
					return response()->json([
						'status' => false,
						'message' => "{$newDeptName} Department already exists. Please enter a different department!",
					]);
				}

				//update table name
				$dept_name = strtolower($newDeptName);
				$newTableName1 = $dept_name . '_player_attendance';
				$newTableName2 = $dept_name . '_coach_attendance';
				$newTableName3 = $dept_name . '_assessments';
				$newTableName4 = $dept_name . '_batch_logs';
				$newTableName5 = $dept_name . '_fee_validity';
				$newTableName6 = $dept_name . '_player_qrcodes';



				//get old dept name 

				$old_dept_name = strtolower($oldDeptName);
				$oldTableName1 = $old_dept_name . '_player_attendance';
				$oldTableName2 = $old_dept_name . '_coach_attendance';
				$oldTableName3 = $old_dept_name . '_assessments';
				$oldTableName4 = $old_dept_name . '_batch_logs';
				$oldTableName5 = $old_dept_name . '_fee_validity';
				$oldTableName6 = $old_dept_name . '_player_qrcodes';

				// validate duplicate table
				$tables = [
					$dept_name . '_player_attendance',
					$dept_name . '_coach_attendance',
					$dept_name . '_assessments',
					$dept_name . '_batch_logs',
					$dept_name . '_fee_validity',
					$dept_name . '_player_qrcodes',
				];


				foreach ($tables as $newTbl) {
					if (Schema::hasTable($newTbl)) {
						throw new \Exception('This department already exists , Please enter a different department name.');
					}
				}

				// rename the tbl name 
				Schema::rename($oldTableName1, $newTableName1);
				Schema::rename($oldTableName2, $newTableName2);
				Schema::rename($oldTableName3, $newTableName3);
				Schema::rename($oldTableName4, $newTableName4);
				Schema::rename($oldTableName5, $newTableName5);
				Schema::rename($oldTableName6, $newTableName6);
			}

			$data = $request->all();
			if ($request->hasFile('dept_icon')) {
				$deptName = $request->input('dept_name');
				$file = $request->file('dept_icon');
				$fileName = time() . '_' . $deptName . '.' . $file->getClientOriginalExtension();
				$folder = 'images/' . $deptName . '/icon';

				if (!file_exists($folder)) {
					mkdir($folder, 0777, true);
				}
				$file->move($folder, $fileName);
				$imgPath = $folder . '/' . $fileName;
				$data['dept_icon'] = $imgPath;
			}

			$this->dept->updateDept($id, $data);
			$this->user->updateUserByDept($id, $data);

			return response()->json([
				'status' => 'success',
				'message' => 'Department update successfully!',
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 500);
		}
	}

	public function deleteDept($id)
	{
		try {
			if ($id) {
				$this->dept->destroyDept($id);
				$this->user->destroyUserByDept($id);
			}
			return response()->json(['status' => 'success', 'message' => 'Department deleted successfully!']);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}
}
