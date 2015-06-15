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

        $('.btn-comment').each(function()
        {
            $(this).click(function (e)
            {
                e.preventDefault();
                var packageid = $(this).data('packageid');
                $.ajax({
                    type: "POST",
                    url: "/packages/ajax/getcomment",
                    data: { packageid: packageid },
                    cache: false,
                    success: function(data){
                        $('#send-comment').data('packageid', packageid);
                        if(data.success == "success")
                        {
                            var html = '';
                            for (var i = 0; i < data.comments.length; i++) {
                                var comment = data.comments[i];

                                html += '<div class="pull-left comment-content">';
                                html += '<div class="image-wrap">';
                                html += '<img src="'+ comment.image +'" alt="">'
                                html += '</div>';
                                html += '<div class="pull-left content-text-wrap">';
                                html += '<p><b>'+ comment.fullname +'</b> Comments on this Package</p>';
                                html += '<p class="font-sm datetime-format" data-messagetime="' + comment.created.date + '">'+ comment.updated.date +'</p>';
                                html += '</div>';
                                html += '</div>';
                            }

                            $('.comment-list').html(html);

                            $('.datetime-format').each(function() {
                                var dateTime = $(this).data('messagetime');
                                //$(this).text(DateFormat.format.prettyDate(dateTime));
                                $(this).text(moment(dateTime, 'YYYY-MM-DD HH:mm Z').fromNow());

                            });

                        }
                        else
                        {
                            //alert("empty");
                            var html = '';
                            html += '<div class="pull-left comment-content">';
                            html += 'No Comment yet';
                            html += '</div>';
                            $('.comment-list').html(html);
                            console.log($('.comment-list').html());
                        }
                        console.log( 'get comments ' + packageid + ' - '+ data.comments.length);

                        $('#modal-comment').modal('show'); 
                    }
                });
                
                console.log(packageid);
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
                            <h4 class="spaced">Active Package(s)</h4>
                            </div>
                            <div class="col-xs-4 col-sm-6 col-md-6 new-package">
                            @if( Auth::check() && $selectedUser->id == Auth::user()->id)
                            <a href="#" data-toggle="modal" data-target=".add-package" class="btn btn-default pull-right"><i class="glyphicon glyphicon-plus-sign green"></i><b>  New Package</b></a>
                            @endif
                            </div>
                        </div>
                </div>
                        <!-- start active packages -->
                        
                    <?php 
					if ($selectedUser->id)
					{
						$user_id = $selectedUser->id;
					}
					
					function packageInterval($endDate)
					{  
							$datetime1 = new DateTime();
							$datetime2 = new DateTime("@" . $endDate);
							$interval = $datetime1->diff($datetime2);
							
							$day =  $interval->format('%d');
							$hour =  $interval->format('%h');
							$minutes =  $interval->format('%i');
							
							$totalleft = '';
							if($day != 0)
								$totalleft = $day.'D ';
							if($hour != 0)
								$totalleft .= $hour.'H ';
							if($minutes != 0)
								$totalleft .= $minutes.'M';
								
							return $totalleft;//$interval->format('%d D %h H %i M');
					}
						
						$packages = Package::where('user_id','=', $user_id)
									->where('cancel','!=',1)
									->orderBy('created_at','DESC')
									->paginate(2);
						
						if(!is_null($packages)){
							$index=0;
							foreach ($packages as $package)								
							{								
								
						?>
				<div class="col-md-6">
            		<div class="panel panel-default package-wrap">
                        <div class="panel-body">
                            <div class="row package-head">
                                <h3><b>{{$package->title}}</b><span class="label label-primary pull-right">ACTIVE</span></h3>
                                
                                <p class="font-sm">POSTED ON {{ strtoupper(date("j M, Y",strtotime($package->posted))) }} <span>ENDING IN 
                                <b>{{ packageInterval(strtotime($package->ended))}}</b></span></p>
                                <?php 
								$_payment = '';												
								foreach($package->payments as $payment)
								{
									
									if($_payment!='')
									{
										$_payment .= ', '.$payment->payment_name;
									}else
									{
										$_payment = $payment->payment_name;
									}	
								}											
								?>
                                <p class="font-sm grey pull-left mtop5">Payment available : <b>{{$_payment}}</b>                                           
                                </p>
                          	</div>
                            <div class="row">
                            	<div class="col-xs-8 col-sm-9 col-md-8 package-content">
                                	<div class="row package-tournament-list">
                                    	<table class="package-tournaments">
                                        		<tr>
                                                	<th class="spaced">Tournament</th>
                                                    <th class="spaced">Buy-In</th>
                                                </tr>
                                                <?php
                                                $turnaments = $package->turnaments;
												//setlocale(LC_MONETARY, 'en_US');
												$total_buyin =0;
												foreach($turnaments as $turnament)
												{
													$total_buyin .= $turnament->buyin;
												?>
                                                <tr>
                                                	<td>{{$turnament->name}}</td>
                                                    <td>${{money_format('%(#10n', $turnament->buyin)}}</td>
                                                </tr>
                                              	<?php }?>
                                        </table>
                                    </div>
                                    <div class="row package-total-value">
                                    	
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                        	<div class="row">
                                            Facebook Share
                                        	</div>
                                        </div>
                                        
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                        	<div class="row">
                                        		<p class="font-sm">Total</p>
                                                <p><b>${{money_format('%(#10n', $package->total)}}</b></p>
                                        	</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-3 col-md-4" style="position:static;">
                                	<div class="row package-right">
                                		<p class="font-sm">Mark-up</p>
                                        <p><b>{{$package->markup}}</b></p>
                                        <p class="font-sm">Sold</p>
                                        <p><span class="blue"><b>{{$package->sellingPercent()}}%</b></span> of <b>50%</b></p>
                                        
                                        @if( Auth::check() && $selectedUser->id != Auth::user()->id)
                                        <!--	<a class="btn btn-primary btn-buy toggle" href="#" data-toggle="modal" data-target=".percent1{{$package->id}}"><span class="glyphicon glyphicon-shopping-cart"></span> Buy</a>
                                        -->
                                        <button data-toggle="modal" data-target=".percent1{{$package->id}}" class="btn btn-lg btn-buy-amount"  <?php if(($package->selling - $package->sellingPercent()) == 0) echo 'disabled'; ?>>BUY
                            			<span class="amount-value-sm"></span></button>
                                        @elseif( Auth::check() && $selectedUser->id == Auth::user()->id)
                                        	
                                            <a class="btn btn-danger" href="/packs/cancel/{{$package->id}}"><span class="glyphicon glyphicon-trash"></span> Cancel </a>
                                        @else
                                        <!-- 	<a class="btn btn-primary btn-buy toggle" href="#" data-toggle="modal" data-target=".percent1{{$package->id}}"><span class="glyphicon glyphicon-shopping-cart"></span> Buy</a>
                                       	-->
                                         <button data-toggle="modal" data-target=".percent1{{$package->id}}" class="btn btn-lg btn-buy-amount"  <?php if(($package->selling - $package->sellingPercent()) == 0) echo 'disabled'; ?>>BUY
                            			<span class="amount-value-sm"></span></button>
                                        
                                        @endif 	
                 
                                        <div class="modal fade percent1{{$package->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content buy-amount-dialog">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                                        </div>
                                                        <div class="panel-body">
                                                            {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                                            <input type="hidden" id="package_id" name="package_id" value="{{ $package->id }}" />
                                                            <input type="hidden" id="package_name" name="package_name" value="{{ $package->title }}" />
                                                            <input type="hidden" id="selling_price" name="selling_price" value="" />
                                                                                      
                                                            <div class="col-md-12" style="border-right:solid 1px #ccc;">
                                                                <span class="font-sm">
                                                                    <b>Percentage</b>
                                                                </span>
                                                         <?php 
            											 
            											 											 
            											 
            											 	$soldPercent = $package->sellingPercent();
            												$sellPercent =  $package->selling;												
            												$availablePencent = $sellPercent - $soldPercent;												
            												$countX = $availablePencent/$package->button1;												
            												
            											 ?>
                                                         	<script type="text/javascript">
            												$( document ).ready(function() {
                
            													$("#selling").change(function () {
            														var percent = this.value;
            														var total = {{$package->total}};
            														var value = total * (percent/100);
            														$("#selling_price").val(value);
            														
            													});
            													
            													var percent = $("#selling").val();
            													var total = {{$package->total}};
            													var value = total * (percent/100);
            													$("#selling_price").val(value);
            													
            													//console.log( "ready!" );
            												});
                                                            </script>
                                                         
                                                           		<select id="selling" name="selling" class="form-control form-sm">
                                                                 @for ($x=1; $x<=$countX; $x++)                                                          
                                                                 	<option value="{{$package->button1 * $x}}">{{$package->button1 * $x}}% - ${{$package->total * (($package->button1 * $x)/100)}}</option>
                                                                  @endfor
                                                                </select>
                                                            </div>
                                                    
                                                            <div class="payment-by">
                                                                <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                                    <p class="font-sm"><b>Payment by</b></p>
                                                                                        
                                                                    <select class="form-control form-sm"  id="payment_method" name="payment_method">
                                                                        <option value="pokerstars">PokerStars</option>
                                                                        <option value="fulltilt">Fulltilt</option>
                                                                        <option value="888">888</option>
                                                                        <option value="partypoker">Party Poker</option>
                                                                         <option value="titanpoker">Titan Poker</option>
                                                                        <option value="ipoker">iPoker</option>
                                                                    </select>                                                 
                                                                     
                                                                                       
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p class="font-sm"><b>In Game Name</b></p>
                                                                    <input type="text" class="form-control form-sm" id="in_game_name"  name="in_game_name" required>
                                                                </div>
                                                            </div>
                                                            <div class="panel-footer">
                                                            	<button class="btn btn-primary spaced font-sm" type="submit">Pay &amp; Submit</button>
                                                                <button class="btn btn-grey spaced pull-right font-sm" type="button" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                            {{ Form::close() }}
                                                        </div>                      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer pull-left">
                          	<div class="col-xs-10 col-sm-10 col-md-10">
                            	<div class="row">
                                    <p>Comments from Seller</p>
                                    <p>{{$package->notes}}</p>
                            	</div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 btn-comment-wrap">
                            	<a href="#" data-packageid="{{$package->id}}" id="package-{{$package->id}}" class="btn btn-default btn-comment pull-right">Comment<span class="badge-sm pull-right">{{ $package->comments->count() }}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                        <?php $index++;
							}
						}else{  ?>
                        	There is no package.							
						<?php } ?>
                        <!-- End active packages -->
                <div class="col-md-12">
            		<div class="row"> 
           				{{ $packages->links() }} 
           			</div>
                </div>
                        
            </div>
        </div>
  		
    </div>	

</div>
<!-- Comment Dialog Box -->
    <div class="modal fade comment-box" id="modal-comment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content buy-amount-dialog">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title spaced">Comments</h4>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12" style="border-right:solid 1px #ccc;">
                            <div class="comment-list">
                                
                            </div>
                            <div class="comment-box-area">
                                <span class="small">
                                    Comment on this
                                </span>
                                <textarea class="form-control" cols="3"></textarea>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <a href="#" id="send-comment" data-packageid="0" class="btn btn-primary font-sm">Comment</a>
                        </div>
                    </div>      
                </div>
            </div>
        </div>
    </div>

    
    <!-- Comment Box End -->
    
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
                                                            <div class="form-group col-md-2">
                                                                <label>Selling</label>
                                                                <input class="form-control"  id="add-selling" name="add-selling" placeholder="Enter Percentage to Sell" type="text" value="50">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label>Mark Up</label>
                                                                <input class="form-control" id="markup" name="markup" placeholder="Enter Mark Up Value" type="text" value="1.5">
                                                            </div>
                                                    
                                                            <div class="form-group col-md-3">
                                                                <label>Selling Amount</label>
                                                                <p class="amount-total"><input name="selling_amount" id="selling_amount" disabled="disabled" type="text" /></p>
                                                                <input name="selling_amount_form" id="selling_amount_form" type="hidden" />
                                                           		<span id="keterangan"></span>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label>Total</label>
                                                                <p class="amount-total"><input name="total" id="total" disabled="disabled" type="text" /></p>
                                                                <input name="total_form" id="total_form" type="hidden" />
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
                                                       <input type="hidden" name="user_id" id="user_id" value="<?php if(Auth::check()) echo Auth::user()->id; ?>" />
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
			var selling = Number(jQuery('#add-selling').val());
			var sellingP = (selling/100);
			var total =  0;//(totalbuyin * sellingP) ;
			
			//alert(sellingP);
			
			var markup = 0;
			if(jQuery('#markup').val()=='') markup = 0;
			else markup = jQuery('#markup').val();
			
			totalmarkup = totalbuyin * markup;
			
			selling_total = totalmarkup * sellingP;
			
			total = totalbuyin * markup;//(totalbuyin+selling_total)-(totalbuyin*sellingP);
			//jQuery('#keterangan').html('selling='+selling+' , selling % ='+ sellingP + ' , markup = '+ totalmarkup +' , totalbuyin = '+totalbuyin + ', total = '+ total);
						
			jQuery('#selling_amount').val(selling_total); 
			jQuery('#selling_amount_form').val(selling_total);
			
			jQuery('#total').val(total); 
			jQuery('#total_form').val(total);
			
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