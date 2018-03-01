<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Lcobucci\JWT\Parser;

class UserController extends Controller
{


    public $successStatus = 200;

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request['email'])->first();

        if($user == null) {
            return $this->outputJSON(null,"Incorrect Email Address",404);
        } elseif (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $user = Auth::user();
            $token['token'] = $user->createToken('token')->accessToken;
            return $this->outputJSON($token,"Logged In Successfully");
        } else {
            return $this->outputJSON(null,"Incorrect Password",404);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->token()->revoke();
        $user->token()->delete();
        return $this->outputJSON(null,"Successfully Logged Out");
    }


    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
//        $validator = $request->validate([
//            'name' => 'required',
//            'email' => 'required|email',
//            'password' => 'required',
//            'password_confirmation' => 'required|same:password',
//        ]);

        $input = $request->all();
        if ($input['password'] != $input['password_confirmation']) {
            return $this->outputJSON(null,"Passwords do not match", 404);
        }
        if (User::where('name', $input['name'])->first() != null) {
            return $this->outputJSON(null,"Name already taken", 404);
        }
        if (User::where('email', $input['email'])->first() != null) {
            return $this->outputJSON(null,"Email already taken", 404);
        }
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token['token'] = $user->createToken('token')->accessToken;

        Auth::attempt(['email' => request('email'), 'password' => request('password')]);

        return $this->outputJSON($token,"Successfully Registered");
    }


    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request)
    {
        $user = $request->user();
        return $this->outputJSON($user,"Found user details");
    }
}