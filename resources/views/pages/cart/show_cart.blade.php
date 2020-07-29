@extends('welcome')
@section('content')

<section id="cart_items">
	<div class="container">
		<div class="breadcrumbs">
			<ol class="breadcrumb">
			  <li><a href="{{URL::to('trangchu')}}">Trang chủ</a></li>
			  <li class="active">Shopping Cart</li>
			</ol>
		</div>
		<div class="table-responsive cart_info">
			<?php 
			$content = Cart::content();
			?>
			<table class="table table-condensed">
				<thead>
					<tr class="cart_menu">
						<td class="image">Ảnh Sản phẩm</td>
						<td class="description">Tên sản phẩm</td>
						<td class="price">Giá</td>
						<td class="quantity">SL</td>
						<td class="total">Tổng tiền</td>
						<td></td>
					</tr>
				</thead>
				<tbody>

					@foreach($content as $v_content)
					<tr>
						<td class="cart_product">
							<a href=""><img src="{{URL::to('public/upload/product/'.$v_content->options->image)}}" alt=""></a>
						</td>
						<td class="cart_description">
							<h4><a href="">{{$v_content->name}}</a></h4>
							<p>Web ID: 1089772</p>
						</td>
						<td class="cart_price">
							<p>{{number_format($v_content->price)}}</p>
						</td>
						<td class="cart_quantity">
							<div class="cart_quantity_button">
								<form action="{{URL::to('update-to-cart')}}" method="post">
									{{csrf_field()}}
									<input class="cart_quantity_input" type="number" min="1" name="quantity_cart" value="{{$v_content->qty}}" autocomplete="off" size="2">
									<input type="submit" name="UpdateQty" class="btn-defaulte btn-sm" value="Update">
									<input type="hidden" name="rowId_cart" class="form-control" value="{{$v_content->rowId}}">
								</form>
							</div>
						</td>
						<td class="cart_total">
							<p class="cart_total_price">
								<?php 
								$subtotal = $v_content->price * $v_content->qty ?>
								<?php echo number_format($subtotal) ?>
							</p>
						</td>
						<td class="cart_delete">
							<a class="cart_quantity_delete" href="{{URL::to('delete-to-cart/'.$v_content->rowId)}}"><i class="fa fa-times"></i></a>
						</td>
					</tr>
					@endforeach
					@if(count($content))
					<tr>
						<td>
							<button><a href="{{URL::to('delete-all-cart')}}" >Delete all cart</a></button>
						</td>
					</tr>
					@else
					<tr>
						<td>
							<p>Your cart empty</p>
						</td>
					</tr>
					@endif

				</tbody>
			</table>
		</div>
	</div>
</section> <!--/#cart_items-->

<section id="do_action">
	<div class="container">
		<div class="heading">
			<h3>What would you like to do next?</h3>
			<p>Choose if you have a discount code or reward points you want to use or would like to estimate your delivery cost.</p>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="total_area">
					<ul>
						<li>Tổng<span>{{Cart::subtotal()}}</span></li>
						<li>Thuế<span>{{Cart::tax()}}</span></li>
						<li>Phí vận chuyển<span>Free</span></li>
						<li>Thành tiền<span>{{Cart::total()}}</span></li>
					</ul>
						<!-- <a class="btn btn-default update" href="">Update</a> -->
						<?php 
                            $customer_id = Session::get('customer_id');
                            $shipping_id = Session::get('shipping_id');
                            if ($customer_id && $shipping_id == null) {
                        ?>
                            <a href="{{URL::to('checkout')}}" class="btn btn-default check_out">Thanh toán</a>

                        <?php }elseif($customer_id && $shipping_id){?>

                        	<a href="{{URL::to('payment')}}" class="btn btn-default check_out">Thanh toán</a>

                        <?php }else{ ?>

                            <a href="{{URL::to('login-checkout')}}" class="btn btn-default check_out">Thanh toán</a>

                        <?php } ?>
						
				</div>
			</div>
		</div>
	</div>
</section><!--/#do_action-->

@endsection