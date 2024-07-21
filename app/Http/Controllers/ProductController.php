<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $product = Product::all();
        
        return response()->json(['data'=>new ProductResource($product),'message'=>'Dataretrive successfully!']);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'detail' => 'required'
        ]);
        
        $product = Product::create($request->all());

        return response()->json(['data'=>new ProductResource($product), 'message'=>'Product Created Successfully']);
    }

    // public function show($id='1') {
    //     $product = Product::find($id);
    //     if(!$product){
    //         return response()->json(['error'=>'Data Not Found'],404);
    //     }
    //     return response()->json(['data'=>new ProductResource($product), 'message'=>'Product Fetched!'], 200);
    // }
}
