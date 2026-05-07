<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerQrcode extends Model
{
    use SoftDeletes;
    protected $table = 'player_qrcodes';
    protected $fillable = [
        'id',
        'dept_id',
        'player_id',
        'qr_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];




    public function insertPlayerQr($data)
    {
        $qr = new PlayerQrcode;

        $qr->dept_id = $data['dept_id'];
        $qr->player_id = $data['player_id'];
        $qr->qr_code = $data['qr_code'];

        $result = $qr->save();
        return $result;
    }
}