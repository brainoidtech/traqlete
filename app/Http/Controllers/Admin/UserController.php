<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\StoreCoachRequest;
use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Player;
use App\Models\Batch;
use App\Models\BatchLog;
use App\Models\PlayerParent;
use App\Models\Department;
use App\Models\PlayerQrcode;
use App\Models\CoachQrcode;
use App\Models\DepartmentPlayers;
use App\Models\DepartmentCoach;
use App\Models\PlayerAttendance;
use App\Models\CoachAttendance;
use App\Models\FeesValidity;
use App\Models\Assessment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;


use PDF;


use Exception;

class UserController extends Controller
{
	protected $coach;
	protected $player;
	protected $department;
	protected $playerParent;
	protected $batch;
	protected $player_qrcode;
	protected $coach_qrcode;
	protected $player_attend;
	protected $coach_attend;
	protected $dept_player;
	protected $dept_coach;
	protected $fee_validity;
	protected $batchlog;
	protected $assessment;
	protected $user;
	protected $role;

	public function __construct(
		Coach $coach,
		Player $player,
		Department $department,
		PlayerParent $playerParent,
		Batch $batch,
		PlayerQrcode $player_qrcode,
		PlayerAttendance $player_attend,
		CoachAttendance $coach_attend,
		DepartmentPlayers $dept_player,
		DepartmentCoach $dept_coach,
		CoachQrcode $coach_qrcode,
		FeesValidity $fee_validity,
		BatchLog $batchlog,
		Assessment $assessment,
		User $user,
		Role $role,
	) {
		$this->coach = $coach;
		$this->player = $player;
		$this->department = $department;
		$this->playerParent = $playerParent;
		$this->batch = $batch;
		$this->player_qrcode = $player_qrcode;
		$this->coach_qrcode = $coach_qrcode;
		$this->player_attend = $player_attend;
		$this->dept_player = $dept_player;
		$this->dept_coach = $dept_coach;
		$this->fee_validity = $fee_validity;
		$this->coach_attend = $coach_attend;
		$this->batchlog = $batchlog;
		$this->assessment = $assessment;
		$this->user = $user;
		$this->role = $role;
	}




	//****************** 1. player ****************** 
	public function addPlayer()
	{
		try {
			$dept = getDepartmentData();

			$department = ucfirst(strtolower($dept->dept_name));
			$playerData = $this->player->getLastIdOfPlayer($dept->dept_id);

			if ($playerData) {

				$Id = $playerData['player_id'];
				//extract number from id
				if ($Id) {

					$playerId = intVal(preg_replace('/[^0-9]/', '', $Id));
				} else {
					$playerId = 1;
				}

				$nextPlayerId = $playerId ? $playerId + 1 : 1;
				$playerCode = $dept->code_for_player . str_pad($nextPlayerId, $dept->digit_for_player, '0', STR_PAD_LEFT);
			} else {
				$nextPlayerId = 1;
				$playerCode = $dept->code_for_player . str_pad($nextPlayerId, $dept->digit_for_player, '0', STR_PAD_LEFT);
			}

			if ($dept->code_for_coach != null) {
				$playersBatchData = $this->batch->loadBatchWithAdmin($dept->code_for_coach);
			}


			$data['topmenu'] = 'Add Player';
			$data['submenu'] = 'player';
			$data['pagetitle'] = 'Add player' . ' ' . $department;

			return view('admin/players/addPlayer', compact('data', 'dept', 'playerCode', 'playersBatchData'));
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}


	private function generateQrCode($dept_name, $player_id)
	{
		try {

			$dept = getDepartmentData();

			$qrData = [
				'player_id' => $player_id,
				'dept_name' => $dept->dept_name,
				'dept_id' => $dept->dept_id,
			];
			$qrString = json_encode($qrData);
			$qrCode = QrCode::format('png')->size(240)->generate($qrString);

			//file name
			$folder = public_path('images/' . $dept_name . '/players/' . $player_id . '/qr_code');

			if (!file_exists($folder)) {
				mkdir($folder, 0777, true);
			}

			//fileName
			$fileName = 'qr_code_' . $player_id . '.png';
			$filePath = $folder . '/' . $fileName;

			//store in folder
			file_put_contents($filePath, $qrCode);

			$Path = "images/$dept_name/players/$player_id/qr_code/$fileName";
			return $Path;
		} catch (\Exception $e) {

			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 500);
		}
	}

