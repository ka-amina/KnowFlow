<?php

namespace App\Http\Controllers\V3;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Course;
use App\Models\Enrollement;
use App\Models\User;
use Exception;
use Stripe\Webhook;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Termwind\Components\Dd;

class PaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);
        $course = Course::findOrFail($request->course_id);
        // $enrollement = Enrollement::where('course_id', $request->course_id)
        //     ->where('user_id', Auth::user()->id)->first();
        // dd($enrollement->id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'MAD',
                        'product_data' => [
                            'name' => $course->title,
                        ],
                        'unit_amount' => $course->price * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'metadata' => [
                    'course_id' => $course->id,
                    'user_id' => Auth::id()
                ]
            ]);

            Payment::create([
                'enrollement_id' => null,
                'payment_type' => 'card',
                'status' => 'pending',
                'amount' => $course->price,
                'transaction_id' => $session->id
            ]);

            return response()->json([
                'checkout_url' => $session->url,
                'session_id' => $session->id
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Payment session creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function handleSuccessfulPayment(Request $request)
    {
        $request->validate([
            'session_id' => 'required',
            'course_id' => 'required|exists:courses,id'
        ]);

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::retrieve($request->session_id);

            if ($session->payment_status !== 'paid') {
                return response()->json([
                    'message' => 'Payment not completed'
                ], 400);
            }

            $course = Course::findOrFail($request->course_id);

            $enrollment = Enrollement::create([
                'course_id' => $course->id,
                'user_id' => Auth::id(),
                'status' => 'accepted',
                'enrolled_at' => now()
            ]);

            $payment = Payment::where('transaction_id', $session->id)->first();
            if ($payment) {
                $payment->update([
                    'enrollement_id' => $enrollment->id,
                    'status' => 'successful'
                ]);
            }

            return response()->json([
                'message' => 'Enrollment successful',
                'enrollment' => $enrollment
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Payment processing failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
