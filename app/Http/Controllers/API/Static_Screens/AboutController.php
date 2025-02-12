<?php

namespace App\Http\Controllers\API\Static_Screens;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AboutController extends Controller
{

    use ApiResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        try {
            $content = AboutPage::all()->first()->toArray();
            return $this->successResponse($content);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
