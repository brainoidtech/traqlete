<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoachQrcode extends Model
{
    use SoftDeletes;
    protected $table = 'coach_qrcodes';
    protected $fillable = [
        'id',
        'dept_id',
        'coach_id',
        'qr_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];




    public function coachQr($data)
    {
        $qr = new CoachQrcode;

        $qr->dept_id = $data['dept_id'];
        $qr->coach_id = $data['id'];
        $qr->qr_code = $data['qr_code'];

        $result = $qr->save();
        return $result;
    }
}