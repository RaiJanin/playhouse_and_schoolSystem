<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class M06Resource extends JsonResource
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
            'd_code' => $this->d_code,
            'd_name' => $this->d_name,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'mi' => $this->mi,
            'birthday' => Carbon::parse($this->birthday)->format('Y-m-d') ?? null,
            'mobileno' => $this->mobileno,
            'email' => $this->email,
            'isparent' => $this->isparent,
            'isguardian' => $this->isguardian,
            'guardianauthorized' => $this->guardianauthorized,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'guardians' => GuardianResource::collection($this->guardians),
            'children' => ChildrenResource::collection($this->children),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at, 
        ];
    }
}
