@extends('layouts.master')
@section('content')

				@if(Session::has('message'))
		            <div class="alert alert-danger alert-wrap">
		                <div>{{ Session::get('message') }}</div>
		            
                         <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>	
                    </div>
		        @endif
                
                
                privacy page here	
                
                			
@stop