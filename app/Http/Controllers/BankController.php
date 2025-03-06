<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
class BankController extends Controller
{
    //
     //select business type
     public function bank(Request $req)
     {
         try {
             $banks = Bank::all();

             if ($banks->isEmpty()) {
                 return response()->json([
                     'message' => 'No banks found!',
                     'debug' => Bank::count() // Check count for debugging
                 ], 404);
             }

             return response()->json([
                 'message' => 'All banks retrieved successfully!',
                 'banks' => $banks
             ], 200);
         } catch (Exception $e) {
             return response()->json([
                 'message' => 'Something went wrong!',
                 'error' => $e->getMessage()
             ], 500);
         }
     }

}
