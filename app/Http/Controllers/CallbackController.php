<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Callback;

class CallbackController extends Controller
{
    public function index()
    {
        try {
            $users = Callback::all();
            return response()->json(['success' => true, 'callbacks' => $users], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch Callback.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = Callback::findOrFail($id);
            return response()->json(['success' => true, 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Callback not found.'], 404);
        }
    }
}
