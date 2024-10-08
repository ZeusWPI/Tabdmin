<?php

namespace App\Http\Controllers;

use App\Mail\ReconnectBankMail;
use App\Services\NordigenService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Nordigen\NordigenPHP\Enums\AccountProcessingStatus;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(NordigenService $nordigenService)
    {
        $accounts = $nordigenService->getListOfAccountsMetadata();

        $possibleBanks = $nordigenService->getListOfInstitutions("BE");

        return view('dashboard.bankAccounts', [
            'banks' => $possibleBanks,
            'accounts' => $accounts
        ]);
    }

    /**
     * Show the form for connecting a new resource.
     */
    public function connect(NordigenService $nordigenService, $id)
    {
        $redirectUri = route('bankConnectCallback');
        $sessionData = $nordigenService->getSessionData($redirectUri, $id);

        session(['requisitionId' => $sessionData["requisition_id"]]);
        return redirect()->away($sessionData["link"]);
    }

    /**
     * Callback for the bank connection.
     */
    public function callback()
    {
        $requisitionId = request()->session()->get('requisitionId');
        if (!$requisitionId) {
            throw new Exception('Requisition id not found.');
        }

        // Return back to the bank accounts page.
        return redirect()->route('bankAccounts');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NordigenService $nordigenService, string $id)
    {
        if ($nordigenService->deleteAccount($id)) {
            return response()->json([
                'status_code' => Response::HTTP_OK,
                'message' => 'Account deleted successfully'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status_code' => Response::HTTP_NOT_FOUND,
                'message' => 'Account not found with id ' . $id
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Check if reminders need to be sent to reconnect the bank.
     */
    public function __invoke(NordigenService $nordigenService)
    {
        $email = env('RECONNECT_BANK_EMAIL');
        if (!$email) {
            return;
        }

        $accounts = $nordigenService->getListOfAccountsMetadata();

        $activeAccount = false;

        foreach ($accounts as $account) {
            if ($account["status"] === AccountProcessingStatus::READY) {
                $activeAccount = true;

                $created = Carbon::parse($account["created"]);
                // If the account was created 89 days ago, send a reminder.
                if ($created->diffInDays(Carbon::now()) === 89) {
                    // Send a reminder to reconnect the bank.
                    Mail::to($email)->send(new ReconnectBankMail());
                }
            }
        }

        if (!$activeAccount) {
            // Send a reminder to connect a bank account.
            Mail::to($email)->send(new ReconnectBankMail());
        }
    }
}
