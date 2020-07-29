<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Mail;
use Illuminate\Support\Facades\Redirect;
session_start();

class HomeController extends Controller
{
    public function index(Request $request){

        // seo
        $meta_desc = "thời trang cuộc sống";
        $meta_keywords = "bán thời trang nam,nữ";
        $meta_title = "áo váy nữ";
        $url_canonical = $request->url();

    	$cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id', 'desc')->get();
    	$brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id', 'desc')->get();

    	$all_product = DB::table('tbl_product')->where('product_status','1')->orderby('product_id', 'desc')->paginate(1);

        //cách 1 dùng with
    	return view('pages.home')
        ->with('category', $cate_product)
        ->with('brand', $brand_product)
        ->with('all_product',$all_product)
        ->with('meta_desc',$meta_desc)
        ->with('meta_keywords',$meta_keywords)
        ->with('meta_title',$meta_title)
        ->with('url_canonical',$url_canonical);

        // cach2 sử dụng with với compact 
        // return view('pages.home')->with(compact('cate_product','brand_product','all_product'));

    	// $all_product = DB::table('tbl_product')
    	// ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
    	// ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')->orderby('tbl_product.product_id','desc')->get();
    	// $manager_product = view('admin.all_product')->with('all_product', $all_product);
    }

    public function search(Request $request){
        $keywords = $request->keywords_submit;

        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id', 'desc')->get();


        $search_product = DB::table('tbl_product')->where('product_name','like','%'.$keywords.'%')->get();
        return view('pages.sanpham.search')->with('category', $cate_product)->with('brand', $brand_product)->with('search_product',$search_product);

    }

    public function send_mail(){
        $to_name = "lethe";
        $to_email = "theminhle369@gmail.com";//send to this email

        $data = array("name"=>"Mail từ khách hàng","body"=>'Đặt mua PS4'); //body of mail.blade.php
    
        Mail::send('pages.mail.send_mail',$data,function($message) use ($to_name,$to_email){

            $message->to($to_email)->subject('test mail nhé');//send this mail with subject
            $message->from($to_email,$to_name);//send from this mail
        });
        //--send mail
        return Redirect::to('trangchu')->with('message','');

    }
}
