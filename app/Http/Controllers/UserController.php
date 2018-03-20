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
        return $this->outputJSON($users, "Users retrieved");
    }


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $input = $request->all();
        $user = User::where('email', $input['email'])->first();

        if($user == null) {
            return $this->outputJSON(null,"Incorrect Email Address",404);
        } elseif (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            $token['token'] = $user->createToken('token')->accessToken;
            return $this->outputJSON([$user, $token],"Logged In Successfully");
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

    public function update(Request $request, User $user) {
        $user->update($request->all());
        $user->save();
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

    public function isStudent(Request $request) {
        $input = $request->all();
        $user_id = $input['user_id'];
        $user = User::find($user_id);
        $isStudent = $user->is_student;
        return $this->outputJSON($isStudent,"Checked if student");
    }
}