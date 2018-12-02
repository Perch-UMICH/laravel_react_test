<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\LoginType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Lcobucci\JWT\Parser;
use App\Controllers\Auth\IdpGrant;

class UserController extends Controller
{

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
     * Login api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'email' => 'required|email',
//            'password' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json(['errors'=>$validator->errors()]);
//        }

        $input = $request->all();
        $user = User::where('email', $input['email'])->first();

        if($user == null) {
            return $this->outputJSON(null,"Invalid Email Address",404);
        } elseif (Auth::guard('web')->attempt(['email' => $input['email'], 'password' => $input['password']], false, false)) {
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
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        $input = $request->all();
        if ($input['password'] != $input['password_confirmation']) {
            return $this->outputJSON(null,"Passwords do not match", 404);
        }
        if (User::where('email', $input['email'])->first() != null) {
            return $this->outputJSON(null,"Email already taken", 404);
        }
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $token = $user->createToken('token')->accessToken;

        Auth::attempt(['email' => request('email'), 'password' => request('password')]);

        return $this->outputJSON($token,"Successfully Registered");
    }

    // WARNING: This function assumes the idp id has already been verified!!!
    // Returns string on error, User on success
    public function registerIdp(array $data) {
        $idp = $data['idp'];
        $idp_id = $data['idp_id'];
        $email = $data['email'];
        $name = $data['name'];

        if(User::where('email', $email)->first() != null) {
            // TODO: Temporary, currently deletes
            User::where('email', $email)->delete();
            LoginType::where(['login_type' => $idp, 'login_id' => $idp_id])->delete();
            //throw new \Exception("Registration failed: Email already taken");
        }
        if(LoginType::where(['login_type' => $idp, 'login_id' => $idp_id])->first() != null) {
            throw new \Exception("Registration failed: Idp ID already registered");
        }

        // If either the user creation or the login info fails,
        // process should fail and both entries should be deleted (if they exist)
        // Register new user
        try {
            $user = User::create($data);
        } catch(\Exception $e) {
            throw new \Exception("User creation failed.");
        }
        if(is_null($user)) {
            throw new \Exception("User creation failed.");
        }

        $login_type_data = [
            'user_id' => $user->id,
            'login_type' => $idp,
            'login_id' => $idp_id,
            'email' => $email,
            'name' => $name
        ];
        // Add the login info
        try {
            $loginType = LoginType::create($login_type_data);
        } catch (\Exception $e) {
            $user->delete;
            throw new \Exception("LoginType creation failed.");
        }
        if(is_null($loginType)) {
            $user->delete;
            throw new \Exception("LoginType creation failed.");
        }

        return $user;
    }

    public function update(Request $request, User $user) {
        $input = $request->all();
        $input = array_filter($input);

        // Password update
        if ($request->has('password'))
            $input['password'] = bcrypt($input['password']);

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

        if (!$student) {
            return $this->outputJSON(null,"Error: " . $user->email . " does not have a student profile");
        }

        $s = $student->toArray();
        $s['skills'] = $student->skills;
        $s['tags'] = $student->tags;
        $s['work_experiences'] = $student->work_experiences;
        $s['edu_experiences'] = $student->edu_experiences()->with('classes','majors','university')->get();
        $s['position_list'] = $student->position_list()->with('departments','skills','tags','lab')->get();
        return $this->outputJSON($s,"Retrieved student profile of user " . $user->email);
    }

    public function get_faculty_profile(User $user) {
        if ($user == null) {
            return $this->outputJSON(null,"Error: invalid user_id");
        }
        $faculty = $user->faculty;
        if ($faculty != null) {
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
        return $this->outputJSON($labs,"User's labs retrieved.");

    }

    public function get_user_from_credentials(string $provider, string $username, string $password = null) {
        if($provider = 'password') {
            if(is_null($password)) {
                return null;
            }
            // Search for username and verify password
            $user = User::where(['provider' => 1, 'username' => username, 'password' => bcrypt(password)])::first();
            if(is_null($user)) {
                return null;
            }
            if($user->password === bcrypt($password)) {
                // Authenticated!
                return $user;
            }
        } else {
            // Authenticated through 3rd party idp
            // $providerId = LoginMethod::getId($provider);
            if(!is_null($providerId)) {
                $user = User::where(['provider_id' => $providerId, 'username' => $username])::first();
                if(!is_null($user)) {
                    return $user;
                }
            }
        }
        return null;
    }

    // Events
    public function get_events(User $user) {
        return $this->outputJSON($events, "User's events retrieved.");
        $count = 0;
        $events = [];
        foreach ($user->events as $event) {
            $invitees = $event->pivot->user;
            $events[$count] = ['event' => $event, 'invitees' => $invitees];
            $count++;
        }
        return $this->outputJSON($events, "User's events retrieved.");
    }

    public function create_event(User $user, string $start, string $end) {
        $datetime_start = new \DateTime($start);
        $datetime_end = new \DateTime($end);
        $event = Event::create([
            'owner_user_id' => $user->id,
            'start' => $datetime_start,
            'end' => $datetime_end,
        ]);
        return $this->outputJSON($user, 'Event created');
    }
}