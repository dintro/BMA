@extends('layouts.master')
@section('content') 
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 ">
    	<h2>Notifications</h2>
    	<ul class="notification-list">
    	<?php
    		$notifications = Auth::user()->notifications()->orderBy('created_at','desc')->paginate(20);
    	?>
    	@if($notifications->count() > 0)
    		@foreach($notifications as $item)
    		<li>
    			<a href="{{$item->url}}"> {{$item->content}} </a> <span class="notification-date">{{date('M j, Y, g:i a',strtotime($item->created_at))}}</span>
    		</li>
    		@endforeach
    	@else
    		<li>
    			No new notifications
    		</li>
    	@endif
    	</ul>
    	<div>
    		<?php echo $notifications->links(); ?>
    	</div>
	</div>
</div>    	
@endsection