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
    public function index(Request $request, Auth $firebaseAuth)
    {
        $firebaseUser = $firebaseAuth->getUser();
        $contacts = Contact::where('user_id', $firebaseUser->uid)->get(['name', 'number']);
        return response()->json(['contacts' => $contacts], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , Auth $firebaseAuth)
    {
        $firebaseUser = $firebaseAuth->getUser();

        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|numeric',
        ]);

        $contact = new Contact();
        $contact->name = $request->input('name');
        $contact->number = $request->input('number');
        $contact->user_id = $firebaseUser->uid;
        $contact->save();

        return response()->json(['contact' => $contact], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Auth $firebaseAuth,$id)
    {
        $firebaseUser = $firebaseAuth->getUser();
        $contact = Contact::where('id', $id)
            ->where('user_id', $firebaseUser->uid)
            ->firstOrFail(['name', 'number']);
        return response()->json(['contact' => $contact], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Auth $firebaseAuth,Request $request, $id)
    {
        $firebaseUser = $firebaseAuth->getUser();

        $request->validate([
            'name' => 'string|max:255',
            'number' => 'numeric',
        ]);

        $contact = Contact::where('id', $id)
            ->where('user_id', $firebaseUser->uid)
            ->firstOrFail();

        if ($request->has('name')) {
            $contact->name = $request->input('name');
        }

        if ($request->has('number')) {
            $contact->number = $request->input('number');
        }

        $contact->save();

        return response()->json(['contact' => $contact], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auth $firebaseAuth,$id)
    {
        $firebaseUser = $firebaseAuth->getUser();

        $contact = Contact::where('id', $id)
            ->where('user_id', $firebaseUser->uid)
            ->firstOrFail();

        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully'], 200);

    }
}
