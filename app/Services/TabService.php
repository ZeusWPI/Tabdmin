<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TabService
{

    public function __construct($baseUri, $accessToken)
    {
        $this->baseUri = $baseUri;
        $this->accessToken = $accessToken;
    }

    public function createTransaction($debtor, $creditor, $amount, $message = 'Tab opladen'): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token token=' . $this->accessToken,
        ])->post($this->baseUri . '/api/v1/transactions', [
            'transaction' => [
                'debtor' => $debtor,
                'creditor' => $creditor,
                'cents' => round(floatval($amount) * 100),
                'message' => $message,
            ],
        ]);

        if (!$response->successful()) {
            try {
                Log::error('Could not create transaction. Response from Tab:');
                Log::error($response->json());
            } catch (\Exception) {
                Log::error('Could not create transaction. Tab request failed.');
            }

        }

        return $response->successful();
    }
}
