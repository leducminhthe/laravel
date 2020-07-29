@extends('welcome')
@section('content')

	<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
			  		<li><a href="{{URL::to('trangchu')}}">Trang chủ</a></li>
			  		<li class="active">Thanh toán giỏ hàng</li>
				</ol>
			</div>

			<div class="review-payment">
				<h2>Xem giỏ hàng</h2>
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

				</tbody>
			</table>
		</div>
			<h4 style="margin: 35px 0px;">Chọn hình thức thanh toán</h4>
			<form action="{{URL::to('order-pay')}}" method="post">
				{{csrf_field()}}
				<div class="payment-options">
					<span>
						<label><input name="payment_option" type="radio" value="1"> thanh toán bằng ATM</label>
					</span>
					<span>
						<label><input name="payment_option" type="radio" value="2" checked> Nhận tiền mặt</label>
					</span>
					<span>
						<input style="margin: 0px;" type="submit" name="send_order" class="btn btn-primary btn-sm" value="Gửi">
					</span>
				</div>
				
			</form>
		</div>
	</section> <!--/#cart_items-->

@endsection