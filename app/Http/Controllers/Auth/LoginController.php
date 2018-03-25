<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite; // OAuth
use App\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $oauth_user = Socialite::driver('google')->stateless()->user();

        $token = $oauth_user->token;
        // $user = Socialite::driver('google')->userFromToken($token);

        echo '<pre>';
        print_r($oauth_user);
        echo '</pre>';

        $id = $oauth_user->getId();
        $name = $oauth_user->getName();
        $email = $oauth_user->getEmail();

        $user = User::where('email', $email);

        if (!$user->exists()) {
            // TODO redirect user to registration form with email?

            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->google_id = $id;
            $user->save();

        }
        else {
            // Update google id in case null
            $user->google_id = $id;
        }

        $token = $user->createToken('token')->accessToken;
        return $this->outputJSON([$user, $token],"Logged In Successfully");


        // login the user, redirect
        // comment out to see object returned by google api
//        if ($this->userLogin($oauth_user)) {
//            return redirect($this->redirectTo);
//        }
    }

    /**
     * Login the user, if user doesn't exist, create a record, then login
     */
    public function userLogin($oauth_user) {
        $id = $oauth_user->getId();
        $name = $oauth_user->getName();
        $email = $oauth_user->getEmail();

        // TODO check if user is from umich.edu domain
        // if not, return false

        $user = User::where('email', $email)->first();

        if (!$user) {


            // TODO redirect user to registration form?

            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->google_id = $id;
            $user->save();

        }
        else {
            // Update google id in case null
            $user->google_id = $id;
        }

        Auth::login($user, true);
        return true;

    }
}
