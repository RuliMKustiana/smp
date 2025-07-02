<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationBell extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        $unreadCount = 0;
        $notifications = collect();
        
        if (Auth::check()) {
            $unreadCount = Auth::user()->unreadNotifications->count();
            $notifications = Auth::user()->unreadNotifications->take(5);
        }

        return view('components.notification-bell', [
            'unreadCount' => $unreadCount,
            'notifications' => $notifications
        ]);
    }
}
