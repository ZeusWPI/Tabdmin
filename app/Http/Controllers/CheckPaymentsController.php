<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\NordigenService;
use App\Services\TabService;
use Carbon\Carbon;
use Nordigen\NordigenPHP\Enums\AccountProcessingStatus;

class CheckPaymentsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(NordigenService $nordigenService, TabService $tabService)
    {
        $accounts = $nordigenService->getListOfAccounts();

        foreach ($accounts as $account) {
            $metadata = $account->getAccountMetaData();
            if ($metadata["status"] === AccountProcessingStatus::READY) {
                $transactions = $account->getAccountTransactions()["transactions"]["booked"];
                foreach ($transactions as $bankTransaction) {
                    // Check if the transaction is one with TAB followed by username.
                    if (array_key_exists("additionalInformation", $bankTransaction) && preg_match('/tab\s+(\w+)/', strtolower($bankTransaction["additionalInformation"]), $matches)) {
                        $username = $matches[0];
                        // Check if the transaction is already in the database.
                        // If it is, we don't want to process it again.
                        if (!Transaction::where('transaction_id', $bankTransaction["transactionId"])->exists()) {
                            // If not, check if there exists a manually added transaction that matches the username. We don't want to process the transaction twice.
                            $transaction = Transaction::where('debtor', $username)
                                ->where('creditor', 'zeus')
                                ->where('transaction_id', null)
                                ->where('amount', $bankTransaction["transactionAmount"]["amount"])
                                ->where('cash', false)
                                ->first();

                            if ($transaction) {
                                // The transaction was manually added, so we just want to update the transaction to signal that the bank transfer was received.
                                $transaction->update([
                                    'transaction_id' => $bankTransaction["transactionId"],
                                    'transaction_debtor' => $bankTransaction["debtorName"],
                                    'transaction_date' => $bankTransaction["bookingDate"],
                                    'currency' => $bankTransaction["transactionAmount"]["currency"],
                                ]);
                            } else {
                                // The transaction was not manually added, so we want to create a new transaction and process it to Tab.
                                $successfullyProcessedToTab = $tabService->createTransaction('zeus', $username, $bankTransaction["transactionAmount"]["amount"]);

                                // Create the transaction.
                                Transaction::create([
                                    'transaction_id' => $bankTransaction["transactionId"],
                                    'transaction_debtor' => $bankTransaction["debtorName"],
                                    'transaction_date' => $bankTransaction["bookingDate"],
                                    'amount' => $bankTransaction["transactionAmount"]["amount"],
                                    'currency' => $bankTransaction["transactionAmount"]["currency"],
                                    'cash' => false,
                                    'debtor' => 'zeus',
                                    'creditor' => $username,
                                    'issuer' => 'Tabdmin',
                                    'executed' => $successfullyProcessedToTab ? Carbon::now() : null,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
