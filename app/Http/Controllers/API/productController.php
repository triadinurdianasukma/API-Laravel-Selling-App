<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;

class productController extends Controller
{
    public function all(Request $request)
    {
        //Declare parameter input URL di API
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('id');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        //get individual data (read data)
        if ($id){
            $product = Product::with(['category', 'galleries'])->find($id);

            if($product){
                return ResponseFormatter::success(
                    $product,
                    'Data Produk Berhasil di ambil'    
                );
            }
            else {
                return ResponseFormatter::error(
                    null,
                    'Data Produk tidak ada',
                    404    
                );
            }
        }

        //Get All Data
        $product = Product::with(['category', 'galleries']);
        
        //Filtering section
        //Mencari produk dengan nama tertentu
        if($name){
            $product->where('name', 'like', '%'.  $name . '%');
        }

        if($description){
            $product->where('description', 'like', '%'.  $description . '%');
        }

        if($tags){
            $product->where('tags', 'like', '%'.  $tags . '%');
        }

        if($price_from){
            $product->where('price', '>=', $price_from);
        }

        if($price_to){
            $product->where('price', '<=', $price_to);
        }

        if($categories){
            $product->where('categories', $categories);
        }

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data Produk Berhasil di ambil'    
        );

    }
}
