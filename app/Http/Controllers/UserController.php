<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\PendingUser;
use App\Team;
use App\UserTeam;
use Illuminate\Http\Request;
use App\Project;
use App\User;
use Hash;
use Illuminate\Support\Facades\Storage;
use Auth;
use Intervention\Image\Facades\Image;
use App\Notifications\welcome;
class UserController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api', ['except' => ['invite']]);
    }

    /**
     * Store user from email invite
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function invite(Request $request){
        $user = new User();
        /** validate the request data */
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'handle' => 'required',
            'password' => 'required|min:7'
        ],$user->messages);
        /** if validation errors return customer to login page with error */
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        /** get pending user session data */
        $pending = $request->session()->get('pending');
        /** delete session */
        $request->session()->forget('pending');
        if(!$pending){
            return redirect()->route('home')->with('inviteError','Sorry we couldn\'t find your invitation');
        }
        /** create new user */
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $pending['email'];
        $user->handle = $request->handle;
        $user->password = Hash::make($request->password);
        $user->save();
        /** log user in */
        Auth::login($user, true);
        /** add user to team */
        UserTeam::create(['user_id' => $user->id, 'team_id' => $pending['team_id']]);
        /** remove pending user record */
        PendingUser::where('id', $pending['id'])->delete();
        /** send user welcome email**/
        $user->notify(new welcome());
        /** redirect to app home page */
        return redirect()->route('home');
    }
    /**
     * Set users avatar
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function avatar(Request $request) {
        /*** Validate request */
        $this->validate(Request(),['base64' => 'required'], ['base64.required' => 'Avatar image not found']);
        $path = 'public/users/'.Auth::User()->id;
        /** get image */
        $base64 = $request->get('base64');
        /** convert to file */
        $base64 = explode( ',', $base64 );
        $file = base64_decode($base64[1]);
        /** create directory if is does'nt exist */
        if(!Storage::exists($path) ){
            Storage::makeDirectory($path);
        }
        /** delete current avatar */
        if(Storage::exists($path.'/avatar.png') ){
            Storage::delete($path.'/avatar.png');
        }
        /** store image */
        Storage::put($path.'/avatar.png', $file);
        /** get $image and resize */
        $image = Storage::get($path.'/avatar.png');
        $image = Image::make($image)->resize(100,100)->encode('png');
        /** re-save image */
        Storage::put($path.'/avatar.png', $image);
        return response()->json(['success' => true, 'message' => 'The avatar has been uploaded']);
    }

    /**
     * get logged in user data
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        /** return current logged in user */
        return response()->json($request->user());
    }

    /**
     * Update project
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        /** todo: only allow user to update themselves */
       /** validate the request data */
        $this->validate(Request(),$user->updateValidation, $user->messages);
        /** update record */
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->handle = $request->handle;
        $user->save();
        /** return success and updated project */
        return response()->json(['success' => true, 'message' => 'user has been updated', 'user' => $user]);
    }
    /**
     * Update current team
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function updateTeam(Request $request, User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** validate the request data */
        $this->validate(Request(),['teamId' => 'required']);
        /** get team */
        $team = Team::find($request->teamId);
        /** authorize user belongs to team */
        $this->authorize('access-team',$team);
        /** update record */
        $user->current_team_id = $team->id;
        $user->save();
        /** return success and updated project */
        return response()->json(['success' => true, 'message' => 'Team has been switched', 'user' => $user]);
    }

    /**
     * Delete user
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        /** delete user */
        $user->delete();
        /** return success message */
        return response()->json(['success' => true, 'message' => 'User '.$user->getFullName().' has been successfully deleted']);
    }

    /**
     * Get users teams
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function teams(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** return success message */
        return response()->json($user->teams()->with(['projects','users'])->get());
    }

    /**
     * all users tasks
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function tasks(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** get tasks user is currently working on */
        $tasks = $user->tasks()->with('section', 'section.project', 'section.project.team')->get();
        /** return success message */
        return response()->json($tasks);
    }

    /**
     * Get tasks user is currently working on
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function workingOnIt(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** get tasks flagged as working on it */
        $tasks = $user->workingOnIt()->with('section', 'section.project', 'section.project.team')->get();
        /** return success message */
        return response()->json($tasks);
    }

    /**
     * Get tasks that are over due
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function overDue(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** get users over due tasks */
        $tasks = $user->overDue()->with('section', 'section.project', 'section.project.team')->get();
        /** return over due tasks */
        return response()->json($tasks);
    }

    /**
     * Get users unread notifications
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function notifications(User $user) {
        /** return unread notifications */
        return response()->json($user->unreadNotifications);
    }

    /**
     * Mark all user notifications as read
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function clearNotifications(User $user) {
        /** mark all user notifications as read **/
        $user->unreadNotifications->markAsRead();
        /** return success */
        return response()->json(['success' => 'true', 'message' => 'All notifications marked as read']);
    }

}
