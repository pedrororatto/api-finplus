<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return NotificationResource::collection(
            $request->user()->notifications()->latest()->paginate(20)
        );
    }

    public function markAsRead(Request $request, $id)
    {
        $n = $request->user()->notifications()->findOrFail($id);
        $n->markAsRead();
        return response()->json(['status'=>'ok']);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->each->markAsRead();
        return response()->json(['status'=>'ok']);
    }
}
