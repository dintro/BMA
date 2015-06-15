@extends('layouts.master')
@section('content')

<link rel="stylesheet" href="/css/bootstrapValidator.min.css">
	
	<div class="row">
	    <div class="col-xs-10 col-sm-8 col-md-8 col-xs-offset-1 col-sm-offset-2 col-md-offset-2">
			<!-- <form role="form" class="signup-form"> -->
				{{ Form::open(array('url'=>'users/create', 'class'=>'signup-form', 'id'=>'togglingForm')) }}

				<h2>Sign Up</h2>
			
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
			

				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
	                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" tabindex="1" required value="{{ Input::old('first_name') }}">
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" tabindex="2" required  value="{{ Input::old('last_name') }}">
						</div>
					</div>
				</div>
				
				
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="email" name="email" id="email" class="form-control" placeholder="Email Address" tabindex="4" required  value="{{ Input::old('email') }}">
						</div>
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control" placeholder="Password" tabindex="5" required>
						</div>
						<div class="form-group">
							<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password" tabindex="6" required>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							
							{{Form::captcha(array('theme' => 'white'))}}
										
						</div>
					</div>
				</div>

				

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<span class="button-checkbox">
							<!-- <button type="button" class="btn btn-default" class="hidden" data-color="info" tabindex="8"><i class="state-icon glyphicon glyphicon-unchecked hidden"></i>&nbsp;I Agree</button>
	                         -->
	                         <input type="checkbox" name="terms_and_conditions" id="terms_and_conditions" value="1">
	                        <label>By clicking <strong>Register</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a></label>
						</span>
					</div>
					<!-- <div class="col-xs-8 col-sm-9 col-md-10">
						 <p>By clicking <strong>Register</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a></p>
					</div> -->
				</div>

				<div class="row margtop20">
					<div class="col-xs-12 col-md-12">
					 {{ Form::submit('Register', array('class'=>'btn btn-primary btn-block btn-lg'))}}
					</div> 
					<!-- <div class="col-xs-6 col-md-6"><input type="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
					<div class="col-xs-6 col-md-6"><a href="#" class="btn btn-success btn-block btn-lg">Sign In</a></div> -->
					<!-- <div class="col-xs-6 col-md-6">
						<a href="/sign-in-with-facebook" class="btn btn-block btn-social btn-facebook"><i class="fa fa-facebook"></i>Register with Facebook</a>
          		  	</div>  -->
				</div>
				{{ Form::close() }}

				<br>
			<!-- </form> -->
	</div>
</div>
<script src="/js/bootstrapValidator.min.js"></script>

@stop