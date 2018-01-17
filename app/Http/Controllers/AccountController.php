<?php

namespace App\Http\Controllers;

use App\Mail\ActivateAccount;
use App\Mail\ResetPassword;
use App\Models\DB\AdvertisingAccount;
use App\Models\DB\PasswordReset;
use App\Models\DB\Verification;
use App\Models\DB\Wallet;
use App\Models\DB\User;
use App\Models\Processors\Account;
use App\Models\Validation\ValidationMessages;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Mockery\Exception;


class AccountController extends Controller
{
    /**
     * AccountController constructor.
     */
    public function __construct()
    {
    }

    /**
     * Register users
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function register(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                    'password' => 'Password'
                ],
                [
                    'email.unique' => 'User with such Email already registered',
                    'password.min' => 'The Password field must be at least 6 characters',
                    'password.confirmed' => 'The Password confirmation does not match',
                ]
            )
        );

        $email = $request->input('email');
        $password = $request->input('password');

        DB::beginTransaction();

        try {

            Account::addUser(
                [
                    'email' => $email,
                    'password' => User::hashPassword($password),
                ]
            );

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error creating user',
                    'errors' => [
                        'email' => '',
                        'password' => '',
                        'password_confirmation' => 'Registration failed'
                    ]
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            []
        );

    }

    /**
     * Login user
     *
     * @param Request $request
     *
     * @return json
     */
    public function login(Request $request)
    {

        $email = $request->input('email', '');
        $password = $request->input('password', '');

        try {
            $isAuthorized = Auth::attempt(
                [
                    'email' => $email,
                    'password' => $password,
                ]
            );

            if (!$isAuthorized) {
                throw new AuthenticationException('Login or password incorrect');
            }

        } catch (\Exception $e) {

            $message = ($e instanceof AuthenticationException) ? $e->getMessage() : 'Authentification failed';

            return response()->json(
                [
                    'message' => $message,
                    'errors' => [
                        'email' => '',
                        'password' => $message
                    ]
                ],
                422
            );
        }

        return response()->json(
            [
                'userPage' => Account::getUserPage(Auth::user()->role)
            ]
        );
    }

    /**
     * Logout user
     */
    public function logout()
    {
        try {

            Auth::logout();

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Can not log out current user',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            [
                'userPage' => '/'
            ]
        );

    }

    /**
     * Send email with password reset link
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function resetPassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255',
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                ]
            )
        );

        $email = $request->input('email');

        DB::beginTransaction();

        try {

            $resetInfo = PasswordReset::create(
                [
                    'email' => $email,
                    'token' => uniqid()
                ]
            );

            Mail::to($resetInfo['email'])->send(new ResetPassword($resetInfo['token']));

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Can not restore password',
                    'errors' => [
                        'email' => 'Error restoring password'
                    ]
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            []
        );

    }

    /**
     * Save new user password
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function savePassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'password' => 'required|string|min:6|confirmed',
                'token' => 'required|string'
            ],
            ValidationMessages::getList(
                [
                    'password' => 'Password',
                    'token' => 'Token',
                ],
                [
                    'password.min' => 'The Password field must be at least 6 characters',
                    'password.confirmed' => 'The Password confirmation does not match',
                ]
            )
        );

        DB::beginTransaction();

        try {
            $resetInfo = PasswordReset::where('token', $request->token)
                ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 15 MINUTE)'))
                ->first();

            if (is_null($resetInfo)) {
                throw new \Exception('Error changing password');
            }

            $user = User::where('email', $resetInfo->email)->first();

            if (is_null($user)) {
                throw new \Exception('Error changing password');
            }

            $user->password = User::hashPassword($request->password);
            $user->save();

            PasswordReset::where('email', $user->email)->delete();

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'nextStep' => action('IndexController@resetPassword')
                ]
            );
        }

        DB::commit();

        return response()->json(
            [
                'nextStep' => action('IndexController@main')
            ]
        );
    }

    /**
     * Redirect to Facebook
     * @return redirect
     */
    public function toFacebookProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Provide callback from Facebook
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     */
    public function FacebookProviderCallback()
    {

        try {

            $snUser = Socialite::driver('facebook')->user();

            $userID = Account::processFBUser($snUser);

            $isAuthorized = Auth::loginUsingId($userID);

            if (!$isAuthorized) {
                throw new Exception('Authentification failed.');
            }

        } catch (\Exception $e) {

            return redirect()->action('IndexController@main');

        }

        return redirect()->action('UserController@profile');
    }

}
