<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{

    //define properti
    public $status;
    public $message;
    public $resource;


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'     => $this->name,
            'detail'     => $this->detail,
            'due_date'     => $this->due_date,
            'status'     => $this->status,
            'user_id' => $this->user_id,
            'user' => $this->user->name
        ];
    }
}
