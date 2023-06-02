<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Validator;


class ProjectController extends Controller
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
        // Get all projects
        $projects = Project::all();

        // Get active projects
        $activeProjects = Project::where('status', 'active')->get();

        // Get done projects
        $doneProjects = Project::where('status', 'done')->get();

        return response()->json([
            'projects' => $projects,
            'activeProjects' => $activeProjects,
            'doneProjects' => $doneProjects
        ]);
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
            'name' => 'required|string|max:255',
            'category' => 'required|in:1,2,3,4',
            'priority' => 'required|in:1,2,3,4',
            'status' => 'required|in:active,done',
            'time_hours' => 'required|numeric|min:0|max:23',
            'time_min' => 'required|numeric|min:0|max:59',
            'time_am_bm' => 'required|in:am,pm',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $projects = new Project();
        $projects->name = $request->input('name');
        $projects->category = $request->input('category');
        $projects->priority = $request->input('priority');
        $projects->status = $request->input('status');
        $projects->time_hours = $request->input('time_Hours');
        $projects->time_min = $request->input('time_Min');
        $projects->time_am_bm = $request->input('time_Am_BM');
        $projects->save();

        return response()->json([
            'status' => true,
            'message' => "Created successfully",
            'data' => $projects,
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
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'project' => $project
        ], 200);
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
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'category' => 'in:work,personal,wishlist,birthdays',
            'priority' => 'in:important_AND_URGENT,important_But_Not_URGENT,not_Important_Or_URGENT,not_A_Proirity',
            'status' => 'in:active,done',
            'time_Hours' => 'date_format:H:i',
            'time_Min' => 'date_format:H:i',
            'time_Am_BM' => 'in:am,pm',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $project->name = $request->input('name', $project->name);
        $project->category = $request->input('category', $project->category);
        $project->priority = $request->input('priority', $project->priority);
        $project->status = $request->input('status', $project->status);
        $project->time_Hours = $request->input('time_Hours', $project->time_Hours);
        $project->time_Min = $request->input('time_Min', $project->time_Min);
        $project->time_Am_BM = $request->input('time_Am_BM', $project->time_Am_BM);
        $project->is_active = $request->input('is_active', $project->is_active);

        $isUpdated = $project->save();

        if ($isUpdated) {
            return response()->json([
                'status' => true,
                'message' => 'Project updated successfully',
                'data' => $project
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update project'
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
        $project=Project::destroy($id);
        return response()->json([
            'status'=>true ,
            'massage'=>'deleled succsesfully',
        ]);
    }
}
