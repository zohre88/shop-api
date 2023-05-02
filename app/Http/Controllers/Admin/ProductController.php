<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\ProductResource;
use App\Http\Requests\Admin\ProductCreateRequest;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('show-product')){
            return $this->errorResponse(403, 'not permission user with gates');
        }
        $products=Product::paginate(10);

        return $this->successResponse(200,[
            
            'products' => ProductResource::collection($products),
            'links' => ProductResource::collection($products)->response()->getData()->links,
            'meta' => ProductResource::collection($products)->response()->getData()->meta
        ],'get products');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCreateRequest $request, Product $product)
    {
        
        $product->newProduct($request);

        $dataResponse=$product->orderBy('id','desc')->first();

        return $this->successResponse(200, new ProductResource($dataResponse),'product created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->successResponse(200, new ProductResource($product), 'show producId');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validate=Validator::make($request->all(),[
            'category_id'=>'required|exists:categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'name' => 'required|string',
            'slug' =>  'required',
            'image' => 'image|mimes:png,jpg,jpeg,svg',
            'description' => 'required|string' ,
            'price' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        if($validate->fails()){
           return $this->errorResponse(422,$validate->Messages()); 
        }

        $slugUnique=Product::query()->where('slug',$request->slug)
        ->where('id','!=',$product->id)->exists();

        if($slugUnique)
        {
            return $this->errorResponse(422,'slug has already been taken ');
        }

        $product->updateProduct($request);

        return $this->successResponse(200, new ProductResource($product),$product->name.' updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->successResponse(200, new ProductResource($product),$product->name.' deleted successfully');
    }
}
