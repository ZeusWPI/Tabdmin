<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TabService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::all();

        return view('dashboard.transactions', [
            'transactions' => $transactions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TabService $tabService)
    {
        $request->validate([
            'amount' => 'required|decimal:0,2',
            'cash' => 'required|boolean',
            'debtor' => 'required|string',
            'creditor' => 'required|string',
            'message' => 'required|string',
        ]);

        $successfullyProcessedToTab = $tabService->createTransaction($request->input('debtor'), $request->input('creditor'), $request->input('amount'), $request->input('message'));

        if (!$successfullyProcessedToTab) {
            return response()->json([
                'status_code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Failed to process transaction to Tab. Check username and try again.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $transaction = Transaction::create([
            'amount' => $request->input('amount'),
            'cash' => $request->input('cash'),
            'debtor' => $request->input('debtor'),
            'creditor' => $request->input('creditor'),
            'issuer' => Auth::user()->name,
            'executed' => Carbon::now(),
        ]);

        return response()->json([
            'status_code' => Response::HTTP_CREATED,
            'transaction' => $transaction
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
