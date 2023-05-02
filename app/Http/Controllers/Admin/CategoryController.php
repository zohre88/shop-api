<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=Category::paginate(10);

        return $this->successResponse(200,[

            'categories' => CategoryResource::collection($categories),

            'link' => CategoryResource::collection($categories)->response()->getData()->links,

            'meta' => CategoryResource::collection($categories)->response()->getData()->meta


        ],'show all categories successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Category $category)
    {
        $validate=Validator::make($request->all(),[
            'title'=>'required|string|unique:categories,title',
            'parent_id' => 'integer|exists:categories,id'
        ]);

        if($validate->fails()){
            return $this->errorResponse(402,$validate->messages());
        }

        $category->newCategory($request);

        $dataResponse=$category->orderBy('id','desc')->first();

        return $this->successResponse(200,new CategoryResource($dataResponse) ,'Category created successfully');



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->successResponse(200,new CategoryResource($category),'Get-id-'.$category->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $categoryUnique=Category::query()
                        ->where('title',$request->title)
                        ->where('id','!=',$category->id)
                        ->exists();

        if($categoryUnique){
            return $this->errorResponse(422, 'title has already been taken');
        }

        $validate=Validator::make($request->all(),[
            'title'=> 'required|string',
            'parent_id' => 'nullable|integer'
        ]);

        if($validate->fails()){
            return $this->errorResponse('401',$validate->messages());
        }

        $category->updateCategory($request);

        return $this->successResponse(200, new CategoryResource($category), 'category updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->successResponse(200, new CategoryResource($category), 'category deleted');
    }


    public function parent(Category $category)
    
    {
        return $this->successResponse(200,new CategoryResource($category->load('parent')),'show parent');
    }


    public function children(Category $category)
    
    {
        return $this->successResponse(200,new CategoryResource($category->load('children')),'show children');
    }

    public function getProducts(Category $category)
    {
        return $this->successResponse(200, new CategoryResource($category->load('products')->load('children')),'get products of category');
    }
}
