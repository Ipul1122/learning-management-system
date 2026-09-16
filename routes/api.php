<?php

use App\Http\Controllers\Api\V1\SuperAdmin\ActivityLogApiController;
use App\Http\Controllers\Api\V1\SuperAdmin\AdminCabangApiController;
use App\Http\Controllers\Api\V1\SuperAdmin\BranchApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Token Generation (Sanctum Login)
Route::post('/v1/auth/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'nullable|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Kredensial tidak valid.'], 401);
    }

    $token = $user->createToken($request->input('device_name', 'default'))->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
        ],
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// V1 Super Admin APIs
Route::prefix('v1/super-admin')
    ->middleware(['auth:sanctum', 'role:super-admin'])
    ->name('api.v1.super-admin.')
    ->group(function () {
        // Branches
        Route::patch('branches/{branch}/toggle-status', [BranchApiController::class, 'toggleStatus'])->name('branches.toggle-status');
        Route::apiResource('branches', BranchApiController::class);

        // Admin Cabang
        Route::patch('admins/{admin}/toggle-status', [AdminCabangApiController::class, 'toggleStatus'])->name('admins.toggle-status');
        Route::apiResource('admins', AdminCabangApiController::class);

        // Activity Logs
        Route::get('logs', [ActivityLogApiController::class, 'index'])->name('logs.index');
        Route::get('logs/{log}', [ActivityLogApiController::class, 'show'])->name('logs.show');
    });

use App\Http\Controllers\Api\V1\AdminCabang\ActivityLogApiController as CabangActivityLogApiController;
use App\Http\Controllers\Api\V1\AdminCabang\ClassApiController as CabangClassApiController;
use App\Http\Controllers\Api\V1\AdminCabang\ClassSessionApiController as CabangClassSessionApiController;
use App\Http\Controllers\Api\V1\AdminCabang\TrainerApiController as CabangTrainerApiController;

// V1 Admin Cabang APIs
Route::prefix('v1/admin-cabang')
    ->middleware(['auth:sanctum', 'role:admin-cabang'])
    ->name('api.v1.admin-cabang.')
    ->group(function () {
        // Master Trainer Cabang
        Route::patch('trainers/{trainer}/toggle-status', [CabangTrainerApiController::class, 'toggleStatus'])->name('trainers.toggle-status');
        Route::apiResource('trainers', CabangTrainerApiController::class);

        // Kelas Pelatihan
        Route::patch('classes/{class}/update-status', [CabangClassApiController::class, 'updateStatus'])->name('classes.update-status');
        Route::apiResource('classes', CabangClassApiController::class);

        // Sesi Pertemuan Kelas & Zoom
        Route::get('classes/{class}/sessions', [CabangClassSessionApiController::class, 'index'])->name('classes.sessions.index');
        Route::post('classes/{class}/sessions', [CabangClassSessionApiController::class, 'store'])->name('classes.sessions.store');
        Route::get('classes/{class}/sessions/{session}', [CabangClassSessionApiController::class, 'show'])->name('classes.sessions.show');
        Route::put('classes/{class}/sessions/{session}', [CabangClassSessionApiController::class, 'update'])->name('classes.sessions.update');
        Route::delete('classes/{class}/sessions/{session}', [CabangClassSessionApiController::class, 'destroy'])->name('classes.sessions.destroy');

        // Log Aktivitas Internal Cabang
        Route::get('logs', [CabangActivityLogApiController::class, 'index'])->name('logs.index');
        Route::get('logs/{log}', [CabangActivityLogApiController::class, 'show'])->name('logs.show');
    });

use App\Http\Controllers\Api\V1\Trainer\ClassScheduleApiController;
use App\Http\Controllers\Api\V1\Trainer\QuestionBankApiController;
use App\Http\Controllers\Api\V1\Trainer\QuizApiController;

// V1 Trainer APIs
Route::prefix('v1/trainer')
    ->middleware(['auth:sanctum', 'role:trainer'])
    ->name('api.v1.trainer.')
    ->group(function () {
        // Bank Soal Setara (PRD 3.1)
        Route::apiResource('questions', QuestionBankApiController::class);

        // Paket Kuis (PRD 3.2)
        Route::apiResource('quizzes', QuizApiController::class);

        // Kelas & Zoom Sesi (PRD 3.6)
        Route::get('classes', [ClassScheduleApiController::class, 'index'])->name('classes.index');
        Route::get('classes/{class}', [ClassScheduleApiController::class, 'show'])->name('classes.show');
        Route::patch('classes/{class}/sessions/{session}/zoom', [ClassScheduleApiController::class, 'updateZoom'])->name('classes.sessions.zoom');
    });
