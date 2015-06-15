@extends('layouts.profilemaster')
@section('content') 


<div class="container">
<div class="col-md-12">
      		
     <h1>Shopping Cart</h1>     
@if (Session::has('packagecart'))

	@foreach (Session::get('packagecart') as $cart_itm)
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-lg-8">{{$cart_itm["package_name"]}} - {{$cart_itm["selling"]}}%</div>
            <div class="col-xs-6 col-lg-4">${{$cart_itm["selling_price"]}}</div>
          </div>
	@endforeach     
@endif 
	<div class="row">
            <div class="col-xs-12 col-sm-6 col-lg-8">Payment Method: 
                <select id="payment_method" name="payment_method">
					<option value="Pokerstars">Pokerstars</option> 
                    <option value="Fulltilt">Fulltilt</option> 
                    <option value="888">888</option>
                    <option value="Party Poker">Party Poker</option>
                    <option value="Titan Poker">Titan Poker</option>                
                </select>
            </div>
            <div class="col-xs-6 col-lg-4"><button type="submit">Buy</button></div>
          </div>
</div>
</div>
@stop