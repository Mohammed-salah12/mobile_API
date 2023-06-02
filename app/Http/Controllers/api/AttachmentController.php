<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Comment;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Validator;


class AttachmentController extends Controller
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
        $attachment = Attachment::with('task', 'user')->get();
        return response()->json(['data' => $attachment]);
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
            'name' => 'string|nullable',
            'size' => 'string|nullable',
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create and save new Attachment
        $attachment = new Attachment();
        $attachment->name = $request->input('img');
        $attachment->size = $request->input('name');
        $attachment->task_id = $request->input('task_id');
        $attachment->user_id = $request->input('user_id');
        $attachment->save();

        // Return success response with the created attachment
        return response()->json([
            'status' => true,
            'message' => "Created successfully",
            'data' => $attachment,
        ], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attachment = Attachment::with('task', 'user')->findOrFail($id);
        return response()->json([
            'Attachment' => $attachment,
            'task' => $attachment->task,
            'user' => $attachment->user,
        ]);
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
        $attachment = Attachment::find($id);

        if (!$attachment) {
            return response()->json([
                'status' => false,
                'message' => 'attachment not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|nullable',
            'size' => 'string|nullable',
            'task_id' => 'exists:tasks,id',
            'user_id' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $attachment->name = $request->input('name', $attachment->name);
        $attachment->size = $request->input('size', $attachment->size);
        $attachment->task_id = $request->input('task_id', $attachment->task_id);
        $attachment->user_id = $request->input('user_id', $attachment->user_id);
        $isUpdated = $attachment->save();

        if ($isUpdated) {
            return response()->json([
                'status' => true,
                'message' => 'attachment updated successfully',
                'data' => $attachment
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update attachment'
            ], 500);
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
        $attachment=Attachment::destroy($id);
        return response()->json([
            'status'=>true ,
            'massage'=>'deleled succsesfully',
        ]);
    }
}
