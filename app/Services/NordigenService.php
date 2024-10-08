<?php

namespace App\Services;

use Nordigen\NordigenPHP\API\NordigenClient;
use Nordigen\NordigenPHP\DTO\Nordigen\SessionDTO;


class NordigenService
{

    private NordigenClient $client;

    public function __construct(string $secretId, string $secretKey)
    {
        $this->secretId = $secretId;
        $this->secretKey = $secretKey;
        $this->client = new NordigenClient($secretId, $secretKey);
        $this->client->createAccessToken();
    }


    public function getListOfInstitutions(string $country)
    {
        return $this->client->institution->getInstitutionsByCountry($country);
    }

    public function getSessionData(string $redirectUri, string $institutionId, int $maxHistoricalDays = 90): array
    {
        return $this->client->initSession($institutionId, $redirectUri, $maxHistoricalDays);
    }

    public function getListOfRequisitions(): array
    {
        return $this->client->requisition->getRequisitions()["results"];
    }

    public function getListOfAccounts(): array
    {
        $requisitions = $this->getListOfRequisitions();
        $accounts = [];

        foreach ($requisitions as $requisition) {
            foreach ($this->getListOfAccountsForRequisition($requisition["id"]) as $accountArray) {
                $accounts[] = $this->client->account($accountArray);
            }
        }
        return $accounts;
    }

    public function getListOfAccountsMetadata(): array
    {
        $accounts = $this->getListOfAccounts();
        $accountMetadata = [];

        foreach ($accounts as $account) {
            $accountMetadata[] = $account->getAccountMetaData();
        }
        return $accountMetadata;
    }

    private function getListOfAccountsForRequisition(string $requisitionId): array
    {
        return $this->client->requisition->getRequisition($requisitionId)["accounts"];
    }

    public function getAccountData(string $requisitionId): array
    {
        $accountArray = $this->getListOfAccountsForRequisition($requisitionId);
        $accountData = [];

        foreach ($accountArray as $id) {
            $account = $this->client->account($id);
            $accountData[] = [
                "metaData" => $account->getAccountMetaData(),
                "details" => $account->getAccountDetails(),
                "balances" => $account->getAccountBalances(),
                "transactions" => $account->getAccountTransactions()
            ];
        }
        return $accountData;
    }

    public function deleteAccount(string $accountId): bool
    {
        // Get requisition id from the account id.
        $requisitions = $this->getListOfRequisitions();
        $requisitionId = null;

        foreach ($requisitions as $requisition) {
            if (in_array($accountId, $this->getListOfAccountsForRequisition($requisition["id"]))) {
                $requisitionId = $requisition["id"];
                break;
            }
        }

        if (!$requisitionId) {
            return false;
        }

        $this->client->requisition->deleteRequisition($requisitionId);
        return true;
    }

}
