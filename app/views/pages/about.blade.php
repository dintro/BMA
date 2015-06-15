@extends('layouts.master')
@section('content')
				@if(Session::has('message'))
		            <div class="alerts">
		                <div class="alert-message warning">{{ Session::get('message') }}</div>
		            </div>
		        @endif
				<ul>
			        @foreach($errors->all() as $error)
			            <li>{{ $error }}</li>
			        @endforeach
			    </ul>		
                
                
                about page here	
                	
@stop