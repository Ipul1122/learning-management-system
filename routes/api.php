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
