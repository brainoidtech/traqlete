<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessLevels extends Model
{
    use SoftDeletes;

    protected $table = 'access_levels';

    protected $fillable = [
        'id',
        'role_id',
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
        'edit_player_attendance',
        'batch_player_filter',
        'coach_player_filter',
        'coach_overview',
        'coach_batch_log',
        'in_time_player_filter',
        'out_time_player_filter',
        'coach_edit_attendance',
    ];

    public function userRole()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function loadAccessLevel()
    {
        $role_id = auth()->user()->role_id;
        return static::where('role_id', $role_id)->first();
    }
    
    public function getAccessLevels()
    {
        return parent::select('id', 'role_id')->with('userRole')->where('role_id', '!=', 2)->get();
    }
}
