<?php

namespace App\Actions;

use App\Events\OurExampleEvent;
use App\Http\Requests\LoginRequest;

/**
 * Handles the user login action.
 *
 * This class is responsible for processing user login requests,
 * validating credentials, and managing authentication logic.
 */
class LoginAction
{
    /**
     * Handles the login request.
     *
     * Processes the given LoginRequest and attempts to authenticate the user.
     *
     * @param LoginRequest $request The login request containing user credentials.
     * @return bool Returns true if authentication is successful, false otherwise.
     */
    public function handle(LoginRequest $request): bool
    {
        $credentials = $request->validated();

        if (!auth()->attempt($credentials)) return false;

        $request->session()->regenerate();

        event(new OurExampleEvent([
            'username' => auth()->user()->username,
            'action' => 'login'
        ]));

        return true;
    }
}
