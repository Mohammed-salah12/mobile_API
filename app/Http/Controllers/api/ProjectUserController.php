<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectUserController extends Controller
{
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

    public function attachUser(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $project = Project::findOrFail($request->input('project_id'));
        $user = User::findOrFail($request->input('user_id'));

        $project->users()->attach($user);

        return response()->json([
            'message' => 'User attached to project successfully.',
        ]);
    }

    public function detachUser(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $project = Project::findOrFail($request->input('project_id'));
        $user = User::findOrFail($request->input('user_id'));

        $project->users()->detach($user);

        return response()->json([
            'message' => 'User detached from project successfully.',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
