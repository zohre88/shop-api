<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=[];



    //relationShips:
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function galleries(){
        return $this->hasMany(Gallery::class);
    }


    //endRelationShip:

    public function newProduct(Request $request)
    {

        $imagePath=Carbon::now()->microsecond.'.'.$request->image->extension();
        $request->image->storeAs('uploads/products',$imagePath,'public');
        $this->query()->create([
            'category_id' => $request->category_id, 
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'image' => $imagePath,
            'price' =>$request->price,
            'quantity' => $request->quantity,
        ]);
    }


    public function updateProduct(Request $request)
    {
        if($request->has('image')){
            $imagePath=Carbon::now()->microsecond.'.'.$request->image->extension();
            $request->image->storeAs('uploads/products',$imagePath,'public');
        }
        $this->update([
            'category_id' => $request->category_id, 
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'image' => $request->has('image')? $imagePath:$this->image,
            'price' =>$request->price,
            'quantity' => $request->quantity,
        ]);
    }
    public function newGallery(Request $request)
    {
        if($request->has('path')){
            foreach($request->path as $image){
                $imageGalleryPath=Carbon::now()->microsecond.'.'.$image->extension();
                $image->storeAs('uploads/galleries',$imageGalleryPath, 'public');

                $this->galleries()->create([
                    'product_id' => $this->id,
                    'path' => $imageGalleryPath,
                    'mime' => $image->getClientMimeType()
                ]);
            }
        }
    }

    public function deleteGallery(Gallery $gallery)
    {
        // File::delete('storage/uploads/galleries/'.$gallery->path);
        unlink(public_path('storage/uploads/galleries/'.$gallery->path));
        $gallery->delete();
    }

   
}
