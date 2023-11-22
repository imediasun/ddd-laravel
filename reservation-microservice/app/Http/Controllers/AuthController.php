<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use \Mailjet\Resources;
use Mailjet\Client;
use App\Http\Requests\Auth\AuthenticationRequest;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints of Authentication"
 * )
 */

/**
 * @SWG\Swagger(client_secret=CLIENT_SECRET)
 */

class AuthController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = DB::table('oauth_clients')->where('id', 2)->first();

    }


    public function register(Request $request)
    {
        $validatedEmail = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        if ($validatedEmail->fails()) {
            return response()->json([
                'error' => 'Duplicate Email found',
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Duplicate Email detected. Please input another email'
            ], Response::HTTP_UNPROCESSABLE_ENTITY); //422
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed',

        ]);

        $validatedData['password'] = bcrypt($request->password);
        $user = User::create($validatedData);
        // sends a verification email once registration is done
        event(new Registered($user));
        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['access_token' => $accessToken], Response::HTTP_OK); //200

    }


    public function login(Request $request)
    {
        $loginData = $request->validate([

            'email' => 'email|required',
            'password' => 'required',

        ]);
        if (!auth()->attempt($loginData)) {
            return response(['message' => 'invalid credentials'], 401);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out',
        ], Response::HTTP_OK); //Status 200
    }


    /**
     * @OA\Post(
     *      path="/auth/token",
     *      operationId="authenticate",
     *      tags={"Authentication"},
     *      summary="authenticate new user",
     *      security={{"token":{}}},
     *      description="Returns access tokend and refresh token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AuthenticationRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *     @OA\Property(
     *              property="access_token",
     *              type="string",
     * ),
     * ),
     * ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    protected function authenticate(AuthenticationRequest $request)
    {
        $client_secret = $request->header('client_secret');
        $client_id = $request->header('client_id');
        $request->request->add([
            'grant_type' => 'password',
            'username' => $request->username,
            'password' => $request->password,
        ]);

        $req = $request->all();
        $req['client_id'] = $client_id;
        $req['client_secret'] = $client_secret;
        $req['scope'] = '*';
        $tokenRequest = $request->create('/oauth/token', 'POST', $req);

        return  \Route::dispatch($tokenRequest);
    }

    protected function refreshToken(Request $request): mixed
    {
        $request->request->add([
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => ''
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return \Route::dispatch($proxy);
    }

    public function forgotPassword(Request $request)
    {
        $input = $request->only('email');
        $validator = Validator::make($input, [
            'email' => "required|email"
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::where('email', '=', $request->email)->first();
        //Check if the user exists
        if (!$user) {
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }

        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]);
        //Get the token just created above
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->orderBy('created_at', 'desc')->first();

        if ($this->sendResetEmail($request->email, $tokenData->token)) {
            \Log::info('A reset link has been sent to your email address.');
            return response()->json(['success' => true]);
        } else {
            \Log::info('A Network Error occurred. Please try again.');
            return response()->json(['success' => false]);
        }


    }

    public function passwordReset(Request $request): \Illuminate\Http\JsonResponse
    {
        //Validate input
        $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'password' => 'required|confirmed',
        'token' => 'required' ]);

    //check if payload is valid before moving on
    if ($validator->fails()) {
        $message = ['success' => false, 'message' => 'Validation fails'];
        return response()->json($message);

    }

    $password = $request->password;
    $tokenData = DB::table('password_resets')
        ->where('token', $request->token)->first();
    if (!$tokenData) {
        $message = ['success' => false, 'message' => 'Not correct tolen'];
        return response()->json($message);
    }

    $user = User::where('email', $tokenData->email)->first();
    if (!$user) {
        $message = ['success' => false, 'message' => 'Email not found'];
    }else{
        $user->password = \Hash::make($password);
        $user->update(); //or $user->save();

        //Delete the token
        DB::table('password_resets')->where('email', $user->email)
            ->delete();

        //Send Email Reset Success Email
        if ($this->sendSuccessEmail($tokenData->email)) {
            $message = ['success' => true, 'message' => 'Ok'];
        } else {
            $message = ['success' => false, 'message' => '!Ok'];
        }
    }

        return response()->json($message);
    }

    private function sendSuccessEmail($email)
    {
        //Retrieve the user from the database
        $link = config('app.url') . '/';
        $details = [
            'title' => 'Password Successfully reseted',
            'body' => 'Password Successfully reseted',
            'link' => $link

        ];
        try {
            Mail::to($email)->send(new \App\Mail\PasswordResetSuccessfullMail($details));
            return true;
        } catch (\Exception $e) {
            \Log::debug('MailExceptionDebug=>'.$e->getMessage());
            return false;
        }
    }
    private function sendResetEmail($email, $token)
    {
        //Retrieve the user from the database
        $user = DB::table('users')->where('email', $email)->select('name', 'email')->first();
        //Generate, the password reset link. The token generated is embedded in the link
        \Log::info('URL=>'.config('app.url'));
        $link = config('app.url') . '/authentication/reset-password/' . $token . '/' . urlencode($user->email);
        $details = [

            'title' => 'Now you can reset your password using link below',

            'body' => 'Please use next link to reset your password',
            'link' => $link

        ];
        try {
            Mail::to($email)->send(new \App\Mail\ForgotPasswordMail($details));
            return true;
        } catch (\Exception $e) {
            \Log::debug('MailExceptionDebug=>'.$e->getMessage());
            return false;
        }
    }
}
