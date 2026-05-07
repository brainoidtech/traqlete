<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerParent extends Model
{
	use SoftDeletes;
	protected $table = 'parent_names';

	protected $fillable = [
		'id',
		'player_id',
		'dept_id',
		'parent_name',
		'relation',
		'parent_email',
		'parent_contact',
		'parent_profession',
		'created_at',
		'updated_at',
		'deleted_at',

	];
	public function player()
	{
		return $this->belongsTo(Player::class, 'player_id', 'id');
	}


	public function insertParentData($data) {
		$player_id = $data['player_id'];
		$dept_id = $data['dept_id'];

		foreach($data['parent'] as $parents){
			$parent = new PlayerParent;

			$parent->player_id = $player_id;
			$parent->dept_id = $dept_id;
			$parent->parent_name = $parents['parent_name'] ?? null;
			$parent->relation = $parents['relation'] ?? '';
			$parent->parent_email = $parents['parent_email'] ?? '';
			$parent->parent_contact = $parents['parent_contact'] ?? '';
			$parent->parent_profession = $parents['parent_profession'] ?? '';

			$parent->save();
		}
	}

	public function updateParent($data, $player_id, $dept_id)
	{
		$totalParent = count($data);
		$parentData = self::where('player_id', $player_id)->whereNull('deleted_at')->get();
		$totalParentInTable = count($parentData);


		if($totalParent === $totalParentInTable){
			foreach($parentData as $index => $pd){
				$parent_ids = array_column($data, 'parent_id');
				$exist = in_array($pd['id'], $parent_ids);
				// if parent id not exist in $data then delete from table 
				if(!$exist){
					$pd->delete();
				}

				// if parent id is set then update else create
				if(isset($data[$index]['parent_id'])){

					$existingData = self::find($data[$index]['parent_id']);

					$existingData->parent_name = $data[$index]['parent_name'] ?? '';
					$existingData->relation = $data[$index]['relation'] ?? '';
					$existingData->parent_email = $data[$index]['parent_email'] ?? '';
					$existingData->parent_contact = $data[$index]['parent_contact'] ?? '';
					$existingData->parent_profession = $data[$index]['parent_profession'] ?? '';

					$existingData->save();
				}else{
					$newParent = new PlayerParent;
					$newParent->player_id = $player_id;
					$newParent->dept_id = $dept_id;
					$newParent->parent_name = $data[$index]['parent_name'] ?? null;
					$newParent->relation = $data[$index]['relation'] ?? '';
					$newParent->parent_email = $data[$index]['parent_email'] ?? '';
					$newParent->parent_contact = $data[$index]['parent_contact'] ?? '';
					$newParent->parent_profession = $data[$index]['parent_profession'] ?? '';

					$newParent->save();
				}
			}

		}elseif($totalParent < $totalParentInTable){
			foreach($parentData as $pd){

				$parent_ids = array_column($data, 'parent_id');
				$exist = in_array($pd['id'], $parent_ids);

				if(!$exist){
					$pd->delete();
				}else{
					foreach($data as $parent){
						if($parent['parent_id'] == $pd['id']){
							$pd->parent_name = $parent['parent_name'] ?? '';
							$pd->relation = $parent['relation'] ?? '';
							$pd->parent_email = $parent['parent_email'] ?? '';
							$pd->parent_contact = $parent['parent_contact'] ?? '';
							$pd->parent_profession = $parent['parent_profession'] ?? '';

							$pd->save();
						}
					}
				}
			}
		}elseif($totalParent > $totalParentInTable){
			foreach($data as $parent){
				if(isset($parent['parent_id'])){
					$existingData = self::find($parent['parent_id']);

					$existingData->parent_name = $parent['parent_name'] ?? '';
					$existingData->relation = $parent['relation'] ?? '';
					$existingData->parent_email = $parent['parent_email'] ?? '';
					$existingData->parent_contact = $parent['parent_contact'] ?? '';
					$existingData->parent_profession = $parent['parent_profession'] ?? '';

					$existingData->save();
				}else{
					$newParent = new PlayerParent;

					$newParent->player_id = $player_id;
					$newParent->dept_id = $dept_id;
					$newParent->parent_name = $parent['parent_name'] ?? null;
					$newParent->relation = $parent['relation'] ?? '';
					$newParent->parent_email = $parent['parent_email'] ?? '';
					$newParent->parent_contact = $parent['parent_contact'] ?? '';
					$newParent->parent_profession = $parent['parent_profession'] ?? '';

					$newParent->save();
				}
			}
		}
	}

	public function getPlayerParents($playerId, $deptId)
	{
		return self::where('player_id', $playerId)
			->where('dept_id', $deptId)
			->get();
	}
}
