<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function show($id):View
    {
        $user = User::findOrFail($id);
        return view('users.show', ['user'=>$user]);

    }

    public function fetchTransactions(Request $request, $id): JsonResponse
    {
        $query = Transaction::where('user_id', $id)->with('location');

        // Check if a date is provided and filter by it
        if ($request->has('date')) {
            $query->whereDate('transaction_date', $request->date);
        }
        $totalTransactions = [];
        if ($request->has('location')) {
            $searchValue = $request->location;
            $query->whereHas('location', function ($query) use ($searchValue) {
                // Search in location fields
                $query->where('name', 'like', "%$searchValue%");
            });
            $totalTransactions = $query
            ->when(isset($request['type']), function ($query) use ($request) {
                return $query->whereDate('transaction_date', $request->date);
            })
            ->whereHas('location', function ($query) use ($searchValue) {
                // Search in location fields
                $query->where('name', 'like', "%$searchValue%");
            })
            ->sum('amount');
        }

        $transactions = $query->paginate(10); // Adjust the per-page count as needed

        return response()->json(['transactions'=>$transactions,'totalTransactions'=>$totalTransactions]);
    }

}

