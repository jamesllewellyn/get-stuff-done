<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\User;
use Illuminate\Support\Facades\Storage;
use Auth;
use Intervention\Image\Facades\Image;
class UserController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }
    /**
     * Store new user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
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
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project) {
        /** If project cant be found return error */
        if(!$project){
            return response()->json(['success' => false, 'message' => 'The requested project could not be found']);
        }
        /** validate the request data */
        $this->validate(Request(),$project->validation, $project->messages);
        /** update record */
        $project->name = $request->get('name');
        $project->save();
        /** return success and updated project */
        return response()->json(['success' => true, 'message' => 'project has been updated', 'project' => $project]);
    }

    /**
     * Delete user
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        /** If user cant be found return error */
        if(!$user){
            return response()->json(['success' => false, 'message' => 'The requested user could not be found']);
        }
        /** delete user */
        $user->delete();
        /** return success message */
        return response()->json(['success' => true, 'message' => 'User '.$user->name.' has been successfully deleted']);
    }

    /**
     * Get users projects
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function projects(User $user) {
        /** If user cant be found return error */
        if(!$user){
            return response()->json(['success' => false, 'message' => 'The requested user could not be found']);
        }
        /** return success message */
        return response()->json($user->projects()->with('sections', 'sections.tasks')->get());
    }
}
