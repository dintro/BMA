@extends('layouts.profilemaster')
@section('content')    
	
	<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
    </script>
    <style>
		.btn-join-wrap {
			display:none!important;
		}
	
    .navbar{
        margin-bottom: 0;
    }
.addtour-wrap {
float:left;
width:100%;
margin:0;
padding:0;
}

.addtour-wrap li {
float:left;
list-style:none;
width:100%;
padding:0;
margin-bottom:5px;
}
    /*.loading {
        z-index:    1000;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .8 ) 
                    url('http://sampsonresume.com/labs/pIkfp.gif') 
                    50% 50% 
                    no-repeat;
    }*/
</style>
<script type="text/javascript">

    $(document).ready(function()
    {
        if(!$('#cash_enabled').is(":checked"))
        {
            $('#cash').prop('disabled', true);
        }
        
        $( "#cash_enabled" ).change(function() {
            if($('#cash_enabled').is(":checked"))
            {
                $('#cash').prop('disabled', false);
            }
            else
            {
                $('#cash').prop('disabled', true);
            }
          
        });

        if(!$('#bankwire_enabled').is(":checked"))
        {
            $('#bankwire').prop('disabled', true);
        }
        
        $( "#bankwire_enabled" ).change(function() {
            if($('#bankwire_enabled').is(":checked"))
            {
                $('#bankwire').prop('disabled', false);
            }
            else
            {
                $('#bankwire').prop('disabled', true);
            }
          
        });

		$('.show-edit').each(function(){
			$(this).click(function(e)
			{
				e.preventDefault();
				$(this).parent().parent().find('div.showscrnamepanel').hide();
				$(this).parent().parent().find('div.editscrnamepanel').show();
			});
		});

		$('.remove').each(function(){
			$(this).click(function(e){
                e.preventDefault();
        		var screennameid = $(this).data('screennameid');
                var screennametype = '.'+$(this).data('screennametype');
                var parent = $(this).parents('.row');
                console.log(screennameid + ";" + screennametype);
    			if (confirm('Are you sure you want to remove this screen name?')) {
    		        $.ajax({
    		            url: '/settings/removescreenname',
    		            type: "POST",
    		            data: { screennameid: screennameid },
    		            success: function () {
                            parent.find('.col-md-8').text('N/A');
                            parent.find('div.showscrnamepanel').show();
                            parent.find('div.editscrnamepanel').hide();
                            parent.find('.remove').hide();
                            parent.find('.show-edit').text('Add');
                            parent.find('#screenname').val('');
                            $(screennametype).hide();
    		            }
    		        });
    		    }
			});
						 	
		});

    	$('.cancel-edit').each(function(){
			$(this).click(function(e)
			{
				e.preventDefault();
				//console.log($(this).parent().parent().parent());
				$(this).parent().parent().parent().parent().find('div.showscrnamepanel').show();
				$(this).parent().parent().parent().parent().find('div.editscrnamepanel').hide();

			});
    	});

        $('.save-screenname').each(function(){
            $(this).click(function(e)
            {
                e.preventDefault();
                var screennameid = $(this).data('screennameid');
                var screennametype = '.'+$(this).data('screennametype');
                var newscreenname = $(this).parent().parent().find('#screenname');
                var parent = $(this).parents('.row');
                $.ajax({
                    url: '/settings/editscreenname',
                    type: "POST",
                    data: { screennameid: screennameid, screenname: newscreenname.val() },
                    cache: false,
                    success: function( data )
                    {
                        //alert(parent);
                        //console.log('haaaai');
                        //console.log(parent);
                        parent.find('.col-md-8').text(newscreenname.val());
                        parent.find('div.showscrnamepanel').show();
                        parent.find('div.showscrnamepanel').append();
                        parent.find('div.editscrnamepanel').hide();
                        parent.find('.remove').show();
                        parent.find('.show-edit').text('Edit');
                        $(screennametype).show();
                        $(screennametype).find('.caption p').text(newscreenname.val());

                        
                    }
                }).fail(function() {
                  alert( "error" );
                });;
                //console.log(screennameid + ' ' + newscreenname.val());
                //$(this).parent().parent().parent().parent().find('div.showscrnamepanel').show();
                //$(this).parent().parent().parent().parent().find('div.editscrnamepanel').hide();

            });
        });

        function updateScreename(parent)
        {

        }

        $('.add-screenname').each(function(){
            $(this).click(function(e)
            {
                e.preventDefault();
                var screennameid = $(this).data('screennameid');
                var screennametype = '.'+$(this).data('screennametype');
                var newscreenname = $(this).parent().parent().find('#screenname');
                console.log(newscreenname.val() + " - " + screennametype + " - " + screennameid);
                var parent = $(this).parents('.row');
                $.ajax({
                    url: '/settings/addscreenname',
                    type: "POST",
                    data: { username: newscreenname.val(), screennametype: screennameid },
                    cache: false,
                    success: function( data )
                    {
                        //alert(parent);
                        //console.log('haaaai');
                        //console.log(parent);
                        parent.find('.col-md-8').text(newscreenname.val());
                        parent.find('div.showscrnamepanel').show();
                        parent.find('.remove').removeData( "screennameid" );
                        parent.find('.remove').data('screennameid', data.screennameid);
                        
                        parent.find('div.editscrnamepanel').find(".add-screenname").remove();
                        parent.find('div.editscrnamepanel').find(".save-screenname").show();
                        parent.find('div.editscrnamepanel').find(".save-screenname").removeData( "screennameid" );
                        parent.find('div.editscrnamepanel').find(".save-screenname").data('screennameid', data.screennameid);
                        
                        parent.find('div.editscrnamepanel').hide();
                        parent.find('.remove').show();
                        parent.find('.show-edit').text('Edit');
                        $(screennametype).show();
                        $(screennametype).find('.caption p').text(newscreenname.val());

                        
                    }
                }).fail(function() {
                  alert( "error" );
                });;

            });
        });

        $('#edit-firstname').submit(function(e){
            e.preventDefault();
            var userid =  $("#user_id").val();
            var firstname = $('#firstname').val();
            var spantext =  $(this).find('#spanFirstName');
            $.ajax({
                url: '/settings/editfirstname',
                type: "POST",
                data: { user_id: userid, firstname: firstname },
                cache: false,
                success: function( data )
                {
                    spantext.text(firstname);
                    $('#user-firstname').text(firstname);
                    $('#header-firstname').text(firstname);
                    toggleEditFirstName();
                }
            }).fail(function() {
              alert( "error" );
            });;
        });

        $('#edit-lastname').submit(function(e){
            e.preventDefault();
            var userid =  $("#user_id").val();
            var lastname = $('#lastname').val();
            var spantext =  $(this).find('#spanLastName');
            $.ajax({
                url: '/settings/editlastname',
                type: "POST",
                data: { user_id: userid, lastname: lastname },
                cache: false,
                success: function( data )
                {
                    spantext.text(lastname);
                    $('#user-lastname').text(lastname);
                    $('#header-lastname').text(lastname);
                    toggleEditLastName();
                }
            }).fail(function() {
              alert( "error" );
            });;
        });
		
		 $('#edit-ispublic').submit(function(e){
            e.preventDefault();
            var userid =  $("#user_id").val();
            var ispublic = $('#ispublic').val();
			var spantext =  $(this).find('#spanIsPublic');
            $.ajax({
                url: '/settings/editispublic',
                type: "POST",
                data: { user_id: userid, ispublic: ispublic },
                cache: false,
                success: function( data )
                {
					if(ispublic==1) {
						spantext.text('Public');
					}else{
						spantext.text('My Circle');
					}
                    
                    toggleEditIsPublic();
                }
            }).fail(function() {
              alert( "error" );
            });;
        });

        $('#edit-country').submit(function(e){
            e.preventDefault();
            var userid =  $("#user_id").val();
            var country = $('#country').val();
            var spantext =  $(this).find('#spanCountry');
            $.ajax({
                url: '/settings/editcountry',
                type: "POST",
                data: { user_id: userid, country: country },
                cache: false,
                success: function( data )
                {
                    spantext.text(country);
                    $('#user-country').text(country);
                    //$('#header-country').text(country);
                    toggleEditCountry();
                }
            }).fail(function() {
              alert( "error" );
            });;
        });

        $('#edit-paypal').submit(function(e){
            e.preventDefault();
            var userid =  $("#user_id").val();
            var paypal = $('#paypal').val();
            var spantext =  $(this).find('#spanPaypal');
            $.ajax({
                url: '/settings/editpaypal',
                type: "POST",
                data: { user_id: userid, paypal: paypal },
                cache: false,
                success: function( data )
                {
                    spantext.text(paypal);
                    toggleEditPaypal();
                }
            }).fail(function() {
              alert( "error" );
            });;
        });

        $('#edit-skrill').submit(function(e){
            e.preventDefault();
            var userid =  $("#user_id").val();
            var skrill = $('#skrill').val();
            var spantext =  $(this).find('#spanSkrill');
            $.ajax({
                url: '/settings/editskrill',
                type: "POST",
                data: { user_id: userid, skrill: skrill },
                cache: false,
                success: function( data )
                {
                    spantext.text(skrill);
                    toggleEditSkrill();
                }
            }).fail(function() {
              alert( "error" );
            });;
        });

        $('#edit-neteller').submit(function(e){
            e.preventDefault();
            var userid =  $("#user_id").val();
            var neteller = $('#neteller').val();
            var spantext =  $(this).find('#spanNeteller');
            $.ajax({
                url: '/settings/editneteller',
                type: "POST",
                data: { user_id: userid, neteller: neteller },
                cache: false,
                success: function( data )
                {
                    spantext.text(neteller);
                    toggleEditNeteller();
                }
            }).fail(function() {
              alert( "error" );
            });;
        });

        $('#edit-cash').submit(function(e){
            e.preventDefault();
            var userid =  $("#user_id").val();
            var cash = $('#cash').val();
            var enable = $('#cash_enabled').is(":checked");
            
            var spantext =  $(this).find('#spanCash');
            $.ajax({
                url: '/settings/editcash',
                type: "POST",
                data: { user_id: userid, cash: cash, enable: enable },
                cache: false,
                success: function( data )
                {
                    if($('#cash_enabled').is(":checked"))
                    {
                        spantext.text(cash);    
                    }
                    else
                    {
                        spantext.text('N/A');
                    }
                    
                    toggleEditCash();
                }
            }).fail(function() {
              alert( "error" );
            });;
        });

        $('#edit-bankwire').submit(function(e){
            e.preventDefault();
            var userid =  $("#user_id").val();
            var bankwire = $('#bankwire').val();
            var enable = $('#bankwire_enabled').is(":checked");
            
            var spantext =  $(this).find('#spanBankwire');
            $.ajax({
                url: '/settings/editbankwire',
                type: "POST",
                data: { user_id: userid, bankwire: bankwire, enable: enable },
                cache: false,
                success: function( data )
                {
                    if($('#bankwire_enabled').is(":checked"))
                    {
                        spantext.text(bankwire);    
                    }
                    else
                    {
                        spantext.text('N/A');
                    }
                    
                    toggleEditBankwire();
                }
            }).fail(function() {
              alert( "error" );
            });;
        });

        function toggleEditFirstName()
        {
            $('#spanFirstName').toggle();
            $('#firstname').toggle();    
            $("#editFirstName").toggle();
            $("#btnSubmitFirstName").toggle();
            $("#btnCancelSubmitFirstName").toggle();
        }

        $("#editFirstName").click(function() {       
            toggleEditFirstName();
        });

        $("#btnCancelSubmitFirstName").click(function(e) {
            e.preventDefault();
            toggleEditFirstName();      
        });

        function toggleEditLastName()
        {
            $('#spanLastName').toggle();
            $('#lastname').toggle(); 
            $("#editLastName").toggle(); 
            $("#btnSubmitLastName").toggle();
            $("#btnCancelSubmitLastName").toggle();
        }
        
        $("#editLastName").click(function() {        
              toggleEditLastName();
        });

        $("#btnCancelSubmitLastName").click(function(e) {
            e.preventDefault();
            toggleEditLastName();      
        });
		
		
		 function toggleEditIsPublic()
        {
            $('#spanIsPublic').toggle();
            $('#ispublic').toggle(); 
            $("#editIsPublic").toggle(); 
            $("#btnSubmitIsPublic").toggle();
            $("#btnCancelSubmitIsPublic").toggle();
        }
        
        $("#editIsPublic").click(function() {        
              toggleEditIsPublic();
        });

        $("#btnCancelSubmitIsPublic").click(function(e) {
            e.preventDefault();
            toggleEditIsPublic();      
        });

        function toggleEditCountry()
        {
            $('#spanCountry').toggle();
            $('#country').toggle(); 
            $("#editCountry").toggle(); 
            $("#btnSubmitCountry").toggle();
            $("#btnCancelSubmitCountry").toggle();
        }
        
        $("#editCountry").click(function() {        
              toggleEditCountry();
        });

        $("#btnCancelSubmitCountry").click(function(e) {
            e.preventDefault();
            toggleEditCountry();      
        });

        function toggleEditPaypal()
        {
            $('#spanPaypal').toggle();
            $('#paypal').toggle();    
            $("#editPaypal").toggle();
            $("#btnSubmitPaypal").toggle();
            $("#btnCancelSubmitPaypal").toggle();
        }

        $("#editPaypal").click(function() {       
            toggleEditPaypal();
        });

        $("#btnCancelSubmitPaypal").click(function(e) {
            e.preventDefault();
            toggleEditPaypal();      
        });

        function toggleEditSkrill()
        {
            $('#spanSkrill').toggle();
            $('#skrill').toggle();    
            $("#editSkrill").toggle();
            $("#btnSubmitSkrill").toggle();
            $("#btnCancelSubmitSkrill").toggle();
        }

        $("#editSkrill").click(function() {       
            toggleEditSkrill();
        });

        $("#btnCancelSubmitSkrill").click(function(e) {
            e.preventDefault();
            toggleEditSkrill();      
        });

        function toggleEditNeteller()
        {
            $('#spanNeteller').toggle();
            $('#neteller').toggle();    
            $("#editNeteller").toggle();
            $("#btnSubmitNeteller").toggle();
            $("#btnCancelSubmitNeteller").toggle();
        }

        $("#editNeteller").click(function() {       
            toggleEditNeteller();
        });

        $("#btnCancelSubmitNeteller").click(function(e) {
            e.preventDefault();
            toggleEditNeteller();      
        });

        function toggleEditCash()
        {
            $('#spanCash').toggle();
            $('#cash').toggle();    
            $('.cash-enable').toggle();
            $("#editCash").toggle();
            $("#btnSubmitCash").toggle();
            $("#btnCancelSubmitCash").toggle();
        }

        $("#editCash").click(function() {       
            toggleEditCash();
        });

        $("#btnCancelSubmitCash").click(function(e) {
            e.preventDefault();
            toggleEditCash();      
        });

        function toggleEditBankwire()
        {
            $('#spanBankwire').toggle();
            $('#bankwire').toggle();    
            $('.bankwire-enable').toggle();
            $("#editBankwire").toggle();
            $("#btnSubmitBankwire").toggle();
            $("#btnCancelSubmitBankwire").toggle();
        }

        $("#editBankwire").click(function() {       
            toggleEditBankwire();
        });

        $("#btnCancelSubmitBankwire").click(function(e) {
            e.preventDefault();
            toggleEditBankwire();      
        });
    });
