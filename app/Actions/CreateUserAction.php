<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * Action class responsible for creating a new user.
 *
 * This class encapsulates the logic required to create a user within the application.
 * It may handle validation, persistence, and any additional business logic related to user creation.
 */
class CreateUserAction
{
    public function handle(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $this->generateUsername($data['name'], $data['email']),
            'password' => Hash::make($data['password']),
        ]);
    }

    private function generateUsername(string $name, string $email): string
    {
        // slugify the name → "John Doe" → "john-doe"
        $base = Str::slug($name);

        // if no slug (non-latin characters), fallback to email prefix
        if (empty($base)) {
            $base = Str::before($email, '@');
        }

        $username = $base;
        $counter  = 1;

        // ensure uniqueness
        while (User::where('username', $username)->exists()) {
            $username = $base . '-' . $counter++;
        }

        return $username;
    }
}
