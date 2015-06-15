@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 ">
    	<h2>Frequently Asked Questions</h2>
    	@if(Session::has('message'))
            <div class="alerts">
                <div class="alert-message warning">{{ Session::get('message') }}</div>
            </div>
        @endif
    	
    	@if(Auth::check() && Auth::user()->isadmin)
    	<a href='/faq/add' class="btn btn-primary"> Add Faqs </a>
    	@endif
    	<?php $faqs = Faq::orderBy('created_at')->get(); ?>
		@if($faqs->count() > 0)
    	<ul class="faqs-list">
    		

    		@foreach($faqs as $faq)
    		<li>
    			<div class="question-wrap">
    				{{ $faq->question }}
    			</div>
    			<div class="answer-wrap">
    				{{ $faq->answer }}
    			</div>
    			@if(Auth::check() && Auth::user()->isadmin)
    			<div class="manage-faqs-wrap">
    				<a href='/faq/edit/{{$faq->id}}' class="btn btn-primary"> Edit </a>
    				<a href='/faq/delete/{{$faq->id}}' class="btn btn-danger" onclick="return confirm('are you sure you want to delete this faq?')"> Delete </a>
    			</div>
    			@endif
    		</li>
    		@endforeach
    	</ul>
    	@else
    	<div>
    		No Frequently Asked Questions available
    	</div>
    	@endif
    </div>

</div>
@stop