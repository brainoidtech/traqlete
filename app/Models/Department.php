<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Coach;
use App\Models\batch;

class Department extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'dept_id';
    protected $table = 'department';
    protected $fillable = [
        'dept_id',
        'dept_name',
        'dept_icon',
        'dept_email',
        'dept_contact_number',
        'code_for_player',
        'digit_for_player',
        'code_for_coach',
        'digit_for_coach',
        'dept_admin_name',
        'dept_admin_email',
        'dept_admin_contact',
        'dept_admin_password',
        'dept_manager_name',
        'dept_manager_email',
        'dept_manager_contact',
        'dept_manager_password',
        'latitude',
        'longitude',
        'geofence_radius',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];



    //relations
    public function players()
    {
        return $this->hasMany(
            Player::class,
            'dept_id',
            'dept_id'
        );
    }
    public function coach()
    {
        return $this->hasMany(Coach::class, 'dept_id', 'dept_id');
    }

    public function batch()
    {
        return $this->hasMany(Batch::class, 'batch_id', 'id');
    }

    public function userDepts()
    {
        return $this->hasMany(User::class, 'dept_id', 'dept_id');
    }

    public function loadDepartment()
    {
        $user   = auth()->user();
        $roleId = $user->role_id;

        // ── Role 2 (Admin/Manager) → all departments ────────────────────────────
        if ($roleId == 2 || $roleId == 4) {
            return parent::withCount([
                'players' => fn($q) => $q->where('status', 1),
                'coach',
            ])->get();
        }

        // ── Role 3 (Coach) → find all dept assignments from coaches table ────────
        // coaches table has multiple rows for same coach email across departments
        // e.g. id=DGKDC007 dept_id=8, id=DGATC0007 dept_id=13, id=DGRN0006 dept_id=9
        $coachRows = \DB::table('coaches')
            ->where('coach_email', $user->email)  // match by email across all depts
            ->whereNull('deleted_at')
            ->select('id as coach_id', 'dept_id')
            ->get();

        if ($coachRows->isEmpty()) {
            return collect();
        }

        // Build map: [dept_id => coach_id]
        // e.g. [8 => 'DGKDC007', 13 => 'DGATC0007', 9 => 'DGRN0006']
        $deptCoachMap = $coachRows->pluck('coach_id', 'dept_id');
        $assignedDeptIds = $deptCoachMap->keys();

        // Only fetch assigned departments
        $depts = parent::whereIn('dept_id', $assignedDeptIds)->get();

        $depts->each(function ($dept) use ($deptCoachMap) {
            $coachIdForDept = $deptCoachMap->get($dept->dept_id);
             $dept->setAttribute('coach_id', $coachIdForDept);
            $dept->players_count = $coachIdForDept
                ? $dept->players()
                ->where('assign_coach_id', $coachIdForDept)
                ->where('status', 1)
                ->count()
                : 0;

            $dept->batches_count = $coachIdForDept
                ? $dept->batches()
                ->where('coach_id', $coachIdForDept)
                ->count()
                : 0;
        });

        return $depts;
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'dept_id', 'dept_id')
            ->whereNull('deleted_at')
            ->where('status', 1);
    }
    public function loadDeptById($id)
    {
        return  parent::where('dept_id', $id)->latest()->first();
    }


    //################################################################## Department dashboard function ####################################################################

    public function getAllDept()
    {
        return self::with('userDepts')->orderBy('dept_name')->get();
    }

    public function getDeptById($id)
    {
        return self::with('userDepts')->where('dept_id', $id)->first();
    }

    public function isDeptExist($dept_name)
    {
        return self::where('dept_name', $dept_name)->exists();
    }

    public function getExistingCode($data)
    {
        return self::select('code_for_player', 'code_for_coach')
            ->where('code_for_player', $data[0])
            ->orWhere('code_for_coach', $data[1])
            ->first();
    }


    public function insertDept($data)
    {
        $dept = new Department;

        if (!empty($data['dept_icon'])) {
            $dept->dept_icon = $data['dept_icon'];
        }


        $dept->dept_name = strtolower($data['dept_name']);
        $dept->dept_email = $data['dept_email'];
        $dept->dept_contact_number = $data['dept_contact_number'];
        $dept->code_for_player = $data['code_for_player'];
        $dept->digit_for_player = $data['digit_for_player'];
        $dept->code_for_coach = $data['code_for_coach'];
        $dept->digit_for_coach = $data['digit_for_coach'];
        $dept->latitude = $data['latitude'];
        $dept->longitude = $data['longitude'];
        $dept->geofence_radius = $data['geofence_radius'];
        $dept->status = 1;
        $dept->save();
        return $dept->dept_id;
    }

    public function updateDept($id, $data)
    {


        $dept = self::where('dept_id', $id)->first();
        if (empty($dept)) {
            return false;
        }

        if (!empty($data['dept_icon'])) {
            $dept->dept_icon = $data['dept_icon'];
        }

        $dept->dept_name = strtolower($data['dept_name']);
        $dept->dept_email = $data['dept_email'];
        $dept->dept_contact_number = $data['dept_contact_number'];
        $dept->code_for_player = $data['code_for_player'];
        $dept->digit_for_player = $data['digit_for_player'];
        $dept->code_for_coach = $data['code_for_coach'];
        $dept->digit_for_coach = $data['digit_for_coach'];
        $dept->latitude = $data['latitude'];
        $dept->longitude = $data['longitude'];
        $dept->geofence_radius = $data['geofence_radius'];
        $dept->status = 1;




        return $dept->save();
    }

    public function destroyDept($id)
    {
        return self::where('dept_id', $id)->delete();
    }

    //superAdmin dashboard
    public function getDeptData($dept = null)
    {
        $query = self::withCount([
            'players' => function ($query) {
                $query->where('status', 1);
            },
            'coach'
        ]);

        if ($dept != null && $dept != 'all') {
            $query->where('dept_name', 'like', '%' . $dept . '%');
        }
        return $query->get();
    }

    public function getUserdepartment()
    {
        return self::select('dept_id', 'dept_name')->get();
    }

    public function getDeptIdById($dept_id)
    {
        return self::where('dept_id', $dept_id)->first();
    }
    public function getDeptIdByName($dept)
    {
        return self::select('dept_id')->where('dept_name', $dept)->first();
    }

    public function getDeptLatLong($dept_id)
    {
        return self::select('dept_id', 'latitude', 'longitude', 'geofence_radius')->where('dept_id', $dept_id)->get();
    }
}
