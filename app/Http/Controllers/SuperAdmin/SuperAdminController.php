<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\{Player, Coach, Department, User, Role, DepartmentPlayers, AccessLevels};
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class SuperAdminController extends Controller
{
    protected $dept;
    protected $player;
    protected $coach;
    protected $user;
    protected $role;
    protected $dept_player;
    protected $access_levels;
    public function __construct(Department $dept, Player $player, Coach $coach, User $user, Role $role, DepartmentPlayers $dept_player, AccessLevels $access_levels)
    {
        $this->dept = $dept;
        $this->player = $player;
        $this->coach = $coach;
        $this->user = $user;
        $this->role = $role;
        $this->dept_player = $dept_player;
        $this->access_levels = $access_levels;
    }

    public function index($deptName = null)
    {
        try {

            session()->forget('department_id');

            $data['topmenu'] = 'Dashboard1';
            $data['submenu'] = 'Dashboard1';
            $data['pagetitle'] = 'Dashboard1';

            $depts =  $this->dept->getDeptData($deptName);
            // for search dept
            if ($deptName != null) {
                return response()->json([
                    'status' => 'success',
                    'dept' => $depts
                ]);
            }

            return view('super-admin/index', compact('data', 'depts'));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    //department
    //********************* User ************************** */
    public function addUser()
    {
        try {
            $roles = $this->role->getUserRoles();
            return view('super-admin.addUser', ['roles' => $roles]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    protected function validateUserData(Request $request, $id = null)
    {
        return $request->validate(
            [
                'role_id'  => 'required',
                'name'   => 'required|string|max:255',
                'contact_number' => 'required',
                'email' => $id ? 'required|email|unique:users,email,' . $id : 'required|email|unique:users,email',
                'password' => $id ? 'nullable|min:6' : 'required|min:6',
            ],
            [
                'role_id.required' => 'Role is required',
                'name.required' => 'Name is required',
                'contact_number.required' => 'Contact number is required',
                'email.required' => 'Email is required',
                'email.email' => 'Please enter valid email',
                'email.unique' => 'Email already exists',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 6 characters',
            ]
        );
    }

    public function addUserData(Request $request)
    {
        $validatedData = $this->validateUserData($request);
        try {
            $this->user->addUserData($validatedData);
            return redirect()->route('userList')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function editUser($id)
    {
        try {
            $user = $this->user->editUserData($id);
            if (!$user) {
                return redirect()->route('userList')->with('error', 'User is not in our system.');
            }
            $roles = $this->role->getUserRoles();
            return view('super-admin.editUser', ['user' => $user, 'roles' => $roles]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        $this->validateUserData($request, $id);
        try {
            $userData = $request->all();
            $this->user->updateUserData($userData, $id);
            return redirect()->route('userList')->with('success', 'User Updated successfully');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function userList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->user->getUserList();
                return DataTables::of($query)
                    ->editColumn('role_id', function ($user) {
                        $userRollName = $user->userRole->role_name;
                        return $userRollName;
                    })
                    ->make(true);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
        return view('super-admin.userList');
    }

    public function deleteUser($id)
    {
        try {
            $deleteUserData = $this->user->editUserData($id);
            $this->user->deleteUserData($deleteUserData);
            return redirect()->route('userList')->with('success', 'User Deleted successfully');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //********************* player & coach ************************** */

    public function playerProfileShow($id)
    {
        try {
            $data['topmenu'] = 'Player Profile';
            $data['submenu'] = 'player';
            $data['pagetitle'] = 'Player Overview';
            $allPlayerIds = DB::table('players')
                ->where('aadhar_number', function ($query) use ($id) {
                    $query->select('aadhar_number')
                        ->from('players')
                        ->where('player_id', $id)
                        ->limit(1);
                })
                ->whereNull('deleted_at')
                ->pluck('player_id');
            // echo '<pre>';
            // print_r($allPlayerIds);
            // echo '</pre>';
            // die;
            $playerDepts = DB::table('players')
                ->join('dept_players', 'players.player_id', '=', 'dept_players.player_id')
                ->join('department', 'dept_players.dept_id', '=', 'department.dept_id')
                ->leftJoin('batches', 'dept_players.batch_id', '=', 'batches.id')
                ->leftJoin('coaches', 'batches.coach_id', '=', 'coaches.id')
                ->whereIn('players.player_id', $allPlayerIds)
                ->whereNull('players.deleted_at')
                ->select(
                    'players.player_id',
                    'players.status as player_status',
                    'players.foot',
                    'players.inch',
                    'players.weight',
                    'dept_players.id as dept_player_id',
                    'dept_players.dept_id',
                    'department.dept_name',
                    'batches.batch_name',
                    'coaches.first_name as coach_first_name',
                    'coaches.middle_name as coach_middle_name',
                    'coaches.last_name as coach_last_name',
                )
                ->get();
            $deptData = [];
            foreach ($playerDepts as $deptRecord) {
                $deptSlug = strtolower(preg_replace('/[^a-z0-9]/i', '_', $deptRecord->dept_name));
                $qr_table = $deptSlug . '_player_qrcodes';
                try {
                    $qrRow = DB::table($qr_table)
                        ->where('player_id', $deptRecord->player_id)
                        ->first();
                    $qrCode = $qrRow->qr_code ?? null;
                } catch (\Exception $e) {
                    $qrCode = null;
                }
                $deptData[] = [
                    'dept_id'           => $deptRecord->dept_id,
                    'dept_name'         => $deptRecord->dept_name,
                    'player_status'         => $deptRecord->player_status,
                    'player_id'         => $deptRecord->player_id,
                    'batch_name'        => $deptRecord->batch_name,
                    'coach_first_name'  => $deptRecord->coach_first_name,
                    'coach_middle_name' => $deptRecord->coach_middle_name,
                    'coach_last_name'   => $deptRecord->coach_last_name,
                    'qr_code'           => $qrCode,
                    'foot'   => $deptRecord->foot ?? null,
                    'inch'   => $deptRecord->inch ?? null,
                    'weight' => $deptRecord->weight ?? null,
                ];
            }
            $player = DB::table('players')
                ->where('players.player_id', $id)
                ->join('parent_names', function ($join) {
                    $join->on('players.player_id', '=', 'parent_names.player_id')
                        ->whereNull('parent_names.deleted_at');
                })
                ->select('players.*', 'parent_names.*')
                ->get();
            $playerBase = $player->first();
            $playerBase->parents = $player->map(function ($item) {
                return [
                    'parent_name'       => $item->parent_name,
                    'relation'          => $item->relation,
                    'parent_contact'    => $item->parent_contact,
                    'parent_email'      => $item->parent_email,
                    'parent_profession' => $item->parent_profession,
                ];
            });
            $firstQr = $deptData[0]['qr_code'] ?? null;
            $firstDeptSlug = strtolower(preg_replace('/[^a-z0-9]/i', '_', $deptData[0]['dept_name']));
            $feesData = [];

            foreach ($deptData as $dept) {

                $deptSlug = strtolower(preg_replace('/[^a-z0-9]/i', '_', $dept['dept_name']));

                try {
                    $fees = DB::table('dept_players')
                        ->where('dept_players.player_id', $dept['player_id'])
                        ->where('dept_players.dept_id', $dept['dept_id'])
                        ->join(
                            $deptSlug . '_fee_validity',
                            $deptSlug . '_fee_validity.dept_player_id',
                            '=',
                            'dept_players.id'
                        )
                        ->orderByDesc($deptSlug . '_fee_validity.valid_to')
                        ->first();

                    $feesData[$dept['dept_id']] = $fees;
                } catch (\Exception $e) {
                    $feesData[$dept['dept_id']] = null;
                }
            }
            $qrData = [];
            foreach ($deptData as $dept) {
                $qrData[$dept['dept_id']] = (object)[
                    'qr_code' => $dept['qr_code']
                ];
            }
            return view('super-admin/playerProfile', compact(
                'data',
                'playerBase',
                'deptData',
                'firstQr',
                'feesData',
                'qrData'
            ));
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function coachProfileShow($coach_id, $deptName)
    {
        try {
            $data['topmenu'] = 'Coach Profile';
            $data['submenu'] = 'Coach';
            $data['pagetitle'] = 'Coach Overview';
            $coachProfile = $this->coach->getCoachProfile($coach_id);
            $coachRecords = Coach::with(['department', 'latestBatch'])
                ->where('coach_email', $coachProfile->coach_email)
                ->get();
            $todays_date = Carbon::today()->toDateString();
            return view('super-admin/coachProfile', compact(
                'data',
                'coachProfile',
                'coachRecords'
            ));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function allPlayerList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->player->getPlayerList();
                return DataTables::of($query)
                    ->addColumn('player_name', function ($player) {
                        return trim($player->first_name . ' ' . $player->last_name);
                    })
                    ->filterColumn('player_name', function ($query, $keyword) {
                        $matchingAadhars = $this->player
                            ->whereRaw("CONCAT(first_name,' ',last_name) like ?", ["%{$keyword}%"])
                            ->whereNotNull('aadhar_number')
                            ->pluck('aadhar_number');
                        $query->where(function ($q) use ($keyword, $matchingAadhars) {
                            $q->whereRaw("CONCAT(players.first_name,' ',players.last_name) like ?", ["%{$keyword}%"])
                                ->orWhereIn('players.aadhar_number', $matchingAadhars);
                        });
                    })
                    ->addColumn('coach_name', function ($player) {
                        return $player->coach
                            ? $player->coach->first_name . ' ' . $player->coach->last_name
                            : 'No Coach Assigned';
                    })
                    ->filterColumn('coach_name', function ($query, $keyword) {
                        $query->whereHas('coach', function ($q) use ($keyword) {
                            $q->whereRaw("CONCAT(first_name,' ',last_name) like ?", ["%{$keyword}%"]);
                        });
                    })
                    ->addColumn('department_name', function ($player) {
                        $samePlayers = $this->player->where('aadhar_number', $player->aadhar_number)
                            ->whereNotNull('aadhar_number')
                            ->where('aadhar_number', '!=', '')
                            ->with('dept')
                            ->get();

                        if ($samePlayers->count() > 1) {
                            return $samePlayers->map(fn($p) => $p->dept->dept_name ?? '')->implode(', ');
                        }

                        return $player->dept->dept_name ?? '';
                    })
                    ->addColumn('player_id', function ($player) {
                        $samePlayers = $this->player->where('aadhar_number', $player->aadhar_number)
                            ->whereNotNull('aadhar_number')
                            ->where('aadhar_number', '!=', '')
                            ->get();
                        if ($samePlayers->count() > 1) {
                            return $samePlayers->map(fn($p) => $p->player_id)->implode(', ');
                        }
                        return $player->player_id;
                    })
                    ->filterColumn('department_name', function ($query, $keyword) {
                        $matchingAadhars = $this->player
                            ->whereHas('dept', function ($q) use ($keyword) {
                                $q->where('dept_name', 'like', "%{$keyword}%");
                            })
                            ->whereNotNull('aadhar_number')
                            ->pluck('aadhar_number');
                        $query->where(function ($q) use ($keyword, $matchingAadhars) {
                            $q->whereHas('dept', function ($dq) use ($keyword) {
                                $dq->where('dept_name', 'like', "%{$keyword}%");
                            })->orWhereIn('players.aadhar_number', $matchingAadhars);
                        });
                    })
                    ->addColumn('action', function ($player) {
                        return '<a href="' . route('player-profile-show', [
                            'id' => $player->player_id,
                        ]) . '">
                            <i class="bi bi-eye-fill"></i>
                        </a>';
                    })
                    ->filterColumn('player_id', function ($query, $keyword) {
                        $matchingAadhars = $this->player
                            ->where('player_id', 'like', "%{$keyword}%")
                            ->whereNotNull('aadhar_number')
                            ->pluck('aadhar_number');

                        $query->where(function ($q) use ($keyword, $matchingAadhars) {
                            $q->where('players.player_id', 'like', "%{$keyword}%")
                                ->orWhereIn('players.aadhar_number', $matchingAadhars);
                        });
                    })
                    ->filter(function ($query) {
                        $minIds = $this->player
                            ->whereNotNull('aadhar_number')
                            ->where('aadhar_number', '!=', '')
                            ->selectRaw('MIN(player_id) as min_id')
                            ->groupBy('aadhar_number')
                            ->pluck('min_id');
                        $nullAadharIds = $this->player
                            ->where(function ($q) {
                                $q->whereNull('aadhar_number')
                                    ->orWhere('aadhar_number', '=', '');
                            })
                            ->pluck('player_id');

                        $query->whereIn('players.player_id', $minIds->merge($nullAadharIds));
                    }, true)
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
        return view('super-admin.allPlayerList');
    }

    public function allCoachList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->coach->getCoachList();
                if ($request->has('deptId') && !empty($request->deptId)) {
                    $query->where('department_id', $request->deptId);
                }
                return DataTables::of($query)
                    ->addColumn('coach_full_name', function ($coach) {
                        return trim($coach->first_name . ' ' . $coach->middle_name . ' ' . $coach->last_name);
                    })
                    ->addColumn('coach_ids', function ($coach) {
                        return $coach->coach_ids;
                    })
                    ->addColumn('department_name', function ($coach) {
                        return $coach->department_names;
                    })
                    ->filterColumn('department_name', function ($query, $keyword) {
                        $query->whereExists(function ($q) use ($keyword) {
                            $q->select(DB::raw(1))
                                ->from('department as d')
                                ->whereColumn('d.dept_id', 'coaches.dept_id')
                                ->where('d.dept_name', 'like', "%{$keyword}%");
                        });
                    })
                    ->addColumn('action', function ($coach) {
                        $firstId   = trim(explode(',', $coach->coach_ids)[0]);
                        $firstDept = trim(explode(',', $coach->department_names)[0]);
                        return '<a href="' . route('coach-profile-show', [
                            'id'   => $firstId,
                            'dept' => $firstDept,
                        ]) . '" class="bi bi-eye-fill">
                        </a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
        return view('super-admin.allCoachList');
    }

    public function accessLevels(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->access_levels->getAccessLevels();
                return DataTables::of($query)
                    ->editColumn('role_id', function ($row) {
                        return $row->userRole ? $row->userRole->role_name : 'N/A';
                    })
                    ->addColumn('action', function ($row) {
                        return '<a href="' . route('editAccessLevels', $row->id) . '" 
                                class="btn-edit-action" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
        return view('super-admin.accessLevelList');
    }

    public function editAccessLevels(Request $request, $id)
    {
        $accessLevel = AccessLevels::with('userRole')->findOrFail($id);
        $roles = Role::orderBy('role_name')->get();
        return view('super-admin.editAccessLevel', compact('accessLevel', 'roles'));
    }

    public function updateAccessLevels(Request $request, $id)
    {
        try {
            $accessLevel = AccessLevels::findOrFail($id);

            $checkboxFields = [
                'player_view',
                'coach_view',
                'player_attendance_list',
                'coach_attendance_list',
                'player_overview',
                'player_attendance_summury',
                'player_assesment',
                'player_assessment_add',
                'player_assessment_edit',
                'player_batch_logs',
                'player_batch_log_add',
                'player_batch_log_edit',
                'player_fees_details',
                'player_fees_details_add',
                'player_documents',
                'player_documents_add',
                'login_user_attendance',
                'player_list',
                'coach_list',
                'edit_player_attendance',
                'batch_player_filter',
                'coach_player_filter',
                'coach_overview',
                'coach_batch_log',
                'in_time_player_filter',
                'out_time_player_filter',
                'coach_edit_attendance',
            ];
            $data = ['role_id' => $request->role_id];
            foreach ($checkboxFields as $field) {
                $data[$field] = $request->input($field) == '1' ? 1 : 0;
            }
            $accessLevel->update($data);
            return redirect()->route('accessLevels')->with('success', 'Access level updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
