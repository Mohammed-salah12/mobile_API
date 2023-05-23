<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth\SignIn\FailedToSignIn;
use Kreait\Firebase\Auth;

class PromotionController extends Controller
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
        $user = $request->user();
        $promotions = Promotion::where('user_id', $user->id)->get();
        return response()->json(['promotions' => $promotions]);
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
            'promotion_type' => 'required|in:yearly,monthly',
            'duration' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = $request->user();

        try {
            $idToken = $request->header('Authorization');
            $verifiedIdToken = app('firebase.auth')->verifyIdToken($idToken);
            $uid = $verifiedIdToken->getClaim('sub');

            if ($uid !== $user->firebase_uid) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (FailedToSignIn $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $promotion = new Promotion();
        $promotion->promotion_type = $request->promotion_type;
        $promotion->duration = $request->duration;
        $promotion->user_id = $user->id;
        $promotion->save();

        return response()->json(['promotion' => $promotion]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $promotion = Promotion::findOrFail($id);
        return response()->json(['promotion' => $promotion], 200);
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
            'promotion_type' => 'required|in:yearly,monthly',
            'duration' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = $request->user();
        $promotion = Promotion::find($id);

        if ($promotion->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $promotion->promotion_type = $request->promotion_type;
        $promotion->duration = $request->duration;
        if ($request->has('is_active')) {
            $promotion->is_active = $request->is_active;
        }
        $promotion->save();

        return response()->json(['promotion' => $promotion]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $user = $request->user();
        $promotion = Promotion::find($id);

        if ($promotion->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $promotion->delete();

        return response()->json(['message' => 'Promotion deleted']);
    }
}
