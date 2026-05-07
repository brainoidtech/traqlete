<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject; // <-- Add this
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable implements JWTSubject // <-- Implement JWTSubject
{

    use SoftDeletes;
    use HasApiTokens, HasFactory;
    use Notifiable;


    protected $fillable = [
        'name',
        'password',
        'contact_number',
        'document_number',
        'role_id',
        'dept_id',
        'last_login_at',
        'last_login_ip',
        'profile_photo_path',
        'document_number'
    ];

    // /**
    //  * The attributes that should be hidden for serialization.
    //  *
    //  *
    //  */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array<string, string>
    //  */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    //     'last_login_at' => 'datetime',
    // ];

    /**
     * JWTSubject: Get the identifier for JWT
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // usually the user id
    }

    /**
     * JWTSubject: Return custom claims for JWT
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // ----------------- Other existing methods -----------------

    // public function getProfilePhotoUrlAttribute()
    // {
    //     if ($this->profile_photo_path) {
    //         return asset('storage/' . $this->profile_photo_path);
    //     }

    //     return $this->profile_photo_path;
    // }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function getDefaultAddressAttribute()
    {
        return $this->addresses?->first();
    }

    public function userRole()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function userDept()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }


    //function


    /**
     * Check if user is SuperAdmin
     */
    public function isSuperAdmin(): bool
    {
        return (int) $this->role_id === 2;
    }

    /**
     * Safely resolve dept name
     * SuperAdmin → reads X-Dept-Id header
     * Normal user → uses their own userDept
     */
    // public function resolveDeptName(): ?string
    // {
    //     if ($this->isSuperAdmin()) {
    //         $deptId = request()->input('dept_id');
    //         if (!$deptId) return null;
    //         $dept = \DB::table('department')
    //             ->where('dept_id', $deptId)
    //             ->first();
    //         if (!$dept) return null;
    //         return preg_replace('/[^a-z0-9]/', '_', strtolower($dept->dept_name));
    //     }
    //     if (!$this->userDept) return null;
    //     $name = is_array($this->userDept)
    //         ? $this->userDept['dept_name']
    //         : $this->userDept->dept_name;
    //     return preg_replace('/[^a-z0-9]/', '_', strtolower($name));
    // }

    public function resolveDeptName(): ?string
    {
        $deptId = $this->resolveDeptId();
        if (!$deptId) return null;
        $dept = \DB::table('department')
            ->where('dept_id', $deptId)
            ->first();
        if (!$dept) return null;
        return preg_replace('/[^a-z0-9]/', '_', strtolower($dept->dept_name));
    }

    /**
     * Safely resolve dept_id
     * SuperAdmin → reads X-Dept-Id header
     * Normal user → uses their own dept_id
     */
    // public function resolveDeptId(): ?int
    // {
    //     if ($this->isSuperAdmin()) {
    //         $deptId = request()->input('dept_id');
    //         return $deptId ? (int) $deptId : null;
    //     }
    //     return $this->dept_id ? (int) $this->dept_id : null;
    // }
    public function resolveDeptId(): ?int
    {
        // ✅ If dept_id passed (coach / super admin)
        if (request()->has('dept_id') && request()->input('dept_id')) {
            return (int) request()->input('dept_id');
        }

        // ✅ Otherwise fallback to user's own dept (normal users)
        return $this->dept_id ? (int) $this->dept_id : null;
    }

    public function insertUserbyDept($data, $id)
    {
        try {


            if (!$id) {
                throw new \Exception('Department not found.');
            }

            $admin = new User();
            $admin->name = $data['dept_admin_name'];
            $admin->email = $data['dept_admin_email'];
            $admin->contact_number = $data['dept_admin_contact'];
            $admin->password = Hash::make($data['dept_admin_password']);
            $admin->role_id = 6;
            $admin->dept_id = $id;
            $admin->save();

            $manager = new User();
            $manager->name = $data['dept_manager_name'];
            $manager->email = $data['dept_manager_email'];
            $manager->contact_number = $data['dept_manager_contact'];
            $manager->password = Hash::make($data['dept_manager_password']);
            $manager->role_id = 5;
            $manager->dept_id = $id;
            $manager->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Department admin created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function updateUserByDept($id, $data)
    {
        try {

            if (!$id) {
                throw new \Exception('Department not found.');
            }

            $admin = self::where('dept_id', $id)
                ->where('role_id', 6)
                ->first();

            if ($admin) {
                $admin->name = $data['dept_admin_name'];
                $admin->email = $data['dept_admin_email'];
                $admin->contact_number = $data['dept_admin_contact'];

                if (!empty($data['dept_admin_password'])) {
                    $admin->password = Hash::make($data['dept_admin_password']);
                }

                $admin->save();
            }

            $manager = self::where('dept_id', $id)
                ->where('role_id', 5)
                ->first();



            if ($manager) {
                $manager->name = $data['dept_manager_name'];
                $manager->email = $data['dept_manager_email'];
                $manager->contact_number = $data['dept_manager_contact'];


                if (!empty($data['dept_manager_password'])) {
                    $manager->password = Hash::make($data['dept_manager_password']);
                }

                $manager->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Department Updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function destroyUserByDept($id)
    {
        try {
            if (!$id) {
                throw new \Exception('Department not found.');
            }
            User::where('dept_id', $id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Department users deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public static function updateAuthUserProfile($data)
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        $user->name = $data['name'];
        $user->save();
        return $user;
    }

    public function getUserList()
    {
        return parent::with('userRole:id,role_name')->whereNotIn('role_id', [1, 3, 4, 5, 6])->select('id', 'role_id', 'name');
    }

    public function getAdminUserList($dept_id)
    {
        return parent::with('userRole:id,role_name')->where('dept_id', $dept_id)
            ->whereNotIn('role_id', [2, 3])
            ->select('id', 'role_id', 'name');
    }


    // public function getUserData($deptId)
    // {
    //     return  self::whereIn('role_id', [5, 6])
    //         ->orderBy('created_at', 'asc')
    //         ->where('dept_id', $deptId)
    //         ->limit(2)
    //         ->get();
    // }

    public function getUserData($deptId)
    {

         $role6 = self::where('role_id', 6)  // admin
            ->where('dept_id', $deptId)
            ->orderBy('created_at', 'asc')
            ->first();
            
        $role5 = self::where('role_id', 5)  // manager
            ->where('dept_id', $deptId)
            ->orderBy('created_at', 'asc')
            ->first();

       

        $result = collect([$role6, $role5])->filter();
        return  $result;
    }






    public function addUserData($userData)
    {
        $adminUserData  = new User();
        $adminUserData->role_id = $userData['role_id'];
        $adminUserData->name =  $userData['name'];
        $adminUserData->email = $userData['email'];
        $adminUserData->contact_number =  $userData['contact_number'];
        $adminUserData->password = Hash::make($userData['password']);
        $adminUserData->save();
    }

    public function addAdminUserData($userData)
    {
        $adminUserData  = new User();
        $adminUserData->role_id = $userData['role_id'];
        $adminUserData->dept_id = $userData['dept_id'];
        $adminUserData->name =  $userData['name'];
        $adminUserData->email = $userData['email'];
        $adminUserData->contact_number =  $userData['contact_number'];
        $adminUserData->password = Hash::make($userData['password']);
        $adminUserData->save();
    }

    public function editUserData($id)
    {
        return self::where('id', $id)->where('role_id', 2)->first();
    }

    public function editAdminUserData($id)
    {
        // $users = User::where('role_id', 2)->get();
        $roles = [1, 4, 5, 6];
        // $users = self::whereIn('role_id', $roles)->get();
        return self::where('id', $id)->whereIn('role_id', $roles)->first();
    }

    public function updateUserData($userData, $id)
    {
        $updateUserData = $this->editUserData($id);
        $updateUserData->role_id = $userData['role_id'];
        $updateUserData->name = $userData['name'];
        $updateUserData->email = $userData['email'];
        $updateUserData->contact_number = $userData['contact_number'];
        if (!empty($userData['password'])) {
            $updateUserData->password = Hash::make($userData['password']);
        }
        return $updateUserData->save();
    }

    public function updateAdminUserData($userData, $id)
    {
        $updateUserData = $this->editAdminUserData($id);
        $updateUserData->role_id = $userData['role_id'];
        $updateUserData->dept_id = $userData['dept_id'];
        $updateUserData->name = $userData['name'];
        $updateUserData->email = $userData['email'];
        $updateUserData->contact_number = $userData['contact_number'];
        if (!empty($userData['password'])) {
            $updateUserData->password = Hash::make($userData['password']);
        }
        return $updateUserData->save();
    }

    public function deleteUserData($deleteUserData)
    {
        return $deleteUserData->delete();
    }

    public function updateCoachUserData($data, $deptId, $coachId)
    {

        $coach = self::where('coach_id', $coachId)->first();

        $coach->name = trim($data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name']);
        $coach->email = $data['coach_email'];
        $coach->contact_number = $data['coach_contact_no'];
        $coach->password = Hash::make($data['password']);
        $coach->role_id = 3;
        $coach->dept_id = $deptId;
        $coach->document_number = $data['document'];
        $coach->save();
    }

    public function updateUserProfile($data)
    {
        try {
            $user = self::where('id', $data['user_id'])->first();
            $user->name = $data['name'];
            $user->contact_number = $data['contact_number'];
            $user->password = Hash::make($data['password']);
            $user->updated_at = now();

            return $user->save();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getCoachIdByDept($deptId)
    {
        if (empty($this->document_number)) {
            return null;
        }

        return \App\Models\Coach::where('document', $this->document_number)
            ->where('dept_id', $deptId)
            ->whereNull('deleted_at')
            ->value('id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
