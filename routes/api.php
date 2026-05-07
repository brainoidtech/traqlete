<?php

use App\Actions\SamplePermissionApi;
use App\Actions\SampleRoleApi;
use App\Actions\SampleUserApi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\v1_01\ApiController;
use App\Http\Controllers\v1_01\PasswordResetController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('refresh-token', [LoginController::class, 'refreshToken']);
    Route::post('logout', [LoginController::class, 'logout']);
});

// Protected routes (JWT auth required)
Route::prefix('v1')->middleware('jwt.auth')->group(function () {

    Route::post('player-coach', [ApiController::class, 'loadPlayer']);
    Route::post('department', [ApiController::class, 'loadDepartment']);
    Route::post('attendance', [ApiController::class, 'markAttendance']);
    Route::post('player', [ApiController::class, 'loadPlayerById']);
    Route::post('get-attendance', [ApiController::class, 'loadAttendance']);
    Route::post('report', [ApiController::class, 'report']);

    // Coach attendance
    Route::get('getCoach/{id}', [ApiController::class, 'loadCoach']);
    Route::post('coachById', [ApiController::class, 'coachById']);
    Route::post('playerById', [ApiController::class, 'playerById']);
    Route::post('coach-attendance', [ApiController::class, 'loadCoachAttendance']);

    //Start Batch Logs
    Route::post('/batchLogs', [ApiController::class, 'getAllBatchLogs']);
    Route::post('/addBatchLog', [ApiController::class, 'addBatchLog']);
    Route::post('/batchLogs/{id}', [ApiController::class, 'getBatchLogs']);
    Route::put('/batchLogs', [ApiController::class, 'updateBatchLog']);
    Route::get('/getBatchesCoaches', [ApiController::class, 'getBatchesCoaches']);
    //End Batch Logs

    //Start Fees Validity of Player Deatils
    Route::post('/playerByFeesDeatails', [ApiController::class, 'playerByFeesDeatails']);
    Route::post('/addPlayerFee', [ApiController::class, 'addPlayerFee']);
    //End Fees Validity of Player Deatils

    //Start Athenticate User Profile
    Route::get('user-profile', [ApiController::class, 'getAuthenticateUserProfile']);
    Route::post('updateAuthenticateUserProfile', [ApiController::class, 'updateAuthenticateUserProfile']);
    //End Athenticate User Profile

    //filter api 
    Route::get('filter', [ApiController::class, 'loadfilters']);

    //Coach Geo Fencing Attendance Start
    Route::post('add-coach-attendance-geofancing', [ApiController::class, 'addUpdateCoachAttendance']);
    //Coach Attendance End

    //Start Api Player Assesment.
    Route::post('get-assesments', [ApiController::class, 'getAssesments']);
    Route::post('add-assesment', [ApiController::class, 'addAssesment']);
    Route::post('update-assesment', [ApiController::class, 'updateAssesment']);
    //End Api Player Assesment.

    //Get Geofrncing Deparment Wise
    Route::get('get-latitude-longitude', [ApiController::class, 'getLatitudeLongitude']);

    Route::get('access-level', [ApiController::class, 'loadAccessLevel']);

    //player document 
    Route::post('get-player-documents', [ApiController::class, 'getPlayerDocument']);
    Route::post('add-player-documents', [ApiController::class, 'addPlayerDocument']);

    //coach batch log 
    Route::post('get-coach-batch-log', [ApiController::class, 'getCoachBatchLog']);

    //Coach Attendance Start
    Route::post('add-coach-attendance', [ApiController::class, 'updateCoachAttendance']);
});

Route::prefix('v1')->group(function () {
    
    //Reset Password Api
    Route::post('forgot-password', [PasswordResetController::class, 'forgotPasswordApi']);
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);

});
