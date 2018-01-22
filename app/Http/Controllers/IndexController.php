<?php

namespace App\Http\Controllers;

use App\Mail\ContactUs;
use App\Mail\IcoRegistrationAdmin as IcoRegistrationAdminMail;
use App\Mail\IcoRegistration as IcoRegistrationMail;
use App\Models\Currency;
use App\Models\DB\IcoRegistration;
use App\Models\DB\Investor;
use App\Models\DB\PasswordReset;
use App\Models\DB\State;
use App\Models\DB\User;
use App\Models\Validation\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class IndexController extends Controller
{
    /**
     * Main page
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function main(Request $request)
    {
        return view(
            'main.index'
        );
    }

    /**
     * Reset user password
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $resetToken = $request->input('rt', '');

        $resetInfo = PasswordReset::where('token', $resetToken)
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 15 MINUTE)'))
            ->first();

        // Check for expiration date
        if (is_null($resetInfo)) {
            return view(
                'main.reset-password',
                [
                    'resetToken' => ''
                ]
            );
        }

        $resetEmail = $resetInfo->email;

        $lastResetInfo = PasswordReset::where('email', $resetEmail)
            ->orderBy('created_at', 'desc')
            ->first();

        // Check if there is no other tokens after current
        if (is_null($lastResetInfo) || $lastResetInfo->token !== $resetToken) {
            return view(
                'main.reset-password',
                [
                    'resetToken' => ''
                ]
            );
        }

        return view(
            'main.reset-password',
            [
                'resetToken' => $resetToken
            ]
        );
    }

    /**
     * Send contact us email
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactUs(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'message' => 'required'
            ],
            ValidationMessages::getList(
                [
                    'name' => 'Name',
                    'email' => 'Email',
                    'message' => 'Message',
                ]
            )

        );

        $name = $request->input('name');
        $email = $request->input('email');
        $message = $request->input('message');

        try {

            Mail::to(env('CONTACT_EMAIL'))->send(new ContactUs($name, $email, $message));

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error sending message',
                    'errors' => [
                        'name' => '',
                        'email' => '',
                        'message' => 'Error sending message'
                    ]
                ]
            );
        }

        return response()->json(
            []
        );
    }

    /**
     * Show user document
     *
     * @param Request $request
     *
     * @return View
     */
    public function creative(Request $request)
    {
        $this->validate($request, [
            'cin' => 'required|string',
        ]);

        $fileName = $request->input('cin', '');

        $filePath = 'creatives/' . $fileName;

        if (!Storage::exists($filePath)) {
            return redirect('/');
        }
        $mimeType = Storage::mimeType($filePath);

        return response()->file(
            storage_path('app/' . $filePath),
            ['Content-Type' => $mimeType]
        );
    }

}
