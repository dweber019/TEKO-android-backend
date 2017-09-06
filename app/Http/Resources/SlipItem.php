<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SlipItem extends Resource
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
          'name' => $this->name,
          'description' => $this->whenPivotLoaded('slips_items', function () {
              return $this->pivot->description;
          }),
          'createdAt' => is_null($this->created_at) ? $this->created_at : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? $this->updated_at : $this->updated_at->toDateTimeString(),
        ];
    }
}
