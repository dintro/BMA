@extends('layouts.master')
@section('content')
<script src="{{ url('/js/ckeditor/ckeditor.js') }}" ></script>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 ">
    	<h2>Add FAQ</h2>

    	{{ Form::open(array('url'=>'faq/insert', 'class'=>'form-signin')) }}
		<div class="row faq-editor">
			<div class="form-group">
	    		<label class="col-md-12">Question</label>
	        </div>
			<div class="form-group">
				<input type="text" id="question" name="question" class="form-control" required /> 
			</div>
			<div class="form-group" style="height: 10px;">
	    		<label class="col-md-12">Answer</label>
	        </div>
			<div class="form-group">
				<textarea class="form-control ckeditor" rows="30" id="answer" name="answer" required ></textarea>
			</div>
			<div class="form-group">
				
				<button class="btn btn-primary" type="submit" >Save</button>
            	<a href="/faq" class="btn btn-danger" >Cancel</a>	
			</div>
			
			
		</div>
		{{ Form::close() }}
    </div>
</div>
@endsection