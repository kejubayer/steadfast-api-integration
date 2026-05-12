<?php

declare(strict_types=1);

namespace Kejubayer\Steadfast\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Kejubayer\Steadfast\Models\SteadfastParcelStatus;

class SteadfastWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'notification_type' => ['required', 'string', Rule::in(['delivery_status', 'tracking_update'])],
            'consignment_id' => ['required'],
            'invoice' => ['nullable', 'string', 'max:255'],
            'cod_amount' => ['required_if:notification_type,delivery_status', 'nullable', 'numeric'],
            'status' => ['required_if:notification_type,delivery_status', 'nullable', 'string', 'max:255'],
            'delivery_charge' => ['required_if:notification_type,delivery_status', 'nullable', 'numeric'],
            'tracking_message' => ['nullable', 'string'],
            'updated_at' => ['nullable', 'date'],
        ]);

        $parcelStatus = SteadfastParcelStatus::create([
            'notification_type' => $payload['notification_type'],
            'consignment_id' => (string) $payload['consignment_id'],
            'invoice' => $payload['invoice'] ?? null,
            'cod_amount' => $payload['cod_amount'] ?? null,
            'status' => $payload['status'] ?? null,
            'delivery_charge' => $payload['delivery_charge'] ?? null,
            'tracking_message' => $payload['tracking_message'] ?? null,
            'provider_updated_at' => $payload['updated_at'] ?? null,
            'payload' => $request->all(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Parcel status stored successfully.',
            'data' => [
                'id' => $parcelStatus->id,
            ],
        ]);
    }
}
