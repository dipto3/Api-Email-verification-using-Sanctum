<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{

    public function index(){
        $products = Product::with('category')->get();
        $categories = Category::with('products')->get();

        return response()->json(['Status'=>'Success!','Products'=>$products]);
    }

    public function search($name){

        return Product::where('name',$name)->get();

    }

}
