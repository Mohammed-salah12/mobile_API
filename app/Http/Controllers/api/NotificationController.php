<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{

    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    private function verifyToken($token)
    {
        try {
            return $this->auth->verifyIdToken($token);
        } catch (ExpiredException $e) {
            // The token is expired, show an error message
            abort(401, 'Token is expired');
        } catch (InvalidToken  $e) {
            // The token is invalid, show an error message
            abort(401, 'Token is invalid');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Auth $firebaseAuth)
    {
        $firebaseUser = $firebaseAuth->getUser();
        $notifications = Notification::where('user_id', $firebaseUser->uid)->get();
        return response()->json(['notifications' => $notifications], 200);

    }
    public function store(Request $request, Auth $firebaseAuth)
    {
        $firebaseUser = $firebaseAuth->getUser();
        $notification = new Notification();
        $notification->img = $request->img;
        $notification->name = $request->name;
        $notification->message = $request->message;
        $notification->status = 'active';
        $notification->user_id = $firebaseUser->uid;
        $notification->is_active = true;
        $notification->save();
        return response()->json(['notification' => $notification], 201);
    }

    public function show($id, Auth $firebaseAuth)
    {
        $firebaseUser = $firebaseAuth->getUser();
        $notification = Notification::where('user_id', $firebaseUser->uid)->find($id);
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }
        return response()->json(['notification' => $notification], 200);
    }

    public function update(Request $request, $id, Auth $firebaseAuth)
    {
        $firebaseUser = $firebaseAuth->getUser();
        $notification = Notification::where('user_id', $firebaseUser->uid)->find($id);
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }
        $notification->img = $request->img ?? $notification->img;
        $notification->name = $request->name ?? $notification->name;
        $notification->message = $request->message ?? $notification->message;
        $notification->status = $request->status ?? $notification->status;
        $notification->is_active = $request->is_active ?? $notification->is_active;
        $notification->save();
        return response()->json(['notification' => $notification], 200);
    }

    public function destroy($id, Auth $firebaseAuth)
    {
        $firebaseUser = $firebaseAuth->getUser();
        $notification = Notification::where('user_id', $firebaseUser->uid)->find($id);
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }
        $notification->delete();
        return response()->json(['message' => 'Notification deleted'], 200);
    }
}
