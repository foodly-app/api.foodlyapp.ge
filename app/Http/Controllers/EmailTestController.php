<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Models\Reservation\Reservation;

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

    // Send all statuses for a reservation to QA address (dev-only)
    public function sendAllStatuses(Request $request)
    {
        // Protect: only allow local or when APP_DEBUG is true
        if (!app()->isLocal() && env('APP_DEBUG') !== true && env('APP_DEBUG') !== 'true') {
            return response()->json(['ok' => false, 'error' => 'Not allowed in this environment'], 403);
        }

        $qa = 'david.gakhokia@gmail.com';
        $reservation = null;
        if ($request->has('reservation_id')) {
            $reservation = Reservation::find($request->input('reservation_id'));
        }

        if (!$reservation) {
            // Create a lightweight fake-reservation object for rendering templates.
            // Note: property defaults cannot call runtime functions (PHP restriction),
            // so set dynamic values after instantiation.
            $reservation = new class {
                public $id = 0;
                public $name = 'QA User';
                public $email = 'qa@example.test';
                public $phone = '+0000000000';
                public $reservation_date = null; // set dynamically below
                public $time_from = '19:00';
                public $time_to = '21:00';
                public $guests_count = 2;
                public $reservable;
                public $status = 'pending';
            };

            // assign runtime-calculated date after creating the object
            $reservation->reservation_date = now()->addDay()->toDateString();
        }

        // statuses to test (match your app statuses)
        $statuses = ['confirmed', 'cancelled', 'payment_confirmed', 'payment_failed', 'completed', 'reminder'];
        $audiences = ['client', 'restaurant', 'administrator'];

        $sync = $request->boolean('sync');

        foreach ($audiences as $aud) {
            foreach ($statuses as $status) {
                try {
                    // Use the namespaced mail subclasses we created
                    switch ($aud) {
                        case 'restaurant':
                            $mail = new \App\Mail\Restaurant\ReservationStatusMail($reservation, $status);
                            break;
                        case 'administrator':
                            $mail = new \App\Mail\Administrator\ReservationStatusMail($reservation, $status);
                            break;
                        default:
                            $mail = new \App\Mail\Client\ReservationStatusMail($reservation, $status);
                            break;
                    }

                    if ($sync) {
                        Mail::to($qa)->send($mail);
                    } else {
                        Mail::to($qa)->queue($mail);
                    }
                } catch (\Throwable $e) {
                    Log::error('EmailTestController::sendAllStatuses failed', ['error' => $e->getMessage()]);
                }
            }
        }

        return response()->json(['ok' => true, 'message' => 'Queued test mails for all statuses to ' . $qa]);
    }
}
