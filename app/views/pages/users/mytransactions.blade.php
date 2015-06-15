@extends('layouts.profilemaster')
@section('content')    
<div id="content" class="content-profile">
    <div class="content-full">
		<div class="content-top">
            <div class="container pos-relative">
                @include('includes.profilesidebar')
                
            </div>
        </div>
        <div class="container">
     		<div class="content-bottom">
				<div class="col-md-12">
					<div class="row">
                      	<div class="col-xs-8 col-sm-6 col-md-6 packages-title">
                            <h4 class="spaced">Pending Order(s)</h4>
                        </div>
                    </div>
                    <div class="row">
                		@if(Session::has('message'))
				            <div class="alerts">
				                <div class="alert-message warning">{{ Session::get('message') }}</div>
				            </div>
				        @endif
                    	<table class="table table-striped">
							<thead>
								<tr>
									<th>
										No
									</th>
									<th>
										Order By
									</th>
									<th>
										Package Name
									</th>
									<th>
										Payment Method
									</th>
									<th>
										Contact
									</th>
									<th>
										Value
									</th>
									<th>
										Actions
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$index = 1;
					                if(isset($_REQUEST['page']))
					                {
					                    $page = $_REQUEST['page'];
					                    $page = ($page - 1) * 10;
					                    $index = $index + $page;
					                }
									$orderToMe = Auth::user()->getPendingOrder()->orderBy('created_at', 'desc')->paginate(10);
								?>
								@if($orderToMe->count() == 0)
					            <tr>
					                <td colspan="7">No pending order</td>
					            </tr>
					            @else
					                @foreach($orderToMe as $order)
					            <tr>
					            	<td>
					            		{{$index}}
					            	</td>
					            	<td>
					            		<a href="/about-me/{{$order->buyer->id}}" target="_blank"> {{$order->buyer->getFullname()}} </a>
					            	</td>
					            	<td>
					            		{{$order->orderdetail->package->title}}
					            	</td>
					            	<td>
					            		{{$order->payment_method}}
					            	</td>
					            	<td>
					            		{{$order->in_game_name}}
					            	</td>
					            	<td>
					            		{{$order->orderdetail->selling_price}}
					            	</td>
					            	<td>
					            		<a href="/users/mytransactions/approve/{{$order->id}}" onclick="return confirm('Are you sure you want to approve this order?')" class="btn btn-primary text-right">Approve</a>
					            		<a href="/users/mytransactions/reject/{{$order->id}}" onclick="return confirm('Are you sure you want to reject this order?')" class="btn btn-danger text-right">Reject</a>
					            	</td>
				            	</tr>
					            	@endforeach
				            	@endif
							</tbody>
						</table>
						<?php echo $orderToMe->links(); ?>
                    </div>
				</div>
				<div class="col-md-12">
					<div class="row">
                      	<div class="col-xs-8 col-sm-6 col-md-6 packages-title">
                            <h4 class="spaced">Order History</h4>
                        </div>
                    </div>
                    <div class="row">
                		
                    	<table class="table table-striped">
                    		<thead>
								<tr>
									<th>
										No
									</th>
									<th>
										Seller
									</th>
									<th>
										Package Name
									</th>
									<th>
										Payment Method
									</th>
									<th>
										Contact
									</th>
									<th>
										Value
									</th>
									<th>
										Status
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$index2 = 1;
					                if(isset($_REQUEST['other_page']))
					                {
					                    $page2 = $_REQUEST['other_page'];
					                    $page2 = ($page2 - 1) * 10;
					                    $index2 = $index2 + $page2;
					                }
					                Paginator::setPageName('other_page');
									$orders = Auth::user()->orders()->orderBy('created_at', 'desc')->paginate(10);
									
								?>
								@if($orders->count() == 0)
					            <tr>
					                <td colspan="7">No order history</td>
					            </tr>
					            @else
					                @foreach($orders as $order)
					            <tr>
					            	<td>
					            		{{$index2}}
					            	</td>
					            	<td>
					            		<a href="/about-me/{{$order->seller->id}}" target="_blank"> {{$order->seller->getFullname()}} </a>
					            	</td>
					            	<td>
					            		{{$order->orderdetail->package->title}}
					            	</td>
					            	<td>
					            		{{$order->payment_method}}
					            	</td>
					            	<td>
					            		{{$order->in_game_name}}
					            	</td>
					            	<td>
					            		{{$order->orderdetail->selling_price}}
					            	</td>
					            	<td>
					            		{{$order->payment_status}}
					            	</td>
				            	</tr>
					            	@endforeach
				            	@endif
			            	</tbody>
                    	</table>
                    	<?php echo $orders->links(); ?>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection