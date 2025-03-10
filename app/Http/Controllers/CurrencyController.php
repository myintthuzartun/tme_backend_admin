<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    // Show a list of all currencies
    public function index()
    {
        $currencies = Currency::all();  // Retrieve all records from the currency table
        return response()->json($currencies);
    }

    // Show the form for creating a new currency record
    public function create()
    {
        return response()->json(['message' => 'Create a new currency record']);
    }

    // Store a new currency record in the database
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'en_currency' => 'required|numeric',
            'myan_currency' => 'required|numeric',
            'thai_exchange_rate' => 'required|numeric',
        ]);

        // Create a new currency record
        $currency = Currency::create([
            'en_currency' => $request->en_currency,
            'myan_currency' => $request->myan_currency,
            'thai_exchange_rate' => $request->thai_exchange_rate,
        ]);

        return response()->json($currency, 201);  // Return the newly created record
    }

    // Display the specified currency record
    public function show($id)
    {
        $currency = Currency::find($id);  // Find the record by ID

        if (!$currency) {
            return response()->json(['message' => 'Currency not found'], 404);
        }

        return response()->json($currency);
    }

    // Show the form for editing the specified currency record
    public function edit($id)
    {
        $currency = Currency::find($id);

        if (!$currency) {
            return response()->json(['message' => 'Currency not found'], 404);
        }

        return response()->json($currency);
    }

    // Update the specified currency record in the database
    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $request->validate([
            'en_currency' => 'required|numeric',
            'myan_currency' => 'required|numeric',
            'thai_exchange_rate' => 'required|numeric',
        ]);

        $currency = Currency::find($id);

        if (!$currency) {
            return response()->json(['message' => 'Currency not found'], 404);
        }

        // Update the currency record
        $currency->update([
            'en_currency' => $request->en_currency,
            'myan_currency' => $request->myan_currency,
            'thai_exchange_rate' => $request->thai_exchange_rate,
        ]);

        return response()->json($currency);
    }

    // Remove the specified currency record from the database
    public function destroy($id)
    {
        $currency = Currency::find($id);

        if (!$currency) {
            return response()->json(['message' => 'Currency not found'], 404);
        }

        $currency->delete();
        return response()->json(['message' => 'Currency record deleted']);
    }
}
