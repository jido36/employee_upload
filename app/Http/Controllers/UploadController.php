<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\File;

class UploadController extends Controller
{

    public function uploadEmployeeData(Request $request, UploadService $uploadService)
    {
        $request->validate([
            'employee_data' => [
                'required',
                File::types(['csv'])
            ],

        ]);

        $path = $request->employee_data->path();
        $upload = $uploadService->uploadEmployeeData($path);

        return response()->json(['message' => $upload['message']], $upload['status']);
    }
}
