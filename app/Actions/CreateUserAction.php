<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Action class responsible for creating a new user.
 *
 * This class encapsulates the logic required to create a user within the application.
 * It may handle validation, persistence, and any additional business logic related to user creation.
 */
class CreateUserAction
{
    public function handle(array $data) : User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
