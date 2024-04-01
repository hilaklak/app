<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'paymentorable' => $this->paymentorable,
            'paymentable' => $this->paymentable,
            'resnumber' => $this->resnumber,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => $this->status,
            'payment_success_at' => $this->payment_success_at,
            // 'learnings' => CourseCollection($this->learningg),
            // 'posts_count' => $this->whenCounted('posts'),

            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
