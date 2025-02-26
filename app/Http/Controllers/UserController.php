<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'admin') {
            $users = User::all();
        } else {
            $users = User::where('id', '=', $currentUser->id)->get();
        }

        return response()->json($users, 200);
    }

    // new user
    public function create(Request $request) {
        $currentUser = Auth::user();

        if ($currentUser->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $createUserWithValidate = Validator::make($request->all(), [
           'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase(),
            ],
            'role' => 'in:admin,user'
        ]);

        if ($createUserWithValidate->fails()) {
            return response()->json($createUserWithValidate->errors(), 422);
        }

        $saveUser = $createUserWithValidate->validated();

        //hash
        $saveUser['password'] = Hash::make($saveUser['password']);

        User::insert($saveUser);

        return response()->json($saveUser, 201);
    }

    public function show($id)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $findUser = User::findOrFail($id);

        return response()->json($findUser);
    }

    public function update($id, Request $request) {
        $currentUser = Auth::user();
        $updateUser = User::findOrFail($id);

        if ($currentUser->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $createUserWithValidate = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'unique:users,email,' . $updateUser->id],
            'password' => [
                'sometimes',
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase(),
            ],
            'role' => ['sometimes', 'in:admin,user'],
        ]);

        if ($createUserWithValidate->fails()) {
            return response()->json($createUserWithValidate->errors(), 422);
        }

        $data = $createUserWithValidate->validated();

        // hash
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $updateUser->update($data);

        return response()->json($updateUser);
    }

    public function delete($id){
        $currentUser = Auth::user();

        $deleteUser = User::findOrFail($id);

        if ($currentUser->role !== 'admin' && $currentUser->id !== $deleteUser->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $deleteUser->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

}
