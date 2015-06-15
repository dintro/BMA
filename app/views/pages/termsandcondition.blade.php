@extends('layouts.master')
@section('content')
@if(Auth::check() && Auth::user()->isadmin)
		    	
		    	
<script src="{{ url('/js/ckeditor/ckeditor.js') }}" ></script>
<script type="text/javascript">
	$(document).ready(function(){

		function toggleEditPage()
        {
            $('.page-content').toggle();
            
            $(".page-editor").toggle();
            
            $('#edit-page').toggle();
        }

        $("#edit-page").click(function() {       
            toggleEditPage();
        });

        $("#btnCancelSubmitPage").click(function(e) {
            e.preventDefault();
            toggleEditPage();      
        });

        
    });
</script>
@endif
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 ">
    	<h2>Terms and Conditions</h2>
    	@if(Session::has('message'))
            <div class="alerts">
                <div class="alert-message warning">{{ Session::get('message') }}</div>
            </div>
        @endif
        <?php 

        $page = Page::where('type','termsandconditions')->first();
		$content = '';
		if(!is_null($page))
		{
			$content = $page->content;
		}
        	
        		
		?>
        <div class="row ">
        	<div class="page-content">
        		{{$content}}
        		@if(Auth::check() && Auth::user()->isadmin)
		    	<a id="edit-page" class="btn btn-primary"> Edit </a>
		    	@endif
        	</div>
        	@if(Auth::check() && Auth::user()->isadmin)

	        {{ Form::open(array('url' => 'termsandcondition/edit', 'class'=>'form-signin', 'id'=>'form-edit-page')) }}
	        <div class="page-editor" style="display:none">
	        	<div class="form-group">
    				<textarea class="form-control ckeditor" rows="30" id="content" name="content" required>
    					{{{$content}}}
    				</textarea>
    			</div>
    			<div class="form-group">
    				<input class="form-control" id="type" name="type" type="hidden" value="termsandconditions">
    				<button class="btn btn-primary" type="submit" id="btnSubmitPage" >Save</button>
                	<button class="btn btn-danger" type="submit" id="btnCancelSubmitPage">Cancel</button>	
    			</div>
	        </div>
	        {{ Form::close() }}
	        @endif
        </div>
       
    </div>
</div>
@stop