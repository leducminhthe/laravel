<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use DB;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class CartController extends Controller
{
    public function save_cart(Request $request){
    	$productId = $request->product_id;
    	$quantity = $request->qty;

    	$product_info = DB::table('tbl_product')->where('product_id', $productId)->first();

   		$data['id'] = $productId;
   		$data['qty'] = $quantity;
   		$data['name'] = $product_info->product_name;
   		$data['price'] = $product_info->product_price;
   		$data['weight'] = '123';
   		$data['options']['image'] = $product_info->product_image;
   		Cart::add($data); 	

    	return Redirect::to('show-cart');
    }

    public function show_cart(Request $request){
    	$cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id', 'desc')->get();
    	$brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id', 'desc')->get();

      $content = Cart::content();

    	return view('pages.cart.show_cart')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function delete_cart($rowId){
    	$cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id', 'desc')->get();
    	$brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id', 'desc')->get();

    	Cart::remove($rowId);
    	return view('pages.cart.show_cart')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function delete_all_cart(){
      $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id', 'desc')->get();
      $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id', 'desc')->get();

      Cart::destroy();
      return view('pages.cart.show_cart')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function update_cart(Request $request){
    	$rowId = $request->rowId_cart;
    	$qty = $request->quantity_cart;

    	$cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id', 'desc')->get();
    	$brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id', 'desc')->get();

    	Cart::update($rowId, $qty);
    	return view('pages.cart.show_cart')->with('category', $cate_product)->with('brand', $brand_product);
    }

}
