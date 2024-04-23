<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Callback;
use Illuminate\Support\Facades\Validator;

class CallbackController extends Controller
{
    public function index()
    {
        try {
            $callbacks = Callback::all();
            return response()->json(['success' => true, 'callbacks' => $callbacks], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch Callback.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $callback = Callback::with('customer', 'activity')->findOrFail($id);//->with('customer');
            return response()->json(['success' => true, 'data' => $callback], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Callback not found.'], 404);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote' => 'required',
            'enquiry_date' => 'required|date_format:Y-m-d',
            'booking_date' => 'required|date_format:Y-m-d',
            'job_status' => 'required',
            'customer_id' => 'required',
            'pick_up' => 'required',
            'drop_off' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        // Attempt to create the callback
        try {
            $callback = Callback::create($request->all());
            return response()->json(['success' => true, 'message' => 'Callback created successfully.', 'data' => $callback], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create Callback.'], 500);
        }
    }
}
