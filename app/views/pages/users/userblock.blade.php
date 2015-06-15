@extends('layouts.profilemaster')
@section('content') 
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
                            <h4 class="spaced">Block Lists</h4>
                            </div>                    
                        </div>
                </div>
                
                <script type="text/javascript">
				 
					function cancelblock(userID,userBlockID)
					{					
						//var userID = $userid;
						//var userBlockID = $userblockid;			
						
						$.post("/canceluserblock/" + userID +"/"+ userBlockID, function( data ) {
							
							$('#rowblock'+userBlockID).remove();
							
						}, "json")
						.fail(function() {
							alert( "block user failed" );
						});
					}
	
                </script>
                
                <div class="col-md-12">
                	   <div class="row">
                        	<div class="col-md-2"><strong>No</strong></div><div class="col-md-4"><strong>Name</strong></div><div class="col-md-4"></div>            
                        </div>
                        
                        @foreach($userblocks->get() as $index => $block)	
                        <?php
                        	$userblock = User::find( $block->userblock_id );
						?> 
                        <div class="row" id="rowblock{{$userblock->id}}">                       
                        	<div class="col-md-2">{{ $index+1 }}</div><div class="col-md-4">{{$userblock->firstname}} {{$userblock->lastname}}</div><div class="col-md-4"><a href="javascript:cancelblock({{ Auth::user()->id }},{{ $userblock->id }})" id="unblock-button" class="btn btn-primary" data-userid="{{ Auth::user()->id }}"  data-userblockid="{{ $userblock->id }}"><span class="unblockuser">Unblock</span><b>Unblock</b></a>	</div>
                        </div>
                        @endforeach
               		
               </div>
           </div>
        </div>
   </div>
</div>

@endsection