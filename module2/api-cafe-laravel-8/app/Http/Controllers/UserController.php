<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) $this->getValidationErrorResponse($validator);

        if (!($user = User::query()->where('login', $request->login)->first())) {
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Authentication failed'
                ]

            ], 401);
        }
        if (!($user->password === $request->password)) {
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Authentication failed'
                ]

            ], 401);
        }
        $abilities = [];
        if ($user->isAdmin()) $abilities[] = 'admin';
        if ($user->isCook()) $abilities[] = 'cook';
        if ($user->isWaiter()) $abilities[] = 'waiter';
        return response()->json([
            'data' => [
                'user_token' => $user->createToken('access_token', $abilities)->plainTextToken
            ]
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'data' => [
                'message' => 'logout'
            ]
        ]);
    }

    public function read() {
        return response()->json([
            'data' => UserResource::collection(User::all())
        ]);

    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'surname' => 'string',
            'patronymic' => 'string',
            'login' => 'required|string|unique:users,login',
            'password' => 'required|string',
            'photo_file' => 'file|image|mimes:jpeg,png',
            'role_id' => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) $this->getValidationErrorResponse($validator);
        $imageName = time() . '.' . $request->photo_file->extension();
        $request->photo_file->move(public_path('photos'), $imageName);
        $request->merge(['photo_file' => $imageName, 'status' => User::STATUSES['created']]);
        $data = $request->all();
        $data = array_merge($data, ['photo_file' => $imageName, 'status' => User::STATUSES['created']]);
        $user = User::query()->create($data);
        return response()->json([
            'data' => [
                'id' => $user->id,
                'status' => $user->status
            ]
        ]);
    }
}
