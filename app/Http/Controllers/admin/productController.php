<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Cate;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Can;

class productController extends Controller
{
    public function create()
    {
        $category = Cate::orderBy('name', 'ASC')->get();
        $brand = Brand::orderBy('name', 'ASC')->get();
        return view('admin.product.create', ['category' => $category, 'brands' => $brand]);
    }

    public function store(Request $req)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:product',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];
        if (!empty($req['track_qty']) && $req['track_qty'] == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($req->all(), $rules);
        if ($validator->passes()) {
            $product = new Product();
            $product->title = $req->title;
            $product->slug = $req->slug;
            $product->description = $req->description;
            $product->price = $req->price;
            $product->compare_price = $req->compare_price;
            $product->category_id = $req->category;
            $product->sub_cate_id = $req->sub_category;
            $product->brand_id = $req->brand;
            $product->isfeature = $req->is_featured;
            $product->sku = $req->sku;
            $product->barcode = $req->barcode;
            $product->trackqty = $req->track_qty;
            $product->qty = $req->qty;
            $product->save();
            session()->flash('success', 'Successfully product created');
            return response()->json([
                'status' => true,
                'message' => 'validation done'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function index(){
        $product = Product::select(
            'product.*',
            'cat.name as categoryName',
            'sub_cate.name as subCategoryName',
            'brand.name as brandName'
        )
        ->leftJoin('cat', 'cat.id', '=', 'product.category_id') // Join with categories
        ->leftJoin('sub_cate', 'sub_cate.id', '=', 'product.sub_cate_id') // Join with sub_categories
        ->leftJoin('brand', 'brand.id', '=', 'product.brand_id') // Join with brands
        ->latest('product.id') // Order by products.id in descending order
        ->paginate(10);
        return view('admin.product.list',['product'=>$product]);
    }

    public function subCategory(Request $req)
    {
        if (!empty($req->category)) {
            $subcategory = Subcategory::where('category_id', $req->category)->orderBy('name', 'ASC')->get();
            return response()->json([
                'status' => true,
                'subcategory' => $subcategory
            ]);
        } else {
            return response()->json([
                'status' => false,
                'subcategory' => ''
            ]);
        }
    }
}
