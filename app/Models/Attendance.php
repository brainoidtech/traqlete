<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    protected $table = 'attendance';

    protected $fillable = [
        // 'id',
        'player_coaches_id',
        'date',
        'in_time',
        'out_time',
        'status',
        'role',
    ];

    //one to many relationship
    public function players()
    {
        return $this->belongsTo(Player::class, 'player_coaches_id', 'id');
    }

    public function coaches()
    {
        return $this->belongsTo(Coach::class, 'player_coaches_id', 'id');
    }

    //functions
    public function storeAttendance($data)
    {
        $attend = new Attendance;

        $attend->player_coaches_id = $data['id'] ?? null;
        $attend->date = $data['date'] ?? null;
        $attend->in_time = $data['in_time'] ?? null;
        $attend->out_time = $data['out_time'] ?? null;
        $attend->status = "OUT";


        $attend->role = $data['role'];

        $saveData = $attend->save();

        return $saveData;
    }

    public function loadPlayerAttendance()
    {
        $player = parent::with(['players', 'coaches'])->where('role', 'Player')->get();

        return $player;
    }

    public function loadCoachAttendance()
    {
        $coach = parent::with(['coaches'])->where('role', 'Coach')->get();
        return $coach;
    }
}
