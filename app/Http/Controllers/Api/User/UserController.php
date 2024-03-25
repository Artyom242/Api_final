<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\RegRequest;
use App\Http\Resources\Api\User\LoginResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function authorization(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::query()->firstWhere('email', $data['email']);

        if (isset($user)) {
            if ($user->password == $data['password']) {
                $user->update([
                    'api_token' => Str::random(100)
                ]);

                return response()->json(new LoginResource($user));
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Login failed',
        ]);
    }

    public function registration(RegRequest $request)
    {
        $data = $request->validated();
        $data['api_token'] = Str::random(100);
        $user = User::query()->create($data);
        return response()->json(new LoginResource($user));
    }

    public function logout(Request $request)
    {
        $data = $request->bearerToken();
        $user = User::query()->firstWhere('api_token', $data);
        $user->update(['api_token' => '']);
        return response()->json([
            'success' => true,
            'message' => 'Logout',
        ]);
    }
}
