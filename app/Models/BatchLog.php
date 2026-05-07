<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Coach, Batch, DepartmentPlayers};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BatchLog extends Model
{
    use HasFactory;

    protected $table = 'batch_logs';

    protected $fillable = [
        'batch_id',
        'coach_id',
        'player_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function setDepartmentTable(string $deptName): static
    {
        $this->table = strtolower($deptName) . '_batch_logs';
        return $this;
    }

    public static function forDept(?string $deptName = null): static
    {
        $instance = new static();
        $dept     = $deptName ?? Auth::user()->resolveDeptName();
        if (!$dept) {
            throw new \Exception('Please select a department first.');
        }
        $instance->table = strtolower($dept) . '_batch_logs';
        return $instance;
    }

    public static function onDept(?string $deptName = null)
    {
        $instance = static::forDept($deptName);
        return $instance->newQuery();
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    // Coach relationship
    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id', 'id');
        // assuming coach_id matches coaches.id
        // If coach_id is a string like 'DGBBC004', adjust accordingly
    }
    public function batchLog()
    {
        return static::with(['batch', 'coach'])->get();
    }

    // public static function forDept(?string $deptName = null): static
    // {
    //     $instance = new static();
    //     $dept     = $deptName ?? Auth::user()->resolveDeptName();

    //     if (!$dept) {
    //         throw new \Exception('Please select a department first.');
    //     }

    //     $instance->table = strtolower($dept) . '_batch_logs';
    //     return $instance;
    // }

    public function getPlayerBatchLogs(string $player_id): \Illuminate\Support\Collection
    {
        $user     = Auth::user();
        $deptName = $user->resolveDeptName();
        $deptId   = $user->resolveDeptId();

        if (!$deptName || !$deptId) {
            throw new \Exception('Please select a department first.');
        }

        $mappedBatchIds = DepartmentPlayers::where('player_id', $player_id)
            ->where('dept_id', $deptId)
            ->pluck('batch_id')
            ->toArray();

        if (empty($mappedBatchIds)) return collect();

        return static::onDept($deptName)
            ->with(['batch:id,batch_name,dept_id', 'coach:id,first_name,last_name'])
            ->where('player_id', $player_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) use ($mappedBatchIds) {
                $item->is_assigned = in_array($item->batch_id, $mappedBatchIds);
                return $item;
            });
    }

    public function addBatchLogData(array $data): mixed
    {
        $user     = Auth::user();
        $deptName = $user->resolveDeptName();
        $deptId   = $user->resolveDeptId();

        if (!$deptName || !$deptId) {
            throw new \Exception('Please select a department first.');
        }

        $playerExists = DepartmentPlayers::where('player_id', $data['player_id'])
            ->where('dept_id', $deptId)->exists();

        if (!$playerExists) {
            return ['status' => false, 'message' => 'This player does not belong to your department.'];
        }

        $batchExists = Batch::where('id', $data['batch_id'])
            ->where('dept_id', $deptId)->exists();

        if (!$batchExists) {
            return ['status' => false, 'message' => 'This batch does not belong to your department.'];
        }

        $instance = static::forDept($deptName);
        $instance->fill([
            'batch_id'   => $data['batch_id'],
            'coach_id'   => $data['coach_id'],
            'player_id'  => $data['player_id'],
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
        ]);
        $instance->save();

        return $instance->load(['batch:id,batch_name,dept_id', 'coach:id,first_name,last_name']);
    }

    public function updateBatchLog(array $data): array
    {
        $user     = Auth::user();
        $deptName = $user->resolveDeptName();
        $deptId   = $user->resolveDeptId();

        if (!$deptName || !$deptId) {
            throw new \Exception('Please select a department first.');
        }

        $playerExists = DepartmentPlayers::where('player_id', $data['player_id'])
            ->where('dept_id', $deptId)->exists();

        if (!$playerExists) {
            return ['status' => false, 'message' => 'This player does not belong to your department.'];
        }

        $batchExists = Batch::where('id', $data['batch_id'])
            ->where('dept_id', $deptId)->exists();

        if (!$batchExists) {
            return ['status' => false, 'message' => 'Selected batch does not belong to your department.'];
        }

        $batchLog = static::onDept($deptName)
            ->where('id', $data['id'])
            ->whereHas('batch', fn($q) => $q->where('dept_id', $deptId))
            ->first();

        if (!$batchLog) {
            return ['status' => false, 'message' => 'Batch log not found or unauthorized.'];
        }

        $batchLog->update([
            'batch_id'   => $data['batch_id'],
            'coach_id'   => $data['coach_id'],
            'player_id'  => $data['player_id'],
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
        ]);

        DepartmentPlayers::updateOrCreate(
            ['player_id' => $data['player_id'], 'dept_id' => $deptId],
            ['batch_id'  => $data['batch_id']]
        );

        return [
            'status' => true,
            'data'   => $batchLog->load([
                'batch:id,batch_name,dept_id',
                'coach:id,first_name,last_name',
            ]),
        ];
    }

    public function getAllBatchesCoaches(): array
    {
        $user   = Auth::user();
        $deptId = $user->resolveDeptId();

        if (!$deptId) {
            throw new \Exception('Please select a department first.');
        }

        return [
            'batches' => Batch::where('dept_id', $deptId)->select('id', 'batch_name')->get(),
            'coaches' => Coach::where('dept_id', $deptId)->select('id', 'first_name', 'last_name')->get(),
        ];
    }
}
