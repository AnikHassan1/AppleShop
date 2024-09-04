<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\customer_profile;
use App\Models\product;
use App\Models\product_cart;
use App\Models\product_detail;
use App\Models\product_review;
use App\Models\product_slider;
use App\Models\product_wishe;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function ListProductByCategory(Request $request): JsonResponse
    {
        $data = product::where('category_id', $request->id)->with('brand', 'category')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function ListProductByRemark(Request $request): JsonResponse
    {
        $data = Product::where('remark', $request->remark)->with('brand', 'category')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function ListProductByBrand(Request $request): JsonResponse
    {
        $data = Product::where('brand_id', $request->id)->with('brand', 'category')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function ListProductSlider(): JsonResponse
    {
        $data = product_slider::all();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function ProductDetailsById(Request $request): JsonResponse
    {

        $data = product_detail::where('product_id', $request->id)->with('product', 'product.brand', 'product.category')->get();

        return ResponseHelper::Out('success', $data, 200);
    }

    public function ListReviewByProduct(Request $request): JsonResponse
    {
        $data = product_review::where('product_id', $request->product_id)
            ->with(['profile' => function ($query) {
                $query->select('id', 'cus_name');
            }])
            ->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function createProductReview(Request $request): JsonResponse
    {
        $user_id = $request->header('id');
        $profile = customer_profile::where('user_id', $user_id)->first();

        if ($profile) {
            $request->merge(['customer_id' => $profile->id]);
            $data = product_review::updateOrCreate(
                ['customer_id' => $profile->id, 'product_id' => $request->input('product_id')],
                $request->input()
            );
            return ResponseHelper::Out('success', $data, 200);
        } else {
            return ResponseHelper::Out('fail', '', 200);
        }
    }

    public function productWishlist(Request $request)
    {
        $user_id = $request->header('id');
        $data = product_wishe::where('user_id', $user_id)->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function createWishlist(Request $request)
    {
        $user_id = $request->header('id');
        $data = product_wishe::updateOrCreate(
            ['user_id' => $user_id, 'product_id' => $request->product_id],
            ['user_id' => $user_id, 'product_id' => $request->product_id]
        );
        return ResponseHelper::Out('success', $data, 200);
    }
    public function removeWishlist(Request $request)
    {
        $user_id = $request->header('id');
        $data = product_wishe::where(['user_id' => $user_id, 'product_id' => $request->product_id])->delete();
        return ResponseHelper::Out('success', $data, 200);
    }
    public function productCart(Request $request)
    {
        $user_id    = $request->header('id');
        $product_id = $request->input('product_id');
        $color      = $request->input('color');
        $size       = $request->input('size');
        $qty        = $request->input('qty');
        $unitPrice = 0;
        $productDetails = product::where('id','=',$product_id)->first();
        
      

        if ($productDetails->discount == 1) {
            $unitPrice = $productDetails->discount_price;
        }
        else{
            $unitPrice = $productDetails->price;
        }
          $totalPrice = $qty * $unitPrice;
        $data = product_cart::updateOrCreate(
            ['user_id' => $user_id,'product_id'=>$product_id],
            [
                    'user_id'=>$user_id,
                    'product_id'=>$product_id,
                    'color'=>$color,
                    'size'=>$size,
                    'qty'=>$qty,
                    'price'=>$totalPrice
            ]
            );
        return ResponseHelper::Out('success', $data, 200);
    }
    public function CartList(Request $request):JsonResponse{
        $user_id = $request ->header('id');
        $data = product_cart::where('user_id',$user_id)->with('product')->get();
        return ResponseHelper::Out('success', $data, 200);
    }
    public function CartListDelete(Request $request):JsonResponse{
        $user_id = $request ->header('id');
        $product_id = $request->product_id;
        $data = product_cart::where(['user_id'=>$user_id,'product_id'=>$product_id])->delete();
        return ResponseHelper::Out('success', $data, 200);
    }
}
