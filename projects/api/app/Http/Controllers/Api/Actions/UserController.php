<?php

namespace App\Http\Controllers\Api\Actions;

use Alvin0\RedisModel\Exceptions\KeyExistException;
use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    /**
     * @throws KeyExistException
     */
    public function registerByIp(Request $request): JsonResponse
    {
        $ip = $request->ip();

        /** @var User $existingIp */
        $existingIp = User::where('ip', $ip)->exists();

        if ($existingIp) {
            return $this->success([], message: 'This IP already exists.');
        }

        /** @var User $user */
        $user = User::create([
            'hash' => Hash::make("$ip." . microtime()),
            'ip' => $ip,
        ]);

        return $this->success(data: [
            'user' => $user
        ], message: "User register successfully");
    }
}
