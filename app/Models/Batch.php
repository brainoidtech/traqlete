<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Batch extends Model
{
    use SoftDeletes;
    protected $table = 'batches';
    protected $fillable = [
        'id',
        'batch_name',
        'start_time',
        'end_time',
        'coach_id',
        'dept_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function coach()
    {
        return $this->hasMany(Coach::class, 'batch_id', 'id');
    }
    public function loadCoach()
    {
        return $this->belongsTo(Coach::class, 'coach_id', 'id');
    }

    public function batch($deptId)
    {
        return parent::with('loadCoach')->where('dept_id', $deptId)->get();
    }

    public function loadBatch($request)
    {
        $user = auth()->user();

        // ✅ Step 1: resolve dept_id (from request or user)
        $deptId = $user->resolveDeptId();

        if (!$deptId) {
            return collect();
        }

        // ✅ Step 2: If Super Admin → no coach restriction
        if ($user->isSuperAdmin()) {
            return Batch::where('dept_id', $deptId)
                ->where('status', 1)
                ->get();
        }

        // ✅ Step 3: For Coach → get correct coach_id for that dept
        $coachId = $user->getCoachIdByDept($deptId);

        if (!$coachId) {
            return collect();
        }

        // ✅ Step 4: Filter batches
        return Batch::where('coach_id', $coachId)
            ->where('dept_id', $deptId)
            ->where('status', 1)
            ->get();
    }

    // //################################################################## Batch dashboard function ####################################################################


    public function loadBatchByDept($deptId)
    {
        $data =  parent::where('dept_id', $deptId)->where('status', 1)->get();
        return $data;
    }

    public function getBatchesByDept($deptId)
    {
        $batches = parent::select('id', 'dept_id', 'batch_name', 'coach_id')->where('dept_id', $deptId)
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->get();
        return $batches;
    }


    public function loadBatchWithAdmin($code)
    {

        $data =  self::where('coach_id', 'LIKE', "%{$code}%")
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->get();
        return $data;
    }

    public function batchList($deptId)
    {
        $data =  self::with('loadCoach')
            ->where('dept_id', $deptId)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy(function ($items) {
                return $items->batch_name . '_' . $items->dept_id;
            })
            ->map(function ($item) {
                $headCoach = $item->where('status', 1)->first();
                $subCoach = $item->where('status', 2)
                    ->map(fn($ch) => ($ch->loadCoach->first_name ?? '-') . ' ' . ($ch->loadCoach->middle_name ?? '-') . ' ' . ($ch->loadCoach->last_name ?? '-'))->implode(', ');


                return [
                    'head_coach' => $headCoach,
                    'sub_coach' => $subCoach,
                ];
            });



        return $data;
    }

    public function getAllSubCoach($deptId, $batch_id)
    {
        $batch = self::findOrFail($batch_id);


        return  self::with('loadCoach')
            ->where('dept_id', $deptId)
            ->where('batch_name', $batch->batch_name)
            ->where('status', 2)
            // ->select('first_name')
            ->orderBy('batch_name')
            ->get()
            ->map(function ($b) {
                return [
                    'coach_id'    => $b->coach_id,
                    'first_name'  => $b->loadCoach->first_name  ?? '',
                    'middle_name' => $b->loadCoach->middle_name ?? '',
                    'last_name'   => $b->loadCoach->last_name   ?? '',
                ];
            });
    }

    public function getBatchTimeOfCoach($deptId, $coachId)
    {
        return self::where('dept_id', $deptId)
            ->where('coach_id', $coachId)
            ->select('start_time', 'end_time')
            ->where('status', 1)
            ->first();
    }

    public function getBatchById($id, $deptId)
    {
        return  parent::where('dept_id', $deptId)->where('id', $id)->first();
    }

    public function isBatchExist($dept_id, $batch_name)
    {
        return self::where('dept_id', $dept_id)
            ->where('batch_name', $batch_name)
            ->exists();
    }


    // batch insert

    public function insertBatch($data, $dept_id, $status)
    {

        if ($status == 2) {
            foreach ($data['other_coach_ids'] as $coach_id) {
                $b = new Batch;

                $b->batch_name = $data['batch_name'];
                $b->start_time = $data['start_time'];
                $b->end_time = $data['end_time'];
                $b->coach_id = $coach_id;
                $b->dept_id = $dept_id;
                $b->status = $status;

                $b->save();
            }
        } else {
            $b = new Batch;

            $b->batch_name = $data['batch_name'];
            $b->start_time = $data['start_time'];
            $b->end_time = $data['end_time'];
            $b->coach_id = $data['head_coach_id'];
            $b->dept_id = $dept_id;
            $b->status = $status;

            $b->save();
        }
    }

    public function updateBatch($data, $id, $status)
    {
        $batch = parent::findOrFail($id);
        
        if (!$batch) {
            return false;
        }

        if ($status === 2) {
           
            foreach ($data['other_coach_ids'] as $coach_id) {
                $batch->batch_name = $data['batch_name'];
                $batch->start_time = $data['start_time'];
                $batch->end_time = $data['end_time'];
                $batch->coach_id = $coach_id;
                $batch->status = $status;
                $batch->save();
            }
        } else {
            $batch->batch_name = $data['batch_name'];
            $batch->start_time = $data['start_time'];
            $batch->end_time = $data['end_time'];
            $batch->coach_id = $data['head_coach_id'];
            $batch->status = $status;
            $batch->save();
        }
    }
}
