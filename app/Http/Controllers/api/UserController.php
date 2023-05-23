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


class UserController extends Controller
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
        $users=User::orderBy('id' , 'desc')->get();
        return response()->json([
            'status' => true ,
            'massege' => 'data of User' ,
            'data' => $users ,
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
        $valedetor=validator(request()->all([
            'full_Name'=>'required|string',
            'gmail'=>'nullable|string',
            'bio'=>'required|string',
            'phone_Number'=>'required',

        ]));
        if(! $valedetor->fails() ){
            $users= new User();
            $users->full_Name=$request->get('full_Name');
            $users->gmail=$request->get('gmail');
            $users->bio=$request->get('bio');
            $users->phone_Number=$request->get('phone_Number');

            $IsSaved=$users->save();
            if ($IsSaved) {
                return response()->json([
                    'status' => true,
                    'massage' => "Created is successfully"],
                    200);
            } else {
                return response()->json([
                    'status' => false,
                    'massage' => "Created is faild"],
                    400);
            }
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
        $users = User::find($id);

        if (!$users) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'project' => $users
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
        $validator = Validator::make($request->all(), [
            'full_Name' => 'required|string',
            'gmail' => 'nullable|string',
            'bio' => 'required|string',
            'phone_Number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::find($id);
        if ($user) {
            $user->full_Name = $request->get('full_Name');
            $user->gmail = $request->get('gmail');
            $user->bio = $request->get('bio');
            $user->phone_Number = $request->get('phone_Number');

            $isUpdated = $user->save();
            if ($isUpdated) {
                return response()->json([
                    'status' => true,
                    'message' => "Updated successfully",
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
        $user=User::destroy($id);
        return response()->json([
            'status'=>true ,
            'massage'=>'deleled succsesfully',
        ]);
    }
}
