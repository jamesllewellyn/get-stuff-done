<?php

namespace App\Http\Controllers;

use App\User;

class UserNotificationController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * Get users unread notifications
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** return unread notifications */
        return response()->json($user->unreadNotifications);
    }

    /**
     * Mark all user notifications as read
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** mark all user notifications as read **/
        $user->unreadNotifications->markAsRead();
        /** return success */
        return response()->json(['success' => 'true', 'message' => 'All notifications marked as read']);
    }
}
