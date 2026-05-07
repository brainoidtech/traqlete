<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'report';
    protected $fillable = [
        'id',
        'dept_id',
        'player_id',
        'batch_id',
        'description',
    ];
    public function saveReport($data)
    {
        $report = self::create([
            'dept_id' => $data['dept_id'],
            'player_id' => $data['player_id'],
            'batch_id' => $data['batch_id'],
            'description' => $data['description']
        ]);

        return $report;
    }
}
