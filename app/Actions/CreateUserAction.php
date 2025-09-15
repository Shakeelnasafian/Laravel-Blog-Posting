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
    /**
     * Handles the creation of a new user with the provided data.
     *
     * @param array $data The data required to create a new user.
     * @return User The newly created user instance.
     */
    public function handle(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $this->generateUsername($data['name'], $data['email']),
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Generates a unique username based on the provided name and email address.
     *
     * @param string $name  The full name of the user.
     * @param string $email The email address of the user.
     * @return string       The generated username.
     */
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
