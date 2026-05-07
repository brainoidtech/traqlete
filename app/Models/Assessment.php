<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use SoftDeletes;

    protected $table = 'assessment';

    protected $fillable = [
        'id',
        'dept_player_id',
        'assessment_date',
        'foot',
        'weight',
        'sprint',
        'inch',
        'standing_broad_jump',
        'jump_with_stepping',
        'vertical_jump',
        'approved_by'
    ];


    public function dept_players()
    {
        return $this->belongsTo(DepartmentPlayers::class, 'id', 'dept_player_id');
    }

    public function storePlayerAssessment($data, $playerId)
    {
        $assessment = new Assessment();

       
        $assessment->dept_player_id       = $playerId->id ?? null;
        $assessment->assessment_date      = $data['assessment_date'] ?? null;
        $assessment->foot                 = $data['foot'] ?? null;
        $assessment->weight               = $data['weight'] ?? null;
        $assessment->sprint               = $data['sprint'] ?? null;
        $assessment->inch                 = $data['inch'] ?? null;
        $assessment->standing_broad_jump  = $data['standing_broad_jump'] ?? null;
        $assessment->jump_with_stepping   = $data['jump_with_stepping'] ?? null;
        $assessment->vertical_jump        = $data['vertical_jump'] ?? null;
        $assessment->approved_by          = $data['measuredBy'] ?? null;

        $saveData = $assessment->save();

        return $saveData;
    }

    public function getAssessmentById($deptPlayerId)
    {
        
       return parent::where('dept_player_id', $deptPlayerId)->orderBy('assessment_date', 'desc')->get();
    }

    public function updateAssessment($data, $Id)
    {
        $assessment = parent::findOrFail($Id);

       
        $assessment->assessment_date      = $data['assessment_date'] ?? null;
        $assessment->foot                 = $data['foot'] ?? null;
        $assessment->weight               = $data['weight'] ?? null;
        $assessment->sprint               = $data['sprint'] ?? null;
        $assessment->inch                 = $data['inch'] ?? null;
        $assessment->standing_broad_jump  = $data['standing_broad_jump'] ?? null;
        $assessment->jump_with_stepping   = $data['jump_with_stepping'] ?? null;
        $assessment->vertical_jump        = $data['vertical_jump'] ?? null;
        $assessment->approved_by           =  $data['approved_by'] ?? null; 

         $saveData = $assessment->save();

        return $saveData;

    }
    
}
