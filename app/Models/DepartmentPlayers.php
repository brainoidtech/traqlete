<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentPlayers extends Model
{
	protected $table = 'dept_players';
	use HasFactory;

	protected $fillable = [
		'id',
		'player_id',
		'batch_id',
		'dept_id',
		'player_code'
	];




	public function feeValidity()
	{
		$user = auth()->user();
		$deptId = $user->resolveDeptId(); // ✅ correct
		$dept = \App\Models\Department::find($deptId);
		if (!$dept) {
			return $this->hasMany(\App\Models\FeesValidity::class, 'dept_player_id');
		}
		$tableName = strtolower(str_replace(' ', '_', $dept->dept_name)) . '_fee_validity';
		$instance = new \App\Models\FeesValidity;
		$instance->setTable($tableName);
		return $this->hasMany(
			\App\Models\FeesValidity::class,
			'dept_player_id'
		)->setModel($instance);
	}

	public function dept()
	{
		return $this->belongsTo(Department::class, 'dept_id');
	}
	public function feeValiditys()
	{
		return $this->hasOne(FeesValidity::class, 'dept_player_id')
			->latest('valid_from'); // OR ->latest('created_at')
	}

	public function player()
	{
		return $this->belongsTo(Player::class, 'player_id');
	}

	public function assessment()
	{
		return $this->belongsTo(Assessment::class, 'dept_player_id');
	}

	public function batchLogs()
	{
		return $this->hasMany(BatchLog::class, 'batch_id', 'batch_id');
	}

	public function batch()
	{
		return $this->belongsTo(Batch::class, 'batch_id', 'id');
	}




	//****************************************** Dashboard function ******************************************************* */

	public function addDeptPlayerData($data)
	{

		$player = new DepartmentPlayers;

		$player_code = preg_replace('/^DG|[^A-Za-z]/', '', $data['player_id']);
		$player->dept_id = $data['dept_id'];
		$player->player_id = $data['player_id'];
		$player->batch_id = $data['batch_id'];
		$player->player_code = $player_code;

		$player->save();
	}

	public function getPlayerById($player_id, $dept_id)
	{
		return parent::where('player_id', $player_id)->where('dept_id', $dept_id)->select('id')->first();
	}

	public function getDeptPlayerId($player_id, $dept_id)
	{
		$deptPlayerId = parent::where('player_id', $player_id)->where('dept_id', $dept_id)->get();
		return $deptPlayerId;
	}

	public function getId($id)
	{
		return parent::where('player_id', $id)->select('id')->first();
	}

	public function getDepartmentPlayers($player_id)
	{
		$deptId = auth()->user()->resolveDeptId();
		return parent::where('player_id', $player_id)
			->where('dept_id', $deptId)
			->first();
	}

	/// admin dashboard data display
	public function totalDeptPlayer($deptId, $status)
	{
		$countPlayers = parent::whereHas('player', function ($query) use ($status) {
			$query->where('status', $status);
		})->where('dept_id', $deptId)
			->count();
		return $countPlayers;
	}

	public function getBatchTime($deptId, $playerId)
	{
		return parent::with('batch')
			->where('dept_players.dept_id', $deptId)
			->where('dept_players.player_id', $playerId)
			->first();
	}

	
}
