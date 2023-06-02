<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth;
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
    public function index()
    {
        $notifications = Notification::with('user')->get();
        return response()->json(['data' => $notifications]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'img' => 'nullable',
            'name' => 'string|nullable',
            'massage' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create and save new Notification
        $notification = new Notification();
        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $imageName = time() . 'img.' . $img->getClientOriginalExtension();
            $img->move('storage/images/' . $notification->id, $imageName);
            $notification->img = $imageName;
        }

        $notification->name = $request->input('name');
        $notification->massage = $request->input('massage');
        $notification->status = $request->input('status');
        $notification->user_id = $request->input('user_id');
        $notification->save();

        // Return success response
        return response()->json([
            'status' => true,
            'message' => "Created successfully",
            'data' => $notification,
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::with('user')->findOrFail($id);
        return response()->json(['data' => $notification]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'img' => 'nullable',
            'name' => 'string|nullable',
            'massage' => 'string',
            'status' => '|in:active,inactive',
            'user_id' => '|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $notification = Notification::find($id);
        if ($notification) {
            if (request()->hasFile('img')) {
                $img = request()->file('img');
                $imageName = time() . 'img.' . $img->getClientOriginalExtension();
                $img->move('storage/images/' . $notification->id, $imageName);
                $notification->img = $imageName;
            }

            $notification->name = request()->input('name');
            $notification->massage = request()->input('massage');
            $notification->status = request()->input('status');
            $notification->user_id = request()->input('user_id');

            $isUpdated = $notification->save();

            if ($isUpdated) {
                return response()->json([
                    'status' => true,
                    'message' => "Updated successfully",
                    'data'=> $notification
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Update failed",
                ], 400);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "User not found",
            ], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notification::destroy($id);
        return response()->json([
            'status' => true,
            'message' => 'Notification deleted successfully',
        ]);
    }
}