</script>
<link rel="stylesheet" type="text/css" media="screen" href="/css/datepicker.css" /> 
<div id="content" class="content-profile">
    <div class="content-full">
		<div class="content-top">
            <div class="container pos-relative">
                @include('includes.profilesidebar')
                
            </div>
        </div>
        <div class="container settings-pane">
     		<div class="content-bottom">
				<div class="col-md-12">
            		<div class="row">
                      	<div class="col-xs-8 col-sm-6 col-md-6 packages-title">
                            <h4 class="spaced">Settings</h4>
                        </div>
                        <div class="col-xs-4 col-sm-6 col-md-6 new-package">
                          
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                		<?php
                    		$user = User::where('id',Auth::user()->id)->first();
	              		?>
                    <ul class="settings">
                        @if(Session::has('uploadInformation'))
                        <li>
                            <div class="row">
                                <div class=" col-md-7">
                                    {{ Session::get('uploadInformation') }}

                                </div>
                            </div>
                            
                        </li>
                        @endif
                        <li>
                          	{{ Form::open(array('', 'class'=>'form-signin', 'id'=>'edit-firstname')) }}
                            <div class="row">
                        		<div class=" col-md-2">
                            		<label class="col-md-12">First Name</label>
                                </div>
                                <div class=" col-md-5">
                                    <span class="col-md-8" id="spanFirstName">{{$user->firstname }}</span><input type="text" id="firstname" name="firstname" class="form-control" value="{{$user->firstname }}" style="display:none" required /> 
                 				</div>
                                <div class=" col-md-3">
                                 		<input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                    <button class="btn btn-primary" type="submit" id="btnSubmitFirstName" style="display:none">Save</button>
                                    <button class="btn btn-danger" type="submit" id="btnCancelSubmitFirstName" style="display:none">Cancel</button>
                                </div>  
                                <a href="javascript:void(0)" id="editFirstName" class="col-md-2 text-right pull-right">Edit</a>
                            </div>  
                            {{ Form::close() }}
                        </li>
                        <li>
                           {{ Form::open(array('', 'class'=>'form-signin', 'id'=>'edit-lastname')) }}
                            <div class="row">
                        		<div class=" col-md-2">
                            		<label class="col-md-12">Last Name</label>
                                </div>
                                <div class=" col-md-5">
                                   <span class="col-md-8" id="spanLastName">{{$user->lastname }}</span><input type="text" id="lastname" name="lastname" class="form-control" value="{{$user->lastname }}"  style="display:none" required/>
                         				</div>
                                <div class=" col-md-3">
                                   <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                   <button class="btn btn-primary" type="submit" id="btnSubmitLastName" style="display:none">Save</button>
                                   <button class="btn btn-danger" type="submit" id="btnCancelSubmitLastName" style="display:none">Cancel</button>
                                </div> 
                                <a href="javascript:void(0)"  id="editLastName" class="col-md-2 text-right pull-right">Edit</a>
                            </div>
                        	{{ Form::close() }}
                        </li>
                        <li>
                           {{ Form::open(array('', 'class'=>'form-signin', 'id'=>'edit-country')) }}
                            <div class="row">
                                <div class=" col-md-2">
                                    <label class="col-md-12">Country</label>
                                </div>
                                <div class=" col-md-5">
                                   <span class="col-md-8" id="spanCountry">{{$user->country }}</span>
                                    <?php
                                        $listcountry = ['' => 'No Country Selected'] + Country::lists('name', 'name');
                                    ?>
                                    {{ Form::select('country', $listcountry, $user->country, array('id' => 'country', 'class' => 'form-control', 'style' => 'display:none')) }}
                                    
                                   
                                </div>
                                <div class=" col-md-3">
                                   <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                   <button class="btn btn-primary" type="submit" id="btnSubmitCountry" style="display:none">Save</button>
                                   <button class="btn btn-danger" type="submit" id="btnCancelSubmitCountry" style="display:none">Cancel</button>
                                </div> 
                                <a href="javascript:void(0)"  id="editCountry" class="col-md-2 text-right pull-right">Edit</a>
                            </div>
                            {{ Form::close() }}
                        </li>
                        
                          <li>
                           {{ Form::open(array('', 'class'=>'form-signin', 'id'=>'edit-ispublic')) }}
                            <div class="row">
                        		<div class=" col-md-2">
                            		<label class="col-md-12">Who can see my page?</label>
                                </div>
                                <div class=" col-md-5">
                                   <span class="col-md-8" id="spanIsPublic">{{ $user->ispublic == 1 ? 'Public' : 'My Circle' }}</span>
                                   <select name="ispublic" id="ispublic" style="display:none" class="form-control" required>
                                   		<option value="1" {{$user->ispublic == 1 ? 'selected' : ''}}>Public</option>
                                        <option value="0" {{$user->ispublic == 1 ? '' : 'selected'}}>My Circle</option>
                                   </select>
                                
                         				</div>
                                <div class=" col-md-3">
                                   <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                   <button class="btn btn-primary" type="submit" id="btnSubmitIsPublic" style="display:none">Save</button>
                                   <button class="btn btn-danger" type="submit" id="btnCancelSubmitIsPublic" style="display:none">Cancel</button>
                                </div> 
                                <a href="javascript:void(0)"  id="editIsPublic" class="col-md-2 text-right pull-right">Edit</a>
                            </div>
                        	{{ Form::close() }}
                        </li>
                        
                        <li>
                           
                            <div class="row">
                            		<div class=" col-md-2">
                              	  	<label class="col-md-12">Email</label>
                                </div>
                                <div class=" col-md-5">
                                   <span class="col-md-8"  id="spanEmail">{{$user->email }}</span>
                         				</div>
                                <div class=" col-md-3">
                                   
                                </div>
                               	
                            </div>    
                       		
                        </li>                                        
                    </ul>
                    
					<h5 class="spaced pull-left">Screen Names </h5>
					
                   	<ul class="settings" id="sc1">
                        <?php
                            $array = array(
                                "pokerstars" 	=> "Pokerstars",
                                "fulltilt" 		=> "Fulltilt",
                                "888" 			=> "888",
                                "partypoker" 	=> "Party Poker",
                                "titanpoker" 	=> "Titan Poker",
								"AmericasCardRoom " 		=> "Americas Card Room ",
                            ); 
							$screennames = Screenname::where('user_id','=',Auth::user()->id)->get();
							$count = 1;
							//foreach($screennames as $screenname){
                            foreach($array as $col => $val){
                                $screenname = Screenname::where('user_id', Auth::user()->id)->where('screen_name', $val)->first();
                                $username = "N/A";
                                $id = '0';
                                if($screenname)
                                {
                                    $username = $screenname->username != '' ? $screenname->username : '';
                                    $id = $screenname->id;
                                }
						?>
  											
                      	<li>
                  			<div class="row">
                      			<div class="showscrnamepanel">
                  					<div class=" col-md-2">
                        				<label>{{$val}}</label>
                            		</div>
				                            
	                                <span class="col-md-8" >{{ $username == '' ? 'N/A' : $username }}</span>
                                    
                                    <a href="#" class="col-md-2 text-right pull-right show-edit">Edit</a>
                                    @if($screenname)
	                                <a href="#" data-screennameid="{{ $id }}" style="display:{{ ($username != '') ? 'block' : 'none' }}" data-screennametype="{{ 'scrname-'.$count }}" class="col-md-2 text-right pull-right remove">Remove</a>
                                    @else
                                    <a href="#" data-screennameid="{{ $id }}" style="display:none" data-screennametype="{{ 'scrname-'.$count }}" class="col-md-2 text-right pull-right remove">Remove</a>
                                    @endif      
	                          	</div>
	                          	<div class="editscrnamepanel" style="display:none">
		                          			
                          			<div class=" col-md-2">
	                              	  	<label class="col-md-12">{{$val}}</label>
	                                </div>
	                                <div class=" col-md-3">
	                                  	{{ Form::text('screenname',$username == 'N/A' ? '' : $username,array('id' => 'screenname' ,'class'=>'form-control', 'required'))}}
                     				</div>
	                                <div class=" col-md-3">
                                        @if($screenname)
                                        {{ Form::submit('Save',['class' => 'btn btn-primary save-screenname', 'data-screennameid' =>  $id, 'data-screennametype' => 'scrname-'.$count]) }}
                                        @else
                                        {{ Form::submit('Save',['class' => 'btn btn-primary save-screenname', 'data-screennameid' =>  $id, 'data-screennametype' => 'scrname-'.$count, 'style' => 'display:none' ]) }}
                                        {{ Form::submit('Save',['class' => 'btn btn-primary add-screenname', 'data-screennameid' =>  $val, 'data-screennametype' => 'scrname-'.$count ]) }}
                                        @endif
	                                  	<button class="btn btn-danger cancel-edit">Cancel</button>
	                                </div>
                                    {{ Form::hidden('userid', $id,array('id' => 'userid') )}}
                          			{{ Form::hidden('screennameid', $id,array('id' => 'screennameid') )}}
		                        			 	
	                          	</div>
                          	</div>
                      	</li>
                      	
                  	<?php $count++; }?>
                  	</ul>

                    <h5 class="spaced pull-left">Payment Methods </h5>
                    <ul class="settings">
                        <li>
                            {{ Form::open(array('', 'class'=>'form-signin', 'id'=>'edit-paypal')) }}
                            <div class="row">
                                <div class=" col-md-2">
                                    <label class="col-md-12">Paypal Account</label>
                                </div>
                                <div class=" col-md-5">
                                    <span class="col-md-8" id="spanPaypal">
                                        {{ $user->paypal_account == '' ? 'N/A' : $user->paypal_account }}
                                        
                                    </span><input type="text" id="paypal" name="paypal" class="form-control" value="{{$user->paypal_account }}" style="display:none" required /> 
                                </div>
                                <div class=" col-md-3">
                                    <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                    <button class="btn btn-primary" type="submit" id="btnSubmitPaypal" style="display:none">Save</button>
                                    <button class="btn btn-danger" type="submit" id="btnCancelSubmitPaypal" style="display:none">Cancel</button>
                                </div>  
                                <a href="javascript:void(0)" id="editPaypal" class="col-md-2 text-right pull-right">Edit</a>
                            </div>  
                            {{ Form::close() }}
                        </li>

                        <li>
                            {{ Form::open(array('', 'class'=>'form-signin', 'id'=>'edit-skrill')) }}
                            <div class="row">
                                <div class=" col-md-2">
                                    <label class="col-md-12">Skrill Account</label>
                                </div>
                                <div class=" col-md-5">
                                    <span class="col-md-8" id="spanSkrill">
                                        {{ $user->skrill_account == '' ? 'N/A' : $user->skrill_account }}
                                        
                                    </span><input type="text" id="skrill" name="skrill" class="form-control" value="{{$user->skrill_account }}" style="display:none" required /> 
                                </div>
                                <div class=" col-md-3">
                                    <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                    <button class="btn btn-primary" type="submit" id="btnSubmitSkrill" style="display:none">Save</button>
                                    <button class="btn btn-danger" type="submit" id="btnCancelSubmitSkrill" style="display:none">Cancel</button>
                                </div>  
                                <a href="javascript:void(0)" id="editSkrill" class="col-md-2 text-right pull-right">Edit</a>
                            </div>  
                            {{ Form::close() }}
                        </li>
                        <li>
                            {{ Form::open(array('', 'class'=>'form-signin', 'id'=>'edit-neteller')) }}
                            <div class="row">
                                <div class=" col-md-2">
                                    <label class="col-md-12">Neteller Account</label>
                                </div>
                                <div class=" col-md-5">
                                    <span class="col-md-8" id="spanNeteller">
                                        {{ $user->neteller_account == '' ? 'N/A' : $user->neteller_account }}
                                        
                                    </span><input type="text" id="neteller" name="neteller" class="form-control" value="{{$user->neteller_account }}" style="display:none" required /> 
                                </div>
                                <div class=" col-md-3">
                                    <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                    <button class="btn btn-primary" type="submit" id="btnSubmitNeteller" style="display:none">Save</button>
                                    <button class="btn btn-danger" type="submit" id="btnCancelSubmitNeteller" style="display:none">Cancel</button>
                                </div>  
                                <a href="javascript:void(0)" id="editNeteller" class="col-md-2 text-right pull-right">Edit</a>
                            </div>  
                            {{ Form::close() }}
                        </li>
                        <li>
                            {{ Form::open(array('', 'class'=>'form-signin', 'id'=>'edit-cash')) }}
                            <div class="row">
                                <div class=" col-md-2">
                                    <label class="col-md-12">Cash</label>
                                </div>
                                <div class=" col-md-5">
                                    <span class="col-md-8" id="spanCash">
                                        {{ $user->cash == '' ? 'N/A' : $user->cash }}
                                        
                                    </span>
                                    <input type="text" id="cash" name="cash" class="form-control" value="{{$user->cash }}" style="display:none" required /> 
                                    
                                    
                                </div>
                                <div class=" col-md-3 cash-enable" style="display:none">
                                    <label>Enable</label> <input type="checkbox" name="cash_enabled" id="cash_enabled"  {{ $user->cash_enabled ? 'checked' : '' }} > 
                                </div>
                                <div class=" col-md-3">
                                    <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                    <button class="btn btn-primary" type="submit" id="btnSubmitCash" style="display:none">Save</button>
                                    <button class="btn btn-danger" type="submit" id="btnCancelSubmitCash" style="display:none">Cancel</button>
                                </div>  

                                <a href="javascript:void(0)" id="editCash" class="col-md-2 text-right pull-right">Edit</a>
                            </div>  
                            {{ Form::close() }}
                        </li>
                        <li>
                            {{ Form::open(array('', 'class'=>'form-signin', 'id'=>'edit-bankwire')) }}
                            <div class="row">
                                <div class=" col-md-2">
                                    <label class="col-md-12">Bank Wire</label>
                                </div>
                                <div class=" col-md-5">
                                    <span class="col-md-8" id="spanBankwire">
                                        {{ $user->bank_wire == '' ? 'N/A' : $user->bank_wire }}
                                        
                                    </span>
                                    <input type="text" id="bankwire" name="bankwire" class="form-control" value="{{$user->bank_wire }}" style="display:none" required /> 
                                    
                                    
                                </div>
                                <div class=" col-md-3 bankwire-enable" style="display:none">
                                    <label>Enable</label> <input type="checkbox" name="bankwire_enabled" id="bankwire_enabled"  {{ $user->bank_wire_enabled ? 'checked' : '' }} > 
                                </div>
                                <div class=" col-md-3">
                                    <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                    <button class="btn btn-primary" type="submit" id="btnSubmitBankwire" style="display:none">Save</button>
                                    <button class="btn btn-danger" type="submit" id="btnCancelSubmitBankwire" style="display:none">Cancel</button>
                                </div>  

                                <a href="javascript:void(0)" id="editBankwire" class="col-md-2 text-right pull-right">Edit</a>
                            </div>  
                            {{ Form::close() }}
                        </li>
                    </ul>
                                      
                    {{ Form::open(array('url'=>'users/screenname', 'class'=>'form-signin', 'id'=>'form-setting')) }}
                                  	  
                    <ul class="settings" id="sc2" style="display:none">
                    <?php 
						$array = array(
								"pokerstars" 	=> "Pokerstars",
								"fulltilt" 		=> "Fulltilt",
								"888" 			=> "888",
								"partypoker" 	=> "Party Poker",
								"titanpoker" 	=> "Titan Poker",
								"AmericasCardRoom " => "Americas Card Room ",
						);
									
						foreach ($array as $col => $val) {
							//echo "\$array[$k] => $v.\n";									
							$xx = '';
							foreach($screennames as $screenname){
								if($val == $screenname->screen_name){
									//echo $screenname->screen_name.'<br>';
									$xx = $screenname->screen_name;
					?>
						<li>
        					<div class="row">
                                <div class=" col-md-2">
                                    <label>{{$val}}</label>
                           		</div>
                                <div class=" col-md-3">                                                                       
                                    <input type="text" id="{{$col}}" name="{{$col}}" class="form-control" value="{{$screenname->username}}" />
                                </div>
                            </div>
		                     
            			</li>
					<?php
								}
							}
							if($xx != $val){										
					?>
						<li>
          					<div class="row">
                                <div class=" col-md-2">
                                    <label>{{$val}}</label>
                         		</div>
                                <div class=" col-md-3">                                                                       
                                    <input type="text" id="{{$col}}" name="{{$col}}" class="form-control" value="" />
                                </div>
                            </div>
		                     
              			</li>													
					<?php
							}
					   }
					?>                     
                        <li>           	
                            <div class="row">
                                <div class=" col-md-2">
                                   <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                             		</div>
                                <div class=" col-md-3">
                                   <button class="btn btn-primary" type="submit">Save</button>
                                </div>
                            </div>     
                        </li>
                    </ul>
                    {{ Form::close() }}
                                     
                </div>
                          
          	</div>
		</div>
    </div>	

    <script type="text/javascript">

    </script>

    <script type="text/javascript" src="/js/jquery_.js"></script>
    <script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>

    <script type="text/javascript">
	jQuery.noConflict();
	$(function(){
	
		jQuery("#editScreenname").click(function() {
				
			jQuery('#sc1').toggle();
			jQuery('#sc2').toggle();			
		});
		
		
		//  jQuery("#editFirstName").click(function() {				
		// 	jQuery('#spanFirstName').toggle();
		// 	jQuery('#firstname').toggle();		
		// 	jQuery("#editFirstName").toggle();
		// 	jQuery("#btnSubmitFirstName").toggle();				
		// });
		
		// jQuery("#editLastName").click(function() {				
		// 	jQuery('#spanLastName').toggle();
		// 	jQuery('#lastname').toggle();	
		// 	jQuery("#editLastName").toggle();	
		// 	jQuery("#btnSubmitLastName").toggle();		
		// });
		
		// jQuery("#editEmail").click(function() {				
		// 	jQuery('#spanEmail').toggle();
		// 	jQuery('#email').toggle();	
		// 	jQuery("#editEmail").toggle();	
		// 	jQuery("#btnSubmitEmail").toggle();					
		// });
		
     });
    </script>
    <!--- Add Package Modal End --->

@stop