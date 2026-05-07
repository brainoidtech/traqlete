<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Batch;
use App\Models\Player;

use App\Models\DepartmentPlayers;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


use Exception;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
	protected $coach;
	protected $batch;
	protected $player;
	protected $player_attend;
	protected $coach_attend;
	protected $department;
	protected $dept_player;

	public function __construct(Coach $coach, Batch $batch, Player $player, Department $department, DepartmentPlayers $dept_player)
	{
		$this->coach = $coach;
		$this->batch = $batch;
		$this->player = $player;
		$this->department = $department;
		$this->dept_player = $dept_player;
	}

	public function addAttendance()
	{
		try {
			$dept = getDepartmentData();
			$dept_name = ucfirst(strtolower($dept->dept_name));

			$data['topmenu'] = 'Attendance';
			$data['submenu'] = 'Attendance';
			$data['pagetitle'] = 'Add Attendance' . ' ' . $dept_name;

			return view('admin/attendance/addAttendance', ['data' => $data]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 500);
		}
	}

	// check attendance alredy maeked if markd then show swal else not show anything 
	public function checkUserAttendAlredyMark(Request $request, $table)
	{
		try {
			$attendance = DB::table($table)
				->where('player_id', $request->id)
				->where('attendance_date', $request->date)
				->where('in_time', '<=',  $request->in_time)
				->where('out_time', '>=', $request->in_time)
				->first();

			if ($attendance) {
				return response()->json([
					'status' => 'exists',
					'data' => $attendance
				]);
			} else {
				return response()->json([
					'status' => 'not_exists',
					'data' => $attendance
				]);
			}
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 500);
		}
	}


	// public function insertPlayerAttendance(Request $request)
	// {
	// 	try {
	// 		$dept = getDepartmentData();
	// 		$dept_id = $dept->dept_id;

	// 		$user_id = Auth::user()->id;
	// 		$dept_name = $dept->dept_name;

	// 		$dept = strtolower($dept_name);
	// 		$player_attend_tbl = $dept . '_player_attendance';


	// 		$player_modified_id = $this->player->isPlayerIdExist($request->id, $dept_id);

	// 		if (!$player_modified_id) {
	// 			return response()->json([
	// 				'status' => 'error',
	// 				'message' => 'Please enter valid Player Id',
	// 			]);
	// 		}

	// 		// Validation
	// 		// 1. in_time OR out_time required
	// 		if (empty($request->in_time) && empty($request->out_time)) {
	// 			return response()->json([
	// 				'status' => 'error',
	// 				'message' => 'In Time and Out Time cannot both be left blank. Please fill in at least one field.',
	// 			]);
	// 		}

	// 		// 2. if in and out time both have same 
	// 		if ($request->in_time && $request->out_time && $request->out_time === $request->in_time) {
	// 			return response()->json([
	// 				'status' => 'error',
	// 				'message' => 'In Time and Out Time cannot be the same.',
	// 			]);
	// 		}

	// 		//3. if out time is greater than in time 
	// 		if (!empty($request->out_time) && !empty($request->in_time) &&  Carbon::parse($request->in_time) >= Carbon::parse($request->out_time)) {
	// 			return response()->json([
	// 				'status' => 'error',
	// 				'message' => 'Out time must be later than In time.',
	// 			]);
	// 		}

	// 		// 2.  if player alredy exist on the same date and time 
	// 		$attendanceMarked = $this->checkUserAttendAlredyMark($request, $player_attend_tbl);
	// 		if ($attendanceMarked->getData()->status === 'exists') {


	// 			$data = $attendanceMarked->getData()->data;

	// 			$player_out_time = $data->out_time ?? null;
	// 			$inFormatted = Carbon::createFromFormat('H:i:s', $player_out_time)->format('g:i A')
	// 				?? 'previous out time';

	// 			return response()->json([
	// 				'status' => 'error',
	// 				'message' => "New entry must be after $inFormatted",
	// 			]);

	// 			// return response()->json([
	// 			// 	'status' => 'error',
	// 			// 	'message' => 'Attendance for this player has already been marked for the selected date and time range',
	// 			// 	// 'message' => 'New entry must be after ',
	// 			// ]);
	// 		}


	// 		$query = DB::table($player_attend_tbl)
	// 			->where([
	// 				'player_id' => $request->id,
	// 				'attendance_date' => $request->date,
	// 			]);

	// 		$Exists = $query->first();
	// 		if ($Exists) {


	// 			// 3. out time should be grater then in time
	// 			if (!empty($request->out_time) && empty($request->in_time) && Carbon::parse($Exists->in_time) >= Carbon::parse($request->out_time)) {

	// 				$outFormatted = Carbon::createFromFormat('H:i', $request->out_time)->format('g:i A')
	// 					??  'previous in time';

	// 				return response()->json([
	// 					'status' => 'error',
	// 					'message' => 'Out time must be after ' . $outFormatted,
	// 				]);
	// 			}



	// 			// if out time empty then try to insert in time show error
	// 			if (empty($Exists->out_time) &&  $request->in_time) {
	// 				return response()->json([
	// 					'status' => 'error',
	// 					'message' => 'An In Time has already been entered. Please enter the Out Time first.',
	// 				]);
	// 			}

	// 			if ($Exists->out_time === null) {
	// 				$query->update([
	// 					'out_time' => $request->out_time,
	// 					'status' => 'OUT',
	// 					'updated_at' => now(),
	// 				]);
	// 			} else {
	// 				// update in_time / out_time if player came on batch between tolerance timing
	// 				$batchTime = $this->dept_player->getBatchTime($dept_id, $request->id);

	// 				if ($batchTime && $batchTime->batch) {


	// 					$start = Carbon::parse($batchTime->batch->start_time);
	// 					$end = Carbon::parse($batchTime->batch->end_time);

	// 					//tolerance
	// 					$tolerance_start_time = $start->copy()->subMinutes(60);
	// 					$tolerance_end_time = $end->copy()->addMinutes(60);

	// 					// players input
	// 					$players_in_time = Carbon::parse($request->in_time);
	// 					$players_out_time = Carbon::parse($request->out_time);

	// 					// db value existing
	// 					$existing_in_time =  Carbon::parse($Exists->in_time);
	// 					$existing_out_time =  Carbon::parse($Exists->out_time);


	// 					// if coach' in_time  Or Out_time is according to  tolerance_time then only update 
	// 					// in time
	// 					if ($players_in_time && $players_in_time->between($tolerance_start_time, $tolerance_end_time) && $players_in_time >= $existing_in_time) {
	// 						$query->update([
	// 							'in_time' => $request->in_time,
	// 							'out_time' => null,
	// 							'status' => 'IN'
	// 						]);
	// 					} elseif ($players_out_time && $players_out_time->between($tolerance_start_time, $tolerance_end_time) && $players_out_time >= $existing_out_time) {
	// 						$query->update([
	// 							'out_time' => $request->out_time,
	// 							'status' => 'OUT'
	// 						]);
	// 					} else {
	// 						$in = $Exists->in_time ?? 'this time';
	// 						$out = $Exists->out_time ?? null;

	// 						if ($out) {
	// 							$outFormatted = Carbon::createFromFormat('H:i:s', $out)->format('g:i:s A');
	// 						} else {
	// 							$outFormatted = 'previous out time';
	// 						}

	// 						return response()->json([
	// 							'status' => 'error',
	// 							// 'message' => 'New entry must be after ' . $outFormatted,
	// 							'message' => 'Player has already marked attendance from '. $in .'-'. $out
	// 						]);
	// 					}
	// 				}
	// 			}
	// 		} else {

	// 			if (empty($request->in_time) &&  $request->out_time) {
	// 				return response()->json([
	// 					'status' => 'error',
	// 					'message' => 'Please enter In Time first.',
	// 				]);
	// 			}



	// 			$data = [
	// 				'player_id' => $request->id,
	// 				'dept_id' => $dept_id,
	// 				'in_time' => $request->in_time,
	// 				'out_time' => $request->out_time,
	// 				'attendance_date' => $request->date,
	// 				'marked_by' => $user_id,
	// 				'created_at' => now(),
	// 				'updated_at' => now(),
	// 			];

	// 			if (!empty($request->in_time)) {
	// 				$data['in_time'] = $request->in_time;
	// 				$data['status'] = 'IN';
	// 			}

	// 			if (!empty($request->out_time)) {
	// 				$data['out_time'] = $request->out_time;
	// 				$data['status'] = 'OUT';
	// 			}

	// 			DB::table($player_attend_tbl)->insert($data);


	// 			$player = $this->player->checkPlayerStatus($request->id);
	// 			if ($player->status == 2) {
	// 				$this->player->updatePlayerStatus($request->id, 1);
	// 			}
	// 			// }
	// 		}

	// 		return response()->json([
	// 			'status' => 'success',
	// 			'message' => "Attendance added successfully.",
	// 		]);
	// 	} catch (\Exception $e) {
	// 		return response()->json([
	// 			'status' => 'error',
	// 			'message' => $e->getMessage(),
	// 		]);
	// 	}
	// }

	public function insertPlayerAttendance(Request $request)
	{
		try {

			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;
			$user_id = Auth::id();

			$player_attend_tbl = strtolower($dept->dept_name) . '_player_attendance';

			/*
        |--------------------------------------------------------------------------
        | 1. VALIDATIONS
        |--------------------------------------------------------------------------
        */


			if (!$request->id) {
				return response()->json([
					'status' => 'error',
					'message' => 'Player ID is required.',
				]);
			}

			if (empty($request->in_time) && empty($request->out_time)) {
				return response()->json([
					'status' => 'error',
					'message' => 'Please provide In Time or Out Time.',
				]);
			}

			if ($request->in_time && $request->out_time && $request->in_time === $request->out_time) {
				return response()->json([
					'status' => 'error',
					'message' => 'In Time and Out Time cannot be the same.',
				]);
			}

			if ($request->in_time && $request->out_time) {
				if (Carbon::parse($request->in_time) >= Carbon::parse($request->out_time)) {
					return response()->json([
						'status' => 'error',
						'message' => 'Out Time must be greater than In Time.',
					]);
				}
			}

			/*
        |--------------------------------------------------------------------------
        | 2. PLAYER VALIDATION
        |--------------------------------------------------------------------------
        */

			$playerExists = $this->player->isPlayerIdExist($request->id, $dept_id);

			if (!$playerExists) {
				return response()->json([
					'status' => 'error',
					'message' => 'Please enter a valid Player ID.',
				]);
			}

			/*
        |--------------------------------------------------------------------------
        | 3. CHECK EXISTING ATTENDANCE (TODAY)
        |--------------------------------------------------------------------------
        */

			$attendance = DB::table($player_attend_tbl)
				->where('player_id', $request->id)
				->where('attendance_date', $request->date)
				->first();

			/*
        |--------------------------------------------------------------------------
        | 4. FIRST ATTENDANCE (NO RECORD)
        |--------------------------------------------------------------------------
        */

			if (!$attendance) {

				if (empty($request->in_time)) {
					return response()->json([
						'status' => 'error',
						'message' => 'Please enter In Time first.',
					]);
				}

				DB::table($player_attend_tbl)->insert([
					'player_id' => $request->id,
					'dept_id' => $dept_id,
					'in_time' => $request->in_time,
					'out_time' => $request->out_time,
					'attendance_date' => $request->date,
					'marked_by' => $user_id,
					'status' => $request->out_time ? 'OUT' : 'IN',
					'created_at' => now(),
					'updated_at' => now(),
				]);

				return response()->json([
					'status' => 'success',
					'message' => 'Attendance marked successfully.',
				]);
			}




			if ($attendance) {

				// IN exists but OUT not yet marked  allow OUT update
				if ($attendance->in_time && empty($attendance->out_time) && $request->out_time && empty($request->in_time) && $attendance->in_time < $request->out_time ) {

					DB::table($player_attend_tbl)
						->where('id', $attendance->id)
						->update([
							'out_time' => $request->out_time,
							'status' => 'OUT',
							'updated_at' => now(),
						]);

					return response()->json([
						'status' => 'success',
						'message' => 'Out time marked successfully.',
					]);
				}
			}

			/*
        |--------------------------------------------------------------------------
        | 5. SECOND / MULTIPLE ATTENDANCE (TOLERANCE RULE)
        |--------------------------------------------------------------------------
        */

			$batchTime = $this->dept_player->getBatchTime($dept_id, $request->id);

			if (!$batchTime || !$batchTime->batch) {
				return response()->json([
					'status' => 'error',
					'message' => 'Batch not assigned to player.',
				]);
			}

			$start = Carbon::parse($batchTime->batch->start_time);
			$end = Carbon::parse($batchTime->batch->end_time);

			$toleranceStart = $start->copy()->subMinutes(60);
			$toleranceEnd = $end->copy()->addMinutes(60);

			$inTime = $request->in_time ? Carbon::parse($request->in_time) : null;
			$outTime = $request->out_time ? Carbon::parse($request->out_time) : null;

			$inAllowed = $inTime && $inTime->between($toleranceStart, $toleranceEnd);
			$outAllowed = $outTime && $outTime->between($toleranceStart, $toleranceEnd);

			if (!$inAllowed && !$outAllowed) {

				$in = $attendance->in_time ? ' from ' . $attendance->in_time :  '';
				$out = $attendance->out_time ?  ' to ' . $attendance->out_time : '';

				return response()->json([
					'status' => 'error',
					'message' => "Player has already marked attendance  $in  $out",
				]);
			}


			
			if ($request->in_time && $attendance->out_time > $request->in_time) {
				return response()->json([
					'status' => 'error',
					'message' => "Attendance cannot be marked ,Your last out time is  $attendance->out_time ",
				]);
			}



			/*
		|--------------------------------------------------------------------------
		| OVERLAP VALIDATION (IMPORTANT FIX)
		|--------------------------------------------------------------------------
		*/

			$existingIn = $attendance->in_time ? Carbon::parse($attendance->in_time) : null;
			$existingOut = $attendance->out_time ? Carbon::parse($attendance->out_time) : null;


			if ($existingIn && $existingOut) {

				// NEW IN overlaps existing range
				if ($inTime && $inTime->between($existingIn, $existingOut)) {
					return response()->json([
						'status' => 'error',
						'message' => 'This In Time is already within existing attendance range.',
					]);
				}

				// NEW OUT overlaps existing range
				if ($outTime && $outTime->between($existingIn, $existingOut)) {
					return response()->json([
						'status' => 'error',
						'message' => 'This Out Time overlaps with existing attendance.',
					]);
				}

				// FULL RANGE overlap (important case)
				if (
					$inTime && $outTime &&
					($inTime < $existingOut && $outTime > $existingIn)
				) {

					return response()->json([
						'status' => 'error',
						'message' => 'Attendance time overlaps with previous entry.',
					]);
				}
			}

			/*
        |--------------------------------------------------------------------------
        | 6. UPDATE ATTENDANCE
        |--------------------------------------------------------------------------
        */

			$update = [];

			if($request->in_time && $attendance->out_time == null  && empty($request->out_time))
			{
				return response()->json([
						'status' => 'error',
						'message' => "Your in time was recorded at $attendance->in_time.  Please enter your out time.",
				]);
			}

			if($request->out_time && empty($request->in_time) && $attendance->out_time != null )
			{
				return response()->json([
						'status' => 'error',
						'message' => "Attendance cannot be marked ,Your last out time was  $attendance->out_time",
				]);
			}

			if($attendance->in_time >= $request->out_time &&  $request->out_time)
			{
				return response()->json([
						'status' => 'error',
						'message' => "Attendance cannot be marked ,Your last in time was  $attendance->in_time",
				]);
			}

			if ($inAllowed && $attendance->out_time != null) {

				$update['in_time'] = $request->in_time;
				$update['out_time'] = null;
				$update['status'] = 'IN';
			}

			

			if ($outAllowed) {
				$update['out_time'] = $request->out_time;
				$update['status'] = 'OUT';
			}

			$update['updated_at'] = now();

			DB::table($player_attend_tbl)
				->where('id', $attendance->id)
				->update($update);

			return response()->json([
				'status' => 'success',
				'message' => 'Attendance updated successfully.',
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}


	private function BaseQuery($table, $coachId, $date)
	{
		return DB::table($table)
			->where([
				'coach_id' => $coachId,
				'attendance_date' => $date,
			]);
	}

	public function insertCoachAttendance(Request $request)
	{
		try {

			$request->validate([
				'id' => 'required|exists:coaches,id',
			]);

			$user = Auth::user();

			$deptData = getDepartmentData();
			$dept_id = $deptData->dept_id;

			$coach_attend_tbl = strtolower($deptData->dept_name) . '_coach_attendance';


			$coach_modified_id = $this->coach->isCoachIdExist($request->id);

			if (!$coach_modified_id) {
				return response()->json([
					'status' => 'error',
					'message' => 'Please enter valid Coach Id',
				]);
			}


			//-------------------------------------------
			// 1. Basic Validation
			//-------------------------------------------
			if (empty($request->in_time) && empty($request->out_time)) {
				return response()->json([
					'status' => 'error',
					'message' => 'Either In Time or Out Time is required.',
				]);
			}


			//-------------------------------------------
			// 1. In Time and Out Time have different time
			//-------------------------------------------

			$outTime = $request->out_time ? Carbon::parse($request->out_time) : '';
			$inTime = $request->in_time ? Carbon::parse($request->in_time) : '';

			if ($outTime && $inTime && $inTime >= $outTime) {
				return response()->json([
					'status' => 'error',
					'message' => 'Out time must be later than In time.',
				]);
			}


			//-------------------------------------------
			// 2. Fetch latest record
			//-------------------------------------------
			$lastRecord = $this->BaseQuery($coach_attend_tbl, $request->id, $request->date)
				->orderBy('id', 'desc')
				->first();

			//-------------------------------------------
			// 3.  OUT TIME update
			//-------------------------------------------
			if ($request->out_time && empty($request->in_time)) {

				if (!$lastRecord || !$lastRecord->in_time || $lastRecord->out_time) {
					return response()->json([
						'status' => 'error',
						'message' => 'Please enter In Time first.',
					]);
				}

				$lastRecordInTime = Carbon::parse($lastRecord->in_time);


				if ($outTime <= $lastRecordInTime) {
					return response()->json([
						'status' => 'error',
						'message' => 'Out Time must be later than In Time.',
					]);
				}

				$this->BaseQuery($coach_attend_tbl, $request->id, $request->date)
					->where('id', $lastRecord->id)
					->update([
						'out_time' => $request->out_time,
						'status' => 'OUT'
					]);

				return response()->json([
					'status' => 'success',
					'message' => 'Out Time added successfully.',
				]);
			}

			//-------------------------------------------
			// 4.  IN TIME insert
			//-------------------------------------------
			if ($request->in_time) {

				// If previous record exists and OUT missing
				if ($lastRecord && $lastRecord->in_time && !$lastRecord->out_time) {
					return response()->json([
						'status' => 'error',
						'message' => 'Please enter Out Time first for previous entry.',
					]);
				}

				// 15 min rule
				if ($lastRecord && $lastRecord->out_time) {
					$lastRecordOutTime = Carbon::parse($lastRecord->out_time)->addMinutes(14);
					$outFormatted = $lastRecordOutTime->format('g:i A');

					$inTimeCarbon = Carbon::parse($inTime);
					$lastOutCarbon = Carbon::parse($lastRecord->out_time);

					if ($inTimeCarbon <= $lastOutCarbon) {
						return response()->json([
							'status' => 'error',
							'message' => 'New entry must be after ' . $lastOutCarbon->addMinutes(14)->format('g:i A'),
						]);
					} elseif ($inTimeCarbon <= $lastRecordOutTime) {
						return response()->json([
							'status' => 'error',
							'message' => 'You can mark attendance only after 15 minutes from last Out Time (' . $outFormatted . ').',
						]);
					}
				}

				//-------------------------------------------
				// Insert new IN record
				//-------------------------------------------
				DB::table($coach_attend_tbl)->insert([
					'coach_id' => $request->id,
					'dept_id' => $dept_id,
					'in_time' => $request->in_time,
					'out_time' => $request->out_time ? $request->out_time : null,
					'attendance_date' => $request->date,
					'marked_by' => $user->id,
					'created_at' => now(),
					'updated_at' => now(),
				]);

				return response()->json([
					'status' => 'success',
					'message' => 'Attendance Marked successfully.',
				]);
			}
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}



	public function attendanceList(Request $request)
	{
		$deptData = getDepartmentData();
		$dept_id = $deptData->dept_id;

		//all batch and coach data
		$batches = $this->batch->loadBatchByDept($dept_id);
		$coaches = $this->coach->loadAllCoachByDept($dept_id);

		//users table
		$dept = strtolower($deptData->dept_name);
		$player_attend_tbl = $dept . '_player_attendance';
		$coach_attend_tbl = $dept . '_coach_attendance';

		$todayDate = Carbon::today()->toDateString();
		$filterDate = $request->input('date');
		$activeTab = $request->input('tab', 'player_tab');
		//--------------------------    player & coach attendance Filter  --------------------- 

		//if filter occure according to date 
		if ($filterDate) {
			if ($activeTab === 'player_tab' && $filterDate) {
				//present player
				$player_attend = $this->playerAttenByDate($player_attend_tbl, $filterDate)->get();



				//absent player
				$presentPlayersIds = $player_attend->pluck('player_id')->toArray();
				$absent_players = $this->player->getAbsentPlayer($dept_id, $presentPlayersIds);

				return response()->json([
					'present_html' => view(
						'admin.attendance.partials.players_tbl',
						compact('player_attend')
					)->render(),

					'absent_html' => view(
						'admin.attendance.partials.player_absent_tbl',
						compact('absent_players', 'filterDate')
					)->render(),
				]);
			}


			if ($activeTab === 'coach_tab' && $filterDate) {
				//present data 
				$coach_attend = $this->coachAttenByDate($coach_attend_tbl, $filterDate)->get();
				// absent data
				$presentCoachIds = $coach_attend->pluck('coach_id')->toArray();
				$absent_coaches = $this->coach->getAbsentCoach($dept_id, $presentCoachIds);

				return response()->json([
					'present_html' => view(
						'admin.attendance.partials.coaches_tbl',
						compact('coach_attend')
					)->render(),

					'absent_html' => view(
						'admin.attendance.partials.coach_absent_tbl',
						compact('absent_coaches', 'filterDate')
					)->render(),
				]);
			}
		}


		//3.  display player present attend
		$player_attend = $this->playerAttenByDate($player_attend_tbl, $todayDate)->get();
		$presentPlayersIds = $player_attend->pluck('player_id')->toArray();
		$absent_players = $this->player->getAbsentPlayer($dept_id, $presentPlayersIds);



		//4.  display coach present attend
		$coach_attend = $this->coachAttenByDate($coach_attend_tbl, $todayDate)->get();
		//absent coach
		$presentCoachIds = $coach_attend->pluck('coach_id')->toArray();
		$absent_coaches = $this->coach->getAbsentCoach($dept_id, $presentCoachIds);

		$data['topmenu'] = 'Attendance';
		$data['submenu'] = 'Attendance';
		$data['pagetitle'] = 'Attendance' . ' ' . ucfirst(strtolower($deptData->dept_name));
// echo "<pre>";
// print_r($player_attend);
// echo "</pre>";
// die;
		return view('admin/attendance/attendanceList', compact('data', 'player_attend', 'coach_attend', 'absent_players', 'absent_coaches', 'batches', 'coaches'));
	}


	//above this function called 
	public function playerAttenByDate($table, $date)
	{
		return  DB::table($table . ' as pa')
			->where('pa.attendance_date', $date)

			->join('players', 'players.player_id', '=', 'pa.player_id')
			->join('batches', 'batches.id', '=', 'players.batch_id')
			->leftjoin('coaches', 'coaches.id', '=', 'players.assign_coach_id')
			->select(
				'players.player_id',
				'players.first_name',
				'players.middle_name',
				'players.last_name',
				'batches.batch_name',
				'coaches.first_name as coach_first_name',
				'coaches.middle_name as coach_middle_name',
				'coaches.last_name as coach_last_name',
				'pa.in_time',
				'pa.out_time',
				'pa.id',
				'pa.attendance_date'

			)
			->orderBy('pa.player_id', 'asc');
	}

	public function coachAttenByDate($table, $date)
	{
		return DB::table($table . ' as ca')
			->where('ca.attendance_date', $date)
			->whereNull('ca.deleted_at')
			->whereIn('ca.id', function ($query) use ($table, $date) {
				$query->select(DB::raw('MAX(id)'))
					->from($table)
					->where('attendance_date', $date)
					->groupBy('coach_id');
			})
			->join('coaches', 'coaches.id', '=', 'ca.coach_id')
			->leftJoin('batches', 'batches.coach_id', '=', 'ca.coach_id')
			->select(
				'coaches.id as coach_id',
				'coaches.first_name',
				'coaches.middle_name',
				'coaches.last_name',
				DB::raw('GROUP_CONCAT(DISTINCT batches.batch_name) as batch_name'),
				'ca.in_time',
				'ca.out_time',
				'ca.attendance_date'
			)
			->groupBy(
				'coaches.id',
				'coaches.first_name',
				'coaches.middle_name',
				'coaches.last_name',
				'ca.in_time',
				'ca.out_time',
				'ca.attendance_date'
			)
			->orderBy('coaches.id', 'asc');
	}


	//1. filter present player attendance 
	public function filterPlayersByAttendance(Request $request)
	{
		$deptData = getDepartmentData();
		$dept_id = $deptData->dept_id;

		$dept_name = $deptData->dept_name;
		$dept = strtolower($dept_name);

		$filterDate = $request->input('date');

		if ($request->input('tab') === 'player_tab') {

			$player_attend_tbl = $dept . '_player_attendance';

			$player_attend = DB::table($player_attend_tbl . ' as pa')
				->where('pa.attendance_date', $filterDate)
				->when($request->filled('in_time'), function ($query) use ($request) {
					return $query->where('pa.in_time', $request->in_time);
				})

				->when($request->filled('out_time'), function ($query) use ($request) {
					return $query->where('pa.out_time', $request->out_time);
				})
				->join('players', 'players.player_id', '=', 'pa.player_id')
				->join('batches', 'batches.id', '=', 'players.batch_id')
				->join('coaches', 'coaches.id', '=', 'players.assign_coach_id')

				->when($request->filled('batch_id'), function ($query) use ($request) {
					return $query->where('batches.id', $request->batch_id);
				})

				->when($request->filled('coach_id'), function ($query) use ($request) {
					return $query->where('coaches.id', $request->coach_id);
				})
				->select(
					'players.player_id',
					'players.first_name',
					'players.middle_name',
					'players.last_name',
					'batches.batch_name',
					'coaches.first_name as coach_first_name',
					'coaches.middle_name as coach_middle_name',
					'coaches.last_name as coach_last_name',
					'pa.in_time',
					'pa.out_time',
					'pa.id',
					'pa.attendance_date'
				)
				->orderBy('pa.player_id', 'asc')
				->get();

			return response()->json([
				'present_html' => view(
					'admin.attendance.partials.players_tbl',
					compact('player_attend')
				)->render()
			]);
		} elseif ($request->input('tab') === 'coach_tab') {
			$coach_attend_tbl = $dept . '_coach_attendance';

			$latestAttendance = DB::table($coach_attend_tbl)
				->select('coach_id', 'attendance_date', DB::raw('MAX(created_at) as max_created'))
				->where('attendance_date', $filterDate)
				->groupBy('coach_id', 'attendance_date')

				->when($request->filled('in_time'), function ($query) use ($request) {
					return $query->where('in_time', $request->in_time);
				})

				->when($request->filled('out_time'), function ($query) use ($request) {
					return $query->where('out_time', $request->out_time);
				});



			$coach_attend = DB::table($coach_attend_tbl . ' as ca')
				->joinSub($latestAttendance, 'latest', function ($join) {
					$join->on('ca.coach_id', '=', 'latest.coach_id')
						->on('ca.attendance_date', '=', 'latest.attendance_date')
						->on('ca.created_at', '=', 'latest.max_created');
				})
				->join('coaches', 'coaches.id', '=', 'ca.coach_id')
				->join('batches', 'batches.coach_id', '=', 'ca.coach_id')

				->when($request->filled('batch_id'), function ($query) use ($request) {
					return $query->where('batches.id', $request->batch_id);
				})

				->when($request->filled('coach_id'), function ($query) use ($request) {
					return $query->where('coaches.id', $request->coach_id);
				})

				->select(
					'ca.id',
					'ca.attendance_date',
					'ca.in_time',
					'ca.out_time',
					'ca.coach_id',
					'coaches.first_name',
					'coaches.middle_name',
					'coaches.last_name',

					'batches.batch_name',
				)
				->orderBy('ca.coach_id', 'asc')
				->get();



			return response()->json([
				'present_html' => view(
					'admin.attendance.partials.coaches_tbl',
					compact('coach_attend')
				)->render()
			]);
		}
	}


	//2. filter absent player attendance
	public function filterAbsentPlayer(Request $request)
	{
		$deptData = getDepartmentData();
		$dept_id = $deptData->dept_id;
		$dept_name = $deptData->dept_name;

		$dept = strtolower($dept_name);
		$filterDate = $request->input('date');


		if ($request->input('tab') === 'player_tab') {

			$player_attend_tbl = $dept . '_player_attendance';

			$absent_players = DB::table('players as pl')
				->join('dept_players as dp', 'dp.player_id', '=', 'pl.player_id')
				->join('batches', 'batches.id', '=', 'dp.batch_id')
				->join('coaches', 'coaches.id', '=', 'batches.coach_id')
				->where('dp.batch_id', $request->batch_id)
				->where('batches.coach_id', $request->coach_id)
				->whereNotIn('pl.player_id', function ($query) use ($player_attend_tbl, $filterDate) {
					$query->select('pa.player_id')
						->from($player_attend_tbl . ' as pa')
						->where('pa.attendance_date', $filterDate);
				})
				->select(
					'pl.player_id',
					'pl.first_name',
					'pl.middle_name',
					'pl.last_name',
					'batches.batch_name',
					'coaches.first_name as coach_fname',
					'coaches.middle_name as coach_mname',
					'coaches.last_name as coach_lname',

				)
				->get();




			return response()->json([

				'absent_html' => view(
					'admin.attendance.partials.player_absent_tbl',
					compact('absent_players', 'filterDate')
				)->render(),
			]);
		} elseif ($request->input('tab') === 'coach_tab') {

			$coach_attend_tbl = $dept . '_coach_attendance';

			$absent_coaches = DB::table('coaches as ch')
				->join('batches', 'batches.coach_id', '=', 'ch.id')
				->where('ch.id', $request->coach_id)
				->where('batches.id', $request->batch_id)
				->whereNotIn('ch.id', function ($query) use ($coach_attend_tbl, $filterDate) {
					$query->select('ch.id')
						->from($coach_attend_tbl . ' as ca')
						->where('ca.attendance_date', $filterDate);
				})
				->select(

					'batches.batch_name',
					'ch.id',
					'ch.first_name',
					'ch.middle_name',
					'ch.last_name',

				)
				->get();

			return response()->json([

				'absent_html' => view(
					'admin.attendance.partials.coach_absent_tbl',
					compact('absent_coaches', 'filterDate')
				)->render(),
			]);
		}
	}



	// update player attend
	public function playerUpdateAttendance(Request $request, $id)
	{
		$dept = getDepartmentData();
		$dept_id = $dept->dept_id;

		$deptName = strtolower($dept->dept_name);
		$deptName = preg_replace('/[^a-z0-9]/', '_', $deptName);
		$table = $deptName . '_player_attendance';

		if ($request->in_time && $request->out_time && $request->in_time >= $request->out_time) {
			return response()->json([
				'success' => false,
				'message' => 'The entered time is invalid. Please provide a valid time.'
			]);
		}

		$updated = DB::table($table)
			->where('id', $id)
			->where('dept_id', $dept_id)
			->update([
				'in_time' => $request->in_time ?: null,
				'out_time' => $request->out_time ?: null,
				'status' => $request->status,
			]);
		if (!$updated) {
			return response()->json([
				'success' => false,
				'message' => 'Record not found or unauthorized'
			], 404);
		}
		return response()->json([
			'success' => true,
			'message' => 'Player Attendance Updated Successfully'
		]);
	}
}
