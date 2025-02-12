<?php

namespace App\Http\Controllers\API\Static_Screens;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AboutController extends Controller
{

    use ApiResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        try {
            $aboutPage = AboutPage::first();

            if (!$aboutPage) {
                return $this->errorResponse('About page content not found.', 404);
            }
            return $this->successResponse($aboutPage->toArray());

        } catch (\Exception $exception) {
            Log::error('Error about page: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }
}
