<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Validator;


class TaskController extends Controller
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
    public function index(Request $request)
    {
        $tasks_all=Task::orderBy('id' , 'desc')->get();
        return response()->json([
            'status' => true ,
            'massege' => 'data of tasks' ,
            'data' => $tasks_all ,
        ]);

        $sortBy = $request->get('sort_by', 'created-at');
        $order = $request->get('order', 'desc');
        $projectId = $request->get('project_id');

        $query = Task::query()->where('is_active', true);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        switch ($sortBy) {
            case 'importance':
                $query->orderBy('priority', $order);
                break;
            case 'alphabetical-order':
                $query = Task::orderByRaw('LOWER(alphabetical-order)')->get();
                break;
            case 'due-date':
                $query->orderByRaw("ABS(TIMESTAMPDIFF(SECOND, NOW(), CONCAT(CURDATE(), ' ', time_hours, ':', time_min, ' ', time_am_bm)))");
                break;
            default:
                $query->orderBy('created_at', $order);
        }

        $tasks = $query->get();

        return response()->json([
            'sorted_tasks' => $tasks,
        ]);


        // Get all tasks
        $tasks = Task::all();

        // Get active tasks
        $activeTasks = Task::where('status', 'active')->get();

        // Get done tasks
        $doneTasks = Task::where('status', 'done')->get();

        return response()->json([
            'tasks' => $tasks,
            'activeTasks' => $activeTasks,
            'doneTasks' => $doneTasks
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
            'name' => 'required|string',
            'category_id' => 'required|exists:projects,id',
            'status' => 'required|in:active,done',
            'description' => 'required|string',
            'time_Min' => 'required|numeric|min:0|max:59',
            'time_Am_BM' => 'required|in:am,pm',
            'time_Hours' => 'required|numeric|min:0|max:23',
            'sort_by' => 'nullable|in:importance,alphabetical-order,due-date,created-at',
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $task = new Task();
        $task->name = $request->get('name');
        $task->category_id = $request->get('category_id');
        $task->status = $request->get('status');
        $task->description = $request->get('description');
        $task->time_Min = $request->get('time_Min');
        $task->time_Am_BM = $request->get('time_Am_BM');
        $task->sort_by = $request->get('sort_by');
        $task->project_id = $request->get('project_id');

        $isSaved = $task->save();

        if ($isSaved) {
            return response()->json([
                'status' => true,
                'message' => 'Task created successfully',
                'task' => $task,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Task creation failed',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::with('project')->find($id);

        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'task' => $task
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
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'category_id' => 'exists:projects,id',
            'status' => 'in:active,done',
            'description' => 'string',
            'time_Min' => 'date_format:H:i',
            'time_Am_BM' => 'in:am,pm',
            'sort_by' => 'in:importance,alphabetical-order,due-date,created-at',
            'project_id' => 'exists:projects,id',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $task->name = $request->input('name', $task->name);
        $task->category_id = $request->input('category_id', $task->category_id);
        $task->status = $request->input('status', $task->status);
        $task->description = $request->input('description', $task->description);
        $task->time_Min = $request->input('time_Min', $task->time_Min);
        $task->time_Am_BM = $request->input('time_Am_BM', $task->time_Am_BM);
        $task->sort_by = $request->input('sort_by', $task->sort_by);
        $task->project_id = $request->input('project_id', $task->project_id);
        $task->is_active = $request->input('is_active', $task->is_active);

        $isUpdated = $task->save();

        if ($isUpdated) {
            return response()->json([
                'status' => true,
                'message' => 'Task updated successfully',
                'data' => $task
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update task'
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
        $task=Task::destroy($id);
        return response()->json([
            'status'=>true ,
            'massage'=>'deleled succsesfully',
        ]);
    }
}
