<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\NotificationResource;
use App\Models\Student;

class NotificationController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', auth()->user()->id)->first();

        $notifications = $student->notifications()->latest()->get();

        return $this->successResponse('All notification retrieved successfully', NotificationResource::collection($notifications));
    }

    public function unread()
    {
        $student = Student::where('user_id', auth()->user()->id)->first();

        $notifications = $student->unreadNotifications()->latest()->get();

        return $this->successResponse('Unread notification retrieved successfully', NotificationResource::collection($notifications));
    }

    public function read()
    {
        $student = Student::where('user_id', auth()->user()->id)->first();

        $notifications = $student->readNotifications()->orderBy('read_at', 'desc')->get();

        return $this->successResponse('Read notification retrieved successfully', NotificationResource::collection($notifications));
    }

    public function show($id)
    {
        $student = Student::where('user_id', auth()->user()->id)->first();

        $notification = $student->unreadNotifications()->whereId($id)->first();
        $notification->update(['read_at' => now()]);

        return $this->successResponse('Notification has been read', new NotificationResource($notification));
    }

    public function markAllAsRead()
    {
        $student = Student::where('user_id', auth()->user()->id)->first();

        $student->unreadNotifications()->update(['read_at' => now()]);

        return $this->acceptedResponse('Marked all as read');
    }
}
