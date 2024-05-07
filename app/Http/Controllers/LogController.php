<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
     public function store(Request $request)
    {
        try {
            $log = Log::create([
                'log_name' => $request->log_name,
                'description' => $request->description,
                'subject_type' => $request->subject_type,
                'subject_id' => $request->subject_id,
                'causer_type' => $request->causer_type,
                'properties' => $request->properties,
                'event' => $request->event,
                'batch_uuid' => $request->batch_uuid,
                ]);
            return response()->json(['success' => true, 'message' => 'Log created successfully.', 'data' => $log], 201);
        } catch (\Exception $e) {
            return $e;
        }   
    }
}