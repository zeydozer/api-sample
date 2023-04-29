<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $source = parent::toArray($request);
        $source['created_at'] = $this->created_at->format('Y-m-d H:i:s');
        $source['updated_at'] = $this->updated_at->format('Y-m-d H:i:s');
        return $source;
    }
}
