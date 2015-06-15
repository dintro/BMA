@extends('layouts.profilemaster')
@section('content')    
<script src="{{ url('/js/ckeditor/ckeditor.js') }}" ></script>
<script type="text/javascript">
	$(document).ready(function(){

		function toggleEditAboutMe()
        {
            $('#spanAboutMe').toggle();
            
            $("#editAboutMe").toggle();
            
            $('.aboutme-editor').toggle();
        }

        $("#editAboutMe").click(function() {       
            toggleEditAboutMe();
        });

        $("#btnCancelSubmitAboutMe").click(function(e) {
            e.preventDefault();
            toggleEditAboutMe();      
        });

        $('#edit-aboutme').submit(function(e){
            e.preventDefault();
            var userid =  $("#user_id").val();
            var about_me = CKEDITOR.instances['aboutme'].getData();//$('#aboutme').val();
            
            var spantext =  $('#spanAboutMe');
            
            $.ajax({
                url: '/settings/editaboutme',
                type: "POST",
                data: { user_id: userid, about_me: about_me },
                cache: false,
                success: function( data )
                {
                    spantext.html($('#aboutme').val());
                    //$('#header-country').text(country);
                    toggleEditAboutMe();
                }
            }).fail(function() {
              alert( "error" );
            });;
        });
	});
</script>
<div id="content" class="content-profile">
    <div class="content-full">
		<div class="content-top">
            <div class="container pos-relative">
                @include('includes.profilesidebar')
                
            </div>
        </div>
        <div class="container">
     		<div class="content-bottom">
				<div class="col-md-12">
					<div class="row">
                      	<div class="col-xs-8 col-sm-6 col-md-6 packages-title">
                            <h4 class="spaced">About Me</h4>
                        </div>
                        <div class="col-xs-4 col-sm-6 col-md-6 new-package">
                          
                        </div>
                    </div>
				</div>
				<div class="col-md-12">
                	
            		<div class="row">
            			<div>
                        @if(Auth::check() && $selectedUser->id == Auth::user()->id)
                             <span id="spanAboutMe">                            
                                    {{ $selectedUser->about_me }}	
                                </span>
                        @else
                        
                            @if( $selectedUser->ispublic == 1) 
                                <span id="spanAboutMe">                            
                                    {{ $selectedUser->about_me }}	
                                </span>
                            @else
                                <?php 
                                if($selectedUser->friends()->count() > 0)
                                {
                                    $isFriend = false;
                                    foreach($selectedUser->friends()->get() as $friend)
                                    {
                                        if(Auth::check() && $friend->friendInfo->id == Auth::user()->id)
                                        {
                                            $isFriend = true;
                                        }
                                    }
                                    
                                    if($isFriend)
                                    {
                                    ?>
                                        <span id="spanAboutMe">                            
                                            {{ $selectedUser->about_me }}	
                                        </span>
                                    <?php
                                    }
                                    else{
                                    ?>
                                        <span id="spanAboutMe">                            
                                           You are not authorized to access this page.	
                                        </span>
                                    <?php
                                    }
                                }else{
                                    ?>
                                        <span id="spanAboutMe">                            
                                           You are not authorized to access this page.	
                                        </span>
                                    <?php
                                    }
                                ?>
                            @endif
                            
                         @endif
                        </div>
            		</div>
                                     
                                        
                        @if(Auth::check() && $selectedUser->id == Auth::user()->id)
    
                        <a href="javascript:void(0)" id="editAboutMe" class="btn btn-primary text-right pull-right">Edit</a>
    
                        {{ Form::open(array('', 'class'=>'form-signin', 'id'=>'edit-aboutme')) }}
                        <div class="row aboutme-editor" style="display:none;">
                            <div class="form-group">
                                <textarea class="form-control ckeditor" rows="30" id="aboutme" name="aboutme" required>{{{ $selectedUser->about_me }}}</textarea>
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                <button class="btn btn-primary" type="submit" id="btnSubmitAboutMe" >Save</button>
                                <button class="btn btn-danger" type="submit" id="btnCancelSubmitAboutMe">Cancel</button>	
                            </div>
                            
                            
                        </div>
                        {{ Form::close() }}
                        @endif
                   
            	</div>
			</div>
		</div>
	</div>
</div>
@endsection