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

class EventController extends Controller
{

    public function index()
    {
        $events = Event::all();
        return $this->outputJSON($events, "Events retrieved");
    }

    public function show(Event $event)
    {
        return $this->outputJSON($event, "Event retrieved");
    }

//    public function update(Request $request, Event $event) {
//        $input = $request->all();
//        $input = array_filter($input);
//
//        // Password update
//        if ($request->has('password'))
//            $input['password'] = bcrypt($input['password']);
//
//        $user->update($input);
//        $user->save();
//        return $this->outputJSON($user, 'User updated');
//    }

    public function delete(Event $event) {
        $event->delete();
        return $this->outputJSON(null, 'Event deleted');
    }

    public function add_invitees(Request $request, Event $event) {

    }

    public function delete_invitees(Request $request, Event $event) {

    }
}