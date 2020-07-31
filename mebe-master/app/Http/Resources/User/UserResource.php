<?php

namespace App\Http\Resources\User;

use App\Model\Children;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Children\ChildrenResource;

class UserResource extends JsonResource
{
    use FormatResponse;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $babyInfo = Children::where('parent_id', $this->getId())->orderBy('date_of_birth', 'DESC')->first();
        if (!$babyInfo) {
            $babyInfo = new \StdClass();
        } else {
            $babyInfo = new ChildrenResource($babyInfo);
        }
        return [
            'id' => $this->format($this->external_id,"integer"),
            'display_name' => $this->format($this->display_name),
            'avatar' => empty($this->avatar) ? "" : $this->avatar,
            'baby_info' => [$babyInfo]
        ];
    }
}
