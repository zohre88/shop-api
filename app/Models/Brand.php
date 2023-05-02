<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=[];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function newBrand(Request $request)
    
    {
        $imagePath=Carbon::now()->microsecond.'.'.$request->image->extension();

        $request->image->storeAs('uploads/brands', $imagePath, 'public');

        $this->query()->create([
            'title' => $request->title,
            'image' => $imagePath
        ]);


    }

    public function updateBrand(Request $request)
    
    {
        if($request->has('image'))
        {
            $imagePath=Carbon::now()->microsecond.'.'.$request->image->extension();
            $request->image->storeAs('uploads/brands', $imagePath, 'public');
        }
       
        $this->update([
            'title' => $request->title,
            'image' => $request->has('image')? $imagePath:$this->image
        ]);
    }
}
