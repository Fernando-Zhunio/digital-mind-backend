<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function index()
    {
        // $pageSize = request('pageSize', 15);
        $builder = User::with('department', 'charge');
        
        if(request()->has('department') && !empty(request()->get('department'))){
            $builder = $builder->where('department_id', request('department'));
        }
        if (request()->has('charge') && !empty(request()->get('charge'))){
            $builder = $builder->orWhere('charge_id', request('charge'));
        }
        $users = $builder->paginate(10000000000000000000000000);
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function create()
    {
        $departments = Department::where('status',1)->get();
        $charges = Charge::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'departments' => $departments,
                'charges' => $charges
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'second_name' => 'required',
            'surname' => 'required',
            'second_surname' => 'required',
            'department_id' => 'required|exists:departments,id',
            'charge_id' => 'required|exists:charges,id',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create(array_merge($request->all(), ['password' => Str::password()])) ;
        $user->load('department', 'charge');
        return response()->json([
            'success'=> true,
            'data' => $user
            ]);
    }

    public function update(Request $request, User $user) {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'second_name' => 'required',
            'surname' => 'required',
            'second_surname' => 'required',
            'department_id' => 'required|exists:departments,id',
            'charge_id' => 'required|exists:charges,id',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->update($request->all());
        $user->load('department', 'charge');
        return response()->json([
            'success'=> true,
            'data' => $user
            ]);
    }

    public function destroy(User $user) {
        $user->delete();
        return response()->json([
            'success'=> true
            ]);
    }
}
