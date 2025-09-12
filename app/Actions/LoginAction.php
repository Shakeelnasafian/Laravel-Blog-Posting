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
