<?php

namespace App\Http\Controllers\v1_01;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\Department;
use App\Models\DepartmentPlayers;
use App\Models\Player;
use App\Models\PlayerParent;
use App\Models\BatchLog;
use App\Models\PlayerAttendance;
use App\Models\CoachAttendance;
use App\Models\FeesValidity;
use App\Models\Report;
use App\Models\User;
use App\Models\AccessLevels;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    protected $coach;
    protected $player;
    protected $department;
    protected $playerAttendace;
    protected $report;
    protected $batchLog;
    protected $batches;
    protected $feesValidity;
    protected $departmentPlayers;
    protected $user;
    protected $coachAttendance;
    protected $accessLevel;
    protected $playerParent;

    public function __construct(
        Coach $coach,
        Player $player,
        Department $department,
        PlayerAttendance $playerAttendace,
        Report $report,
        BatchLog $batchLog,
        FeesValidity $feesValidity,
        DepartmentPlayers $departmentPlayers,
        User $user,
        Batch $batches,
        CoachAttendance $coachAttendance,
        AccessLevels $accessLevel,
        PlayerParent $playerParent
    ) {
        $this->coach             = $coach;
        $this->department        = $department;
        $this->player            = $player;
        $this->playerAttendace   = $playerAttendace;
        $this->report            = $report;
        $this->batchLog          = $batchLog;
        $this->batches           = $batches;
        $this->feesValidity      = $feesValidity;
        $this->departmentPlayers = $departmentPlayers;
        $this->user              = $user;
        $this->coachAttendance   = $coachAttendance;
        $this->accessLevel       = $accessLevel;
        $this->playerParent      = $playerParent;
    }

    // -------------------------------------------------------
    // PRIVATE HELPER
    // Resolves dept name & id for every method
    // SuperAdmin  → reads X-Dept-Id header
    // Normal user → uses their own dept
    // -------------------------------------------------------
    private function resolveDept(): array
    {
        $user     = auth()->user();
        $deptName = $user->resolveDeptName();
        $deptId   = $user->resolveDeptId();

        if (!$deptName || !$deptId) {
            abort(response()->json([
                'status'  => 0,
                'message' => 'Please select a department first.',
            ], 422));
        }

        return [$deptName, $deptId];
    }

    // -------------------------------------------------------
    // No dept logic — unchanged
    // -------------------------------------------------------
    public function loadCoach($id)
    {
        try {
            $data = $this->coach->getCoachData($id);
            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function loadPlayer(Request $request)
    {
        try {
            $player  = $this->player->loadPlayer($request->batch_id, $request->coach_id);
            $coaches = $this->coach->loadCoach($request->batch_id, $request->coach_id);
            return response()->json([
                'status'  => 1,
                'message' => 'Player and coach data retrieved successfully!',
                'player'  => $player,
                'coaches' => $coaches,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong while fetching player and coach data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function Department(Request $request)
    {
        try {
            $player  = $this->player->loadPlayer();
            $coaches = $this->coach->loadCoach();

            return response()->json([
                'status'  => 1,
                'message' => 'Player and coach data retrieved successfully!',
                'player'  => $player,
                'coaches' => $coaches,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong while fetching player and coach data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function loadDepartment()
    {
        try {
            $department = $this->department->loadDepartment();
            return response()->json([
                'status'     => 1,
                'message'    => 'Department data retrieved successfully!',
                'department' => $department,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong while fetching department data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function loadPlayerById(Request $request)
    {
        try {
            $player = $this->player->loadPlayerByIds($request);

            if (empty($player)) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Player not found',
                ], 404);
            }

            return response()->json([
                'status'  => 1,
                'message' => 'Player data retrieved successfully!',
                'player'  => $player,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong while fetching player data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function report(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'dept_id'     => 'required|integer',
            'player_id'   => 'required|string',
            'batch_id'    => 'required|integer',
            'description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $report = $this->report->saveReport($validator->validated());

            if (!$report) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Report could not be saved',
                ], 500);
            }

            return response()->json([
                'status'  => 1,
                'message' => 'Report saved successfully!',
                'report'  => $report,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong while saving report.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function loadAccessLevel()
    {
        try {
            $accessLevel = $this->accessLevel->loadAccessLevel();

            return response()->json([
                'status'  => 1,
                'message' => 'Access levels',
                'data'    => $accessLevel,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateAuthenticateUserProfile(Request $request)
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
            ]);

            $updatedUser = $this->user->updateAuthUserProfile(
                $request->only(['name'])
            );

            if (!$updatedUser) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Unauthenticated user',
                ], 401);
            }

            return response()->json([
                'status'  => 1,
                'message' => 'Profile updated successfully',
                'data'    => $updatedUser,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — getAuthenticateUserProfile
    // SuperAdmin → returns departments list
    // Normal user → returns coach attendance count
    // -------------------------------------------------------
    // public function getAuthenticateUserProfile()
    // {
    //     try {
    //         $user = auth()->user();

    //         // SuperAdmin gets departments list for grid screen
    //         if ($user->isSuperAdmin()) {
    //             $departments = $this->department->loadDepartment();

    //             return response()->json([
    //                 'status'      => 1,
    //                 'message'     => 'SuperAdmin Profile',
    //                 'data'        => $user,
    //                 'departments' => $departments,
    //             ]);
    //         }

    //         // Normal user
    //         $coachAttendanceCount = null;

    //         if ($user->role_id == 3 && $user->dept_id) {
    //             $deptName = $user->resolveDeptName();

    //             if ($deptName) {
    //                 $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
    //                 $endOfMonth   = Carbon::now()->endOfMonth()->toDateString();

    //                 $present = DB::table($deptName . '_coach_attendance')
    //                     ->where('dept_id', $user->dept_id)
    //                     ->where('status', 1)
    //                     ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
    //                     ->count();

    //                 $absent = DB::table($deptName . '_coach_attendance')
    //                     ->where('dept_id', $user->dept_id)
    //                     ->where('status', 0)
    //                     ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
    //                     ->count();

    //                 $coachAttendanceCount = [
    //                     'present' => $present,
    //                     'absent'  => $absent,
    //                 ];
    //             }
    //         }

    //         return response()->json([
    //             'status'          => 1,
    //             'message'         => 'User Authenticate Profile',
    //             'data'            => $user,
    //             'coachAttendance' => $coachAttendanceCount,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status'  => 0,
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function getAuthenticateUserProfile()
    {
        try {
            $user = auth()->user();
            $user->load(['userRole', 'userDept']);

            /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */
            if ($user->isSuperAdmin()) {

                $departments = $this->department->loadDepartment();

                return response()->json([
                    'status'      => 1,
                    'message'     => 'SuperAdmin Profile',
                    'data'        => $user,
                    'departments' => $departments,
                ]);
            }

            $coachAttendanceCount = null;
            $department = [];

            /*
        |--------------------------------------------------------------------------
        | Coach User (Multiple Departments)
        |--------------------------------------------------------------------------
        */
            $isCoach = $user->userRole &&
                strtolower($user->userRole->role_name) === 'coach';

            if ($isCoach) {

                if (!empty($user->document_number)) {

                    $coaches = \App\Models\Coach::with('department')
                        ->where('document', $user->document_number)
                        ->whereNull('deleted_at')
                        ->get();

                    $department = $coaches->map(function ($coach) {
                        return $coach->department ? [
                            'id'       => $coach->department->dept_id,
                            'name'     => $coach->department->dept_name,
                            'coach_id' => $coach->id,
                        ] : null;
                    })->filter()->unique('id')->values();

                    /*
                |--------------------------------------------------------------------------
                | Attendance Count of All Departments
                |--------------------------------------------------------------------------
                */
                    $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
                    $endOfMonth   = Carbon::now()->endOfMonth()->toDateString();

                    $totalPresent = 0;
                    $totalAbsent  = 0;

                    foreach ($department as $dept) {

                        $deptId   = $dept['id'];
                        $deptName = strtolower(str_replace(' ', '_', $dept['name']));

                        $table = $deptName . '_coach_attendance';

                        if (Schema::hasTable($table)) {

                            $present = DB::table($table)
                                ->where('dept_id', $deptId)
                                ->where('status', 1)
                                ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
                                ->count();

                            $absent = DB::table($table)
                                ->where('dept_id', $deptId)
                                ->where('status', 0)
                                ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
                                ->count();

                            $totalPresent += $present;
                            $totalAbsent  += $absent;
                        }
                    }

                    $coachAttendanceCount = [
                        'present' => $totalPresent,
                        'absent'  => $totalAbsent,
                    ];
                }
            } else {

                /*
            |--------------------------------------------------------------------------
            | Normal User
            |--------------------------------------------------------------------------
            */
                $department = $user->userDept ? [[
                    'id'   => $user->userDept->dept_id,
                    'name' => $user->userDept->dept_name
                ]] : [];
            }

            return response()->json([
                'status'          => 1,
                'message'         => 'User Authenticate Profile',
                'data'            => $user,
                'department'      => $department,
                'coachAttendance' => $coachAttendanceCount,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — markAttendance
    // -------------------------------------------------------
    public function markAttendance(Request $request)
    {
        try {
            $attendance = $this->playerAttendace->markAttendance($request);

            return response()->json([
                'status'  => 1,
                'message' => 'Attendance marked: ' . $attendance['action'],
                'data'    => $attendance['data'],
            ], 200);
        } catch (\InvalidArgumentException $e) {
            // Validation / business rule violations → 422
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 200);
        } catch (\Exception $e) {
            // General exceptions (dept not found, etc.) → 400
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 200);
        } catch (\Throwable $e) {
            // Unexpected server errors → 500
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — loadAttendance
    // -------------------------------------------------------
    public function loadAttendance(Request $request)
    {
        try {
            if ($request->filled('player_attendance_id') && $request->filled('in_time')) {
                $attendance = $this->playerAttendace->updateAttendance($request);

                if (!$attendance) {
                    return response()->json([
                        'status'  => 0,
                        'message' => 'Attendance not found',
                    ], 404);
                }

                return response()->json([
                    'status'     => 1,
                    'message'    => 'Attendance updated successfully',
                    'attendance' => $attendance,
                ], 200);
            }

            $player = $this->player->loadPlayerAttendance($request);

            if ($player->isEmpty()) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Player not found',
                ], 200);
            }

            return response()->json([
                'status'  => 1,
                'message' => 'Player and coach attendance data retrieved successfully!',
                'player'  => $player,
                'coach'   => [],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — coachById
    // -------------------------------------------------------
    public function coachById(Request $request)
    {
        try {
            [$deptName, $deptId] = $this->resolveDept();

            $validated = $request->validate([
                'coach_id' => 'required|string|exists:coaches,id',
                'month'    => 'nullable|integer|min:1|max:12',
                'year'     => 'nullable|integer|min:2000|max:2100',
            ]);

            $coach = Coach::with(
                'department:dept_id,dept_name',
                'batch:id,coach_id,batch_name'
            )->where('id', $validated['coach_id'])->first();


            if (!$coach || $coach->dept_id !== $deptId) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'This coach does not belong to the selected department.',
                ], 403);
            }

            $date = (!empty($validated['month']) && !empty($validated['year']))
                ? Carbon::createFromDate((int)$validated['year'], (int)$validated['month'], 1)
                : Carbon::now();

            $attendance = (new CoachAttendance)->getCoachMonthlyAttendance(
                $validated['coach_id'],
                $date->month,
                $date->year,
                $coach->department->dept_name
            );

            return response()->json([
                'status'     => 1,
                'message'    => 'Coach details with attendance',
                'coach'      => $coach,
                'attendance' => $attendance,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — getAllBatchLogs
    // -------------------------------------------------------
    public function getAllBatchLogs(Request $request)
    {
        try {
            $validated = $request->validate([
                'player_id' => 'required',
            ]);

            $batchLogs = $this->batchLog->getPlayerBatchLogs($validated['player_id']);

            return response()->json([
                'status'  => 1,
                'message' => $batchLogs->isEmpty()
                    ? 'No batch logs found for this player'
                    : 'Player batch logs retrieved successfully',
                'data'    => $batchLogs,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — addBatchLog
    // -------------------------------------------------------
    public function addBatchLog(Request $request)
    {
        try {
            $validated = $request->validate([
                'player_id'  => 'required|string|exists:dept_players,player_id',
                'batch_id'   => 'required|integer|exists:batches,id',
                'coach_id'   => 'required|string|exists:coaches,id',
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
            ]);

            $result = $this->batchLog->addBatchLogData($validated);

            if (is_array($result) && isset($result['status']) && $result['status'] === false) {
                return response()->json([
                    'status'  => 0,
                    'message' => $result['message'],
                ], 403);
            }

            return response()->json([
                'status'  => 1,
                'message' => 'Batch log saved successfully!',
                'data'    => $result,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — updateBatchLog
    // -------------------------------------------------------
    public function updateBatchLog(Request $request)
    {
        try {
            [$deptName, $deptId] = $this->resolveDept();

            $deptTable = $deptName . '_batch_logs';

            $validated = $request->validate([
                'id'         => "required|integer|exists:{$deptTable},id",
                'player_id'  => 'required|string|exists:dept_players,player_id',
                'batch_id'   => 'required|integer|exists:batches,id',
                'coach_id'   => 'required|string|exists:coaches,id',
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
            ]);

            $result = $this->batchLog->updateBatchLog($validated);

            if (is_array($result) && !$result['status']) {
                return response()->json([
                    'status'  => 0,
                    'message' => $result['message'],
                ], 403);
            }

            return response()->json([
                'status'  => 1,
                'message' => 'Batch log updated successfully!',
                'data'    => $result['data'],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — getBatchesCoaches
    // -------------------------------------------------------
    public function getBatchesCoaches()
    {
        try {
            $batchesCoaches = $this->batchLog->getAllBatchesCoaches();
            return response()->json([
                'status'  => 1,
                'message' => 'Batches and coaches retrieved successfully',
                'data'    => $batchesCoaches,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — playerById
    // -------------------------------------------------------
    public function playerById(Request $request)
    {
        try {
            [$deptName, $deptId] = $this->resolveDept();

            $validated = $request->validate([
                'player_id' => 'required|string|exists:dept_players,player_id',
                'month'     => 'nullable|integer|min:1|max:12',
                'year'      => 'nullable|integer|min:2000|max:2100',
            ]);

            $feeTable = $deptName . '_fee_validity';

            $player = $this->player->getPlayerIdAttendance($validated['player_id'], $deptId);

            if (!$player) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'This player does not belong to the selected department.',
                ], 403);
            }

            $date = (!empty($validated['month']) && !empty($validated['year']))
                ? Carbon::createFromDate((int)$validated['year'], (int)$validated['month'], 1)
                : Carbon::now();

            $attendance = $this->playerAttendace->getPlayerAttendanceMonthYear(
                $validated['player_id'],
                $date->month,
                $date->year,
                $deptId,
                $player->dept->dept_name
            );

            $deptPlayerIds = DepartmentPlayers::where('player_id', $validated['player_id'])
                ->where('dept_id', $deptId)
                ->pluck('id')
                ->toArray();

            $playerFeesValidity = DB::table($feeTable)
                ->whereIn('dept_player_id', $deptPlayerIds)
                ->orderBy('valid_to', 'desc')
                ->get();

            $playerParents = $this->playerParent->getPlayerParents(
                $validated['player_id'],
                $deptId
            );

            return response()->json([
                'status'             => 1,
                'message'            => 'Player details with attendance & Fees Validity',
                'player'             => $player,
                'attendance'         => $attendance,
                'playerFeesValidity' => $playerFeesValidity,
                'parents'            => $playerParents,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — playerByFeesDeatails
    // -------------------------------------------------------
    public function playerByFeesDeatails(Request $request)
    {
        try {
            [$deptName, $deptId] = $this->resolveDept();
            $request->validate([
                'player_id' => 'required|string|exists:dept_players,player_id',
            ]);
            $user       = auth()->user();
            $deptPlayer = $this->departmentPlayers->getDepartmentPlayers(
                $request->player_id,
                $user
            );
            if (!$deptPlayer) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Player not found in this department',
                ], 403);
            }

            $fees = $this->feesValidity->getFeesValidity($deptPlayer->id, $deptId);

            return response()->json([
                'status' => 1,
                'data'   => $fees,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — addPlayerFee
    // -------------------------------------------------------
    public function addPlayerFee(Request $request)
    {
        try {
            $this->resolveDept();

            $data = $request->validate([
                'player_id'  => 'required|string|exists:dept_players,player_id',
                'valid_from' => 'required|date',
                'valid_to'   => 'required|date|after_or_equal:valid_from',
                'receipt_no' => 'required',
                'amount'     => 'required|numeric|min:0',
                'date'       => 'required|date',
            ]);

            $user       = auth()->user();
            $deptPlayer = $this->departmentPlayers->getDepartmentPlayers(
                $data['player_id'],
                $user
            );

            if (!$deptPlayer) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'This player does not belong to your department',
                ], 403);
            }

            $fee = $this->feesValidity->insertfeesdetailsByDepa($deptPlayer, $user, $data);

            return response()->json([
                'status'  => 1,
                'message' => 'Fee added successfully',
                'data'    => $fee,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — loadfilters
    // -------------------------------------------------------
    public function loadfilters(Request $request)
    {
        try {
            // ✅ Batches
            $batches = $this->batches->loadBatch($request)->map(function ($b) {
                return [
                    'id'         => $b->id,
                    'batch_name' => $b->batch_name,
                ];
            })->values();

            // ✅ Coaches
            $coaches = $this->coach->loadCoach()->map(function ($c) {
                return [
                    'id'         => $c->id,
                    'coach_name' => trim(
                        ($c->first_name ?? '') . ' ' .
                            ($c->middle_name ?? '') . ' ' .
                            ($c->last_name ?? '')
                    ),
                ];
            })->values();

            return response()->json([
                'status' => 1,
                'data'   => [
                    'batches' => $batches,
                    'coaches' => $coaches,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — loadCoachAttendance
    // -------------------------------------------------------
    public function loadCoachAttendance(Request $request)
    {
        try {
            if ($request->filled('coach_attendance_id')) {
                $attendance = $this->coachAttendance->updateCoachAttendance($request);

                if (!$attendance) {
                    return response()->json([
                        'status'  => 0,
                        'message' => 'Coach attendance not found',
                    ], 404);
                }

                return response()->json([
                    'status'     => 1,
                    'message'    => 'Coach attendance updated successfully',
                    'attendance' => $attendance,
                ], 200);
            }

            $coachAttendance = $this->coach->loadCoachAttendance($request);

            return response()->json([
                'status' => 1,
                'data'   => $coachAttendance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — addUpdateCoachAttendance
    // -------------------------------------------------------
    // public function addUpdateCoachAttendance(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'coach_id'        => 'required',
    //         'dept_id'         => 'required',
    //         'attendance_date' => 'required|date',
    //         'in_time'         => 'nullable|date_format:H:i:s',
    //         'out_time'        => 'nullable|date_format:H:i:s',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status'  => 0,
    //             'message' => 'Validation Error',
    //             'errors'  => $validator->errors(),
    //         ], 201);
    //     }

    //     if (empty($request->in_time) && empty($request->out_time)) {
    //         return response()->json([
    //             'status'  => 0,
    //             'message' => 'Please provide either in_time or out_time.',
    //         ], 201);
    //     }

    //     try {
    //         $result = $this->coachAttendance->addCoachAttendance(
    //             $validator->validated(),
    //             auth()->id()
    //         );

    //         $isCreated = $result === 'created';

    //         return response()->json([
    //             'status'  => 1,
    //             'message' => $isCreated
    //                 ? 'Coach check-in marked successfully.'
    //                 : 'Coach check-out updated successfully.',
    //             'data' => [
    //                 'coach_id'        => $request->coach_id,
    //                 'attendance_date' => $request->attendance_date,
    //                 'action'          => $isCreated ? 'check-in' : 'check-out',
    //                 'time'            => $isCreated
    //                     ? ($request->in_time  ?? date('H:i:s'))
    //                     : ($request->out_time ?? date('H:i:s')),
    //             ],
    //         ], $isCreated ? 201 : 200);
    //     } catch (\InvalidArgumentException $e) {
    //         return response()->json([
    //             'status'  => 0,
    //             'message' => $e->getMessage(),
    //         ], 201);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status'  => 0,
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function addUpdateCoachAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coach_id'        => 'required',
            'dept_id'         => 'required',
            'attendance_date' => 'required|date',
            'in_time'         => 'nullable|date_format:H:i:s',
            'out_time'        => 'nullable|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 201);
        }

        if (empty($request->in_time) && empty($request->out_time)) {
            return response()->json([
                'status'  => 0,
                'message' => 'Please provide either in_time or out_time.',
            ], 201);
        }

        try {

            $result = $this->coachAttendance->addCoachAttendance(
                $validator->validated(),
                auth()->id()
            );

            $message = '';
            $action  = '';
            $time    = '';

            /**
             * ============================================================
             * CHECK RESPONSE TYPE
             * ============================================================
             */

            if ($result == 'First IN inserted in main table') {

                $message = 'Coach check-in marked successfully.';
                $action  = 'check-in';
                $time    = $request->in_time ?? date('H:i:s');
            } elseif ($result == 'OUT stored in temp table') {

                $message = 'Coach check-out marked successfully.';
                $action  = 'check-out';
                $time    = $request->out_time ?? date('H:i:s');
            } elseif ($result == 'IN stored in temp and last OUT updated in main') {

                $message = 'Coach check-in marked successfully.';
                $action  = 'check-in';
                $time    = $request->in_time ?? date('H:i:s');
            } else {

                $message = $result;
                $action  = 'unknown';
                $time    = date('H:i:s');
            }

            return response()->json([
                'status'  => 1,
                'message' => $message,
                'data'    => [
                    'coach_id'        => $request->coach_id,
                    'attendance_date' => $request->attendance_date,
                    'action'          => $action,
                    'time'            => $time,
                ],
            ], 200);
        } catch (\InvalidArgumentException $e) {

            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — getAssesments
    // -------------------------------------------------------
    public function getAssesments(Request $request)
    {
        try {
            [$deptName, $deptId] = $this->resolveDept();

            $request->validate([
                'player_id' => 'required',
            ]);

            $table = $deptName . '_assessments';

            $deptPlayer = DB::table('dept_players')
                ->where('player_id', $request->player_id)
                ->where('dept_id', $deptId)
                ->whereNull('deleted_at')
                ->first();

            if (!$deptPlayer) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Player not found in this department',
                ], 404);
            }

            $assessments = DB::table($table)
                ->where('dept_player_id', $deptPlayer->id)
                ->whereNull('deleted_at')
                ->orderByDesc('assessment_date')
                ->get();

            return response()->json([
                'status' => 1,
                'data'   => $assessments,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — addAssesment
    // -------------------------------------------------------
    public function addAssesment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id'           => 'required',
            'assessment_date'     => 'required|date',
            'foot'                => 'required|numeric',
            'inch'                => 'required|numeric',
            'weight'              => 'required|numeric',
            'sprint'              => 'required|numeric',
            'standing_broad_jump' => 'required|numeric',
            'jump_with_stepping'  => 'required|numeric',
            'vertical_jump'       => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            [$deptName, $deptId] = $this->resolveDept();

            $table = $deptName . '_assessments';

            $deptPlayer = DB::table('dept_players')
                ->where('player_id', $request->player_id)
                ->where('dept_id', $deptId)
                ->whereNull('deleted_at')
                ->first();

            if (!$deptPlayer) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Player not found in this department',
                ], 404);
            }

            DB::table($table)->insert([
                'dept_player_id'      => $deptPlayer->id,
                'assessment_date'     => $request->assessment_date,
                'foot'                => $request->foot,
                'inch'                => $request->inch,
                'weight'              => $request->weight,
                'sprint'              => $request->sprint,
                'standing_broad_jump' => $request->standing_broad_jump,
                'jump_with_stepping'  => $request->jump_with_stepping,
                'vertical_jump'       => $request->vertical_jump,
                'approved_by'         => $request->approved_by,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            return response()->json([
                'status'  => 1,
                'message' => 'Assessment added successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — updateAssesment
    // -------------------------------------------------------
    public function updateAssesment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                  => 'required|integer',
            'assessment_date'     => 'required|date',
            'foot'                => 'required|numeric',
            'inch'                => 'required|numeric',
            'weight'              => 'required|numeric',
            'sprint'              => 'required|numeric',
            'standing_broad_jump' => 'required|numeric',
            'jump_with_stepping'  => 'required|numeric',
            'vertical_jump'       => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            [$deptName, $deptId] = $this->resolveDept();

            $table = $deptName . '_assessments';

            $assessment = DB::table($table)
                ->join('dept_players', 'dept_players.id', '=', $table . '.dept_player_id')
                ->where($table . '.id', $request->id)
                ->where('dept_players.dept_id', $deptId)
                ->select($table . '.*')
                ->first();

            if (!$assessment) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Assessment not found or unauthorized',
                ], 404);
            }

            DB::table($table)->where('id', $request->id)->update([
                'assessment_date'     => $request->assessment_date,
                'foot'                => $request->foot,
                'inch'                => $request->inch,
                'weight'              => $request->weight,
                'sprint'              => $request->sprint,
                'standing_broad_jump' => $request->standing_broad_jump,
                'jump_with_stepping'  => $request->jump_with_stepping,
                'vertical_jump'       => $request->vertical_jump,
                'approved_by'         => $request->approved_by,
                'updated_at'          => now(),
            ]);

            return response()->json([
                'status'  => 1,
                'message' => 'Assessment updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — getLatitudeLongitude
    // -------------------------------------------------------
    public function getLatitudeLongitude()
    {
        try {
            $user = auth()->user();
            if ($user->role_id == 3) {
                $departments = Coach::where('document', $user->document_number)
                    ->whereNotNull('dept_id')
                    ->pluck('dept_id');
                $latLongData = $this->department
                    ->whereIn('dept_id', $departments)
                    ->select('dept_id', 'latitude', 'longitude', 'geofence_radius')
                    ->get();
            } else {
                [$deptName, $deptId] = $this->resolveDept();
                $latLongData = $this->department->getDeptLatLong($deptId);
            }
            return response()->json([
                'status'  => 1,
                'data'    => $latLongData,
                'message' => 'Getting Data Of Latitude & Longitude successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    // -------------------------------------------------------
    // FIXED — get player documents
    // -------------------------------------------------------

    public function getPlayerDocument(Request $request)
    {
        try {
            [$deptName, $deptId] = $this->resolveDept();

            $request->validate([
                'player_id' => 'required'
            ]);

            $table = $deptName . '_documents';

            $documents = DB::table($table)
                ->where('player_id', $request->player_id)
                ->whereNull('deleted_at')
                ->get();

            if (!$documents) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Document not found!',
                ]);
            }

            return response()->json([
                'status' => 1,
                'data' => $documents,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — get player documents
    // -------------------------------------------------------

    public function addPlayerDocument(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'dept_id' => 'required',
            'player_id' => 'required',
            'document_name' => 'required',
            'file' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 0,
                'message' => "Validation Error !",
                'error' => $validation->errors(),
            ], 422);
        }

        try {
            [$deptName, $deptId] = $this->resolveDept();

            $table = $deptName . '_documents';

            $player_id = $request->input('player_id');
            $document_name = $request->input('document_name');
            $playerFile = $request->file('file');

            if ($playerFile) {
                $filename = time() . '_' . $document_name . '.' . $playerFile->getClientOriginalExtension();
                $folder = "images/$deptName/players/$player_id/documents";

                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                $playerFile->move($folder, $filename);
                $imgPath = $folder . '/' . $filename;
            }

            DB::table($table)->insert([
                'player_id' => $player_id,
                'document_name' => $document_name,
                'file' => $imgPath,
                'description' => $request->description ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'Player Document added successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — get coach batch log
    // -------------------------------------------------------

    public function getCoachBatchLog(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'coach_id' => 'required',
            'dept_id' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 0,
                'message' => "Validation Error !",
                'error' => $validation->errors(),
            ], 422);
        }

        try {
            [$deptName, $deptId] = $this->resolveDept();

            $table = $deptName . '_coach_logs';
            $current_batch = DB::table($table)
                ->where('coach_id', $request->coach_id)
                ->whereNull('deleted_at')
                ->where('status', 'current_batch')
                ->get();

            $previous_batch = DB::table($table)
                ->where('coach_id', $request->coach_id)
                ->whereNull('deleted_at')
                ->where('status', 'previous_batch')
                ->get();

            if (!$current_batch && !$previous_batch) {
                return response->json([
                    'status' => 0,
                    'message' => 'Batch not assign !',
                ]);
            }


            return response()->json([
                'status' => 1,
                'data' => [
                    'current_batch' => $current_batch,
                    'previous_batch' => $previous_batch,
                ]
            ]);
        } catch (\Exception $e) {
            return response->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------
    // FIXED — addUpdateCoachAttendance
    // -------------------------------------------------------
    public function updateCoachAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coach_id'        => 'required',
            'dept_id'         => 'required',
            'attendance_date' => 'required|date',
            'in_time'         => 'nullable|date_format:H:i:s',
            'out_time'        => 'nullable|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 200);
        }

        // At least one time field must be present
        if (empty($request->in_time) && empty($request->out_time)) {
            return response()->json([
                'status'  => 0,
                'message' => 'Please provide either in_time or out_time.',
            ], 201);
        }

        try {
            $result    = $this->coachAttendance->addCoachAttendance(
                $validator->validated(),
                auth()->id()
            );
            $isCreated = $result === 'created';

            // Build response data — only include time fields that were provided
            $responseData = [
                'coach_id'        => $request->coach_id,
                'attendance_date' => $request->attendance_date,
                'action'          => $isCreated ? 'created' : 'updated',
            ];

            if (!empty($request->in_time)) {
                $responseData['in_time'] = $request->in_time;
            }
            if (!empty($request->out_time)) {
                $responseData['out_time'] = $request->out_time;
            }

            return response()->json([
                'status'  => 1,
                'message' => $isCreated
                    ? 'Coach attendance created successfully.'
                    : 'Coach attendance updated successfully.',
                'data'    => $responseData,
            ], $isCreated ? 201 : 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
