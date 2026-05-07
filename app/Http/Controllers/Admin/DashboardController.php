<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use App\Models\Attendance;
use App\Models\Coach;
use App\Models\DepartmentPlayers;
use App\Models\DepartmentCoach;
use App\Models\Player;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// use Session;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $attendance;
    protected $coach;
    protected $player;
    protected $dept_player;
    protected $dept_coach;
    protected $dept;

    public function __construct(Attendance $attendance, Department $dept, Coach $coach, Player $player, DepartmentPlayers $dept_player, DepartmentCoach $dept_coach)
    {
        $this->attendance = $attendance;
        $this->coach = $coach;
        $this->player = $player;
        $this->dept_player = $dept_player;
        $this->dept_coach = $dept_coach;
        $this->dept = $dept;
    }



    public function index($dept_id)
    {
        try {

            $role = Auth::user()->userRole->role_name;

            if ($role === 'superAdmin') {
                Session::put('department_id', $dept_id);
            }

            $today = Carbon::today()->toDateString();

            $deptData = $this->dept->getDeptIdById($dept_id);
            $deptName = $deptData->dept_name;


            $data['topmenu'] = 'Dashboard';
            $data['submenu'] = 'Dashboard';
            $data['pagetitle'] = 'Dashboard ' . $deptName;

            $dept_name = strtolower($deptName);

            $player_attend_tbl = $dept_name . '_player_attendance';
            $coach_attend_tbl = $dept_name . '_coach_attendance';
            $fee_validity_tbl = $dept_name . '_fee_validity';


            //active player
            $totalPlayer = $this->dept_player->totalDeptPlayer($dept_id, 1);
            $totalDormantPlayer = $this->dept_player->totalDeptPlayer($dept_id, 2);

            $totalCoach = $this->dept_coach->totalDeptCoach($dept_id);


            $totalPlayerAttend = $this->getTodaysAttendance($player_attend_tbl, 'player_id', $today);

            $totalCoachAttend  = $this->getTodaysAttendance($coach_attend_tbl, 'coach_id', $today);

            $expired_day = Carbon::today()->addDays(7)->toDateString();
            $upcoming_fee_validity = $this->feeValididty($fee_validity_tbl, $today, 'dept_players', 'players', 'batches', $expired_day)->get();
            $expired_fee_validity = $this->feeValididty($fee_validity_tbl, $today, 'dept_players', 'players', 'batches', null)->get();


            // live player attend todays
            $live_player_attend = DB::table($player_attend_tbl . ' as pa')
                ->where('pa.attendance_date', $today)
                //     ->whereRaw('pa.created_at = (
                // 	SELECT MAX(pa2.created_at)
                // 	FROM ' . $player_attend_tbl . ' pa2
                // 	WHERE pa2.player_id = pa.player_id
                // 	AND pa2.attendance_date = pa.attendance_date
                // )')

                ->join('players', 'players.player_id', '=', 'pa.player_id')
                // ->where('players.status', 1)
                ->join('dept_players as dp', 'dp.player_id', '=', 'players.player_id')
                ->join('batches', 'batches.id', '=', 'dp.batch_id')
                ->select(

                    'players.first_name',
                    'players.middle_name',
                    'players.last_name',
                    'batches.batch_name',

                    'pa.in_time',
                    'pa.out_time',
                    // 'pa.attendance_date'

                )
                ->orderBy('pa.player_id', 'asc')
                ->limit(20)
                ->get();





            $live_coach_attend =  DB::table($coach_attend_tbl . ' as ca')
                ->where('ca.attendance_date', $today)
                ->whereNull('ca.deleted_at')
                ->whereIn('ca.id', function ($query) use ($coach_attend_tbl, $today) {
                    $query->select(DB::raw('MAX(id)'))
                        ->from($coach_attend_tbl)
                        ->where('attendance_date', $today)
                        ->groupBy('coach_id');
                })
                ->whereRaw('ca.created_at = (
							SELECT MAX(ca2.created_at)
							FROM ' . $coach_attend_tbl . ' ca2
							WHERE ca2.coach_id = ca.coach_id
							AND ca2.attendance_date = ca.attendance_date
						)')
                ->join('batches', 'batches.coach_id', '=', 'ca.coach_id')
                ->join('coaches', 'coaches.id', '=', 'ca.coach_id')
                ->select(
                    'coaches.first_name',
                    'coaches.middle_name',
                    'coaches.last_name',
                    DB::raw('GROUP_CONCAT(DISTINCT batches.batch_name) as batch_name'),
                    'ca.in_time',
                    'ca.out_time',

                    'ca.attendance_date'
                )->groupBy(
                    'coaches.id',
                    'coaches.first_name',
                    'coaches.middle_name',
                    'coaches.last_name',
                    'ca.in_time',
                    'ca.out_time',
                    'ca.attendance_date'
                )
                ->orderBy('ca.coach_id', 'asc')
                ->limit(20)
                ->get();


            return view('admin/index', [
                'data' => $data,
                'totalPlayer' => $totalPlayer,
                'totalDormantPlayer' => $totalDormantPlayer,
                'totalCoach' => $totalCoach,
                'totalPlayerAttend' => $totalPlayerAttend,
                'totalCoachAttend' => $totalCoachAttend,
                'live_player_attend' => $live_player_attend,
                'live_coach_attend' => $live_coach_attend,
                'upcoming_fee_validity' => $upcoming_fee_validity,
                'expired_fee_validity' => $expired_fee_validity,

            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function getTodaysAttendance($table, $column, $date)
    {
        return DB::table($table)
            ->select($column)
            ->where('attendance_date', $date)
            ->whereNull('deleted_at')
            ->distinct()
            ->count($column);
    }


    public function feeValididty($table, $todaysDate, $tbl1, $tbl2, $tbl3, $expiryDate = null)
    {
        $query =  DB::table($table . ' as fee');
        if ($expiryDate != null) {
            $query->whereBetween('fee.valid_to', [$todaysDate, $expiryDate]);
        } else {
            $query->where('fee.valid_to', '<', $todaysDate);
        }

        $query->whereRaw('fee.created_at = (
                    SELECT MAX(fee2.created_at)
                    FROM ' . $table . ' as fee2
                    WHERE fee2.dept_player_id = fee.dept_player_id
                )')
            ->join($tbl1, $tbl1 . '.id', '=', 'fee.dept_player_id')    // dept_player tbl
            ->join($tbl2, $tbl2 . '.player_id', '=', $tbl1 . '.player_id') //player
            ->join($tbl3, $tbl3 . '.id', '=', $tbl1 . '.batch_id') //batches
            ->select(
                $tbl3 . '.batch_name',
                'fee.valid_to',
                $tbl2 . '.first_name',
                $tbl2 . '.middle_name',
                $tbl2 . '.last_name'
            )
            ->limit(20);

        return $query;
    }



    //players
    public function uploadPlayerCsv()
    {
        try {
            $data['topmenu'] = 'Upload CSV File';
            $data['submenu'] = 'Upload csv file';
            $data['pagetitle'] = 'Upload Players CSV File';

            return view('admin/uploadPlayerCsvFile', ['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function importPlayerCsv(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required',
            ]);

            $file = $request->file('csv_file');

            //read and parse the csv file\
            $data = array_map('str_getcsv', file($file->getRealPath()));

            //remove header from csv file
            $header = array_shift($data);


            //insert data in db
            foreach ($data as $row) {
                $row = array_combine($header, $row); //add key name proper instead of index

                // echo '<pre>'; print_r($row); die;
                if (!empty(trim($row['first_name'])) && !empty(trim($row['last_name'])) && !empty(trim($row['aadhar_number']))) {

                    $playerExist = Player::where('aadhar_number', trim($row['aadhar_number']))->exists();
                    if ($playerExist) {
                        throw new \Exception("Player alrady exist: {$row['aadhar_number']} ");
                    }

                    $data = Player::create([
                        'assign_coach_id' => trim($row['assign_coach_id']),
                        'first_name' => trim(trim($row['first_name'])),
                        'last_name' => trim(trim($row['last_name'])),
                        'address1' => trim(trim($row['address1'])),
                        'address2' => trim(trim($row['address2'])),
                        'area' => trim(trim($row['area'])),
                        'city' => trim(trim($row['city'])),
                        'pincode' => trim(trim($row['pincode'])),
                        'dob' => trim(trim($row['dob'])),
                        'age' => trim(trim($row['age'])),
                        'gender' => trim(trim($row['gender'])),
                        'department' => trim(trim($row['department'])),
                        'batch' => trim(trim($row['batch'])),
                        'date_of_enrollment' => trim($row['date_of_enrollment']),
                        'player_contact_no' => trim($row['player_contact_no']),
                        'parent_email' => trim($row['parent_email']),
                        'fee_detail' => trim($row['fee_detail']),
                        'receipt_no' => trim($row['receipt_no']),
                        'receipt_date' => trim($row['receipt_date']),
                        // 'document' => trim($row['document']),
                        'aadhar_number' => trim($row['aadhar_number']),
                        // 'player_image' => $row['player_image'],
                    ]);
                }
            }

            return redirect()->back()->with('message', 'CSV data imported successfully');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }






    //Coaches

    public function uploadCoachCsv()
    {
        try {
            $data['topmenu'] = 'Upload CSV File';
            $data['submenu'] = 'Upload csv file';
            $data['pagetitle'] = 'Upload Coaches CSV File';

            return view('admin/uploadCoachCsvFile', ['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function importCoachCsv(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required',
            ]);

            $file = $request->file('csv_file');

            //read and parse the csv file\
            $data = array_map('str_getcsv', file($file->getRealPath()));

            //remove header from csv file
            $header = array_shift($data);


            //insert data in db
            foreach ($data as $row) {
                $row = array_combine($header, $row); //add key name proper instead of index


                // echo '<pre>'; print_r($row); die;
                if (!empty(trim($row['first_name'])) && !empty(trim($row['last_name'])) && !empty(trim($row['coach_email']))) {

                    $coachExist = Coach::where('coach_email',  trim($row['coach_email']))->exists();
                    if ($coachExist) {
                        throw new \Exception("Coach already exist :{$row['first_name']}");
                    }

                    Coach::create([
                        'first_name' =>  trim($row['first_name']),
                        'last_name' =>   trim($row['last_name']),
                        'address1' =>    trim($row['address1']),
                        'address2' =>    trim($row['address2']),
                        'area'     =>    trim($row['area']),
                        'city'    =>     trim($row['city']),
                        'pincode' =>     trim($row['pincode']),
                        'dob'     =>     trim($row['dob']),
                        'age'     =>     trim($row['age']),
                        'gender'  =>     trim($row['gender']),
                        'department' =>  trim($row['department']),
                        'batch' =>       trim($row['batch']),
                        'date_of_joining' => trim($row['date_of_joining']),
                        'coach_contact_no' => trim($row['coach_contact_no']),
                        'coach_email' =>    trim($row['coach_email']),
                    ]);
                }
            }

            return redirect()->back()->with('message', 'CSV data imported successfully');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function help()
    {
        return view('help.help');
    }
}
