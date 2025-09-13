<?php

namespace App\Http\Controllers;

use App\Actions\LoginAction;
use App\Events\OurExampleEvent;
use App\Actions\CreateUserAction;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param  \App\Http\Requests\LoginRequest  $request  The incoming login request containing user credentials.
     * @param  \App\Actions\LoginAction  $action  The action class responsible for handling the login logic.
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request, LoginAction $action)
    {

        if ($action->handle($request)) {
            return redirect('/')->with('success', 'You have successfully logged in.');
        }

        return redirect('/')->with('failure', 'Invalid login.');
    }

    /**
     * Handle the user registration request.
     *
     * Validates the incoming registration data using the RegisterRequest,
     * and creates a new user using the CreateUserAction.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request  The validated registration request.
     * @param  \App\Actions\CreateUserAction  $action  The action responsible for creating a new user.
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request, CreateUserAction $action)
    {
        // Pass the validated data from the request to the action
        $user = $action->handle($request->validated());

        auth()->login($user);
        return redirect('/')->with('success', 'Thank you for creating an account.');
    }

    public function logout()
    {
        event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out.');
    }
}
