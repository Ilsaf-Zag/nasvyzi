<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'text'=>$this->text,
            'category'=>$this->category,
            'customer_name'=>$this->customer_name,
            'customer_link'=>$this->customer_link,
        ];
    }
}
