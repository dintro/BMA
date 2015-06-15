@extends('layouts.master')
@section('content')
<style>
 .login-wrapper {padding-top: 30px;}
</style>
 <div class="row login-wrapper">
        <div class="col-xs-10 col-sm-6 col-md-6 col-xs-offset-1 col-sm-offset-3 col-md-offset-3">
        	
            <div class="account-box">
              <h2 style="margin-bottom:40px;  font-weight:bold;">Forgot Password</h2>
               @if(Session::has('success'))
                    <div class="alert alert-success alert-wrap">
                        <div>{{ Session::get('message') }}</div>
                  
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul> 
                
                  </div>
                @else
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
                @endif
                
 <div class="row"> 
            {{ Form::open(array('url'=>'users/forgot', 'class'=>'form-signin')) }}
                <div class="col-md-9">
                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email Address" required autofocus  value="{{ Input::old('email') }}"/>
                </div>
                </div>
                <div class="col-md-3 pad0">
                <button class="btn btn-block btn-success" type="submit">
                    Submit
                </button>
                
                </div>
                {{ Form::close() }}
                </div>
	</div>
    </div>
    </div>

@stop