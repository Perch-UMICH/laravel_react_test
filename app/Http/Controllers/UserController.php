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

    public function show(User $user)
    {
        return $this->outputJSON($user, "User retrieved");
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
            return $this->outputJSON(null,"Invalid Email Address",404);
        } elseif (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            // Create token
            $token = $user->createToken('token')->accessToken;
            // Get user type if it exists
            if ($user->is_student) {
                $user->student;
                return $this->outputJSON(['user' => $user, 'token' => $token],"Student Logged In Successfully", 200);
            }
            else if ($user->is_faculty) {
                $user->faculty;
                $user->labs;
                return $this->outputJSON(['user' => $user, 'token' => $token],"Faculty Logged In Successfully", 200);
            }
            else {
                return $this->outputJSON(['user' => $user, 'token' => $token],"Logged In Successfully. User has no type.",200);
            }
        } else {
            return $this->outputJSON(null,"Incorrect Password",404);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->token()->revoke();
        $user->token()->delete();
        $this->guard()->logout();

        $request->session()->flush();
        $request->session()->regenerate();

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
        $input = $request->all();
        $input = array_filter($input);
        $user->update($input);
        $user->save();
        return $this->outputJSON($user, 'User updated');
    }

    public function delete(User $user) {
        $user->delete();

        return $this->outputJSON(null, 'User deleted');
    }

    public function verify(Request $request)
    {
        $user = $request->user();
        return $this->outputJSON($user,"User token verified");
    }

    public function isStudent(Request $request) {
        $input = $request->all();
        $user_id = $input['user_id'];
        $user = User::find($user_id);
        $isStudent = $user->is_student;
        return $this->outputJSON($isStudent,"Checked if student");
    }

    // Profiles

    public function get_student_profile(User $user) {
        if ($user == null) {
            return $this->outputJSON(null,"Error: invalid user_id");
        }
        $student = $user->student;
        if (count($student)) {
            return $this->outputJSON($student,"Retrieved student profile of user " . $user->email);
        }
        else {
            return $this->outputJSON(null,"Error: " . $user->email . " does not have a student profile");
        }
    }

    public function get_faculty_profile(User $user) {
        if ($user == null) {
            return $this->outputJSON(null,"Error: invalid user_id");
        }
        $faculty = $user->faculty;
        if (count($faculty)) {
            return $this->outputJSON($faculty,"Retrieved faculty profile of user " . $user->email);
        }
        else {
            return $this->outputJSON(null,"Error: " . $user->email . " does not have a faculty profile");
        }
    }

    // Labs

    public function get_labs(User $user)
    {
        $count = 0;
        $labs = [];
        foreach ($user->labs as $lab) {
            $role = $lab->pivot->role;
            $labs[$count] = ['lab' => $lab, 'role' => $role];
            $count++;
        }
        return $this->outputJSON($labs,"User's labs retrieved");

    }
}