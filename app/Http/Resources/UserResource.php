<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'family'=>$this->family,
            'mobile'=>$this->mobile,
            'national_code'=>$this->national_code,
             'otp'=>$this->otp,
             'thumbnail'=>$this->thumbnail,
             'username'=>$this->username,
             'password'=>$this->password,
             'status'=>$this->status,
             'remember_token'=>$this->remember_token,
             'created_at'=>$this->created_at,
             'updated_at'=>$this->updated_at

        ];
        
    }
}
