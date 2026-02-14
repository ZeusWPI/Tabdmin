<?php

namespace App\Http\Controllers;

use App\Models\IbanUsername;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IbanUsernameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ibanUsernames = IbanUsername::all();

        return view('dashboard.ibanUsernames', [
            'ibanUsernames' => $ibanUsernames,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'iban' => 'required|string|max:255',
            'username' => 'required|string|max:255',
        ]);

        $ibanUsername = IbanUsername::create([
            'iban' => $request->iban,
            'username' => $request->username,
        ]);

        return response()->json([
            'status_code' => Response::HTTP_CREATED,
            'iban_username' => $ibanUsername,
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IbanUsername $ibanUsername)
    {
        $ibanUsername->delete();

        return response()->json([
            'status_code' => Response::HTTP_OK,
            'message' => 'IBAN-Username pair deleted successfully.',
        ], Response::HTTP_OK);
    }
}
