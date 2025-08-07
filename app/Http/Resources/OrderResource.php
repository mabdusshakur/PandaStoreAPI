<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->resource->id,
            "user_id" => $this->resource->user_id,
            "quantity" => $this->resource->quantity,
            "total_price" => $this->resource->total_price,
            "product" => new ProductResource($this->whenLoaded('product')),
            "delivery_status" => $this->resource->delivery_status,
            "payment_status" => $this->resource->payment_status,
            "created_at" => $this->resource->created_at,
            "updated_at" => $this->resource->updated_at,
        ];
    }
}
