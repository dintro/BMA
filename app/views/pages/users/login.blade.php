@extends('layouts.master')
@section('content')
<style>
 .login-wrap {display:none;}
 .login-wrapper {padding-top: 30px;}
</style>
 <div class="row login-wrapper">
        <div class="col-xs-10 col-sm-6 col-md-6 col-xs-offset-1 col-sm-offset-3 col-md-offset-3">
        	
            <div class="account-box">
            
            <h2 style="margin-bottom:40px;  font-weight:bold;">Account Login</h2>
				
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
				
                
                
            	{{ Form::open(array('url'=>'users/signin', 'class'=>'form-signin')) }}
               <!--  <form class="form-signin" action="#"> -->
                <div class="form-group">
                	{{ Form::text('email', null, array('class'=>'form-control', 'placeholder'=>'Email', 'required','autofocus')) }}
                    <!-- <input type="text" class="form-control" placeholder="Email" required autofocus /> -->
                </div>
                <div class="form-group">
                    {{ Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password','required')) }}
                    <!-- <input type="password" class="form-control" placeholder="Password" required /> -->
                </div>
                <div class="row">
                	<div class="col-sm-6 col-md-6">
                	{{ Form::submit('Login', array('class'=>'btn btn-block btn-success'))}}
                    <a class="forgotLnk" href="/users/forgotpassword">Forgot Password?</a>
                   
                    </div>
                    <div class="col-sm-6 col-md-6">
                	 <a href="/sign-in-with-facebook" class="btn btn-block btn-social btn-facebook"><i class="fa fa-facebook"></i>Sign in with Facebook</a>
                      <a href="/signup" class="text-right col-md-12 padright0 mtop10">Create New Account</a>
                    </div>
                </div>
                <!-- <button class="btn btn-lg btn-block btn-success" type="submit">
                    Sign in</button> -->
                <!-- </form> -->
                {{ Form::close() }}
                             
                
            </div>
        </div>	
    </div>
@stop