<?php

namespace App\Http\Controllers;

use App\Models\Callback;
use App\Mail\CallbackMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\CallbackResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CallbackCollection;

class CallbackController extends Controller
{
    public function index(Request $request)
    {
        try{
            if (!$request->quote) {
                return CallbackResource::collection(Callback::all());
            }else{
                $callback = Callback::where('quote', $request->quote)->first();
                if ($callback) {
                return new CallbackResource($callback);
                }else{
                    return [];
                }
            }
        }
        catch (\Exception $e) {
            return $e;
        }
    }
       
    public function show($id)
    {
        try {
            return new CallbackResource(Callback::findOrFail($id));
        } catch (\Exception $e) {
            return [];
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote' => 'required',
            'enquiry_date' => 'required|date_format:Y-m-d',
            'booking_date' => 'required|date_format:Y-m-d',
            'job_status' => 'required',
            'customer_email' => 'required',
            'pick_up' => 'required',
            'drop_off' => 'required',
            'total' => 'required',
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
            return $e;
        }   
    }

    public function update(Request $request,$id) {
         $validator = Validator::make($request->all(), [
            'quote' => 'required',
            'enquiry_date' => 'required|date_format:Y-m-d',
            'booking_date' => 'required|date_format:Y-m-d',
            'job_status' => 'required',
            'customer_email' => 'required',
            'pick_up' => 'required',
            'drop_off' => 'required',
            'total' => 'required',
        ]);   
          // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

           // Attempt to create the callback
        try {
            $callback = Callback::where('id', $id)->first()->update($request->all());
            return response()->json(['success' => true, 'message' => 'Callback updated successfully.', 'data' => $callback], 201);
        } catch (\Exception $e) {
            return $e;
        }   
    }

    public function mail($id){
        $callback = Callback::findOrFail($id);
          Mail::to($callback->customer_email)->send(new CallbackMail($callback));
    }
}