	public function getCoachByBatchId(Request $request)
	{
		try {

			$data = $this->coach->getCoachByBatch($request->all());

			return response()->json([
				'status' => 'success',
				'data' => $data,
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 500);
		}
	}

	public function storePlayer(StorePlayerRequest $request)
	{

		try {
			DB::beginTransaction();

			$request->validated();

			$dept = getDepartmentData();
			$dept_name = strtolower($dept->dept_name);
			$dept_id = $dept->dept_id;

			$player_id = $request->player_id;
			$request->merge(['dept_name' => $dept_name, 'dept_id' => $dept_id]);
			$playerData = $request->all();


			//validation for duplicate player with same dept
			$player_data = $this->player->getDuplicatePlayer($request->all());
			if ($player_data) {
				return response()->json([
					'status' => 'false',
					'message' => 'Sorry! Player alredy exist.',
				]);
			}

			// ************************qr_code image generate and store **************************

			$qrImgPath = $this->generateQrCode($dept_name, $player_id);
			$playerData['qr_code'] = $qrImgPath;



			// ************************player image store **************************

			$playerImage = $request->file('player_image');
			$filename = time() . '_' . $player_id . '.' . $playerImage->getClientOriginalExtension();

			$folder = "images/$dept_name/players/$player_id/documents";

			if (!file_exists($folder)) {
				mkdir($folder, 0777, true);
			}


			$playerImage->move($folder, $filename);
			$imgPath = $folder . '/' . $filename;

			$playerData['player_image'] = $imgPath;

			// ******************player & parent data *****************
			$this->player->insertPlayer($playerData);

			$this->playerParent->insertParentData($playerData);


			$this->dept_player->addDeptPlayerData($playerData);

			$qr_table = strtolower($dept_name) . '_player_qrcodes';
			DB::table($qr_table)->insert([
				'player_id' => $player_id,
				'qr_code' => $qrImgPath,
				'dept_id' => $dept_id,
				'created_at' => now(),
				'updated_at' => now(),
			]);


			$table = strtolower($dept_name) . '_batch_logs';
			DB::table($table)->insert([
				'start_date' => Carbon::today()->toDateString(),
				'player_id' => $player_id,
				'batch_id' => $request->batch_id,
				'coach_id' => $request->assign_coach_id,
				'status' => 1,
				'created_at' => now(),
				'updated_at' => now(),
			]);


			DB::commit();
			return response()->json([
				'status' => 'success',
				'message' => 'player inserted successfully.',
			]);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}

	public function playerList(Request $request)
	{


		try {
			$dept = getDepartmentData();
			$deptName = strtolower($dept->dept_name);
			$deptId = $dept->dept_id;

			$department = strtolower($deptName);
			$table = $department . '_fee_validity';
			$qr_table = $department . '_player_qrcodes';

			// $this->dept_player->getPlayerListData($qr_table, $deptId);

			// Base query (no status filter — filtered per tab via AJAX)
			$query = DB::table('dept_players as dp')
				->where('dp.dept_id', $deptId)
				->join('players', 'dp.player_id', '=', 'players.player_id')
				->join('batches', 'dp.batch_id', '=', 'batches.id')
				->join('coaches', 'batches.coach_id', '=', 'coaches.id')
				->join($qr_table, 'players.player_id', '=', $qr_table . '.player_id')
				->leftJoin(DB::raw("
					(SELECT * FROM $table t1
						WHERE t1.id = (
							SELECT MAX(t2.id) FROM $table t2
							WHERE t2.dept_player_id = t1.dept_player_id
						)
					) as latest
				"), 'dp.id', '=', 'latest.dept_player_id')
				->whereNull('dp.deleted_at')
				->select(
					'dp.id',
					'dp.created_at',
					'players.player_id',
					'players.status',
					'players.first_name as player_first_name',
					'players.middle_name as player_middle_name',
					'players.last_name as player_last_name',
					'coaches.first_name as coach_first_name',
					'coaches.middle_name as coach_middle_name',
					'coaches.last_name as coach_last_name',
					'batches.batch_name',
					'batches.id as batch_id',
					'coaches.id as coach_id',
					$qr_table . '.qr_code',
					'latest.valid_to'
				);

			// Handle AJAX DataTables request
			if ($request->ajax()) {
				$tab = $request->get('tab', 'allplayers_tab');

				// Filter by tab/status
				if ($tab === 'allplayers_tab') {
					// No status filter — show all
				} elseif ($tab === 'dormantplayers_tab') {
					$query->where('players.status', 2);
				} elseif ($tab === 'leaveplayers_tab') {
					$query->where('players.status', 0);
				}

				// Optional filters from filter modal
				if ($request->filled('batch')) {
					$query->where('batches.id', $request->batch);
				}

				if ($request->filled('coach')) {
					$query->where('coaches.id', $request->coach);
				}

				if ($request->filled('status') && $tab === 'allplayers_tab') {
					$query->where('players.status', $request->status);
				}

				if ($request->validity === 'valid') {
					$query->whereDate('latest.valid_to', '>=', now());
				} elseif ($request->validity === 'expired') {
					$query->whereDate('latest.valid_to', '<', now());
				}

				return DataTables::of($query)
					->addIndexColumn() // DT_RowIndex for Sr No
					->addColumn('name', function ($row) {
						return collect([$row->player_first_name, $row->player_middle_name, $row->player_last_name])
							->filter()->implode(' ');
					})
					->addColumn('coach_name', function ($row) {
						return collect([$row->coach_first_name, $row->coach_middle_name, $row->coach_last_name])
							->filter()->implode(' ');
					})
					->addColumn('validity', function ($row) {
						return $row->valid_to ?? 'N/A';
					})
					->addColumn('status_label', function ($row) {
						return match ((int)$row->status) {
							1 => 'Active',
							2 => 'Dormant',
							0 => 'Left',
							default => 'Unknown',
						};
					})
					->addColumn('action', function ($row) {
						$viewUrl = url('players-profile/' . $row->player_id);
						$qrHtml = $row->qr_code
							? '<div id="print-area" class="print-only"><img src="' . asset($row->qr_code) . '" alt="QR Code"></div>'
							: '';
						return '
							<a href="' . $viewUrl . '"><i class="bi bi-eye-fill px-1"></i></a>
							' . $qrHtml . '
							<button type="button" style="border:none;background-color:transparent;" onclick="printQr(event)">
								<i class="bi bi-printer-fill px-1"></i>
							</button>
						';
					})

					->orderColumn('name', function ($query, $order) {
						$query->orderBy('players.first_name', $order);
					})



					->orderColumn('coach_name', function ($query, $order) {
						$query->orderBy('coaches.first_name', $order);
					})

					->orderColumn('validity', function ($query, $order) {
						$query->orderBy('latest.valid_to', $order);
					})

					->orderColumn('status_label', function ($query, $order) {
						$query->orderBy('players.status', $order);
					})



					->filter(function ($query) use ($request) {
						if ($search = $request->get('search')['value']) {
							$query->where(function ($q) use ($search) {
								$q->where('players.first_name', 'like', "%{$search}%")
									->orWhere('players.last_name', 'like', "%{$search}%")
									->orWhere('players.player_id', 'like', "%{$search}%")
									->orWhere('batches.batch_name', 'like', "%{$search}%")
									->orWhereRaw("CONCAT(coaches.first_name, ' ', coaches.last_name) LIKE ?", ["%{$search}%"]);
							});
						}
					})
					->rawColumns(['action'])
					->make(true);
			}

			$data['topmenu'] = 'Player List';
			$data['submenu'] = 'player';
			$data['pagetitle'] = 'Player List ' . $deptName;

			$batches = $this->batch->getBatchesByDept($deptId);
			$coaches = $this->coach->loadAllCoachByDept($deptId);

			return view('admin/players/playerList', compact('data', 'batches', 'coaches'));
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
		}
	}


	public function checkExistingPlayer(Request $request)
	{
		try {
			$playerData = $this->player->getExistingPlayer($request->all());
			return response()->json([
				'status' => 'success',
				'data' => $playerData,
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}


	public function playerProfile($id)
	{
		try {
			$data['topmenu'] = 'Player Profile';
			$data['submenu'] = 'player';
			$data['pagetitle'] = 'Player Overview';


			$dept = getDepartmentData();
			$dept_name = strtolower($dept->dept_name);
			$dept_id = $dept->dept_id;


			$qr_table = strtolower($dept_name) . '_player_qrcodes';
			$attendance_table = strtolower($dept_name) . '_player_attendance';


			//get player data with qr code with each players all parent name not only one

			$players = DB::table('players')
				->where('players.player_id', $id)
				->join('dept_players', 'players.player_id', '=', 'dept_players.player_id')
				->join('batches', 'dept_players.batch_id', '=', 'batches.id')
				->join('coaches', 'batches.coach_id', '=', 'coaches.id')
				->join('department', 'dept_players.dept_id', '=', 'department.dept_id')
				->join('parent_names', 'players.player_id', '=', 'parent_names.player_id')
				->where('parent_names.deleted_at', null)
				->where('dept_players.dept_id', $dept_id)

				->join($qr_table, 'players.player_id', '=', $qr_table . '.player_id')
				->select(
					'players.*',
					'players.status',
					$qr_table . '.qr_code',
					'parent_names.*',
					// 'batches.*',
					'batches.coach_id',
					'batches.dept_id',
					'batches.batch_name',
					'batches.start_time',
					'batches.end_time',
					'coaches.first_name as coach_first_name',
					'coaches.middle_name as coach_middle_name',
					'coaches.last_name as coach_last_name',
					'department.dept_name'
				)
				->get();



			$player = $players->first();
			if ($player !== null) {
				$player->parents = $players->map(function ($item) {
					return [
						'parent_id' => $item->id,
						'parent_name' => $item->parent_name,
						'relation' => $item->relation,
						'parent_contact' => $item->parent_contact,
						'parent_email' => $item->parent_email,
						'parent_profession' => $item->parent_profession,
					];
				});
			}


			$depts = $this->department->loadDepartment();

			$batches = $this->batch->loadBatchWithAdmin($dept->code_for_coach);

			$coaches = $this->coach->loadAllCoachByDept($dept_id);
			// $coach = $this->coach->loadCoach();


			// get player attendance by id
			$todays_date = Carbon::today()->toDateString();
			$player_profile_attend = DB::table($attendance_table)
				->where('player_id', $id)
				->where('dept_id', $player->dept_id)
				->where('attendance_date', $todays_date)
				->get();

			//count number of parent for each player 
			// $showIndex = count($player['parents']) > 1 ?? 0;
			$showIndex = DB::table('parent_names')->where('player_id', $id)->count() > 1 ? true : false;
			$deptPlayerId = $this->dept_player->getId($id);

			$department = strtolower($dept->dept_name);

			$fee_table = $department . '_fee_validity';
			$batch_log_table = $department . '_batch_logs';
			$assessment_table = $department . '_assessments';




			$playerAssessment = DB::table($assessment_table)
				->where('dept_player_id', $deptPlayerId->id)
				->where('deleted_at', null)
				->orderBy('assessment_date', 'desc')
				->get();



			//show latest fee details for each player
			$fees = DB::table('dept_players')->where('player_id', $id)
				->join($fee_table, $fee_table . '.dept_player_id', '=', 'dept_players.id')
				->orderBy($fee_table . '.created_at', 'desc')
				->get();


			$batchlog = DB::table($batch_log_table)
				->where($batch_log_table . '.player_id', $id)
				->where($batch_log_table . '.status', 1)
				->leftJoin('batches', 'batches.id', '=', $batch_log_table . '.batch_id')
				->leftJoin('coaches', 'coaches.id', '=', $batch_log_table . '.coach_id')
				->orderByDesc($batch_log_table . '.created_at')
				->select(
					$batch_log_table . '.batch_id',
					$batch_log_table . '.start_date',
					$batch_log_table . '.end_date',
					$batch_log_table . '.coach_id',
					$batch_log_table . '.id',
					'batches.batch_name',
					'coaches.first_name',
					'coaches.middle_name',
					'coaches.last_name'
				)
				->get();



			$document_tbl = strtolower($dept_name) . '_documents';
			$documents = DB::table($document_tbl)
				->where('player_id', $id)
				->whereNull('deleted_at')
				->get();



			$player_attendance = DB::table($attendance_table)->where('player_id', $id)->where('dept_id', $dept_id)->get()->groupBy(function ($item) {
				return Carbon::parse($item->attendance_date)->format('Y-m-d');
			});

			return view('admin/players/playerProfile', compact(
				'data',
				'player',
				'showIndex',
				'batches',
				'depts',
				'player_profile_attend',
				'fees',
				'coaches',

				'batchlog',
				'playerAssessment',
				'player_attendance',
				'documents',
			));
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}

	public function updatePlayerProfile(StorePlayerRequest $request, $player_id)
	{
		try {
			DB::beginTransaction();

			$request->validated();

			$dept = getDepartmentData();
			$dept_name = strtolower($dept->dept_name);
			$deptId = $dept->dept_id;

			$playerData = $request->all();

			if ($request->hasFile('player_image')) {

				$playerImage = $request->file('player_image');
				$filename = time() . '_' . $player_id . '.' . $playerImage->getClientOriginalExtension();

				$folder = "images/$dept_name/players/$player_id/documents";

				if (!file_exists($folder)) {
					mkdir($folder, 0777, true);
				}

				$playerImage->move($folder, $filename);
				$imgPath = $folder . '/' . $filename;


				$playerData['player_image'] = $imgPath;
			}

			// Call the method to update the player profile
			$this->player->updatePlayerProfile($playerData, $player_id);
			$this->playerParent->updateParent($playerData['parent'], $player_id, $deptId);

			DB::commit();

			return response()->json([
				'status' => 'success',
				'message' => 'Player updated successfully!',
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}

	public function getPlayerData(Request $request)
	{

		try {

			$dept = getDepartmentData();
			$deptId = $dept->dept_id;

			$data = $this->player->getPlayerData($request->all(), $deptId);
			if ($data !== null) {
				return response()->json([
					'status' => 'success',
					'data' => $data,
				]);
			} else {
				return response()->json([
					'status' => false,
					'message' => 'The entered player ID does not exist. Please check and try again.',
				]);
			}
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}


	//************************************* Player Document function *******************************************/
	public function storeDocument(Request $request)
	{
		try {

			$request->validate([
				'document_name' => 'required',
				'file' => 'required',
			]);


			$dept = getDepartmentData();
			$dept_name = strtolower($dept->dept_name);

			$player_id = $request->input('player_id');
			$document_name = $request->input('document_name');



			$playerDoc = $request->file('file');
			$filename = time() . '_' . $document_name . '.' . $playerDoc->getClientOriginalExtension();

			$folder = "images/$dept_name/players/$player_id/documents";

			if (!file_exists($folder)) {
				mkdir($folder, 0777, true);
			}


			$playerDoc->move($folder, $filename);
			$imgPath = $folder . '/' . $filename;

			$document = $request->all();

			$document_tbl = strtolower($dept->dept_name) . '_documents';

			DB::table($document_tbl)->insert([
				'player_id' => $player_id,
				'file' => $imgPath,
				'document_name' => $document_name,
				'description' => $request->description,
				'created_at' => now(),
				'updated_at' => now(),
			]);

			return response()->json([
				'status' => 'success',
				'message' => 'Document added successfully',
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}



	public function viewDocumentImage($Id)
	{
		try {

			$dept = getDepartmentData();

			if (!$Id) {
				return false;
			}

			$document_tbl = strtolower($dept->dept_name) . '_documents';
			$document = DB::table($document_tbl)->where('id', $Id)->whereNull('deleted_at')->first();

			// PDF::setOptions(['isPhpEnabled' => true]);
			// PDF::setOptions(['isJavascriptEnabled' => true]);

			// $pdf = PDF::loadView("admin.pdf.player_doc", compact('document'));
			// $pdf_name = str_replace(':', '_', $Id);

			// return $pdf->stream($pdf_name . '.pdf');

			return response()->file(public_path($document->file));
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 500);
		}
	}



	public function deleteDocument($id)
	{
		try {

			$dept = getDepartmentData();

			$document_tbl = strtolower($dept->dept_name) . '_documents';
			$document = DB::table($document_tbl)
				->where('id', $id)
				->update([
					'deleted_at' => now(),
				]);

			return response()->json([
				'status' => 'success',
				'message' => 'Document deleted successfully!',
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 500);
		}
	}



	//************************************* Coach function *******************************************/

	//add Coach
	public function addCoach()
	{
		try {

			$dept = getDepartmentData();

			$department = ucfirst(strtolower($dept->dept_name));

			$CoachData = $this->coach->loadLastCoachId($dept->dept_id);
			if ($CoachData) {

				$Id = $CoachData->id;

				//extract number from id
				if ($Id) {

					$coachId = intVal(preg_replace('/[^0-9]/', '', $Id));
				} else {
					$coachId = 1;
				}

				$nextCoachId = $coachId ? $coachId + 1 : 1;
				$coachCode = $dept->code_for_coach . str_pad($nextCoachId, $dept->digit_for_coach, '0', STR_PAD_LEFT);
			} else {

				$nextCoachId = 1;
				$coachCode = $dept->code_for_coach . str_pad($nextCoachId, $dept->digit_for_coach, '0', STR_PAD_LEFT);
			}


			$data['topmenu'] = 'Add Coach';
			$data['submenu'] = 'Coach';
			$data['pagetitle'] = 'Add' . ' ' . $department . ' ' . 'Coach';



			return view('admin/coach/addCoach', compact('data', 'coachCode'));
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}


	public function checkExistingCoach(Request $request)
	{
		try {
			$coachData = $this->coach->getExistingCoach($request->all());
			return response()->json([
				'status' => 'success',
				'data' => $coachData,
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}



	public function storeCoach(StoreCoachRequest $request)
	{
		try {

			DB::beginTransaction();

			//validation
			$request->validated();

			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;

			$coach_data = $this->coach->validateCoachEmail($request->all(), $dept_id);

			if ($coach_data->isNotEmpty()) {
				return response()->json([
					'status' => 'false',
					'message' => 'Sorry! coach email id already exist in this department.',
				]);
			}


			// ************************get selected dept_name by id **************************

			$dept_name = $dept->dept_name;

			$request->merge(['dept_name' => $dept_name, 'dept_id' => $dept_id]);
			$coachData = $request->all();
			$coach_id = $request->id;


			// ************************coach image store **************************

			if ($request->hasFile('coach_image')) {
				$playerImage = $request->file('coach_image');
				$filename = time() . '_' . $coach_id . '.' . $playerImage->getClientOriginalExtension();

				$folder = "images/$dept_name/coaches/$coach_id/documents";

				if (!file_exists($folder)) {
					mkdir($folder, 0777, true);
				}
				$playerImage->move($folder, $filename);
				$imgPath = $folder . '/' . $filename;
				$coachData['coach_image'] = $imgPath;
			}


			// ************************coach data **************************
			// insert in coach and user tbl 
			$this->coach->insertCoach($coachData);

			$this->dept_coach->addDeptCoachData($coachData);


			DB::commit();
			return response()->json([
				'status' => 'success',
				'message' => 'Coach inserted successfully.',
			]);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}



	public function coachList(Request $request)
	{
		try {
			$dept = getDepartmentData();
			$department = ucfirst(strtolower($dept->dept_name));

			$coaches = $this->coach->loadAllCoachByDept($dept->dept_id);
			$batches = $this->batch->getBatchesByDept($dept->dept_id);

			if ($request->ajax()) {

				$query = $this->coach->getAllCoachQuery(
					$dept->dept_id,
					$request->batch,
					$request->coach,
					$request->status ?? null
				);



				return DataTables::of($query)
					->addIndexColumn()
					->addColumn('first_name', function ($row) {
						return collect([$row->first_name, $row->middle_name, $row->last_name])
							->filter()->implode(' ');
					})
					->filterColumn('first_name', function ($query, $keyword) {
						$query->where(function ($q) use ($keyword) {
							$q->where('first_name', 'LIKE', "%{$keyword}%")
								->orWhere('middle_name', 'LIKE', "%{$keyword}%")
								->orWhere('last_name', 'LIKE', "%{$keyword}%")
								->orWhere('id', 'LIKE', "%{$keyword}%");
						});
					})
					->orderColumn('first_name', function ($query, $order) {   // <-- use closure form
						$query->orderBy('first_name', $order);
					})

					->filterColumn('batch_name', function ($query, $keyword) {
						$query->whereHas('latestBatch', function ($q) use ($keyword) {
							$q->where('batch_name', 'LIKE', "%{$keyword}%")
								->where('status', 1)
								->whereNull('deleted_at');
						});
					})

					


					->addColumn('batch_name', fn($row) => $row->latestBatch->first()->batch_name ?? '')
					->addColumn('action', function ($row) {
						$viewUrl = url('coach-profile/' . $row->id);
						return "<a href='{$viewUrl}'><i class='bi bi-eye-fill px-1'></i></a>";
					})
					->rawColumns(['action'])
					->make(true);
			}

			return view('admin.coach.coachList', compact('coaches', 'batches', 'department'));
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}



	//coach profile
	public function coachProfile($coach_id)
	{
		try {
			$data['topmenu'] = 'Coach Profile';
			$data['submenu'] = 'Coach';
			$data['pagetitle'] = 'Coach Overview';


			$todays_date = Carbon::today()->toDateString();

			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;

			$coachProfile = $this->coach->getCoachProfile($coach_id);

			$deptName = $dept->dept_name;
			$departmentName = strtolower($deptName);

			$attendance_table = $departmentName . '_coach_attendance';

			$attendance = DB::table($attendance_table)
				->where('coach_id', $coach_id)
				->where('attendance_date', $todays_date)
				->get();

			$coach_attendance = DB::table($attendance_table)->where('coach_id', $coach_id)->where('dept_id', $dept_id)->get()->groupBy(function ($item) {
				return Carbon::parse($item->attendance_date)->format('Y-m-d');
			});

			//coach log 
			$coach_log_tbl = $departmentName . '_coach_logs';
			$current_batch = DB::table($coach_log_tbl)
				->where('coach_id', $coach_id)
				->whereNull('deleted_at')
				->where('status', 'current_batch')
				->get();

			$previous_batch = DB::table($coach_log_tbl)
				->where('coach_id', $coach_id)
				->whereNull('deleted_at')
				->where('status', 'previous_batch')
				->get();


			return view('admin/coach/coachProfile', compact('data', 'coachProfile', 'attendance', 'coach_attendance', 'current_batch', 'previous_batch'));
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}

	public function updateCoachProfile(StoreCoachRequest $request, $id)
	{

		try {
			//validation
			$request->validated();

			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;
			$dept_name = $dept->dept_name;


			$request->merge(['dept_name' => $dept_name, 'coach_id' => $id, 'dept_id' => $dept_id]);
			$coachData = $request->all();
			$coach_id = $id;

			$coach = $this->coach->getCoachProfile($id);

			// ************************ profile image store **************************

			//file name

			//delete old profile img
			if ($request->hasFile('coach_image')) {
				if ($coach->coach_image && file_exists(public_path($coach->coach_image))) {
					unlink(public_path($coach->coach_image));
				}

				$coachImage = $request->file('coach_image');
				$filename = time() . '_' . $coach_id . '.' . $coachImage->getClientOriginalExtension();

				$folder = "images/$dept_name/coaches/$coach_id/documents";


				if (!file_exists($folder)) {
					mkdir($folder, 0777, true);
				}


				$coachImage->move($folder, $filename);
				$imgPath = $folder . '/' . $filename;

				$coachData['coach_image'] = $imgPath;
			}

			$this->coach->updateCoachData($coachData, $id);
			$this->user->updateCoachUserData($coachData, $dept_id, $id);

			return response()->json([
				'status' => 'success',
				'message' => 'Coach data update successfully.',
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}

	public function getCoachName(Request $request)
	{
		try {
			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;

			$data = $this->coach->getCoachName($request->all(), $dept_id);
			if ($data !== null) {
				return response()->json([
					'status' => 'success',
					'data' => $data,
				]);
			} else {
				return response()->json([
					'status' => false,
					'message' => 'The entered coach ID does not exist. Please check and try again',
				]);
			}
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}


	//************************************* User function *******************************************/

	protected function validateUserData(Request $request, $id = null)
	{
		return $request->validate(
			[
				'role_id' => 'required',
				'dept_id' => 'required',
				'name' => 'required|string|max:255',
				'contact_number' => 'required',
				'email' => $id ? 'required|email|unique:users,email,' . $id : 'required|email|unique:users,email',
				'password' => $id ? 'nullable|min:6' : 'required|min:6',
			],
			[
				'role_id.required' => 'Role is required',
				'dept_id.required' => 'Department is required',
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

	public function addAdminUser()
	{
		try {
			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;

			$departmentName = (ucfirst(strtolower($dept->dept_name))) ?? '';
			$roles = $this->role->getAdminUserRoles();
			return view('admin.user.addUser', [
				'dept_id' => $dept_id,
				'departmentName' => $departmentName,
				'roles' => $roles
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 500);
		}
	}

	public function addAdminUserData(Request $request)
	{
		$validatedData = $this->validateUserData($request);
		try {
			$this->user->addAdminUserData($validatedData);
			return redirect()->route('user-list')->with('success', 'User created successfully');
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 500);
		}
	}

	public function editAdminUser($id)
	{
		try {
			$user = $this->user->editAdminUserData($id);
			if (!$user) {
				return redirect()->route('user-list')->with('error', 'User is not in our system.');
			}
			$dept_id = $user->dept_id;
			$departmentName = (ucfirst(strtolower($user->userDept->dept_name))) ?? '';
			$roles = $this->role->getAdminUserRoles();
			return view('admin.user.editUser', [
				'user' => $user,
				'dept_id' => $dept_id,
				'departmentName' => $departmentName,
				'roles' => $roles
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 500);
		}
	}

	public function updateAdminUser(Request $request, $id)
	{
		$this->validateUserData($request, $id);
		try {
			$userData = $request->all();
			$this->user->updateAdminUserData($userData, $id);
			return redirect()->route('user-list')->with('success', 'User Updated successfully');
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 500);
		}
	}

	public function userAdminList(Request $request)
	{
		$dept = getDepartmentData();
		$dept_id = $dept->dept_id;
		$department = ucfirst(strtolower($dept->dept_name));

		try {
			if ($request->ajax()) {
				$query = $this->user->getAdminUserList($dept_id);

				// ✅ Count on collection (after ->get())
				$roleCounts = $this->user->getAdminUserList($dept_id)
					->get()
					->whereIn('role_id', [5, 6])
					->groupBy('role_id')
					->map(fn($group) => $group->count());

				return DataTables::of($query)
					->addColumn('original_role_id', fn($user) => $user->role_id)  // ✅ save raw role_id first
					->addColumn('role_count', function ($user) use ($roleCounts) {
						return $roleCounts[$user->role_id] ?? 0;                   // ✅ uses raw role_id
					})
					->editColumn('role_id', function ($user) {                     // ✅ overwrite last
						return optional($user->userRole)->role_name ?? '';
					})
					->make(true);
			}
		} catch (Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}

		return view('admin.user.userList', ['department' => $department]);
	}

	public function deleteAdminUser($id)
	{
		try {
			$deleteUserData = $this->user->editAdminUserData($id);
			$this->user->deleteUserData($deleteUserData);
			return redirect()->route('user-list')->with('success', 'User Deleted successfully');
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 500);
		}
	}



	//************************************* Fee function *******************************************/
	public function feeValidityList()
	{
		try {
			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;

			$deptName = ucfirst(strtolower($dept->dept_name));
			$department = strtolower($deptName);
			$data['topmenu'] = 'Fee';
			$data['submenu'] = 'Fee';
			$data['pagetitle'] = $deptName . ' ' . 'Fee Validity';

			$table = $department . '_fee_validity';


			$fee = DB::table($table)
				->join('dept_players', $table . '.dept_player_id', '=', 'dept_players.id')
				->join('players', 'dept_players.player_id', '=', 'players.player_id')
				->join('batches', 'dept_players.batch_id', '=', 'batches.id')
				->join('coaches', 'batches.coach_id', '=', 'coaches.id')
				->select(
					$table . '.*',

					// Player Name
					'players.player_id',
					'players.first_name as player_first_name',
					'players.middle_name as player_middle_name',
					'players.last_name as player_last_name',

					// Coach Name
					'coaches.first_name as coach_first_name',
					'coaches.middle_name as coach_middle_name',
					'coaches.last_name as coach_last_name',

					'batches.batch_name'
				)
				->whereNull($table . '.deleted_at')
				->orderBy($table . '.created_at', 'desc')
				->get()
				->unique('player_id')
				->sortBy('player_id');


			return view('admin/fee/feeValidity', ['data' => $data, 'fees' => $fee]);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}

	public function storeFee(Request $request)
	{

		try {
			$request->validate(
				[
					'valid_from' => 'required',
					'valid_to' => 'required|date|after:valid_from',
					'receipt_no' => 'required',
					'amount' => 'required',
				],
				[
					'valid_to.after' => 'Valid To should be greater than the Valid From.',
				]
			);



			$dept = getDepartmentData();
			$deptId = $dept->dept_id;

			$userId = Auth::user()->id;
			$playerId = $request->input('player_id');

			$deptName = $dept->dept_name;
			$department = strtolower($deptName);

			$dept_player_id = $this->dept_player->getPlayerById($playerId, $deptId);


			$table = $department . '_fee_validity';

			DB::table($table)->insert([
				'valid_from' => $request->valid_from,
				'valid_to' => $request->valid_to,
				'receipt_no' => $request->receipt_no,
				'amount' => $request->amount,
				'date' => $request->date,
				'dept_player_id' => $dept_player_id->id,
				'approved_by' => $userId,
				'created_at' => now(),
				'updated_at' => now()
			]);

			// update player status dormant -> active
			$player = $this->player->checkPlayerStatus($playerId);
			if ($player->status == 2) {
				$this->player->updatePlayerStatus($playerId, 1);
			}


			return response()->json([
				'status' => 'success',
				'message' => 'Fee inserted successfully!',
			]);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}




	//************************************* Batch function *******************************************/
	public function addBatches()
	{
		try {
			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;

			$department = ucfirst(strtolower($dept->dept_name));
			$data['topmenu'] = 'Batches';
			$data['submenu'] = 'Batches';
			$data['pagetitle'] = 'Add' . ' ' . $department . ' ' . 'Batches';
			$coach = $this->coach->loadAllCoachByDept($dept_id);

			return view('admin/batches/addBatches', ['data' => $data, 'coach' => $coach]);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}


	public function storeBatch(Request $request)
	{

		$request->validate([
			'batch_name' => 'required|string|max:255',
			'start_time' => 'nullable',
			'end_time' => 'nullable',
			'head_coach_id' => 'required|exists:coaches,id',

		]);

		$dept = getDepartmentData();
		$dept_id = $dept->dept_id;
		$dept_name = $dept->dept_name;

		if (empty($request->start_time) || empty($request->end_time)) {
			return response()->json([
				'status' => 'error',
				'message' => 'Coach timing is required. Please fill in both time fields.',
			], 422);
		}

		if ($request->end_time <= $request->start_time) {
			return response()->json([
				'status' => 'error',
				'message' => 'The end time should be after the start time',
			], 422);
		}

		$startTime = Carbon::parse($request->start_time)->addMinutes(5)->format('H:i');

		if ($request->start_time === $request->end_time || $request->end_time <= $startTime ||  $request->end_time <=  $request->start_time) {
			return response()->json([
				'status' => 'error',
				'message' => 'A minimum difference of 5 minutes is required between the selected times.',
			], 422);
		}


		// duplicate batch on same dept
		$batchExist = $this->batch->isBatchExist($dept_id, $request->batch_name);

		if ($batchExist) {
			return response()->json([
				'status' => 'error',
				'message' => 'A batch with this name already exists in your department.',
			], 422);
		}

		// $allCoachIds = array_merge(
		// [$request->head_coach_id],
		// $request->other_coach_ids ?? []
		// );

		// coach alredy assign different batch on same time 
		// if ($request->start_time && $request->end_time) {
		// $conflictingTime = Batch::whereIn('coach_id', $allCoachIds)
		// ->where(function ($query) use ($request) {
		// $query->where('start_time', '<', $request->end_time)
		// ->where('end_time', '>', $request->start_time);
		// })
		// ->first();
		// if ($conflictingTime) {
		// return response()->json([
		// 'status' => 'error',
		// 'message' => 'coach already assigned to different batch in this department at this time.".',
		// ], 422);
		// }
		// }

		$this->batch->insertBatch($request->all(), $dept_id, 1);

		if ($request->other_coach_ids !== null) {
			$this->batch->insertBatch($request->all(), $dept_id, 2);
		}

		//create batch log also for coach 
		$coach_log_tbl = strtolower($dept_name) . '_coach_logs';
		DB::table($coach_log_tbl)->insert([
			'batch_name' => $request->batch_name,
			'coach_id' => $request->head_coach_id,
			'status' => 'current_batch',
			'start_date' => Carbon::today()->toDateString(),
			'created_at' => now(),
			'updated_at' => now(),
		]);



		return response()->json([
			'status' => 'success',
			'message' => 'Batch created successfully'
		]);
	}

	public function batchList()
	{
		try {

			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;
			$department = ucfirst(strtolower($dept->dept_name));
			$batches = $this->batch->batchList($dept_id);

			$coachList = $this->coach->loadAllCoachByDept($dept_id);

			$data['topmenu'] = 'Batches';
			$data['submenu'] = 'Batches';
			$data['pagetitle'] = $department . ' ' . 'Batches';
			return view('admin.batches.batchList', ['data' => $data, 'batches' => $batches, 'coaches' => $coachList]);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}

	public function getSubCoaches($batch_id)
	{

		try {
			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;

			$subCoach = $this->batch->getAllSubCoach($dept_id, $batch_id);

			return response()->json([
				'status' => 'success',
				'sub_coaches' => $subCoach
			]);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}

	//update
	public function update(Request $request, $id)
	{

		try {

			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;

			$request->validate([
				'batch_name' => 'required|string|max:255',
				'start_time' => 'required|date_format:H:i',
				'end_time' => 'required',
				'head_coach_id' => 'required',

			]);


			// old batch
			$batch = $this->batch->getBatchById($id, $dept_id);

			// validation

			if ($request->end_time <= $request->start_time) {
				return response()->json([
					'status' => 'error',
					'message' => 'The end time should be after the start time',
				], 422);
			}

			$startTime = Carbon::parse($request->start_time)->addMinutes(5)->format('H:i');

			if ($request->start_time === $request->end_time || $request->end_time <= $startTime ||  $request->end_time <= $request->start_time) {
				return response()->json([
					'status' => 'error',
					'message' => 'A minimum difference of 5 minutes is required between the selected times.',
				], 422);
			}


			// duplicate batch on same dept
			if ($batch->batch_name != $request->batch_name) {

				$batchExist = $this->batch->isBatchExist($dept_id, $request->batch_name);

				if ($batchExist) {
					return response()->json([
						'status' => 'error',
						'message' => 'A batch with this name already exists in your department.',
					], 422);
				}
			}



			//coach_log name and time update 
			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;



			$dept_name = $dept->dept_name;
			$coach_log_tbl = strtolower($dept_name) . '_coach_logs';

			if ($batch->batch_name != $request->batch_name) {
				DB::table($coach_log_tbl)->where('batch_name', $batch->batch_name)
					->update([
						'batch_name' => $request->batch_name,
					]);
			}



			//if head coach change then craete log 
			if ($batch->coach_id != $request->head_coach_id) {

				DB::table($coach_log_tbl)->where('batch_name', $batch->batch_name)
					->update([
						'status' => 'previous_batch',
						'end_date' => Carbon::today()->toDateString(),
					]);


				DB::table($coach_log_tbl)->insert([
					'batch_name' => $request->batch_name,
					'coach_id' => $request->head_coach_id,
					'start_date' => Carbon::today()->toDateString(),
					'status' => 'current_batch',
					'created_at' => now(),
					'updated_at' => now(),
				]);
			}



			//1st delete sub coach data
			DB::table('batches')
				->where('batch_name', $batch->batch_name)
				->where('dept_id', $batch->dept_id)
				->where('status', 2)
				->update(
					[
						'status' => 0,
						'deleted_at' => now(),
					]
				);

			// update head coach data



			$this->batch->updateBatch($request->all(), $id, 1);



			//check coach is change or not 
			$this->player->updatePlayerCoachId($request->all());

			// create subcoach
			if (!empty($request->other_coach_ids)) {

				$this->batch->insertBatch($request->all(), $dept_id,  2);
			}
			//coach log update 


			return response()->json([
				'success' => true,
				'message' => 'Batch updated successfully'
			]);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}


	// store batch_log
	public function store(Request $request)
	{
		$request->validate(
			[
				'start_date' => 'required|date',
				'end_date' => 'required|date|after:start_date',
				'batch_id' => 'required|integer',
				// 'coach_id_for_batch_log' => 'required',
			],
			[
				'end_date.after' => 'End date should be greater than the start date.',
				'batch_id.required' => 'Batch is required field.',
				// 'coach_id_for_batch_log.required' => 'Coach is required field.',
			]
		);

		$dept = getDepartmentData();

		if ($request->start_date >= $request->end_date) {
			return response()->json([
				'status' => false,
				'message' => 'Start Date and end Date should not be same!'
			]);
		}

		$deptName = $dept->dept_name;
		$department = strtolower($deptName);

		$table = $department . '_batch_logs';

		DB::table($table)->insert([
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
			'player_id' => $request->player_id,
			'batch_id' => $request->batch_id,
			'coach_id' => $request->coach_id_for_batch_log,
			'status' => 1, // approved
			'created_at' => now(),
			'updated_at' => now(),
		]);

		DB::table('players')
			->where('player_id', $request->player_id)
			->update([
				'batch_id' => $request->batch_id,
				'assign_coach_id' => $request->coach_id_for_batch_log,
			]);

		DB::table('dept_players')
			->where('player_id', $request->player_id)
			->update([
				'batch_id' => $request->batch_id
			]);


		return response()->json([
			'status' => true,
			'message' => 'Batch log saved successfully'
		]);
	}

	public function updatePlayerBatch(Request $request)
	{
		try {
			// Validate request
			$validated = $request->validate(
				[
					'log_id' => 'required',
					'batch_id' => 'required|exists:batches,id',
					'coach_id' => 'required',
					'start_date' => 'required|date',
					'end_date' => 'nullable|date|after_or_equal:start_date'
				],
				[
					'end_date.after_or_equal' => 'End date should be greater than the start date.',
					'batch_id.required' => 'Batch is required field.',
					// 'coach_id_for_batch_log.required' => 'Coach is required field.',
				]
			);

			$dept = getDepartmentData();
			$deptId = $dept->dept_id;

			$deptName = $dept->dept_name;
			$department = strtolower($deptName);

			$batch_log_table = $department . '_batch_logs';


			DB::table($batch_log_table)
				->where('id', $request->log_id)
				->update([
					'batch_id' => $request->batch_id,
					'coach_id' => $request->coach_id,
					'start_date' => $request->start_date,
					'end_date' => $request->end_date,
				]);


			DB::table('players')
				->where('player_id', $request->player_id)
				->update([
					'batch_id' => $request->batch_id,
					'assign_coach_id' => $request->coach_id,
				]);

			DB::table('dept_players')
				->where('player_id', $request->player_id)
				->update([
					'batch_id' => $request->batch_id
				]);


			return response()->json([
				'status' => 'success',
				'message' => 'Batch updated successfully',
			]);
		} catch (\Illuminate\Validation\ValidationException $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->errors(),
			], 422);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 500);
		}
	}


	//************************************* Assesment function *******************************************/

	public function storeAssesment(Request $request)
	{
		try {

			$request->validate([
				'foot'  => 'required|numeric',
				'inch'  => 'required|numeric',
				'weight'  => 'required|numeric',
				'sprint'  => 'required|numeric',
				'standing_broad_jump'  => 'nullable|numeric',
				'jump_with_stepping'  => 'nullable|numeric',
				'vertical_jump'  => 'nullable|numeric',
				'measuredBy' => 'required',
			]);
			$dept = getDepartmentData();
			$deptId = $dept->dept_id;

			$deptName = $dept->dept_name;

			$department = strtolower($deptName);
			$assessment_tbl = $department . '_assessments';

			$player_id = $request->input('player_id');
			$dept_player = $this->dept_player->getId($player_id);
			if ($dept_player) {


				DB::table($assessment_tbl)->insert([
					'dept_player_id' => $dept_player->id ?? null,
					'assessment_date' => $request->assessment_date ?? null,
					'foot' => $request->foot ?? null,
					'weight' => $request->weight ?? null,
					'sprint' => $request->sprint ?? null,
					'inch' => $request->inch ?? null,
					'standing_broad_jump' => $request->standing_broad_jump ?? null,
					'jump_with_stepping' => $request->jump_with_stepping ?? null,
					'vertical_jump' => $request->vertical_jump ?? null,
					'approved_by' => $request->measuredBy ?? null,
					'created_at' => now(),
					'updated_at' => now(),

				]);
			}
			return response()->json([
				'status' => 'success',
				'message' => 'Assesment saved successfully'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
			]);
		}
	}

	public function editAssessment($id)
	{

		$dept = getDepartmentData();

		$deptName = $dept->dept_name;
		$department = strtolower($deptName);
		$assessment_tbl = $department . '_assessments';


		$assessment = DB::table($assessment_tbl)
			->where('id', $id)
			->where('deleted_at', null)
			->first();

		return response()->json($assessment);
	}

	public function updateAssessment(Request $request, $id)
	{
		try {
			if (!$id) {
				return false;
			}

			$dept = getDepartmentData();
			$dept_id = $dept->dept_id;

			$deptName = $dept->dept_name;
			$department = strtolower($deptName);
			$assessment_tbl = $department . '_assessments';


			$data = DB::table($assessment_tbl)
				->where('id', $id)
				->where('deleted_at', null)
				->update([

					'assessment_date' => $request->assessment_date ?? null,
					'foot' => $request->foot ?? null,
					'weight' => $request->weight ?? null,
					'sprint' => $request->sprint ?? null,
					'inch' => $request->inch ?? null,
					'standing_broad_jump' => $request->standing_broad_jump ?? null,
					'jump_with_stepping' => $request->jump_with_stepping ?? null,
					'vertical_jump' => $request->vertical_jump ?? null,
					'approved_by' => $request->approved_by ?? null,
					'updated_at' => now(),
				]);


			return response()->json([
				'status' => 'success',
				'message' => 'Assesment updated successfully'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
			]);
		}
	}

	public function coachUpdateAttendance(Request $request, $id)
	{

		$dept = getDepartmentData();
		$dept_id = $dept->dept_id;
		$deptName = $dept->dept_name;
		$department = strtolower($deptName);

		$coach_attendance_tbl = $department . '_coach_attendance';
		DB::table($coach_attendance_tbl)
			->where('id', $id)
			->where('dept_id', $dept_id)
			->update([
				'in_time' => $request->in_time,
				'out_time' => $request->out_time,
				'status' => $request->status,
			]);
		return response()->json([
			'success' => true,
			'message' => 'Coach Attendance Updated Successfully'
		]);
	}


	//User Profile

	public function userProfile()
	{
		try {

			$user = Auth::user();

			$data['topmenu'] = 'User Profile';
			$data['submenu'] = 'User Profile';
			$data['pagetitle'] = 'User Profile';

			return view('admin/user/userProfile', compact('data', 'user'));
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}

	public function updateUserProfile(Request $request)
	{
		try {
			$updateUser = $this->user->updateUserProfile($request->all());
			return response()->json([
				'status' => 'success',
				'message' => 'User Data Update Successfully!',
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}
}
