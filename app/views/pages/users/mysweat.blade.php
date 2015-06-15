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
       $('#addcircle').click(function(e)
        {
            e.preventDefault();
            var userID = $(this).data("userid");

            $.post("/sendcirclerequest/" + userID, function( data ) {
                //alert(data);
                $('#addcircle').remove();
                var html = '<a href="#" class="btn btn-primary"><span class="checks">Add</span><b>Request Sent</b></a>'
                $('.user-btn-wrap').prepend(html);
                //alert( "Load was performed." );
            }, "json")
            .fail(function() {
                alert( "circle request failed" );
            });
        });
        
        $('#accept-button').click(function(e){
          e.preventDefault();
          var friendRequestID = $(this).data('friendrequestid');
          $.ajax({
            type: "POST",
            url: "/circle/ajax/accept",
            data: { query: friendRequestID },
            cache: false,
            success: function(html){
              $('#accept-button').remove();
              $('#reject-button').remove();
              console.log( 'accepted ' + friendRequestID);
            }
          });
          

        });

        
        $( '#reject-button' ).click(function(e){
          e.preventDefault();
          var friendRequestID = $(this).data('friendrequestid');
          //console.log(friendRequestID)
          $.ajax({
            type: "DELETE",
            url: "/circle/ajax/reject",
            data: { query: friendRequestID },
            cache: false,
            success: function(html){
              $('#accept-button').remove();
              $('#reject-button').remove();
              console.log( 'rejected ' + friendRequestID);
            }
          });
          
        });

        $('.pastpackage-showdetail').each(function(){
            $(this).click(function(e){
                var packageid = $(this).data('packageid');
                $('.loading-packagedetail').show();
                $('#packagedetail-content').hide();
                $('#modal-package').modal('show');

                $.ajax({
                    type: "POST",
                    url: "/packages/ajax/retrievepackage",
                    data: { packageid: packageid },
                    cache: false,
                    success: function(data){
                        console.log(data.title);
                        $('#packagedetail-title').text(data.title);
                        $('#packagedetail-posted').text('POSTED ON '+ data.posted);
                        $('#packagedetail-payments').html('Payment available : <b>'+ data.payments + '</b>');
                        var table = '<tbody><tr><th class="spaced">Tournament</th><th class="spaced">Buy-In</th></tr>';
                        for (var i = 0; i < data.tournaments.length; i++) {
                            var tournament = data.tournaments[i];
                            table += '<tr>';
                            table += '<td>';
                            table += tournament.url;
                            table += '</td>';
                            table += '<td>';
                            table += '$' + tournament.buyin;
                            table += '</td>';
                            table += '</tr>';
                        }
                        table += '</tbody>';
                        $('#packagedetail-tournaments').html(table);
                        $('#packagedetail-total').text('$' + data.total);
                        $('#packagedetail-markup').text(data.markup);
                        $('#packagedetail-sold').text(data.sold);
                        $('.loading-packagedetail').hide();
                        $('#packagedetail-content').show();
                    }
                });


            });
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
        <div class="container">
            <div class="content-bottom">
				<div class="col-md-12">
                		<div class="row">
                        	<div class="col-xs-8 col-sm-6 col-md-6 packages-title">
                            <h4 class="spaced">My Sweats</h4>
                            </div>
                            <div class="col-xs-4 col-sm-6 col-md-6 new-package">
                           	
                            </div>
                        </div>
                </div>
                        <!-- start active packages -->
                                        
                      <div class="col-md-12">
                            		<div class="row">
                                    
                                    <?php
									$user_id = Auth::user()->id;
                                    $orders = Order::where('user_id','=',$user_id)->where('payment_status','Approved')->paginate(6);							
									?>
                                    
                                    @foreach($orders as $order)	
                                    	<?php
											$orderdetail =  OrderDetail::where('order_id','=',$order->id)->first();
											$package = Package::find($orderdetail->package_id);
											$userp = User::find($package->user_id); 
										?>
                                        <div class="col-xs-6 col-sm-6 col-md-4 sweats-wrap">
                                        		<div class="panel panel-default my-sweats">
                                                  <div class="panel-heading pos-relative">
                                                  <div class="row">
                                                    <div class="image-wrap">
                                                    	<img src="{{ $userp->getPhotoUrl() }}" alt="">
                                                    </div>
                                                    <div class="pull-left content-text-wrap">
                                                    	<h4><a class="pastpackage-showdetail" data-packageid="{{$package->id}}"> {{$package->title }} </a></h4>
                                                        <h6><a href="{{ url('/about-me/'.$userp->id) }}">{{$userp->firstname}} {{$userp->lastname}}</a></h6>
                                                    </div>
                                                  </div>
                                                  </div>
                                                  
                                                  
                                                  <div class="panel-body">
                                                		<div class="row">
                                                        	<div class="col-xs-5 col-sm-5 col-md-5 sweats-percent-bought">
                                                            	<p class="font-sm text-center">You Bought</p>
                                                                <p class="font-big text-center"><b>{{$orderdetail->selling}}%</b></p>
                                                            </div>
                                                            
                                                            <div class="col-xs-7 col-sm-7 col-md-7 sweats-amount-bought">
                                                            	<p class="font-sm text-right">Value</p>
                                                                <p class="font-big text-right"><b>$ {{$orderdetail->selling_price}}</b></p>
                                                            </div>
                                                        </div>    
                                                  </div>
                                                
                                                
                                                </div>
                                        </div>
                                     
                                       	@endforeach
                                       
                                                
                                                
                                                </div>
                                        </div>
                                        
                                        
                                  
                        <!-- End active packages -->
                      <div class="col-md-12">
                        		<div class="row"> 
                       				{{ $orders->links() }} 
                       			</div>
                      </div>
                        
        </div>
        </div>
  		
    </div>	

    </div>
    
    <!-- Package Detail Box Start -->
    <div class="modal fade package-box" id="modal-package" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content buy-amount-dialog">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title spaced">Package Detail</h4>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12" style="border-right:solid 1px #ccc;">
                            <div class="loading-packagedetail" style="text-align: center;margin-top: 20px;display:none">
                                <img src="{{url('/img/loading.GIF')}}" style="width:30px;height:30px;" >
                            </div>
                            <div id="packagedetail-content">
                                <div class="panel-body">
                                    <div class="row package-head">
                                        <h3><b id="packagedetail-title">Awesome Package </b></h3>
                                        
                                        <p class="font-sm" id="packagedetail-posted">POSTED ON 5 APR, 2015</p>
                                        <p class="font-sm grey pull-left mtop5" id="packagedetail-payments">Payment available : <b>Pokerstars, Fulltilt</b>                                           
                                        </p>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-8 col-sm-9 col-md-8 package-content">
                                            <div class="row package-tournament-list">
                                                <table class="package-tournaments" id="packagedetail-tournaments">
                                                    
                                                </table>
                                            </div>
                                            <div class="row package-total-value">                                                                                                
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <div class="row">
                                                        <p class="font-sm">Total</p>
                                                        <p><b id="packagedetail-total">$         15.00</b></p>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-3 col-md-4" style="position:static;">
                                            <div class="row package-right">
                                                <p class="font-sm">Mark-up</p>
                                                <p><b id="packagedetail-markup">1.50</b></p>
                                                <p class="font-sm">Sold</p>
                                                <p><span class="blue"><b id="packagedetail-sold">0%</b></span> of <b id="packagedetail-available">50%</b></p>                                        
                                                    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>      
                </div>
            </div>
        </div>
    </div>

    
<!-- Package Detail Box End -->
     <!--- Add Package Modal Start --->
    <div class="modal fade add-package" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content add-package-dialog">
              <div class="panel panel-default">
                 	<div class="panel-heading">
                    	<h4 class="panel-title spaced">Add New Package</h4>
                  	</div>
                    <div class="panel-body">
        		
                
               
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="tab-head active"><a href="#addpackage-manual" class="spaced" role="tab" data-toggle="tab">Manual</a></li>
                      <li class="tab-head"><a href="#addpackage-reserved" class="spaced" role="tab" data-toggle="tab">Reserved</a></li>	
                    </ul>
                    <div id="myTabContent" class="tab-content">
                      <div class="tab-pane fade active in" id="addpackage-manual">
                        			<div class="col-md-12 mtop20">
                                    	{{ Form::open(array('url'=>'packs/create', 'class'=>'form-signin', 'id'=>'form-package')) }}
                                    						<div class="row">
                                                            		<div class="form-group col-md-7">
                                                                        <label>Package Name</label>
                                                                        <input class="form-control" name="title" id="title" placeholder="Enter Package Name" type="text" required>
                                                                    </div>
                                                                    
                                                                    <div class="form-group col-md-5">
                                                                        <label>Ended</label>
                                                                        <select id="ended" name="ended"  class="form-control">
                                                                        	<option value="12">12 Hours</option>
                                                                            <option value="24">24 Hours</option>
                                                                            <option value="48">2 Days</option>
                                                                            <option value="72">3 Days</option>
                                                                            <option value="96">4 Days</option>
                                                                            <option value="120">5 Days</option>
                                                                            <option value="144">6 Days</option>
                                                                            <option value="168">7 Days</option>
                                                                        </select>
                                                                    </div>
                                                            </div>
                                                            
                                                             
                                                            
                                                            <hr class="divider">
                                    
                                                         
                                                            
                                                            
                                                            <ul class="addtour-wrap">
                                                            	 <li>
                                                                    <div class="row">
                                                                        <div class="form-group col-md-7">
                                                                            <label>Tournament Name</label>
                                                                   		</div>
                                                                        <div class="form-group col-md-5">
                                                                            <label>Buy In</label>
                                                                       </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row addtour">
                                                                        <div class="form-group col-md-7">
                                                                           <input class="form-control" id="turnament[]" name="turnament[]" placeholder="Enter Tournament Name" type="text">
                                                                    </div>
                                                                        <div class="form-group col-md-5">
                                                                           <input class="form-control hitung" id="buyin[]" name="buyin[]" placeholder="Enter Buy In Amount" type="text">
                                                                    	</div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                               <div class="row">
                                                             <div class="col-md-12">
                                                                        <a href="#" class="abc"><span class="glyphicon glyphicon-plus"></span>  Add Tournaments</a>
                                                              </div>
                                                            </div>
                                             				<hr class="divider">
                                                    
                                                            <div class="row">
                                                            <div class="form-group col-md-5">
                                                                <label>Selling</label>
                                                                <input class="form-control"  id="selling" name="selling" placeholder="Enter Percentage to Sell" type="text" value="50">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label>Mark Up</label>
                                                                <input class="form-control" id="markup" name="markup" placeholder="Enter Mark Up Value" type="text" value="1.5">
                                                            </div>
                                                    
                                                            <div class="form-group col-md-4">
                                                                <label>Selling Amount</label>
                                                                <p class="amount-total"><input name="selling_amount" id="selling_amount" disabled="disabled" type="text" /></p>
                                                                <input name="selling_amount_form" id="selling_amount_form" type="hidden" />
                                                           		<span id="keterangan"></span>
                                                            </div>
                                                            <div class="col-sm-12 col-md-12">
                                                            	<label><b>Payment Available by :</b><label>
                                                            </div>
                                                            <div class="col-sm-3 col-md-3">
                                                            		<div class="checkbox">
                                                                        <label>
                                                                          <input type="checkbox" id="payment[]" name="payment[]" value="Pokerstars"> Pokerstars
                                                                        </label>
                                                                      </div>
                                                            </div>
                                                            <div class="col-sm-3 col-md-3">
                                                            		<div class="checkbox">
                                                                        <label>
                                                                          <input type="checkbox" id="payment[]" name="payment[]" value="Fulltilt"> Fulltilt
                                                                        </label>
                                                                      </div>
                                                            </div>
                                                            <div class="col-sm-3 col-md-3">
                                                            		<div class="checkbox">
                                                                        <label>
                                                                          <input type="checkbox" id="payment[]" name="payment[]" value="888"> 888
                                                                        </label>
                                                                      </div>
                                                            </div>
                                                            <div class="col-sm-3 col-md-3">
                                                            		<div class="checkbox">
                                                                        <label>
                                                                          <input type="checkbox" id="payment[]" name="payment[]" value="Party Poker"> Party Poker
                                                                        </label>
                                                                      </div>
                                                            </div>
                                                            <div class="col-sm-3 col-md-3">
                                                            		<div class="checkbox">
                                                                        <label>
                                                                          <input type="checkbox" id="payment[]" name="payment[]" value="Titan Poker"> Titan Poker
                                                                        </label>
                                                                      </div>
                                                            </div>
                                                            </div>
                                                    
                                                    <hr class="divider">
                                                    
                                                            <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label>Note</label>
                                                                <textarea class="form-control" rows="3" id="notes" name="notes" placeholder="Enter Note for Package"></textarea>
                                                            </div>
                                                            </div>
                                                    <hr class="divider">
                                                            <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label>Button 1</label>
                                                                <input class="form-control" id="button1" name="button1" placeholder="Enter Percentage Value" type="text" required>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label>Button 2</label>
                                                                <input class="form-control" id="button2" name="button2" placeholder="Enter Percentage Value" type="text" required>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label>Button 3</label>
                                                                <input class="form-control" id="button3" name="button3" placeholder="Enter Percentage Value" type="text" required>
                                                            </div>
                                                            </div>
                                                       <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                                                      <button class="btn btn-primary" type="submit">Submit Package</button>                                              
                                                </div>
                                    
                                    {{ Form::close() }}
                                        
                                  </div>
                                  <!-- Add Reserved -->
                                      <div class="tab-pane fade" id="addpackage-reserved">
                                        
                                      </div>
                                      
                                  <!-- Add Reserved End -->
                                 
                    </div>
                    
                    
                    
              		</div>
                    
        </div>
      </div>
    </div>
    </div>
    <script type="text/javascript" src="/js/jquery_.js"></script>
    <script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>

    <script type="text/javascript">
	jQuery.noConflict();
	$(function(){
	
		var i = 1;
		$(".abc").click(function() {
			$(".addtour-wrap li:nth-child(2)").clone().find("input").each(function() {
				$(this).val('');
			}).end().appendTo(".addtour-wrap");
			i++;
			
			jQuery('.hitung').keyup(function () { 
				callBuyin();
			 });
		});
		 
		function callBuyin(){
				var buyins = document.getElementsByName('buyin[]');
			var totalbuyin = 0;
			
			for (var i=0; i<buyins.length; i++)
			{
				totalbuyin += Number(buyins[i].value);
			}
			var sellingP = Number(jQuery('#selling').val())/100;
			var total =  (totalbuyin * sellingP) ;
			
			//jQuery('#keterangan').html('selling % ='+ sellingP + ' , totalbuyin = '+totalbuyin + ', total = '+ total);
			
			var markup = 0;
			if(jQuery('#markup').val()=='') markup = 0;
			else markup = jQuery('#markup').val();
			
			//totalmarkup = totalbuyin * (markup/100);
			
			total = total * markup;
			
			jQuery('#selling_amount').val(total); 
			jQuery('#selling_amount_form').val(total);
			
		}
		 
		 jQuery('#selling').keyup(function () { 
		 	callBuyin();
		 });
		 
		 jQuery('#markup').keyup(function () { 
		 	callBuyin();
		 });
		 
		 jQuery('.hitung').keyup(function () { 
		 	callBuyin();
		 });
		 
		 jQuery('#form-package').bootstrapValidator({
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				'payment[]': {
					validators: {
						choice: {
							min: 1,
							max: 5,
							message: 'Please choose payment you are good at'
						}
					}
				}
			}
		});
     });
    </script>
    <!--- Add Package Modal End --->

@stop