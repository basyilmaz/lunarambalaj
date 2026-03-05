<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrackingEventRequest;
use App\Support\TrackingEventLogger;
use Illuminate\Http\JsonResponse;

class TrackingEventController extends Controller
{
    public function __construct(protected TrackingEventLogger $trackingEventLogger)
    {
    }

    public function store(StoreTrackingEventRequest $request): JsonResponse
    {
        $payload = (array) $request->input('payload', []);

        if ($request->filled('page_path')) {
            $payload['page_path'] = (string) $request->input('page_path');
        }

        $event = $this->trackingEventLogger->log(
            $request,
            (string) $request->input('event_key'),
            $payload,
            null,
            $request->filled('page_path') ? (string) $request->input('page_path') : null
        );

        return response()->json([
            'ok' => $event !== null,
        ]);
    }
}
