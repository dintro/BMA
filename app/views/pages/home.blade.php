@extends('layouts.master')
@section('content')
	
	@if(Session::has('message'))
        <div class="alert alert-danger alert-wrap">
            <div>{{ Session::get('message') }}</div>
        
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>	
        </div>
    @endif
				
	<div class="row mtop20 mbtm30">
        <h3 class="text-center spaced"><span class="spade" style="display:inline-block;width:30px; margin-right:20px;">.</span>Search Packages</h3>
        <div class="col-md-8 col-md-offset-2">
            {{ Form::open(array('route'=>'searchPackageHome','method' => 'GET')) }}
    		<div class="input-group input-group-lg">
                

                <input type="text" name="query" id="query" value="{{ $query }}" class="form-control" style="margin-top:1px;">
                <span class="input-group-btn">
                    <button class="btn btn-default glyphicon glyphicon-search search-main" type="button"></button>
                </span>
                {{ Form::hidden('display', $display )}}
                
            </div>
            {{ Form::close() }}
        </div>
    </div>
                            
    <div>
    <?php
    		$paging = 20;
			
			if(isset($_REQUEST['display']))
			{
				$paging = $_REQUEST['display'];
			}
	?>
        <ul id="myTab" class="nav nav-tabs home-tabs" role="tablist">
            <li class="active"><a href="#home-public" class="public-tab spaced" role="tab" data-toggle="tab">Public</a></li>
            @if(Auth::check())
            <li class=""><a href="#home-mycircle" class="circle-tab spaced" role="tab" data-toggle="tab">My Circle</a></li>
            @endif
            <li class="display-number">

          		<select class="form-control transparent" onchange="location.href='?query=<?php echo $query?>&display=' + this.options[this.selectedIndex].value;">
                    <option value="20" <?php if($paging==20) echo 'selected';?>>Display 20 Player Per Page</option>
                    <option value="40" <?php if($paging==40) echo 'selected';?>>Display 40 Player Per Page</option>
                    <option value="60" <?php if($paging==60) echo 'selected';?>>Display 60 Player Per Page</option>
                </select>
            </li>
        </ul>
                                    
        <?php 
							
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
			$now = new DateTime;			
			
							
			//$users = User::has('packages')->paginate($paging);
							
		?>
        
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="home-public">
                @if($users->count() == 0)
                    <div>
                        <h4>No User or Package found</h4>
                    </div>
                @else
        		<div class="row">
                    <ul class="main-stat-head">
                        <li class="spaced col-md-5">Package Details</li>
                        <li class="spaced col-md-1 text-center">Posted</li>
                        <li class="spaced col-md-2 text-center">Timeleft</li>
                        <li class="spaced col-md-1 text-center">Available</li>
                        <li class="spaced col-md-3 text-right">Buy Actions</li>
                    </ul>
                </div>
                                                    
                                                    
            	<ul class="player-list">
                                                    		
                <?php
					$index=0;
					foreach ($users as $user)								
					{	
						$packages = $user->packages()->where('cancel','!=',1)->where('ended', '>=', date("Y-m-d H:i:s"))->orderBy('created_at','DESC');
                        $lastPackage = $packages->get()->first();
                        
				?>
                    <li class="player-box">
                    	<ul class="main-stat-content">
                            <li class="col-xs-12 col-sm-12 col-md-5">
                            	
                                <div class="image-wrap">
                                	<img src="{{ $user->getPhotoUrl() }}" alt="">
                                </div>
                                
                                <div class="pull-left content-text-wrap" style="padding-left:10px;">
                            	<b><a href="{{ url('/profile/'. $user->id) }}" > {{$user->firstname.' '.$user->lastname}}</a></b>
                                <span class="main-stat-gamename">
                                    
                                    {{$lastPackage->title}}
                                    @if($packages->count() > 1)
                                    <a data-toggle="collapse" class="see-detail" data-parent="#accordion" href="#collapseFriend{{$index}}">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                    @endif
                                    </a>
                                </span>
                                <p class="font-sm grey pull-left mtop5">Payment available : 
                                    <b>
                                        <?php $i=0 ?>
                                        @foreach($lastPackage->payments()->get() as $payment)
                                            <?php $i++ ?>
                                            {{ $payment->payment_name }}{{ $i < $lastPackage->payments()->count() ? ',' : '' }}
                                            
                                        @endforeach
                                    </b>
                                </p>
                                <div class="main-package-detail">
                                    <p>
                                            Posted On :
                                    <span>
                                    Jul 25 
                                    </span>
                                            Time Left : 
                                    <span>
                                    15m
                                    </span>
                                            Available : 
                                    <span>
                                    15%
                                    </span>
                                    </p>
                                </div>
                            	</div>
                            </li>
                            <li class="col-md-1 big-lined">{{ date("M d",strtotime($lastPackage->posted))  }} </li>
                            <li class="col-md-2 big-lined"><?php echo packageInterval(strtotime($lastPackage->ended)) ?></li>
                            <li class="col-md-1 big-lined">{{$lastPackage->selling - $lastPackage->sellingPercent()}}%</li>
                            <li class="col-xs-offset-2 col-sm-offset-2 col-md-offset-0 col-xs-8 col-sm-8 col-md-3 text-right buy-btn-group">
                        		<div class="col-xs-3 col-sm-3 col-md-3" style="float:right;">
                                    @if(Auth::check())
                                        @if(Auth::user()->id == $user->id)
                                             <button  class="btn btn-lg btn-buy-amount"  disabled>BUY
                                            <span class="amount-value-sm">
                                            </span>
                                        </button>
                                        @else
                                        <button data-toggle="modal" data-target=".percent1Friend{{$lastPackage->id}}" class="btn btn-lg btn-buy-amount"  <?php if( Auth::check() && ($lastPackage->selling - $lastPackage->sellingPercent()) == 0) echo 'disabled'; ?>>BUY
                                            <span class="amount-value-sm">
                                            </span>
                                        </button>
                                        @endif
                                    @else
                                        <a href="{{url('login')}}" class="btn btn-lg btn-buy-amount"> BUY 
                                            <span class="amount-value-sm">
                                            </span>
                                        </a>
                                    @endif
                                </div>
                            </li>
            		    </ul>                                                            
                        <!-- Popup -->
                                                            
                        <div class="modal fade percent1Friend{{$lastPackage->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-xs">
                                <div class="modal-content buy-amount-dialog">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                        </div>
                                        <div class="panel-body">
                                            {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                            <input type="hidden" id="seller_id" name="seller_id" value="{{ $lastPackage->user_id }}" />
                                            <input type="hidden" id="package_id" name="package_id" value="{{ $lastPackage->id }}" />
                                            <input type="hidden" id="package_name" name="package_name" value="{{ $lastPackage->title }}" />
                                            <input type="hidden" id="selling_price-{{ $lastPackage->id }}" name="selling_price" value="" />
                                                                      
                                            <div class="col-md-12" style="border-right:solid 1px #ccc;">
                                                    <span class="font-sm">
                                                        <b>Percentage</b>
                                                    </span>
                                             <?php 
											 
											 											 
											 
											 	$soldPercent = $lastPackage->sellingPercent();
												$sellPercent =  $lastPackage->selling;												
												$availablePencent = $sellPercent - $soldPercent;												
												$countX = $availablePencent/$lastPackage->button1;												
												
											 ?>
                                             	<script type="text/javascript">
												$( document ).ready(function() {
    
													$("#selling-{{ $lastPackage->id }}").change(function () {
														var percent = this.value;
														var total = {{$lastPackage->total}};
														var value = total * (percent/100);
														$("#selling_price-{{ $lastPackage->id }}").val(value);
														
													});
													
													var percent = $("#selling-{{ $lastPackage->id }}").val();
													var total = {{$lastPackage->total}};
													var value = total * (percent/100);
													$("#selling_price-{{ $lastPackage->id }}").val(value);
													
													//console.log( "ready!" );
												});
                                                </script>
                                             
                                               		<select id="selling-{{ $lastPackage->id }}" name="selling" class="form-control form-sm">
                                                     @for ($x=1; $x<=$countX; $x++)                                                          
                                                     	<option value="{{$lastPackage->button1 * $x}}">{{$lastPackage->button1 * $x}}% - ${{$lastPackage->total * (($lastPackage->button1 * $x)/100)}}</option>
                                                      @endfor
                                                    </select>
                                                   <!-- <input class="form-control form-sm"  value="{{$lastPackage->button1}}" type="text" disabled>-->
                                            </div>
                                          <!--  <div class="col-md-6">
                                                <span class="font-sm text-right">
                                                    <b>Value</b>
                                                </span>
                                                <input class="form-control text-right form-sm" value="{{$lastPackage->selling_amount * ($lastPackage->button1/100)}}" type="text" disabled>
                                            </div>-->
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
                                                        
                        <div class="modal fade percent2Friend{{$lastPackage->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content buy-amount-dialog">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                        </div>
                                        <div class="panel-body">
                                            {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                       		<input type="hidden" id="package_id" name="package_id" value="{{ $lastPackage->id }}" />
                                       		<input type="hidden" id="package_name" name="package_name" value="{{ $lastPackage->title }}" />
                                            <input type="hidden" id="selling" name="selling" value="{{ $lastPackage->button2 }}" />
                                       		<input type="hidden" id="selling_price" name="selling_price" value="{{$lastPackage->selling_amount * ($lastPackage->button2/100)}}" />
                                      		<div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                <span class="font-sm">
                                                    <b>Percentage</b>
                                                </span>
                                                <input class="form-control form-sm"  value="{{$lastPackage->button2}}" type="text" disabled />
                                            </div>
                                            <div class="col-md-6">
                                                    <span class="font-sm text-right">
                                                        <b>Value</b>
                                                    </span>
                                                    <input class="form-control text-right form-sm"  value="{{$lastPackage->selling_amount * ($lastPackage->button2/100)}}" type="text" disabled />
                                            </div>
                                            <div class="payment-by">
                                                <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                    <p class="font-sm"><b>Payment by</b></p>                    
                                                    <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                        <option>PokerStars</option>
                                                        <option>888Poker</option>
                                                        <option>Adjarabet</option>
                                                        <option>iPoker</option>
                                                    </select>            
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="font-sm"><b>In Game Name</b></p>
                                                    <input type="text" class="form-control form-sm" id="in_game_name" name="in_game_name" required />
                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                    <button class="btn btn-primary spaced font-sm">Pay &amp; Submit </button>
                                                    <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                            
                        <div class="modal fade percent3Friend{{$lastPackage->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content buy-amount-dialog">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                        </div>
                                        <div class="panel-body">
                                            {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                       		<input type="hidden" id="package_id" name="package_id" value="{{ $lastPackage->id }}" />
                                       		<input type="hidden" id="package_name" name="package_name" value="{{ $lastPackage->title }}" />
                                            <input type="hidden" id="selling" name="selling" value="{{ $lastPackage->button3 }}" />
                                       		<input type="hidden" id="selling_price" name="selling_price" value="{{$lastPackage->selling_amount * ($lastPackage->button3/100)}}" />
                                      		<div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                    <span class="font-sm">
                                                        <b>Percentage</b>
                                                    </span>
                                                    <input class="form-control form-sm" value="{{$lastPackage->button3}}" type="text" disabled />
                                            </div>
                                            <div class="col-md-6">
                                                    <span class="font-sm text-right">
                                                        <b>Value</b>
                                                    </span>
                                                    <input class="form-control text-right form-sm" value="{{$lastPackage->selling_amount * ($lastPackage->button3/100)}}" type="text" disabled>
                                            </div>
                                            <div class="payment-by">
                                                <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                    <p class="font-sm"><b>Payment by</b></p>                 
                                                    <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                        <option>PokerStars</option>
                                                        <option>888Poker</option>
                                                        <option>Adjarabet</option>
                                                        <option>iPoker</option>
                                                    </select>        
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="font-sm"><b>In Game Name</b></p>
                                                    <input type="text" class="form-control form-sm"  id="in_game_name" name="in_game_name" required />
                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                    <button class="btn btn-primary spaced font-sm">Pay &amp; Submit</button>
                                                    <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>                  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade percent4Friend{{$lastPackage->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content buy-amount-dialog">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                        </div>
                                        <div class="panel-body">
                                            {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                       		<input type="hidden" id="package_id" name="package_id" value="{{ $lastPackage->id }}" />
                                       		<input type="hidden" id="package_name" name="package_name" value="{{ $lastPackage->title }}" />
                                     
                                            <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                <span class="font-sm">
                                                    <b>Percentage</b>
                                                </span>
                                                <input class="form-control form-sm" id="selling" name="selling" placeholder="%" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                    <span class="font-sm text-right">
                                                        <b>Value</b>
                                                    </span>
                                                    <input class="form-control text-right form-sm" id="selling_price" name="selling_price" placeholder="$" type="text" >
                                            </div>
                                            <div class="payment-by">
                                                <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                    <p class="font-sm"><b>Payment by</b></p>
                                                    <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                        <option>PokerStars</option>
                                                        <option>888Poker</option>
                                                        <option>Adjarabet</option>
                                                        <option>iPoker</option>
                                                    </select>
                                                                       
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="font-sm"><b>In Game Name</b></p>
                                                    <input type="text" class="form-control form-sm" id="in_game_name" name="in_game_name" required >
                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                    <button class="btn btn-primary spaced font-sm">Pay &amp; Submit</button>
                                                    <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>                      
                                    </div>
                                </div>
                            </div>
                        </div>         
                        <!-- end popup -->
                        <div id="collapseFriend{{$index}}" class="panel-collapse collapse">
                            <div class="panel-body content-player-body">
                                @foreach($packages->take(7)->skip(1)->get() as $package)
                                <div class="col-md-4 content-player-body-in">
                                    <div class="panel panel-default package-wrap">
                                        <div class="panel-body">
                                            <div class="row package-head">
                                                <h3><b>{{$package->title}}</b><span class="label label-primary pull-right"> {{ $package->getPackageStatus() }}</span></h3>
                                                <p class="font-sm">POSTED ON {{ date("d M, Y ",strtotime($package->posted))  }} <span>ENDING IN <b><?php echo packageInterval(strtotime($package->ended)) ?></b></span></p>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-8 col-sm-9 col-md-8 package-content">
                                                    <div class="row package-tournament-list">
                                                        <table class="package-tournaments">
                                                            <tbody>
                                                                <tr>
                                                                    <th class="spaced">Tournament</th>
                                                                    <th class="spaced">Buy-In</th>
                                                                </tr>
                                                                
                                                                @foreach($package->turnaments()->get() as $tournament)
                                                                <tr>
                                                                    <td><a target="_blank" href="http://www.sharkscope.com/#Find-Tournament//networks/{{$tournament->network}}/tournaments/{{$tournament->game_id}}" > {{$tournament->name}}</a></td>
                                                                    <td>$ {{ $tournament->buyin }}</td>
                                                                </tr>
                                                                @endforeach

                                                            </tbody>
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
                                                                <p><b>$ {{$package->total}}</b></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-3 col-md-4" style="position:static;">
                                                    <div class="row package-right">
                                                        <p class="font-sm">Mark-up</p>
                                                        <p><b>{{$package->markup}}</b></p>
                                                        <p class="font-sm">Sold</p>
                                                        <p><span class="blue"><b>25%</b></span> of <b>50%</b></p>
                                                        @if(Auth::check())
                                                            @if(Auth::user()->id == $user->id)
                                                                
                                                            @else
                                                            <a class="btn btn-primary btn-buy toggle" data-toggle="modal" data-target=".percent11-{{$package->id}}" href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Buy</a>
                                                            @endif
                                                        @else
                                                            <a class="btn btn-primary btn-buy toggle" href="{{url('login')}}"><span class="glyphicon glyphicon-shopping-cart"></span> Buy</a>
                                                        @endif
                                                        
                                                        <div class="buy-overlay" style="display: none;">
                                                            <div class="col-xs-4 col-sm-4 col-md-3">
                                                                <button data-toggle="modal" data-target=".percent11-{{$package->id}}" class="btn btn-lg btn-buy-amount">5%
                                                                    <span class="amount-value-sm">
                                                                    ($ {{$package->button1}})
                                                                    </span>
                                                                </button>
                                                            </div>
                                                                
                                                            <div class="col-xs-4 col-sm-4 col-md-3">
                                                                <button data-toggle="modal" data-target=".percent22-{{$package->id}}" class="btn btn-lg btn-buy-amount">10%
                                                                    <span class="amount-value-sm">
                                                                    ($ {{$package->button2}})
                                                                    </span>
                                                                </button>
                                                            </div>
                                                                
                                                            <div class="col-xs-4 col-sm-4 col-md-3">
                                                                <button data-toggle="modal" data-target=".percent33-{{$package->id}}" class="btn btn-lg btn-buy-amount">ALL
                                                                    <span class="amount-value-sm">
                                                                    ($ {{$package->button3}})
                                                                    </span>
                                                                </button>
                                                            </div>
                                                                
                                                            <div class="col-md-3">
                                                                <button data-toggle="modal" data-target=".percent4-{{$package->id}}" class="btn btn-lg btn-custom-amount font-sm">CUSTOM<br>AMOUNT
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <p>Comments from Seller</p>
                                            <p> {{ $package->notes  }}</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Popup 2 -->
                                                            
                                <div class="modal fade percent11-{{$package->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xs">
                                        <div class="modal-content buy-amount-dialog">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                                </div>
                                                <div class="panel-body">
                                                    {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                                    <input type="hidden" id="package_id" name="package_id" value="{{ $package->id }}" />
                                                    <input type="hidden" id="package_name" name="package_name" value="{{ $package->title }}" />
                                                    <input type="hidden" id="selling_price2-{{$package->id}}" name="selling_price" value="" />
                                                                              
                                                    <div class="col-md-12" style="border-right:solid 1px #ccc;">
                                                            <span class="font-sm">
                                                                <b>Percentage</b>
                                                            </span>
                                                     <?php 
                                                     
                                                                                                 
                                                     
                                                        $soldPercent = $package->sellingPercent();
                                                        $sellPercent =  $package->selling;                                              
                                                        $availablePencent = $sellPercent - $soldPercent;                                                
                                                        $countX = $availablePencent/$package->button1;  
                                                        $total = $package->total;                                            
                                                        
                                                     ?>
                                                        <script type="text/javascript">
                                                        $( document ).ready(function() {
            
                                                            $("#selling2-{{$package->id}}").change(function () {

                                                                var percent = this.value;
                                                                var total = {{ $package->total }};
                                                                var value = total * (percent/100);
                                                                //console.log( percent + "*" + total +" = " + value);
                                                                $("#selling_price2-{{$package->id}}").val(value);
                                                                
                                                            });
                                                            
                                                            var percent = $("#selling2-{{$package->id}}").val();
                                                            var total = {{$package->total}};
                                                            var value = total * (percent/100);
                                                            $("#selling_price2-{{$package->id}}").val(value);
                                                            //console.log( percent + "*" + total +" = " + value);
                                                            //console.log( "ready!" );
                                                        });
                                                        </script>
                                                     
                                                            <select id="selling2-{{$package->id}}" name="selling" class="form-control form-sm">
                                                             @for ($x=1; $x<=$countX; $x++)                                                          
                                                                <option value="{{$package->button1 * $x}}">{{$package->button1 * $x}}% - ${{$package->total * (($package->button1 * $x)/100)}}</option>
                                                              @endfor
                                                            </select>
                                                           <!-- <input class="form-control form-sm"  value="{{$package->button1}}" type="text" disabled>-->
                                                    </div>
                                                  <!--  <div class="col-md-6">
                                                        <span class="font-sm text-right">
                                                            <b>Value</b>
                                                        </span>
                                                        <input class="form-control text-right form-sm" value="{{$package->selling_amount * ($package->button1/100)}}" type="text" disabled>
                                                    </div>-->
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
                                                                
                                <div class="modal fade percent22-{{$package->id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                    <input type="hidden" id="selling" name="selling" value="{{ $package->button2 }}" />
                                                    <input type="hidden" id="selling_price" name="selling_price" value="{{$package->selling_amount * ($package->button2/100)}}" />
                                                    <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                        <span class="font-sm">
                                                            <b>Percentage</b>
                                                        </span>
                                                        <input class="form-control form-sm"  value="{{$package->button2}}" type="text" disabled />
                                                    </div>
                                                    <div class="col-md-6">
                                                            <span class="font-sm text-right">
                                                                <b>Value</b>
                                                            </span>
                                                            <input class="form-control text-right form-sm"  value="{{$package->selling_amount * ($package->button2/100)}}" type="text" disabled />
                                                    </div>
                                                    <div class="payment-by">
                                                        <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                            <p class="font-sm"><b>Payment by</b></p>                    
                                                            <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                                <option>PokerStars</option>
                                                                <option>888Poker</option>
                                                                <option>Adjarabet</option>
                                                                <option>iPoker</option>
                                                            </select>            
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="font-sm"><b>In Game Name</b></p>
                                                            <input type="text" class="form-control form-sm" id="in_game_name" name="in_game_name" required />
                                                        </div>
                                                    </div>
                                                    <div class="panel-footer">
                                                            <button class="btn btn-primary spaced font-sm">Pay &amp; Submit </button>
                                                            <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                                    </div>
                                                    {{ Form::close() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                                                    
                                <div class="modal fade percent33-{{$package->id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                    <input type="hidden" id="selling" name="selling" value="{{ $package->button3 }}" />
                                                    <input type="hidden" id="selling_price" name="selling_price" value="{{$package->selling_amount * ($package->button3/100)}}" />
                                                    <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                            <span class="font-sm">
                                                                <b>Percentage</b>
                                                            </span>
                                                            <input class="form-control form-sm" value="{{$package->button3}}" type="text" disabled />
                                                    </div>
                                                    <div class="col-md-6">
                                                            <span class="font-sm text-right">
                                                                <b>Value</b>
                                                            </span>
                                                            <input class="form-control text-right form-sm" value="{{$package->selling_amount * ($package->button3/100)}}" type="text" disabled>
                                                    </div>
                                                    <div class="payment-by">
                                                        <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                            <p class="font-sm"><b>Payment by</b></p>                 
                                                            <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                                <option>PokerStars</option>
                                                                <option>888Poker</option>
                                                                <option>Adjarabet</option>
                                                                <option>iPoker</option>
                                                            </select>        
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="font-sm"><b>In Game Name</b></p>
                                                            <input type="text" class="form-control form-sm"  id="in_game_name" name="in_game_name" required />
                                                        </div>
                                                    </div>
                                                    <div class="panel-footer">
                                                            <button class="btn btn-primary spaced font-sm">Pay &amp; Submit</button>
                                                            <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                                    </div>
                                                    {{ Form::close() }}
                                                </div>                  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade percent44-{{$package->id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                             
                                                    <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                        <span class="font-sm">
                                                            <b>Percentage</b>
                                                        </span>
                                                        <input class="form-control form-sm" id="selling" name="selling" placeholder="%" type="text">
                                                    </div>
                                                    <div class="col-md-6">
                                                            <span class="font-sm text-right">
                                                                <b>Value</b>
                                                            </span>
                                                            <input class="form-control text-right form-sm" id="selling_price" name="selling_price" placeholder="$" type="text" >
                                                    </div>
                                                    <div class="payment-by">
                                                        <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                            <p class="font-sm"><b>Payment by</b></p>
                                                            <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                                <option>PokerStars</option>
                                                                <option>888Poker</option>
                                                                <option>Adjarabet</option>
                                                                <option>iPoker</option>
                                                            </select>
                                                                               
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="font-sm"><b>In Game Name</b></p>
                                                            <input type="text" class="form-control form-sm" id="in_game_name" name="in_game_name" required >
                                                        </div>
                                                    </div>
                                                    <div class="panel-footer">
                                                            <button class="btn btn-primary spaced font-sm">Pay &amp; Submit</button>
                                                            <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                                    </div>
                                                    {{ Form::close() }}
                                                </div>                      
                                            </div>
                                        </div>
                                    </div>
                                </div>         
                                <!-- end popup 2 -->
                                @endforeach                                    
                            </div>
                                                            
                        </div>
                    </li>
                                                             
                                                             
                    <?php $index++; }?>
                </ul>    
                @endif                                   
            </div>
            @if(Auth::check())
            <div class="tab-pane fade" id="home-mycircle">
                @if($friendspackage->count() == 0)
                    <div>
                        <h4>No User or Package found</h4>
                    </div>
                @else
                <div class="row">
                    <ul class="main-stat-head">
                        <li class="spaced col-md-5">Package Details</li>
                        <li class="spaced col-md-1 text-center">Posted</li>
                        <li class="spaced col-md-2 text-center">Timeleft</li>
                        <li class="spaced col-md-1 text-center">Available</li>
                        <li class="spaced col-md-3 text-right">Buy Actions</li>
                    </ul>
                </div>
                                                    
                                          
                <ul class="player-list">
                                                            
                <?php
                    $index=0;

                    foreach ($friendspackage as $user)                               
                    {   
                        

                        
                        $packages = $user->packages()->where('cancel','!=',1)->where('ended', '>=', date("Y-m-d H:i:s"))->orderBy('created_at','DESC');
                        $lastPackage = $packages->get()->first();
                        
                ?>
                    <li class="player-box">
                        <ul class="main-stat-content">
                            <li class="col-xs-12 col-sm-12 col-md-5">
                                
                                <div class="image-wrap">
                                    <img src="{{ $user->getPhotoUrl() }}" alt="">
                                </div>
                                
                                <div class="pull-left content-text-wrap" style="padding-left:10px;">
                                <b><a href="{{ url('/profile/'. $user->id) }}" > {{$user->firstname.' '.$user->lastname}}</a></b>
                                <span class="main-stat-gamename">
                                    
                                    {{$lastPackage->title}}
                                    @if($packages->count() > 1)
                                    <a data-toggle="collapse" class="see-detail" data-parent="#accordion" href="#collapse{{$index}}">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                    </a>
                                    @endif
                                    
                                </span>
                                <p class="font-sm grey pull-left mtop5">Payment available : 
                                    <b>
                                        <?php $i=0 ?>
                                        @foreach($lastPackage->payments()->get() as $payment)
                                            <?php $i++ ?>
                                            {{ $payment->payment_name }}{{ $i < $lastPackage->payments()->count() ? ',' : '' }}
                                            
                                        @endforeach
                                    </b>
                                </p>
                                <div class="main-package-detail">
                                    <p>
                                            Posted On :
                                    <span>
                                    Jul 25 
                                    </span>
                                            Time Left : 
                                    <span>
                                    15m
                                    </span>
                                            Available : 
                                    <span>
                                    15%
                                    </span>
                                    </p>
                                </div>
                                </div>
                            </li>
                            <li class="col-md-1 big-lined">{{ date("M d",strtotime($lastPackage->posted))  }} </li>
                            <li class="col-md-2 big-lined"><?php echo packageInterval(strtotime($lastPackage->ended)) ?></li>
                            <li class="col-md-1 big-lined">{{$lastPackage->selling - $lastPackage->sellingPercent()}}%</li>
                            <li class="col-xs-offset-2 col-sm-offset-2 col-md-offset-0 col-xs-8 col-sm-8 col-md-3 text-right buy-btn-group">
                                <div class="col-xs-3 col-sm-3 col-md-3" style="float:right;">
                                    @if(Auth::check())
                                        @if(Auth::user()->id == $user->id)
                                             <button  class="btn btn-lg btn-buy-amount"  disabled>BUY
                                            <span class="amount-value-sm">
                                            </span>
                                        </button>
                                        @else
                                        <button data-toggle="modal" data-target=".percent1{{$lastPackage->id}}" class="btn btn-lg btn-buy-amount"  <?php if( Auth::check() && ($lastPackage->selling - $lastPackage->sellingPercent()) == 0) echo 'disabled'; ?>>BUY
                                            <span class="amount-value-sm">
                                            </span>
                                        </button>
                                        @endif
                                    @else
                                        <a href="{{url('login')}}" class="btn btn-lg btn-buy-amount"> BUY 
                                            <span class="amount-value-sm">
                                            </span>
                                        </a>
                                    @endif
                                </div>
                                
                                <!--<div class="col-xs-3 col-sm-3 col-md-3">
                                    <button data-toggle="modal" data-target=".percent2{{$lastPackage->id}}" class="btn btn-lg btn-buy-amount">{{$lastPackage->button2}}%
                                        <span class="amount-value-sm">
                                        ($ {{ number_format($lastPackage->selling_amount * ($lastPackage->button2/100), 2, '.', '') }})
                                        </span>
                                    </button>
                                </div>
                                
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <button data-toggle="modal" data-target=".percent3{{$lastPackage->id}}" class="btn btn-lg btn-buy-amount">ALL
                                        <span class="amount-value-sm">
                                        ($ {{ number_format($lastPackage->selling_amount, 2, '.', '') }})
                                        </span>
                                    </button>                                                                            
                                </div>
                                
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <button data-toggle="modal" data-target=".percent4{{$lastPackage->id}}" class="btn btn-lg btn-custom-amount font-sm">CUSTOM<br>AMOUNT
                                    </button>
                                </div>-->
                            </li>
                        </ul>                                                            
                        <!-- Popup -->
                                                            
                        <div class="modal fade percent1{{$lastPackage->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-xs">
                                <div class="modal-content buy-amount-dialog">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                        </div>
                                        <div class="panel-body">
                                            {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                            <input type="hidden" id="package_id" name="package_id" value="{{ $lastPackage->id }}" />
                                            <input type="hidden" id="package_name" name="package_name" value="{{ $lastPackage->title }}" />
                                            <input type="hidden" id="selling_price-{{ $lastPackage->id }}" name="selling_price" value="" />
                                                                      
                                            <div class="col-md-12" style="border-right:solid 1px #ccc;">
                                                    <span class="font-sm">
                                                        <b>Percentage</b>
                                                    </span>
                                             <?php 
                                             
                                                                                         
                                             
                                                $soldPercent = $lastPackage->sellingPercent();
                                                $sellPercent =  $lastPackage->selling;                                              
                                                $availablePencent = $sellPercent - $soldPercent;                                                
                                                $countX = $availablePencent/$lastPackage->button1;                                              
                                                
                                             ?>
                                                <script type="text/javascript">
                                                $( document ).ready(function() {
    
                                                    $("#selling-{{ $lastPackage->id }}").change(function () {
                                                        var percent = this.value;
                                                        var total = {{$lastPackage->total}};
                                                        var value = total * (percent/100);
                                                        $("#selling_price-{{ $lastPackage->id }}").val(value);
                                                        
                                                    });
                                                    
                                                    var percent = $("#selling-{{ $lastPackage->id }}").val();
                                                    var total = {{$lastPackage->total}};
                                                    var value = total * (percent/100);
                                                    $("#selling_price-{{ $lastPackage->id }}").val(value);
                                                    
                                                    //console.log( "ready!" );
                                                });
                                                </script>
                                             
                                                    <select id="selling-{{ $lastPackage->id }}" name="selling" class="form-control form-sm">
                                                     @for ($x=1; $x<=$countX; $x++)                                                          
                                                        <option value="{{$lastPackage->button1 * $x}}">{{$lastPackage->button1 * $x}}% - ${{$lastPackage->total * (($lastPackage->button1 * $x)/100)}}</option>
                                                      @endfor
                                                    </select>
                                                   <!-- <input class="form-control form-sm"  value="{{$lastPackage->button1}}" type="text" disabled>-->
                                            </div>
                                          <!--  <div class="col-md-6">
                                                <span class="font-sm text-right">
                                                    <b>Value</b>
                                                </span>
                                                <input class="form-control text-right form-sm" value="{{$lastPackage->selling_amount * ($lastPackage->button1/100)}}" type="text" disabled>
                                            </div>-->
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
                                                        
                        <div class="modal fade percent2{{$lastPackage->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content buy-amount-dialog">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                        </div>
                                        <div class="panel-body">
                                            {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                            <input type="hidden" id="package_id" name="package_id" value="{{ $lastPackage->id }}" />
                                            <input type="hidden" id="package_name" name="package_name" value="{{ $lastPackage->title }}" />
                                            <input type="hidden" id="selling" name="selling" value="{{ $lastPackage->button2 }}" />
                                            <input type="hidden" id="selling_price" name="selling_price" value="{{$lastPackage->selling_amount * ($lastPackage->button2/100)}}" />
                                            <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                <span class="font-sm">
                                                    <b>Percentage</b>
                                                </span>
                                                <input class="form-control form-sm"  value="{{$lastPackage->button2}}" type="text" disabled />
                                            </div>
                                            <div class="col-md-6">
                                                    <span class="font-sm text-right">
                                                        <b>Value</b>
                                                    </span>
                                                    <input class="form-control text-right form-sm"  value="{{$lastPackage->selling_amount * ($lastPackage->button2/100)}}" type="text" disabled />
                                            </div>
                                            <div class="payment-by">
                                                <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                    <p class="font-sm"><b>Payment by</b></p>                    
                                                    <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                        <option>PokerStars</option>
                                                        <option>888Poker</option>
                                                        <option>Adjarabet</option>
                                                        <option>iPoker</option>
                                                    </select>            
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="font-sm"><b>In Game Name</b></p>
                                                    <input type="text" class="form-control form-sm" id="in_game_name" name="in_game_name" required />
                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                    <button class="btn btn-primary spaced font-sm">Pay &amp; Submit </button>
                                                    <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                            
                        <div class="modal fade percent3{{$lastPackage->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content buy-amount-dialog">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                        </div>
                                        <div class="panel-body">
                                            {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                            <input type="hidden" id="package_id" name="package_id" value="{{ $lastPackage->id }}" />
                                            <input type="hidden" id="package_name" name="package_name" value="{{ $lastPackage->title }}" />
                                            <input type="hidden" id="selling" name="selling" value="{{ $lastPackage->button3 }}" />
                                            <input type="hidden" id="selling_price" name="selling_price" value="{{$lastPackage->selling_amount * ($lastPackage->button3/100)}}" />
                                            <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                    <span class="font-sm">
                                                        <b>Percentage</b>
                                                    </span>
                                                    <input class="form-control form-sm" value="{{$lastPackage->button3}}" type="text" disabled />
                                            </div>
                                            <div class="col-md-6">
                                                    <span class="font-sm text-right">
                                                        <b>Value</b>
                                                    </span>
                                                    <input class="form-control text-right form-sm" value="{{$lastPackage->selling_amount * ($lastPackage->button3/100)}}" type="text" disabled>
                                            </div>
                                            <div class="payment-by">
                                                <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                    <p class="font-sm"><b>Payment by</b></p>                 
                                                    <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                        <option>PokerStars</option>
                                                        <option>888Poker</option>
                                                        <option>Adjarabet</option>
                                                        <option>iPoker</option>
                                                    </select>        
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="font-sm"><b>In Game Name</b></p>
                                                    <input type="text" class="form-control form-sm"  id="in_game_name" name="in_game_name" required />
                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                    <button class="btn btn-primary spaced font-sm">Pay &amp; Submit</button>
                                                    <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>                  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade percent4{{$lastPackage->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content buy-amount-dialog">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                        </div>
                                        <div class="panel-body">
                                            {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                            <input type="hidden" id="package_id" name="package_id" value="{{ $lastPackage->id }}" />
                                            <input type="hidden" id="package_name" name="package_name" value="{{ $lastPackage->title }}" />
                                     
                                            <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                <span class="font-sm">
                                                    <b>Percentage</b>
                                                </span>
                                                <input class="form-control form-sm" id="selling" name="selling" placeholder="%" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                    <span class="font-sm text-right">
                                                        <b>Value</b>
                                                    </span>
                                                    <input class="form-control text-right form-sm" id="selling_price" name="selling_price" placeholder="$" type="text" >
                                            </div>
                                            <div class="payment-by">
                                                <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                    <p class="font-sm"><b>Payment by</b></p>
                                                    <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                        <option>PokerStars</option>
                                                        <option>888Poker</option>
                                                        <option>Adjarabet</option>
                                                        <option>iPoker</option>
                                                    </select>
                                                                       
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="font-sm"><b>In Game Name</b></p>
                                                    <input type="text" class="form-control form-sm" id="in_game_name" name="in_game_name" required >
                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                    <button class="btn btn-primary spaced font-sm">Pay &amp; Submit</button>
                                                    <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>                      
                                    </div>
                                </div>
                            </div>
                        </div>         
                        <!-- end popup -->
                        <div id="collapse{{$index}}" class="panel-collapse collapse">
                            <div class="panel-body content-player-body">
                                @foreach($packages->take(7)->skip(1)->get() as $package)
                                <div class="col-md-4 content-player-body-in">
                                    <div class="panel panel-default package-wrap">
                                        <div class="panel-body">
                                            <div class="row package-head">
                                                <h3><b>{{$package->title}}</b><span class="label label-primary pull-right"> {{ $package->getPackageStatus() }}</span></h3>
                                                <p class="font-sm">POSTED ON {{ date("d M, Y ",strtotime($package->posted))  }} <span>ENDING IN <b><?php echo packageInterval(strtotime($package->ended)) ?></b></span></p>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-8 col-sm-9 col-md-8 package-content">
                                                    <div class="row package-tournament-list">
                                                        <table class="package-tournaments">
                                                            <tbody>
                                                                <tr>
                                                                    <th class="spaced">Tournament</th>
                                                                    <th class="spaced">Buy-In</th>
                                                                </tr>
                                                                
                                                                @foreach($package->turnaments()->get() as $tournament)
                                                                <tr>
                                                                    <td><a target="_blank" href="http://www.sharkscope.com/#Find-Tournament//networks/{{$tournament->network}}/tournaments/{{$tournament->game_id}}" > {{$tournament->name}}</a></td>
                                                                    <td>$ {{ $tournament->buyin }}</td>
                                                                </tr>
                                                                @endforeach

                                                            </tbody>
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
                                                                <p><b>$ {{$package->total}}</b></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-3 col-md-4" style="position:static;">
                                                    <div class="row package-right">
                                                        <p class="font-sm">Mark-up</p>
                                                        <p><b>{{$package->markup}}</b></p>
                                                        <p class="font-sm">Sold</p>
                                                        <p><span class="blue"><b>25%</b></span> of <b>50%</b></p>
                                                        @if(Auth::check())
                                                            @if(Auth::user()->id == $user->id)
                                                                
                                                            @else
                                                            <a class="btn btn-primary btn-buy toggle" data-toggle="modal" data-target=".percent11-{{$package->id}}" href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Buy</a>
                                                            @endif
                                                        @else
                                                            <a class="btn btn-primary btn-buy toggle" href="{{url('login')}}"><span class="glyphicon glyphicon-shopping-cart"></span> Buy</a>
                                                        @endif
                                                        
                                                        <div class="buy-overlay" style="display: none;">
                                                            <div class="col-xs-4 col-sm-4 col-md-3">
                                                                <button data-toggle="modal" data-target=".percent11-{{$package->id}}" class="btn btn-lg btn-buy-amount">5%
                                                                    <span class="amount-value-sm">
                                                                    ($ {{$package->button1}})
                                                                    </span>
                                                                </button>
                                                            </div>
                                                                
                                                            <div class="col-xs-4 col-sm-4 col-md-3">
                                                                <button data-toggle="modal" data-target=".percent22-{{$package->id}}" class="btn btn-lg btn-buy-amount">10%
                                                                    <span class="amount-value-sm">
                                                                    ($ {{$package->button2}})
                                                                    </span>
                                                                </button>
                                                            </div>
                                                                
                                                            <div class="col-xs-4 col-sm-4 col-md-3">
                                                                <button data-toggle="modal" data-target=".percent33-{{$package->id}}" class="btn btn-lg btn-buy-amount">ALL
                                                                    <span class="amount-value-sm">
                                                                    ($ {{$package->button3}})
                                                                    </span>
                                                                </button>
                                                            </div>
                                                                
                                                            <div class="col-md-3">
                                                                <button data-toggle="modal" data-target=".percent4-{{$package->id}}" class="btn btn-lg btn-custom-amount font-sm">CUSTOM<br>AMOUNT
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <p>Comments from Seller</p>
                                            <p> {{ $package->notes  }}</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Popup 2 -->
                                                            
                                <div class="modal fade percent11-{{$package->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xs">
                                        <div class="modal-content buy-amount-dialog">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title spaced">Confirm Your Purchase</h4>
                                                </div>
                                                <div class="panel-body">
                                                    {{ Form::open(array('url'=>'cart/order', 'class'=>'form-signin')) }}
                                                    <input type="hidden" id="package_id" name="package_id" value="{{ $package->id }}" />
                                                    <input type="hidden" id="package_name" name="package_name" value="{{ $package->title }}" />
                                                    <input type="hidden" id="selling_price2-{{$package->id}}" name="selling_price" value="" />
                                                                              
                                                    <div class="col-md-12" style="border-right:solid 1px #ccc;">
                                                            <span class="font-sm">
                                                                <b>Percentage</b>
                                                            </span>
                                                     <?php 
                                                     
                                                                                                 
                                                     
                                                        $soldPercent = $package->sellingPercent();
                                                        $sellPercent =  $package->selling;                                              
                                                        $availablePencent = $sellPercent - $soldPercent;                                                
                                                        $countX = $availablePencent/$package->button1;  
                                                        $total = $package->total;                                            
                                                        
                                                     ?>
                                                        <script type="text/javascript">
                                                        $( document ).ready(function() {
            
                                                            $("#selling2-{{$package->id}}").change(function () {

                                                                var percent = this.value;
                                                                var total = {{ $package->total }};
                                                                var value = total * (percent/100);
                                                                //console.log( percent + "*" + total +" = " + value);
                                                                $("#selling_price2-{{$package->id}}").val(value);
                                                                
                                                            });
                                                            
                                                            var percent = $("#selling2-{{$package->id}}").val();
                                                            var total = {{$package->total}};
                                                            var value = total * (percent/100);
                                                            $("#selling_price2-{{$package->id}}").val(value);
                                                            //console.log( percent + "*" + total +" = " + value);
                                                            //console.log( "ready!" );
                                                        });
                                                        </script>
                                                     
                                                            <select id="selling2-{{$package->id}}" name="selling" class="form-control form-sm">
                                                             @for ($x=1; $x<=$countX; $x++)                                                          
                                                                <option value="{{$package->button1 * $x}}">{{$package->button1 * $x}}% - ${{$package->total * (($package->button1 * $x)/100)}}</option>
                                                              @endfor
                                                            </select>
                                                           <!-- <input class="form-control form-sm"  value="{{$package->button1}}" type="text" disabled>-->
                                                    </div>
                                                  <!--  <div class="col-md-6">
                                                        <span class="font-sm text-right">
                                                            <b>Value</b>
                                                        </span>
                                                        <input class="form-control text-right form-sm" value="{{$package->selling_amount * ($package->button1/100)}}" type="text" disabled>
                                                    </div>-->
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
                                                                
                                <div class="modal fade percent22-{{$package->id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                    <input type="hidden" id="selling" name="selling" value="{{ $package->button2 }}" />
                                                    <input type="hidden" id="selling_price" name="selling_price" value="{{$package->selling_amount * ($package->button2/100)}}" />
                                                    <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                        <span class="font-sm">
                                                            <b>Percentage</b>
                                                        </span>
                                                        <input class="form-control form-sm"  value="{{$package->button2}}" type="text" disabled />
                                                    </div>
                                                    <div class="col-md-6">
                                                            <span class="font-sm text-right">
                                                                <b>Value</b>
                                                            </span>
                                                            <input class="form-control text-right form-sm"  value="{{$package->selling_amount * ($package->button2/100)}}" type="text" disabled />
                                                    </div>
                                                    <div class="payment-by">
                                                        <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                            <p class="font-sm"><b>Payment by</b></p>                    
                                                            <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                                <option>PokerStars</option>
                                                                <option>888Poker</option>
                                                                <option>Adjarabet</option>
                                                                <option>iPoker</option>
                                                            </select>            
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="font-sm"><b>In Game Name</b></p>
                                                            <input type="text" class="form-control form-sm" id="in_game_name" name="in_game_name" required />
                                                        </div>
                                                    </div>
                                                    <div class="panel-footer">
                                                            <button class="btn btn-primary spaced font-sm">Pay &amp; Submit </button>
                                                            <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                                    </div>
                                                    {{ Form::close() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                                                    
                                <div class="modal fade percent33-{{$package->id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                    <input type="hidden" id="selling" name="selling" value="{{ $package->button3 }}" />
                                                    <input type="hidden" id="selling_price" name="selling_price" value="{{$package->selling_amount * ($package->button3/100)}}" />
                                                    <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                            <span class="font-sm">
                                                                <b>Percentage</b>
                                                            </span>
                                                            <input class="form-control form-sm" value="{{$package->button3}}" type="text" disabled />
                                                    </div>
                                                    <div class="col-md-6">
                                                            <span class="font-sm text-right">
                                                                <b>Value</b>
                                                            </span>
                                                            <input class="form-control text-right form-sm" value="{{$package->selling_amount * ($package->button3/100)}}" type="text" disabled>
                                                    </div>
                                                    <div class="payment-by">
                                                        <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                            <p class="font-sm"><b>Payment by</b></p>                 
                                                            <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                                <option>PokerStars</option>
                                                                <option>888Poker</option>
                                                                <option>Adjarabet</option>
                                                                <option>iPoker</option>
                                                            </select>        
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="font-sm"><b>In Game Name</b></p>
                                                            <input type="text" class="form-control form-sm"  id="in_game_name" name="in_game_name" required />
                                                        </div>
                                                    </div>
                                                    <div class="panel-footer">
                                                            <button class="btn btn-primary spaced font-sm">Pay &amp; Submit</button>
                                                            <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                                    </div>
                                                    {{ Form::close() }}
                                                </div>                  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade percent44-{{$package->id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                             
                                                    <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                        <span class="font-sm">
                                                            <b>Percentage</b>
                                                        </span>
                                                        <input class="form-control form-sm" id="selling" name="selling" placeholder="%" type="text">
                                                    </div>
                                                    <div class="col-md-6">
                                                            <span class="font-sm text-right">
                                                                <b>Value</b>
                                                            </span>
                                                            <input class="form-control text-right form-sm" id="selling_price" name="selling_price" placeholder="$" type="text" >
                                                    </div>
                                                    <div class="payment-by">
                                                        <div class="col-md-6" style="border-right:solid 1px #ccc;">
                                                            <p class="font-sm"><b>Payment by</b></p>
                                                            <select class="form-control form-sm" id="payment_method" name="payment_method">
                                                                <option>PokerStars</option>
                                                                <option>888Poker</option>
                                                                <option>Adjarabet</option>
                                                                <option>iPoker</option>
                                                            </select>
                                                                               
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="font-sm"><b>In Game Name</b></p>
                                                            <input type="text" class="form-control form-sm" id="in_game_name" name="in_game_name" required >
                                                        </div>
                                                    </div>
                                                    <div class="panel-footer">
                                                            <button class="btn btn-primary spaced font-sm">Pay &amp; Submit</button>
                                                            <button class="btn btn-grey spaced pull-right font-sm">Cancel</button>
                                                    </div>
                                                    {{ Form::close() }}
                                                </div>                      
                                            </div>
                                        </div>
                                    </div>
                                </div>         
                                <!-- end popup 2 -->
                                @endforeach                                    
                            </div>
                                                            
                        </div>
                    </li>
                                                             
                                                             
                    <?php $index++; 
                        
                    }
                    ?>
                </ul>    
                @endif  
            </div>
            @endif
        </div>
        
    </div>
	
@stop