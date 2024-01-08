<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
                'euros' => (float)$amount,
                'message' => $message,
            ],
        ]);

        return $response->successful();
    }
}
