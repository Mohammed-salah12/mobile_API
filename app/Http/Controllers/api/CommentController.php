<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
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
        $comments = Comment::with('task', 'user')->get();
        return response()->json(['data' => $comments]);
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
            'img' => 'required',
            'name' => 'required|string',
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create and save new comment
        $comment = new Comment();
        $comment->name = $request->input('name');
        $comment->task_id = $request->input('task_id');
        $comment->user_id = $request->input('user_id');
        $comment->save();

        if (request()->hasFile('img')) {

            $img = $request->file('img');

            $imageName = time() . 'img.' . $img->getClientOriginalExtension();

            $img->move('storage/images/comments', $imageName);

            $comment->img = $imageName;
            $comment->save();

        }
        // Return success response
        return response()->json(['message' => 'Comment created successfully', 'data' => $comment], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::with('task', 'user')->findOrFail($id);
        return response()->json([
            'comment' => $comment,
            'task' => $comment->task,
            'user' => $comment->user,
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
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $validatedData = $request->validate([
            'img' => 'nullable|string',
            'name' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',
            'is_active' => 'nullable|boolean',
        ]);

        $comment->update($validatedData);

        return response()->json(['message' => 'Comment updated successfully', 'data' => $comment], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment=Comment::destroy($id);
        return response()->json([
            'status'=>true ,
            'massage'=>'deleled succsesfully',
        ]);
    }
}
