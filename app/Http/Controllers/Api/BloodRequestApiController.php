<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use Illuminate\Http\Request;

class BloodRequestApiController extends Controller
{
    public function index()
    {
        $bloodRequests = BloodRequest::with(['division', 'district', 'upazilla'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => true,
            'data' => $bloodRequests
        ]);
    }

    public function show(BloodRequest $bloodRequest)
    {
        $bloodRequest->load([
            'division',
            'district',
            'upazilla',
            'cityCorporation',
            'cityArea'
        ]);

        return response()->json(
            [
                'status' => true,
                'data' => $bloodRequest
            ]
        );
    }
}
