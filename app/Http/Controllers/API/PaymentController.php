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

class PaymentController extends Controller
{

    use ApiResponse;
    protected string $baseUrl;
    protected array $headers;
    protected $apiKey;
    protected $integrationsId;

    public function __construct() {
        $this->baseUrl = env("PAYMOB_BASE_URL");
        $this->apiKey = env("PAYMOB_API_KEY");
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $this->integrationsId = [4900709, 4900706];
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

    protected function generateToken () {
        $response = $this->buildRequest('POST', '/auth/tokens', [
            'api_key' => $this->apiKey
        ]);
        return $response->getData(true)['data']['token'];
    }

    public function sendPayment(PaymentProcessRequest $request) {
        $this->headers['Authorization'] = 'Bearer ' . $this->generateToken();
        $data = $request->validated();
        $data['api_source'] = "INVOICE";
        $data['integrations'] = $this->integrationsId;
        $data['merchant_order_id'] = Auth::user()->id;
        $response = $this->buildRequest('POST', '/ecommerce/orders', $data);
        $responseData = $response->getData(true);
        if (isset($responseData['data']['message']) && $responseData['data']['message'] === 'duplicate') {
            return $this->errorResponse('Duplicate payment detected.');
        }
        if ($responseData['success']) {
            return $this->successResponse([
                'url' => $responseData['data']['url']
            ]);
        }
        return $this->errorResponse($responseData['message']);
    }

    public function callback(Request $request) {
        try {
            $response = $request->all();

            if (isset($response['success']) && $response['success'] === 'true') {
                if (!isset($response['merchant_order_id'], $response['amount_cents'])) {
                    return $this->errorResponse('Invalid response from Paymob callback.');
                }

                $userId = $response['merchant_order_id'];

                if (!$userId || !User::find($userId)) {
                    return $this->errorResponse('Invalid user or merchant order ID.');
                }

                $user = User::findOrFail($userId);

                $note = new NotificationController();
                $note->store($user, new \App\Notifications\PaymentSuccessful($response['amount_cents']));

                return redirect()->route('payment.success');
            }

            return redirect()->route('payment.cancel');
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
