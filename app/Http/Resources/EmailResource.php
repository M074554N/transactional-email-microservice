<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'type'              => $this->type,
            'subject'           => $this->subject,
            'body'              => $this->body,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'recipients_count'  => $this->recipients()->count(),
            'recipients'        => $this->recipients,
        ];
    }
}
