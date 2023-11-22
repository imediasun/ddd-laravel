<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Schema(
 *      title="AuthenticationRequest",
 *      description="Store Channel request body data",
 *      type="object",
 *      required={"username","password"}
 * )
 */

class AuthenticationRequest extends FormRequest
{

    /**
     * @OA\Property(
     *      title="username",
     *      description="Username",
     *      example="test5_admin@gmail.com"
     * )
     *
     * @var string
     */

    public $username;

    /**
     * @OA\Property(
     *      title="client_id",
     *      description="client_id",
     *     example=CLIENT_ID
     * )
     *
     * @var string
     */

    public $client_id;

    /**
     * @OA\Property(
     *      title="client_secret",
     *      description="client_secret",
     *     example=CLIENT_SECRET
     * ),

     *
     * @var string
     */

    public $client_secret;

    /**
     * @OA\Property(
     *      title="password",
     *      description="Password",
     *      example="sunimedia"
     * )
     *
     * @var string
     */

    public $password;

    /**
     * @OA\Property(
     *      title="grant_type",
     *      description="grant_type",
     *      example="password"
     * )
     *
     * @var string
     */

    public $grant_type;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
}
