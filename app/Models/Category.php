<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded=[];

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function children(){
        return $this->hasMany(Category::class,'parent_id');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function newCategory(Request $request)
    {
       $this->query()->create([
            'title' => $request->title,
            'parent_id' => $request->parent_id
        ]);
    }

    public function updateCategory(Request $request){
        $this->update([
            'title' => $request->title,
            'parent_id' => $request->parent_id
        ]);
    }
}
