<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\User as UserResource;

class Settle extends Resource
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
          'payed' => $this->payed,
          'amount' => $this->amount,
          'userOwns' => new UserResource($this->resource->owningUser()->first()),
          'userLent' => new UserResource($this->resource->leaningUser()->first()),
          'createdAt' => is_null($this->created_at) ? $this->created_at : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? $this->updated_at : $this->updated_at->toDateTimeString(),
        ];
    }
}
