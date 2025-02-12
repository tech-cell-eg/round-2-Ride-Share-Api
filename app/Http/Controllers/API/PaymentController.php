<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentProcessRequest;
use App\Models\User;
use App\Notifications\PaymentSuccessful;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{

    use ApiResponse;
    protected string $baseUrl;
    protected array $headers;
    protected $apiKey;
    protected $integrationsId;

    public function __construct() {
        $this->baseUrl = env("STRIPE_BASE_URL");
        $this->apiKey = env("STRIPE_SECRET_KEY");
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'authorization' => 'Bearer ' . $this->apiKey,
        ];
    }

    protected function buildRequest ($method, $endPoint, $data = null, $type = 'json') {
        try {
            $response = Http::withHeaders($this->headers)->send($method, $this->baseUrl . $endPoint, [
                $type => $data
            ]);
            return $this->successResponse($response->json());
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    protected function formatData($request) {
        $data = $request->validated();
        return [
            "success_url" => $request->getSchemeAndHttpHost() . '/api/payment/callback?session_id={CHECKOUT_SESSION_ID}',
            "line_items" => [
                [
                    "price_data" => [
                        "unit_amount" => $data["amount_cents"] * 100,
                        "currency" => $data["currency"],
                        "product_data" => [
                            "name" => "ride success",
                        ]
                    ],
                    "quantity" => 1,
                ]
            ],
            "mode" => "payment",
            "metadata" => [
                "user_id" => Auth::id(),
            ]
        ];
    }

    public function sendPayment(PaymentProcessRequest $request) {
        $data = $this->formatData($request);
        $response = $this->buildRequest('POST', '/v1/checkout/sessions', $data, 'form_params');
        $responseData = $response->getData(true);
        if ($responseData['success']) {
            return $this->successResponse([
                'url' => $responseData['data']['url']
            ]);
        }
        return $this->errorResponse($responseData['message']);
    }

    public function callback(Request $request) {
        try {

            $sessionId = $request->get("session_id");
            $response = $this->buildRequest('POST', '/v1/checkout/sessions/' . $sessionId);
            $responseData = $response->getData(true);
            $user = User::findOrFail($responseData['data']['metadata']['user_id']);
            $note = new NotificationController();
            $note->store( $user, new \App\Notifications\PaymentSuccessful($responseData['data']['amount_total']) );
            if($responseData['success']&& $responseData['data']['payment_status']==='paid') {
                return redirect()->route('payment.success');
            }
            return redirect()->route('payment.failed');
            } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function successPayment() {
        $title = "Payment Success";
        return view('payment.success', compact('title'));
    }

    public function cancelPayment() {
        $title = "Payment Cancel";
        return view('payment.cancel', compact('title'));
    }

}
