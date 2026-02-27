<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ChildrenResource extends JsonResource
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
            'd_code_c' => $this->d_code_c,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'birthday' => Carbon::parse($this->birthday)->format('Y-m-d') ?? null,
            'age' => $this->age,
            'photo' => $this->photo,
            'phoneno' => $this->phoneno,
            'createdby' => $this->createdby,
            'updatedby' => $this->updatedby,
            'd_code' => $this->d_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
