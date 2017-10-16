<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use App\Invitation;
use App\Team;
use App\User;
use Auth;

class InvitationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['show', 'createUserFromInvitation']]);
    }

    /**
     * Add User to team.
     * @param  \Illuminate\Http\Request $request
     * @param  Team $team
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Team $team)
    {
        /** authorize user has access to team */
        $this->authorize('access-team', $team);
        /** validate request data */
        $invitation = new Invitation();
        $this->validate(Request(),$invitation->validation, $invitation->messages);
        /** check user is not already in team */
        if($team->isInTeam($request->email)){
            return response()->json(['success' => false, 'message' => "{$request->email} is already a member of team {$team->name}"]);
        }
        /** invite user to team */
        $team->inviteUser($request->email);
        /** return successful response **/
        return response()->json(['success' => true, 'message' => "{$request->email} has been invited to team {$team->name}"]);
    }

    /**
     * Show user invitation form
     *
     * @param \Illuminate\Http\Request $request
     * @return View
     */
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'invitation_code' => 'required'
        ]);
        /** if validation fails show invite page with error  */
        if($validator->fails()){
            return  view('invitation.show', ['inviteError' => [
                'title' => 'We cant find your invitation',
                'message' => 'Sorry we were unable to find you invitation to this team'
                ]
            ]);
        }
        /** decode the users invitation code */
        $invitationData = $this->decodeInvitation($request->get('invitation_code'));
        /** if decodeInvitation returned false show invite page with error */
        if(!$invitationData){
            return  view('invitation.show', ['inviteError' => [
                'title' => 'We cant find your invitation',
                'message' => 'Sorry we were unable to find you invitation to this team'
                ]
            ]);
        }
        /** get invitation using email address and token */
        $invitation = Invitation::findByEmailAndToken($invitationData['email'], $invitationData['token']);
        /** if no invitation show invite page with error  */
        if(!$invitation){
            return  view('invitation.show', ['inviteError' => [
                'title' => 'We cant find your invitation',
                'message' => 'Sorry we were unable to find you invitation to this team'
                ]
            ]);
        }
        /** check if invitee is already an application user */
        if($invitation->user_id){
            $user = User::Find($invitation->user_id);
            $user->addToTeam($invitation->team_id);
            $invitation->delete();
            Auth::login($user, true);
            /** redirect to app home page */
            return redirect()->route('home');
        }
        /** take use to invited user form */
        return view('invitation.show', ['invitation' => $invitation]);
    }

    /**
     * Store user from email invite
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function createUserFromInvitation(Request $request){
        $user = new User();
        /** validate the request data */
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'handle' => 'required',
            'password' => 'required|min:7',
            'invitation_id' => 'required',
            'email' => 'required'
        ],$user->messages);
        /** if validation errors return customer to login page with error */
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        /** get invitation */
        $invitation = Invitation::find($request->invitation_id);
        if(!$invitation){
            return view('invitation.show', ['inviteError' => [
                'title' => 'We cant find your invitation',
                'message' => 'Sorry we were unable to find you invitation to this team'
                ]
            ]);
        }
        /** create user from invitation */
        $user = User::createFromInvitation($invitation, $request->only(['first_name', 'last_name', 'handle', 'password']));
        /** log user in */
        Auth::login($user, true);
        /** redirect to app home page */
        return redirect()->route('home');
    }


    private function decodeInvitation($invitationCode)
    {
        /** invitation array */
        $invitationData = [];
        /** decode invitation code into email and token array */
        parse_str(base64_decode($invitationCode), $invitationData);
        /** validate invitation data */
        if(!isset($invitationData['email']) || !isset($invitationData['token'])){
            return false;
        }
        /** return decoded invitation data */
        return $invitationData;
    }

}
