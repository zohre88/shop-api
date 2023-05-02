<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\BrandResource;
use Illuminate\Support\Facades\Validator;

class BrandController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands=Brand::all();
        
        return $this->successResponse('200',BrandResource::collection($brands),'Get all brands ok');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Brand $brand)
    {
        $validate=Validator::make($request->all(),[
                'title'=> 'required|string|unique:brands,title',
                'image'=>'required|image'
        ]);

        if($validate->fails()){
            return $this->errorResponse(422,$validate->messages());
        }

        $brand->newBrand($request);

        $dataResponse=$brand->orderBy('id','desc')->first();

        return $this->successResponse('201',new BrandResource($dataResponse),'brand created successfully');

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return $this->successResponse('200',new BrandResource($brand),'show custom brand successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
       
       $brandUnique=Brand::query()
                    ->where('title',$request->title)
                    ->where('id','!=',$brand->id)->exists();
       
       if($brandUnique)
       {
        return $this->errorResponse(422,'title has already been taken');
       }
       
        $validate=Validator::make($request->all(),[
            'title'=> 'required|string',
            'image'=>'image'
        ]);

        if($validate->fails()){
            return $this->errorResponse('401',$validate->messages());
        }
        
        $brand->updateBrand($request);

        return $this->successResponse('200',new BrandResource($brand),'brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return $this->successResponse('200',new BrandResource($brand),'brand deleted successfully');
    }

    public function getProducts(Brand $brand)
    {
        return $this->successResponse(200,new BrandResource($brand->load('products')),'get products of this brand');
    }
}
