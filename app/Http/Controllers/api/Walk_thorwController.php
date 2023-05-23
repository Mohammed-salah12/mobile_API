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
            'Fimg'=>'required',
            'Ftitle'=>'nullable|string',
            'Fdescription'=>'required|string',
            'Simg'=>'required',
            'Stitle'=>'nullable|string',
            'Sdescription'=>'required|string',
            'Timg'=>'required',
            'Ttitle'=>'nullable|string',
            'Tdescription'=>'required|string',

        ]));
        if(! $valedetor->fails() ){
            $walk_throws= new Walk_throw();
            $walk_throws->Ftitle=$request->get('Ftitle');
            $walk_throws->Fdescription=$request->get('Fdescription');
            if (request()->hasFile('Fimg')) {

                $img = $request->file('Fimg');

                $imageName = time() . 'Fimg.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->Fimg = $imageName;
                $walk_throws->save();

            }
            $walk_throws->Stitle=$request->get('Stitle');
            $walk_throws->Sdescription=$request->get('Sdescription');
            if (request()->hasFile('Simg')) {

                $img = $request->file('Simg');

                $imageName = time() . 'Simg.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->Simg = $imageName;
                $walk_throws->save();

            }

            $walk_throws->Ttitle=$request->get('Ttitle');
            $walk_throws->Tdescription=$request->get('Tdescription');
            if (request()->hasFile('Timg')) {

                $img = $request->file('Timg');

                $imageName = time() . 'Timg.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->Timg = $imageName;
                $walk_throws->save();

            }

            $IsSaved=$walk_throws->save();
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
            'img' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        if (!$validator->fails()) {
            $walk_throws = Walk_throw::find($id);
            $walk_throws->Ftitle=$request->get('Ftitle');
            $walk_throws->Fdescription=$request->get('Fdescription');
            if (request()->hasFile('Fimg')) {

                $img = $request->file('Fimg');

                $imageName = time() . 'Fimg.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->Fimg = $imageName;
                $walk_throws->save();

            }
            $walk_throws->Stitle=$request->get('Stitle');
            $walk_throws->Sdescription=$request->get('Sdescription');
            if (request()->hasFile('Simg')) {

                $img = $request->file('Simg');

                $imageName = time() . 'Simg.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->Simg = $imageName;
                $walk_throws->save();

            }

            $walk_throws->Ttitle=$request->get('Ttitle');
            $walk_throws->Tdescription=$request->get('Tdescription');
            if (request()->hasFile('Timg')) {

                $img = $request->file('Timg');

                $imageName = time() . 'Timg.' . $img->getClientOriginalExtension();

                $img->move('storage/images/walk_throw', $imageName);

                $walk_throws->Timg = $imageName;
                $walk_throws->save();

            }
            $isUpdated = $walk_throws->save();

            if ($isUpdated) {
                return response()->json([
                    'status' => true,
                    'message' => "Update successful"
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
