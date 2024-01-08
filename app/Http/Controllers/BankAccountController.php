<?php

namespace App\Http\Controllers;

use App\Services\NordigenService;
use Exception;

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
    public function destroy(string $id)
    {
        // DELETE request to on the requisition endpoint.
    }
}
