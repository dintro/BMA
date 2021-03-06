@extends('layouts.master')
@section('content')
<script type="text/javascript">
	$(document).ready(function(){

		$('#recaptcha_response_field').prop('required', true);
	});
</script>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 ">
    	<h2>Contact Us</h2>
    	@if(Session::has('message'))
            <div class="alerts">
                <div class="alert-message warning">{{ Session::get('message') }}</div>
            </div>
        @endif
        {{ Form::open(array('url' => 'contactus/send', 'class'=>'form-signin',)) }}
        <div class="row">
        	<div class="col-md-6">
        		<div class="form-group">
	        		<label class="col-md-12">Name</label>
	            </div>
	            <div class="form-group ">
	                <input type="text" id="name" name="name" class="form-control" required /> 
				</div>
				<div class="form-group">
	        		<label class="col-md-12">Email</label>
	            </div>
	            <div class="form-group ">
	                <input type="email" id="email" name="email" class="form-control" required /> 
				</div>
				
				<div class="form-group ">
					{{Form::captcha(array('theme' => 'white', 'required' => 'required'))}}
				</div>
        	</div>
    		<div class="col-md-6">
    			<div class="form-group">
	        		<label class="col-md-12">Subject</label>
	            </div>
	            <div class="form-group ">
	                <input type="text" id="subject" name="subject" class="form-control" required /> 
				</div>
    			<div class="form-group">
	        		<label class="col-md-12">Message</label>
	            </div>
	            <div class="form-group ">
	                <textarea class="form-control" rows="10" id="message" name="message" required ></textarea>
				</div>	
			</div>
            <div class="form-group col-md-3">
             		
                <button class="btn btn-primary" type="submit" >Send</button>
                
            </div>  
            
        </div>  
        {{ Form::close() }}
	</div>
</div>
@stop