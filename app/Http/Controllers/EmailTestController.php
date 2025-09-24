<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class EmailTestController extends Controller
{
    // Display current mail configuration (safe view)
    public function showConfig()
    {
        $config = [
            'mail.default' => Config::get('mail.default'),
            'mail.mailers.smtp.host' => Config::get('mail.mailers.smtp.host'),
            'mail.mailers.smtp.port' => Config::get('mail.mailers.smtp.port'),
            'mail.mailers.smtp.username' => Config::get('mail.mailers.smtp.username'),
            'mail.mailers.smtp.scheme' => Config::get('mail.mailers.smtp.scheme'),
            'mail.from.address' => Config::get('mail.from.address'),
        ];

        return response()->json($config);
    }

    /**
     * Show a small HTML form to trigger the test email.
     */
    public function showForm(): Response
    {
        $config = [
            'mail.default' => Config::get('mail.default'),
            'mail.mailers.smtp.host' => Config::get('mail.mailers.smtp.host'),
            'mail.mailers.smtp.port' => Config::get('mail.mailers.smtp.port'),
            'mail.mailers.smtp.username' => Config::get('mail.mailers.smtp.username'),
            'mail.mailers.smtp.scheme' => Config::get('mail.mailers.smtp.scheme'),
            'mail.from.address' => Config::get('mail.from.address'),
        ];

        return response()->view('email_test.form', ['config' => $config]);
    }

    // Send a test email to david.gakhokia@gmail.com
    public function sendTest(Request $request)
    {
        $to = 'david.gakhokia@gmail.com';
        $cc = 'lorenababunashvili0@gmail.com';
        try {
            Mail::raw('This is a test email from FoodlyApp', function ($message) use ($to, $cc) {
                $message->to($to)->cc($cc)->subject('FoodlyApp test email');
            });
            return response()->json(['ok' => true, 'message' => 'Test email queued/sent to ' . $to]);
        } catch (\Throwable $e) {
            Log::error('Failed to send test email', ['error' => $e->getMessage()]);
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
