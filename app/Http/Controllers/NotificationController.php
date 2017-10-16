<?php

namespace App\Http\Controllers\Auth;

use Auth;

class NotificationController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * Mark Notification as read
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($id){
        /** get notification from id */
        $notification = auth()->user()->notifications()->findOrFail($id);
        /** authorize user can access notification */
        $this->authorize('access-notification', [ $notification]);
        /** mark notification as read*/
        $notification->markAsRead();
        /** return success and remaining unread notifications */
        return response()->json(['success' => true, 'message' => 'notification marked as read', 'notifications' => auth()->user()->unreadNotifications]);
    }
}
