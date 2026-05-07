<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentCoach extends Model
{
    protected $table = 'dept_coaches';
    use HasFactory;

    protected $fillable = [
        'id',
        'coach_id',
        'batch_id',
        'dept_id',
        'coach_code'
    ];

    // public function feeValidity()
    // {
    //     return $this->hasMany(FeesValidity::class, 'dept_player_id');
    // }

    // public function dept()
    // {
    //     return $this->belongsTo(Department::class, 'dept_id');
    // }






    //****************************************** Dashboard function ******************************************************* */

    public function addDeptCoachData($data)
    {

        $player = new DepartmentCoach;

        $coach_code = preg_replace('/^DG|[^A-Za-z]/', '', $data['id']);
        $player->dept_id = $data['dept_id'];
        $player->coach_id = $data['id'];
        // $player->batch_id = $data['batch_id'];
        $player->coach_code = $coach_code;

        $player->save();
    }

    public function totalDeptCoach($deptId)
    {
        $countCoach = parent::where('dept_id', $deptId)->count();
        return $countCoach;
    }
}
