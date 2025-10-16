<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

Route::get('/database-info', function () {
    return response()->json([
        'status' => 'Database is working!',
        'tables' => DB::select("SELECT name FROM sqlite_master WHERE type='table'"),
        'total_users' => User::count(),
        'migrations_run' => DB::table('migrations')->get()
    ]);
});

Route::get('/create-test-user', function () {
    $user = User::create([
        'name' => 'Test User ' . time(),
        'email' => 'test' . time() . '@example.com',
        'password' => bcrypt('password123')
    ]);
    
    return response()->json([
        'message' => 'Test user created successfully!',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]
    ]);
});

Route::get('/users', function () {
    $users = User::all();
    
    return response()->json([
        'users' => $users
    ]);
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

Route::get('/database-info', function () {
    return response()->json([
        'status' => 'Database is working!',
        'tables' => DB::select("SELECT name FROM sqlite_master WHERE type='table'"),
        'total_users' => User::count(),
        'migrations_run' => DB::table('migrations')->get()
    ]);
});

Route::get('/create-test-user', function () {
    $user = User::create([
        'name' => 'Test User ' . time(),
        'email' => 'test' . time() . '@example.com',
        'password' => bcrypt('password123')
    ]);
    
    return response()->json([
        'message' => 'Test user created successfully!',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]
    ]);
});

Route::get('/users', function () {
    $users = User::all();
    
    return response()->json([
        'users' => $users
    ]);
});