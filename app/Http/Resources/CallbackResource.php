<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CallbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'quote' => $this->quote,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'enquiry_date' => $this->enquiry_date,
            'booking_date' => $this->booking_date,
            'callback_date' => $this->callback_date,
            'job_status' => $this->job_status,
            'callback_status' => $this->callback_status,
            'pick_up' => $this->pick_up,
            'drop_off' => $this->drop_off,
            'via' => $this->via,
            'total' => $this->total,
            'discount' => $this->discount,
        ];
    }
}
