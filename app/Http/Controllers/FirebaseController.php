<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\PhoneNumberExists;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Laravel\Sanctum\PersonalAccessToken;






class FirebaseController extends Controller
{
    /**
     * The Firebase auth instance.
     *
     * @var Auth
     */
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Send an OTP code to the user's phone number.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOTP(Request $request): \Illuminate\Http\JsonResponse
    {
        $phoneNumber = $request->input('phone_number');
        dd($phoneNumber);

        try {
            $this->auth->getUserByPhoneNumber($phoneNumber);
        } catch (PhoneNumberExists $e) {
            return response()->json(['message' => 'Phone number already exists'], 422);
        }

        return response()->json(['message' => 'OTP code sent successfully']);
    }

    /**
     * Verify the OTP code and authenticate the user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'code' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $phoneNumber = $request->input('phone');
        $verificationCode = $request->input('code');

        if (strpos($phoneNumber, '+') !== false) {
            $phoneNumber = str_replace("+", "00", $phoneNumber);
        }

        $firebaseAuth = Firebase::auth();

        try {
            $authVerify = $firebaseAuth->verifyIdToken($phoneNumber, $verificationCode);
            $uid = $authVerify->claims()->get('sub');
            $verifiedPhoneNumber = $this->auth->getUser($uid)->phoneNumber;

            if(! $verifiedPhoneNumber = $phoneNumber){
                return response()->json(['message' => 'phone number invalid'], 400);
            }



            $user = User::where('phone', $verifiedPhoneNumber)->first();

            if (!$user) {
                $user = new User(['phone' => $verifiedPhoneNumber]);
                $user->save();
            }

            $accessToken = $user->createToken('firebase')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $accessToken
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
