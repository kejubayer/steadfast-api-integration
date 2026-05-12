<?php

declare(strict_types=1);

namespace Kejubayer\Steadfast\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use RuntimeException;
use UnexpectedValueException;

class SteadfastService
{
    protected Client $client;
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('steadfast.base_url'), '/').'/';

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => (float) config('steadfast.timeout', 30),
            'headers' => [
                'Api-Key' => config('steadfast.api_key'),
                'Secret-Key' => config('steadfast.secret_key'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Create Parcel
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws RuntimeException
     * @throws UnexpectedValueException
     */
    public function createParcel(array $data): array
    {
        try {
            $response = $this->client->post('create_order', [
                'json' => $data,
            ]);
        } catch (ClientException $exception) {
            $this->throwInactiveAccountException($exception);

            throw $exception;
        }

        return $this->decodeResponse((string) $response->getBody());
    }

    /**
     * Bulk Create Parcel
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnexpectedValueException
     */
    public function bulkCreate(array $data): array
    {
        $response = $this->client->post('create_order/bulk-order', [
            'json' => [
                'data' => json_encode($data, JSON_THROW_ON_ERROR),
            ],
        ]);

        return $this->decodeResponse((string) $response->getBody());
    }

    /**
     * Track Parcel by consignment ID.
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnexpectedValueException
     */
    public function track(string $consignmentId): array
    {
        $response = $this->client->get('status_by_cid/'.rawurlencode($consignmentId));

        return $this->decodeResponse((string) $response->getBody());
    }

    /**
     * Track Parcel by invoice.
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnexpectedValueException
     */
    public function trackByInvoice(string $invoice): array
    {
        $response = $this->client->get('status_by_invoice/'.rawurlencode($invoice));

        return $this->decodeResponse((string) $response->getBody());
    }

    /**
     * Track Parcel by tracking code.
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnexpectedValueException
     */
    public function trackByTrackingCode(string $trackingCode): array
    {
        $response = $this->client->get('status_by_trackingcode/'.rawurlencode($trackingCode));

        return $this->decodeResponse((string) $response->getBody());
    }

    /**
     * Get Balance
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnexpectedValueException
     */
    public function getBalance(): array
    {
        $response = $this->client->get('get_balance');

        return $this->decodeResponse((string) $response->getBody());
    }

    /**
     * @throws JsonException
     * @throws UnexpectedValueException
     */
    protected function decodeResponse(string $body): array
    {
        $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($decoded)) {
            throw new UnexpectedValueException('Steadfast API response must be a JSON object or array.');
        }

        return $decoded;
    }

    /**
     * @throws RuntimeException
     */
    protected function throwInactiveAccountException(ClientException $exception): void
    {
        $response = $exception->getResponse();
        $body = (string) $response->getBody();

        if ($response->getStatusCode() === 401 && stripos($body, 'Account is not active') !== false) {
            throw new RuntimeException(
                'Your Steadfast account is not active. Please contact Steadfast authority to activate your account before creating parcels.',
                401,
                $exception
            );
        }
    }
}
