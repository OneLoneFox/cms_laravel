<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $default = parent::toArray($request);
        $overrides = [
            'payment_file' => $this->whenPivotLoaded('participant_post', function () {
                return asset('storage/'.$this->pivot->payment_file);
            }),
            'payment_verified' => $this->whenPivotLoaded('participant_post', function () {
                return (bool)$this->pivot->payment_verified;
            }),
        ];
        return array_merge($default, $overrides);
    }
}
