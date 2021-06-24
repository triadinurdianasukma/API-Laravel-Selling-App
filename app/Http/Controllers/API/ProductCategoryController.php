<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;

class ProductCategoryController extends Controller
{
    //
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('id');
        $show_product = $request->input('show_product');

        if ($id){
            $category = ProductCategory::with(['products'])->find($id);

            if($category){
                return ResponseFormatter::success(
                    $category,
                    'Data kategori Berhasil di ambil'    
                );
            }
            else {
                return ResponseFormatter::error(
                    null,
                    'Data kategori tidak ada',
                    404    
                );
            }
        }

          //Get All Data
          $category = ProductCategory::query();
        
        //Filtering section
          //Mencari produk dengan nama tertentu
          if($name){
                $category->where('name', 'like', '%'.  $name . '%');
          }

          if($show_product){
              $category->with('products');
          }

          return ResponseFormatter::success(
            $category->paginate($limit),
            'Data kategori Berhasil di ambil'    
        );
    }
}
