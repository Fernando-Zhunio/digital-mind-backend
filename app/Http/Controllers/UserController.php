<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function index()
    {
        $pageSize = request('pageSize', 15);
        $users = User::with('department', 'charge')->paginate($pageSize);
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
