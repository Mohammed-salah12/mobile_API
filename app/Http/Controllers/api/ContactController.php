<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Validator;


class ContactController extends Controller
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
        $contacts = Contact::with('user')->get();
        return response()->json(['data' => $contacts]);
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
            'name' => 'string|required',
            'number' => 'required',
            'user_id' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create and save new Contact
        $contact = new Contact();
        $contact->name = $request->input('name');
        $contact->number = $request->input('number');
        $contact->user_id = $request->input('user_id');
        $contact->save();

        // Return success response
        return response()->json([
            'status' => true,
            'message' => "Created successfully",
            'data' => $contact,
        ], 201);    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::with('user')->findOrFail($id);
        return response()->json(['data' => $contact]);
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
            'name' => 'string|',
            'number' => 'integer',
            'user_id' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $contact = Contact::find($id);
        if ($contact) {
            $contact->name = $request->get('name');
            $contact->number = $request->get('number');
            $contact->user_id = $request->get('user_id');

            $isUpdated = $contact->save();
            if ($isUpdated) {
                return response()->json([
                    'status' => true,
                    'message' => "Updated successfully",
                    'data'=>$contact
                ], 200
                );
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
        $contact = Contact::destroy($id);
        return response()->json([
            'status' => true,
            'message' => 'Contact deleted successfully',
        ]);
    }
}
