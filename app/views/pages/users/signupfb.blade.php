@extends('layouts.master')
@section('content')

<link rel="stylesheet" href="/css/bootstrapValidator.min.css">
	<div class="row">
	    <div class="col-xs-10 col-sm-8 col-md-8 col-xs-offset-1 col-sm-offset-2 col-md-offset-2">
				{{ Form::open(array('url'=>'users/createfb', 'class'=>'signup-form')) }}

				<h2>Sign Up <small>to continue to <a href="#">BuyMyAction.com</a></small></h2>
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
@if(Session::has('message'))

<?php 
$value = Session::get('message');
echo $value;
?>
<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
	                        <input type="text" name="first_name" id="first_name"   required value="{{ Input::old('first_name') }}" class="form-control" placeholder="First Name" tabindex="1">
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="text" name="last_name" id="last_name"  required value="{{ Input::old('last_name') }}" class="form-control" placeholder="Last Name" tabindex="2">
						</div>
					</div>
				</div>
				<div class="form-group">
					<input type="text" name="display_name" id="display_name"  required value="{{ Input::old('displayname') }}" class="form-control" placeholder="Display Name" tabindex="3">
				</div>
				<div class="form-group">
					<input type="email" name="email" id="email"  required value="{{ Input::old('email') }}" class="form-control" placeholder="Email Address" tabindex="4">
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="password" required id="password" class="form-control" placeholder="Password" tabindex="5">
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="password_confirmation" id="password_confirmation" required class="form-control" placeholder="Confirm Password" tabindex="6">
						</div>
					</div>
				</div>

				<div class="form-group">
					<input type="text" name="captcha" id="captcha" placeholder="Captcha" tabindex="7" required>

					{{ HTML::image(Captcha::img(), 'Captcha image') }}
								
				</div>

				<div class="row">
					<div class="col-xs-4 col-sm-3 col-md-2">
						<span class="button-checkbox">
							<button type="button" class="btn btn-default" data-color="info" tabindex="8"><i class="state-icon glyphicon glyphicon-unchecked"></i>&nbsp;I Agree</button>
	                        <input type="checkbox" name="terms_and_conditions" id="terms_and_conditions" class="hidden" value="1">
						</span>
					</div>
					<div class="col-xs-8 col-sm-9 col-md-10">
						 <p>By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a></p>
					</div>
				</div>

				<div class="row margtop20">
					<div class="col-xs-6 col-md-6">
					 {{ Form::submit('Register', array('class'=>'btn btn-primary btn-block btn-lg'))}}
					</div> 
					<!-- <div class="col-xs-6 col-md-6">
						<a href="/signup-with-facebook" class="btn btn-block btn-social btn-facebook"><i class="fa fa-facebook"></i>Register with Facebook</a>
          		  	</div>  -->
				</div>
				<input type="hidden" name="facebook_email" id="facebook_email" value="{{$email}}" class="form-control" placeholder="Email Address" tabindex="4">
				

				{{ Form::close() }}

@else
<?php 
if($firstname){

$value = Session::get('message');
echo $value;
?>		

				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
	                        <input type="text" name="first_name" id="first_name" required  value="{{$firstname}}" class="form-control" placeholder="First Name" tabindex="1">
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="text" name="last_name" id="last_name" required value="{{$lastname}}" class="form-control" placeholder="Last Name" tabindex="2">
						</div>
					</div>
				</div>
				<div class="form-group">
					<input type="text" name="display_name" id="display_name" required value="{{$displayname}}" class="form-control" placeholder="Display Name" tabindex="3">
				</div>
				<div class="form-group">
					<input type="email" name="email" id="email" required value="{{$email}}" class="form-control" placeholder="Email Address" tabindex="4">
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="password" id="password" required class="form-control" placeholder="Password" tabindex="5">
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="password_confirmation" id="password_confirmation" required class="form-control" placeholder="Confirm Password" tabindex="6">
						</div>
					</div>
				</div>

				<div class="form-group">
					<input type="text" name="captcha" id="captcha" placeholder="Captcha" tabindex="7" required>

					{{ HTML::image(Captcha::img(), 'Captcha image') }}
								
				</div>

				<div class="row">
					<div class="col-xs-4 col-sm-3 col-md-2">
						<span class="button-checkbox">
							<button type="button" class="btn btn-default" data-color="info" tabindex="8"><i class="state-icon glyphicon glyphicon-unchecked"></i>&nbsp;I Agree</button>
	                        <input type="checkbox" name="terms_and_conditions" id="terms_and_conditions" class="hidden" value="1">
						</span>
					</div>
					<div class="col-xs-8 col-sm-9 col-md-10">
						 <p>By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a></p>
					</div>
				</div>

				<div class="row margtop20">
					<div class="col-xs-6 col-md-6">
					 {{ Form::submit('Register', array('class'=>'btn btn-primary btn-block btn-lg'))}}
					</div> 
					<!-- <div class="col-xs-6 col-md-6">
						<a href="/signup-with-facebook" class="btn btn-block btn-social btn-facebook"><i class="fa fa-facebook"></i>Register with Facebook</a>
          		  	</div>  -->
				</div>
				<input type="hidden" name="facebook_email" id="facebook_email" value="{{$email}}" class="form-control" placeholder="Email Address" tabindex="4">
				

				{{ Form::close() }}

				
			<!-- </form> -->
<?php 
} ?>
@endif
	</div>
</div>



<script type="text/javascript">
    		$(function () {
    $('.button-checkbox').each(function () {
        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
});
    </script>

    <script src="/js/bootstrapValidator.min.js"></script>
<script>
  $(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
</script>
@stop