<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(20);

        return view('backend.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($request->has('redirect') && ($notification->data['link'] ?? null)) {
            return redirect($notification->data['link']);
        }

        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
