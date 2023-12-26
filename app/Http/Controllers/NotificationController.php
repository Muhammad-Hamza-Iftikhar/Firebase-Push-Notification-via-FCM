<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function saveToken(Request $request)
    {
        // dd($request);
        $userId = Auth::guard('web')->id();
        if ($userId) {
            DB::table('users')->where('id', $userId)->update(['device_token' => $request->token]);
        }
        return response()->json(['Token successfully stored.']);
    }

    public function sendNotification(Request $request)
    {
        $validatedData = $request->validate(
            [
                'title' => 'required',
                'body' => 'required',
                'user_id' => 'required',
            ]
        );

        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $serverKey = 'AAAAAdtzJ9k:APA91bF8vttXUGg11WA7Z6HeUoejy0_SagI6mV4dPW1NUXNXc46V8ZDt7UpxXaAMIY8OzUJDb7wWfiQVH8r0y4HqAAakSq8iVzcY-s_qBYGl3TKnyExwmen-sEtxHVrJLd-g5thaMJiZ';
        $url = 'https://fcm.googleapis.com/fcm/send';
        $notification = [
            'registration_ids' => $FcmToken,
            'notification' => [
                'title' => $request->title,
                'body' => $request->body,
            ],
            'data' => [
                'data' => $request->data,
                'user_id' => $request->user_id,
            ],
        ];
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post($url, $notification);
        $responseBody = $response->json();
        if ($response->successful()) {
            if (isset($responseBody['success']) && $responseBody['success'] === 1) {
                $notification = new Notification();
                $notification->title = $validatedData['title'];
                $notification->body = $validatedData['body'];
                $notification->data = $request->data ?? null;
                $notification->user_id = $validatedData['user_id'];
                $notification->read = "0";
                $notification->save();

                return response()->json(['message' => 'Notification sent successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to send Notification'], 400);
            }
        }
        return response()->json(['error' => 'Failed to send Notification'], 500);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
