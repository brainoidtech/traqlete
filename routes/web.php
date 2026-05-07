<?php

use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
// use App\Http\Controllers\DashboardController1;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\SuperAdmin\DepartmentController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/help', [DashboardController::class, 'help'])->name('help');

Route::middleware(['auth'])->group(function () {

    // Route::get('/', [DashboardController::class, 'index']);
    Route::middleware(['role:departmentAdmin,superAdmin'])->group(function () {


        //Dashboard
        Route::get('/dashboard/{deptId}', [DashboardController::class, 'index'])->name('dashboard');

        // csv file import in table
        Route::get('/players-csv-file', [DashboardController::class, 'uploadPlayerCsv']);
        Route::get('/coaches-csv-file', [DashboardController::class, 'uploadCoachCsv']);
        Route::post('/import-player-csv', [DashboardController::class, 'importPlayerCsv'])->name('player_csv');
        Route::post('/import-coach-csv', [DashboardController::class, 'importCoachCsv'])->name('coach_csv');


        //attendance
        Route::get('/add-attendance', [AttendanceController::class, 'addAttendance'])->name('add-attendance');
        Route::post('/insert-player-attendance', [AttendanceController::class, 'insertPlayerAttendance'])->name('store_attendance');  //player
        Route::put('/player-attendance/{id}', [AttendanceController::class, 'playerUpdateAttendance'])->name('update.player');
        //filter player based on coach 
        Route::get('/filter/player-attendance', [AttendanceController::class, 'filterPlayersByAttendance'])->name('filterPlayersByAttendance'); // filter player attend
        Route::get('filter/filterAbsentPlayers', [AttendanceController::class, 'filterAbsentPlayer'])->name('filter_absent_player'); // filter player attend


        Route::post('/insert-coach-attendance', [AttendanceController::class, 'insertCoachAttendance'])->name('store_coach_attendance');  //coach
        Route::post('/update-coach-attendance/{id}', [AttendanceController::class, 'updateCoachAttendance'])->name('updateCoachAttend');  //update attend

        Route::get('/attendance-list', [AttendanceController::class, 'attendanceList'])->name('attendance-list');
        Route::get('/attendance-filter', [AttendanceController::class, 'attendanceFilter'])->name('attendanceFilter');
        Route::post('/store-attendance', [AttendanceController::class, 'storeAttendance']);


        //players
        Route::get('/add-players', [UserController::class, 'addPlayer'])->name('add-player');
        Route::get('/player-list', [UserController::class, 'playerList'])->name('player-list');
        Route::get('/dormant-player-list', [UserController::class, 'dormantPlayerList'])->name('dormant-player-list');
        Route::get('/left-player-list', [UserController::class, 'leftPlayerList'])->name('left-player-list');
        Route::post('/store-player', [UserController::class, 'storePlayer'])->name('store-player');
        Route::get('/players-profile/{id}', [UserController::class, 'playerProfile'])->name('player-profile');
        Route::post('generateQrCodeForPlayer', [UserController::class,  'generateQrCodePlayer'])->name('generateQrCodeForPlayer');
        Route::post('/update-player-profile/{id}', [UserController::class, 'updatePlayerProfile'])->name('update-player-profile');
        Route::get('/check-exist-player', [UserController::class, 'checkExistingPlayer'])->name('checkExistingPlayer');
        Route::get('/get-player-data', [UserController::class, 'getPlayerData'])->name('getPlayerData');
        Route::get('/get-coach-by-batchId', [UserController::class, 'getCoachByBatchId'])->name('getCoachByBatchId');
        // Route::get('/filterPlayerList', [UserController::class, 'filterPlayerList'])->name('filterPlayerList');


        //Coach
        Route::get('/add-coach', [UserController::class, 'addCoach'])->name('add-coach');
        Route::get('/coach-list', [UserController::class, 'coachList'])->name('coach-list');
        Route::post('/store-coach', [UserController::class, 'storeCoach'])->name('storeCoach');
        Route::get('/coach-profile/{id}', [UserController::class, 'coachProfile'])->name('coach-profile');
        Route::post('/update-coach-profile/{id}', [UserController::class, 'updateCoachProfile'])->name('update-coach-profile');
        Route::get('/get-coach-name', [UserController::class, 'getCoachName'])->name('getCoachName');
        Route::get('/check-exist-coach', [UserController::class, 'checkExistingCoach'])->name('checkExistingCoach');





        //users 
        Route::get('/add-user', [UserController::class, 'addAdminUser'])->name('add-user');
        Route::get('/user-list', [UserController::class, 'userAdminList'])->name('user-list');
        Route::post('/add-user', [UserController::class, 'addAdminUserData'])->name('add-user-data');
        Route::get('/edit-user/{id}', [UserController::class, 'editAdminUser'])->name('edit-user-data');
        Route::put('/update-user/{id}', [UserController::class, 'updateAdminUser'])->name('update-user-data');
        Route::delete('/delete-user/{id}', [UserController::class, 'deleteAdminUser'])->name('delete-user-data');
        //fee
        Route::get('/fee-validity', [UserController::class, 'feeValidityList'])->name('fee-validity');
        Route::post('/store-fee', [UserController::class, 'storeFee'])->name('store-fee');


        //assesment
        Route::post('/store-assesment', [UserController::class, 'storeAssesment'])->name('store-assesment');


        Route::get('/editAssessment/{id}', [UserController::class, 'editAssessment']);
        Route::post('/updateAssessment/{id}', [UserController::class, 'updateAssessment'])->name('update-assessment');

        //batches
        Route::get('/add-batches', [UserController::class, 'addBatches'])->name('add-batch');
        Route::get('/batch-list', [UserController::class, 'batchList'])->name('batch-list');
        Route::post('/store-batch', [UserController::class, 'storeBatch']);
        Route::put('/batches/{id}', [UserController::class, 'update'])->name('batches.update');

        Route::get('/subCoaches/{batchName}', [UserController::class, 'getSubCoaches'])->name('sub-batch');

        //batch log
        Route::post('/batch-log/store', [UserController::class, 'store'])->name('batch-log.store');
        Route::post('/update-player-batch', [UserController::class, 'updatePlayerBatch'])->name('update.batch');

        //Update Coach & Player Attendance
        Route::put('/coach-attendance/{id}', [UserController::class, 'coachUpdateAttendance'])->name('update.coach');



        //document
        Route::post('/store-document', [UserController::class, 'storeDocument'])->name('store-document');
        Route::get('/viewDocumentImage/{id}', [UserController::class, 'viewDocumentImage'])->name('view-document-image');
        Route::post('/deleteDocument/{id}', [UserController::class, 'deleteDocument'])->name('delete-document');

        //user profile
        Route::get('/user-profile', [UserController::class, 'userProfile'])->name('userProfile');
        Route::post('/update-user-profile', [UserController::class, 'updateUserProfile'])->name('updateUserProfile');
    });

    //-------------------------------------------------------- SUPER ADMIN SECTION -----------------------------------------------------------

    Route::middleware(['role:superAdmin'])->group(function () {


        //super admin dashboard
        Route::get('/super-admin/dashboard', [SuperAdminController::class, 'index'])->name('super-admin');
        Route::get('/suer-admin/search-dept/{value}',  [SuperAdminController::class, 'index'])->name('searchDept');


        //department
        Route::get('/super-admin/add-department', [DepartmentController::class, 'addDepartment'])->name('add-department');
        Route::post('/suer-admin/store-department', [DepartmentController::class, 'storeDepartment'])->name('store-dept');

        // Route::get('/suer-admin/search-dept/{value}',  [DepartmentController::class, 'searchDept'])->name('searchDept');



        Route::get('/super-admin/department-list', [DepartmentController::class, 'departmentList'])->name('department-list');
        Route::get('/super-admin/edit-department/{id}', [DepartmentController::class, 'editDepartment'])->name('edit_dept');
        Route::post('/super-admin/update-department/{id}', [DepartmentController::class, 'updateDepartment'])->name('update-dept');
        Route::post('/super-admin/destroy-department/{id}', [DepartmentController::class, 'deleteDept'])->name('delete-dept');

        //all player list
        Route::get('/super-admin/all-player-list', [SuperAdminController::class, 'allPlayerList'])->name('allPlayerList');
        Route::get('/super-admin/players-profile/{id}', [SuperAdminController::class, 'playerProfileShow'])->name('player-profile-show');

        //all coach list
        Route::get('/super-admin/all-coach-list', [SuperAdminController::class, 'allCoachList'])->name('allCoachList');
        Route::get('/super-admin/coaches-profile/{id}/{dept}', [SuperAdminController::class, 'coachProfileShow'])->name('coach-profile-show');

        //users
        Route::get('/super-admin/add-user', [SuperAdminController::class, 'addUser'])->name('addUser');
        Route::get('/super-admin/user-list', [SuperAdminController::class, 'userList'])->name('userList');
        Route::post('/super-admin/add-user', [SuperAdminController::class, 'addUserData'])->name('addUserData');
        Route::get('/super-admin/edit-user/{id}', [SuperAdminController::class, 'editUser'])->name('editUser');
        Route::put('/super-admin/update-user/{id}', [SuperAdminController::class, 'updateUser'])->name('updateUser');
        Route::delete('/super-admin/delete-user/{id}', [SuperAdminController::class, 'deleteUser'])->name('deleteUser');

        //Access Levels
        Route::get('/super-admin/access-levels', [SuperAdminController::class, 'accessLevels'])->name('accessLevels');
        Route::get('/super-admin/edit-access-levels/{id}', [SuperAdminController::class, 'editAccessLevels'])->name('editAccessLevels');
        Route::post('/super-admin/update-access-levels/{id}', [SuperAdminController::class, 'updateAccessLevels'])->name('updateAccessLevels');
    });



    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });
});

Route::get('/error', function () {
    abort(500);
});





Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
