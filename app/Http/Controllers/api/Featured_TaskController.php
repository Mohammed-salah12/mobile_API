<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Featured_Task;
use App\Models\User;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth;



class Featured_TaskController extends Controller
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
        $featuredTasks = Featured_Task::with('user', 'task')->get();

        return response()->json(['featuredTasks' => $featuredTasks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'task_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $featuredTask = Featured_Task::create([
            'user_id' => $request->user_id,
            'task_id' => $request->task_id,
        ]);

        return response()->json(['featuredTask' => $featuredTask], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id , Featured_Task $featuredTask)
    {
        $featuredTask = Featured_Task::findOrFail($id);
        return response()->json(['$featuredTask' => $featuredTask]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Featured_Task $featuredTask,$id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'task_id' => 'exists:tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $featuredTask->user_id = $request->user_id;
        $featuredTask->task_id = $request->task_id;
        $featuredTask->save();

        return response()->json(['featuredTask' => $featuredTask]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Featured_Task $featuredTask,$id)
    {
        $featuredTask=Featured_Task::destroy($id);
        return response()->json([
            'status'=>true ,
            'massage'=>'deleled succsesfully',
        ]);
    }
}
