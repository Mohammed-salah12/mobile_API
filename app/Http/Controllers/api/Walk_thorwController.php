<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Walk_throw;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Validator;


class Walk_thorwController extends Controller
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
        $walk_throws=Walk_throw::orderBy('id' , 'desc')->get();
        return response()->json([
            'status' => true ,
            'massege' => 'data of Walk_throw' ,
            'data' => $walk_throws ,
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
            'f_img'=>'nullable',
            'f_title'=>'nullable|string',
            'f_description'=>'required|string',
            's_img'=>'nullable',
            's_title'=>'nullable|string',
            's_description'=>'required|string',
            't_img'=>'nullable',
            't_title'=>'nullable|string',
            't_description'=>'required|string',

        ]));
        if(! $valedetor->fails() ){
            $walk_throws= new Walk_throw();
            $walk_throws->f_title=$request->get('f_title');
            $walk_throws->f_description=$request->get('f_description');
            if (request()->hasFile('f_img')) {

                $img = $request->file('f_img');

                $imageName = time() . 'f_img.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->f_img = $imageName;
                $walk_throws->save();

            }
            $walk_throws->s_title=$request->get('s_title');
            $walk_throws->s_description=$request->get('s_description');
            if (request()->hasFile('s_img')) {

                $img = $request->file('s_img');

                $imageName = time() . 's_img.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->s_img = $imageName;
                $walk_throws->save();

            }

            $walk_throws->t_title=$request->get('t_title');
            $walk_throws->t_description=$request->get('t_description');
            if (request()->hasFile('t_img')) {

                $img = $request->file('t_img');

                $imageName = time() . 't_img.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->t_img = $imageName;
                $walk_throws->save();

            }

            $IsSaved=$walk_throws->save();
            if ($IsSaved) {
                return response()->json([
                    'status' => true,
                    'message' => "Created successfully",
                    'data' => $walk_throws,
                ], 201);
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
        $walk_throws = Walk_throw::find($id);

        if (!$walk_throws) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'project' => $walk_throws
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
            'f_img' => 'nullable|string',
            'f_title' => 'nullable|string',
            'f_description' => 'nullable|string' ,
            's_img' => 'nullable|string',
            's_title' => 'nullable|string',
            's_description' => 'nullable|string' ,
            't_img' => 'nullable|string',
            't_title' => 'nullable|string',
            't_description' => 'nullable|string'


        ]);

        if (!$validator->fails()) {
            $walk_throws = Walk_throw::find($id);
            $walk_throws->f_title=$request->get('f_title');
            $walk_throws->f_description=$request->get('f_description');
            if (request()->hasFile('f_img')) {

                $img = $request->file('f_img');

                $imageName = time() . 'f_img.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->f_img = $imageName;
                $walk_throws->save();

            }
            $walk_throws->s_title=$request->get('s_title');
            $walk_throws->s_description=$request->get('s_description');
            if (request()->hasFile('s_img')) {

                $img = $request->file('s_img');

                $imageName = time() . 's_img.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->s_img = $imageName;
                $walk_throws->save();

            }

            $walk_throws->t_title=$request->get('t_title');
            $walk_throws->t_description=$request->get('t_description');
            if (request()->hasFile('t_img')) {

                $img = $request->file('t_img');

                $imageName = time() . 't_img.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->t_img = $imageName;
                $walk_throws->save();

            }
            $isUpdated = $walk_throws->save();

            if ($isUpdated) {
                return response()->json([
                    'status' => true,
                    'message' => "Update successful" ,
                    'data' =>$walk_throws
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Update failed"
                ], 400);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
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
        $walk_throw=Walk_throw::destroy($id);
        return response()->json([
            'status'=>true ,
            'massage'=>'deleled succsesfully',
        ]);
    }
}
