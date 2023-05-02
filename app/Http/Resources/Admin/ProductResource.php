<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description ,
            'category' => $this->category_id,
            'brand' => $this->brand_id,
            'image' => url(env('PATH_UPLOADED_FOR_IMAGES').$this->image),
            'price' => $this->price,
            'quantity' => $this->quantity,
            'galleries' => GalleryResource::collection($this->galleries)




        ];
    }
}
