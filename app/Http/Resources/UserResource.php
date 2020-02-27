<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = parent::toArray($request);

        $array['meta'] = $this->meta->mapWithKeys(function ($item) {
            return [ $item['chiave'] => $item["valore"] ];
        });

        return $array;
    }
}
