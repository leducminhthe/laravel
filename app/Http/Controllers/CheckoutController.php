<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Cart;
use Illuminate\Support\Facades\Redirect;
session_start();

class CheckoutController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('admin.dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function login_checkout(){
    	$cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id', 'desc')->get();
    	$brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id', 'desc')->get();

    	return view('pages.checkout.login_checkout')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function add_customer(Request $request){
    	$data['customer_name'] = $request->name;
    	$data['customer_email'] = $request->email;
    	$data['customer_password'] = md5($request->password);
    	$data['customer_phone'] = $request->phone;

    	$customer_id = DB::table('tbl_customer')->insertGetId($data);

    	Session::put('customer_id', $customer_id);
    	Session::put('customer_name', $request->name);
    	return Redirect::to('checkout');
    }

    public function checkout(){
    	$cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id', 'desc')->get();
    	$brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id', 'desc')->get();

    	return view('pages.checkout.show_checkout')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function save_checkout_customer(Request $request){
    	$data['shipping_email'] = $request->shipping_email;
    	$data['shipping_name'] = $request->shipping_name;
    	$data['shipping_address'] = $request->shipping_phone;
    	$data['shipping_phone'] = $request->shipping_phone;
    	$data['shipping_note'] = $request->shipping_note;

    	$shipping_id = DB::table('tbl_shipping')->insertGetId($data);

    	Session::put('shipping_id', $shipping_id);
    	return Redirect::to('payment');
    }

    public function logout_checkout(){
    	Session::flush();
    	return Redirect::to('login-checkout');
    }

    public function login_customer(Request $request){
    	$email = $request->email_account;
    	$password = md5($request->password_account);

    	$result = DB::table('tbl_customer')->where('customer_email', $email)->where('customer_password', $password)->first();
    	if ($result) {
    		Session::put('customer_id', $result->customer_id);
    		return Redirect::to('checkout');
    	}else{
			return Redirect::to('login-checkout');
    	}
    }

    public function payment(){
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id', 'desc')->get();

        return view('pages.checkout.payment')->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function order_pay(Request $request){
        //insert payment method
        $data['payment_method'] = $request->payment_option;
        $data['payment_status'] = 'Đang chờ xử lý';

        $payment_id = DB::table('tbl_payment')->insertGetId($data);

        //insert oder
        $order_data['customer_id'] = Session::get('customer_id');
        $order_data['shipping_id'] = Session::get('shipping_id');
        $order_data['payment_id'] = $payment_id;
        $order_data['order_total'] = Cart::total();
        $order_data['order_status'] = 'Đang chờ xử lý';

        $order_id = DB::table('tbl_order')->insertGetId($order_data);

        //insert oder_details
        $content = Cart::content();
        foreach ($content as $value) {
            $order_details_data['order_id'] = $order_id;
            $order_details_data['product_id'] = $value->id;
            $order_details_data['product_name'] = $value->name;
            $order_details_data['product_price'] = $value->price;
            $order_details_data['product_sales_quantity'] = $value->qty;
            DB::table('tbl_order_details')->insert($order_details_data);
        }
        if ($data['payment_method'] == 1) {
            echo "thanh toán ATM";
        }else{
            Cart::destroy();
            $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id', 'desc')->get();
            $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id', 'desc')->get();

            return view('pages.checkout.cash')->with('category', $cate_product)->with('brand', $brand_product);
        }
        
    }

    public function manage_order(){

        $this->AuthLogin();
        $all_order = DB::table('tbl_order')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_order.customer_id')
        ->select('tbl_order.*','tbl_customer.customer_name')->orderby('tbl_order.order_id','desc')->get();
        $manager_product = view('admin.manage_order')->with('all_order', $all_order);
        
        return view('admin_layout')->with('admin.manage_order', $manager_product);
    }

    public function view_order($orderId){
        $this->AuthLogin();
        $order_by_id = DB::table('tbl_order')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_order.customer_id')
        ->join('tbl_shipping','tbl_shipping.shipping_id','=','tbl_order.shipping_id')
        ->join('tbl_order_details','tbl_order_details.order_id','=','tbl_order.order_id')
        ->select('tbl_order.*','tbl_shipping.*','tbl_order_details.*','tbl_customer.*')->first();

        $manager_order_by_id = view('admin.view_order')->with('order_by_id', $order_by_id);
        
        return view('admin_layout')->with('admin.view_order', $manager_order_by_id);
    }
}
