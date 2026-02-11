<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'parentName' => $this->parentName,
            'parentLastName' => $this->parentLastName,
            'parentEmail' => $this->parentEmail,
            'parentBirthday' => $this->parentBirthday,
            'phone' => $this->phone,
            'children' => ChildrenResource::collection($this->children),
        ];
    }
}
