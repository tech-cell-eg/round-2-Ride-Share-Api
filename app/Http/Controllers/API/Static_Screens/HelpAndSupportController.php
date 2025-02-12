<?php

namespace App\Http\Controllers\API\Static_Screens;

use App\Http\Controllers\Controller;
use App\Models\HelpAndSupportPage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class HelpAndSupportController extends Controller
{
    use ApiResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $content = HelpAndSupportPage::first();

            if (!$content) {
                return $this->errorResponse('This page content not found.', 404);
            }
            return $this->successResponse($content->toArray());

        } catch (\Exception $exception) {
            Log::error('Error about page: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }
}
