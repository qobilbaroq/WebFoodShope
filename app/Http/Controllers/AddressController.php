<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::with('user')->get();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'is_primary' => 'boolean',
            'user_id' => 'required|exists:users,id'
        ]);

        // If this address is primary, set other addresses for this user to not primary
        if ($request->is_primary) {
            Address::where('user_id', $request->user_id)
                    ->update(['is_primary' => false]);
        }

        $address = Address::create($request->all());
        return response()->json($address->load('user'), 201);
    }

    public function show(Address $address)
    {
        return response()->json($address->load('user'));
    }

    public function update(Request $request, Address $address)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'is_primary' => 'boolean',
            'user_id' => 'required|exists:users,id'
        ]);

        // If this address is being set as primary, set other addresses for this user to not primary
        if ($request->is_primary) {
            Address::where('user_id', $request->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_primary' => false]);
        }

        $address->update($request->all());
        return response()->json($address->load('user'));
    }

    public function destroy(Address $address)
    {
        $address->delete();
        return response()->json(null, 204);
    }

    public function getUserAddresses($userId)
    {
        $addresses = Address::where('user_id', $userId)->get();
        return response()->json($addresses);
    }
}