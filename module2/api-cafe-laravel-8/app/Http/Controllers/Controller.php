<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getValidationErrorResponse(Validator $validator) {
        return response()->json([
            'error' => [
                'code' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]

        ], 422);
    }
}
