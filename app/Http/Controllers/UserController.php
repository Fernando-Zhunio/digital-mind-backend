<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\Department;
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

    public function create() {
        $departments = Department::all();
        $charges = Charge::all();

        return response()->json([
            'success' => true,
            'data' => [
               'departments' => $departments,
               'chargers' => $charges
            ]
            ]);
    }

}
