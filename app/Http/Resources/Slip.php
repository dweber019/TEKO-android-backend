<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\User as UserResource;

class Slip extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'date' => is_null($this->date) ? $this->date : $this->date->toDateTimeString(),
          'price' => $this->price,
          'payed' => $this->payed,
          'settled' => $this->settled,
          'user' => new UserResource($this->whenLoaded('user')),
          'createdAt' => is_null($this->created_at) ? $this->created_at : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? $this->updated_at : $this->updated_at->toDateTimeString(),
        ];
    }
}
