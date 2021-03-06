@extends('layouts.master')

@section('content')
	<style type="text/css">
		
		input#recipient:hover, input#recipient:focus {
			color: #3b3b3b;
			border: 1px solid #36a2d2;
			-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) inset, 0 1px 0 rgba(255, 255, 255, 1);
			-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) inset, 0 1px 0 rgba(255, 255, 255, 1);
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) inset, 0 1px 0 rgba(255, 255, 255, 1);
		}
		h4#results-text {
			display: none;
			font-size: 14px!important;
			font-weight: bold;
		}
		ul#results {
			display: none;
			width: 100%;
			margin-top: 4px;
			padding: 0;
			-webkit-border-radius: 2px;
			-moz-border-radius: 2px;
			border-radius: 2px;
			float: left;
		}
		ul#results li {
			padding: 4px;
			cursor: pointer;
			transition: background-color .3s ease-in-out;
			-moz-transition: background-color .3s ease-in-out;
			-webkit-transition: background-color .3s ease-in-out;
			list-style: none;
			float: left;
			width: 100%;
			border-bottom: solid 1px transparent;
		}
		ul#results li:hover {
			background-color: #EEE;
			border-bottom: solid 1px #ddd!important;
		}
		ul#results li:first-child {
			border-top: none;
		}
		ul#results li h3, ul#results li h4 {
			transition: color .3s ease-in-out;
			-moz-transition: color .3s ease-in-out;
			-webkit-transition: color .3s ease-in-out;
			color: #616161;
			line-height: 1.2em;
			margin:0;
			font-size: 14px;
			margin-bottom:5px;
		}
		 ul#results li h4  {
		 	font-size: 11px!important;
		 }
		ul#results li:hover h3, ul#results li:hover h4  {
			color: #000;
		}
		ul#results li a {
			text-decoration: none;
			padding: 5px 15px 5px;
			float: left;
			width: 100%;
			}
		
		.top-pos {
			top:0!important;
			bottom:auto!important;
		}
		.message-text-box-wrap label {
			float:left;
			font-weight: normal!important;
			font-size: 13px;
			width: 100%;
		}
	</style>
	<script type="text/javascript">

		$(document).ready(function() {  

			$(".resultusers").each(function() {
			    $( this ).click(function(e)
		    	{
		    		//alert('hei');
		    		e.preventDefault();
		    		alert($(this).text() + ' - ' + $(this).data('userid'));
		    		$('input#recipient').val($(this).text());
		    		$('#hiddenuserid').val($(this).data('userid'));
		    		$("ul#results").fadeOut();
					$('h4#results-text').fadeOut();

		    	});
		    });
			// Live Search
			// On Search Submit and Get Results
			function search() {
				var query_value = $('input#recipient').val();
				query_value = (query_value.length < 3) ? '' : query_value;
				$('b#search-string').html(query_value);
				if(query_value !== ''){
					$.ajax({
						type: "POST",
						url: "/ajax/searchuser",
						data: { query: query_value },
						cache: false,
						success: function(html){
							$("ul#results").html(html);

							$(".resultusers").each(function() {
							    $( this ).click(function(e)
						    	{
						    		//alert('hei');
						    		e.preventDefault();
						    		$('input#recipient').val($(this).find('span').text());
						    		
		    						// $('input#recipient').val($(this).text());
		    						$('#recipientid').val($(this).data('userid'));
						    		$("ul#results").fadeOut();
									$('h4#results-text').fadeOut();
									
						    	});
						    });
						}
					});
				}return false;    
			}

			$("input#recipient").on("keyup", function(e) {
				// Set Timeout
				if($(this).is('[readonly]'))
				{

				}
				else
				{
					clearTimeout($.data(this, 'timer'));

					// Set Search String
					var search_string = $(this).val();
					search_string = (search_string.length < 3) ? '' : search_string;
					// Do Search
					if (search_string == '') {
						$("ul#results").fadeOut();
						$('h4#results-text').fadeOut();
					}else{
						$("ul#results").fadeIn();
						$('h4#results-text').fadeIn();
						$(this).data('timer', setTimeout(search, 100));
					};
				}
				
			});

			function filter() {
				var query_value = $('input#filteruser').val().toLowerCase();
				//alert('hei');
				if(query_value !== ''){
					$('.message-user').filter(function()
					{
						return ($(this).find('p.user-name b').text().toLowerCase().indexOf(query_value) == -1);
					}).hide();
					$('.message-user').filter(function()
					{
						return ($(this).find('p.user-name b').text().toLowerCase().indexOf(query_value) >= 0);
					}).show();
				}return false;    
			}

			$("input#filteruser").on("keyup", function(e) {
				// Set Timeout
				clearTimeout($.data(this, 'timer'));

				// Set Search String
				var search_string = $(this).val();

				// Do Search
				if (search_string == '') {
					$('.message-user').show();
				}else{
					$(this).data('timer', setTimeout(filter, 100));
				};
			});

			$('#buttonFilter').click(function()
			{
				$("input#filteruser").focus();
			});

			var interval = 7000;
			var refreshInbox = function() {
		    	
		        $.ajax({
		            url: "/message/ajax/refreshInbox",
		            method: "POST",
		            cache: false,
		            success: function(data) {
		            	//alert('refreshInbox');
		            	if(data.conversations.length != $('.message-user').length)
		            	{
		            		var html = '<ul class="message-wrap-user">';
			    			for (var i = 0; i < data.conversations.length; i++) {
			    				var conversation = data.conversations[i];

			    				html += '<li class="message-user" id="conversation-'+ conversation.id +'" >';
			    				html += '<a href="'+ conversation.url +'" class="show-conversation" data-fullname="'+ conversation.fullname +'" data-conversationid="'+ conversation.id +'">';
			    				html += '<span class="image-wrap"><img src="'+ conversation.image + '" alt=""></span>';
			    				html += '<div class="pull-left content-text-wrap">';
			    				html += '<p class="user-name"><b>' + conversation.fullname + '</b><span class="font-sm pull-right sent-date">'+ conversation.updated +'</span></p>';
			    				html += '<p class="last-msg">'+ conversation.message +'</p>';
			    				html += '</div></a>';
			    				html += '</li>';
			    			};
			    			if(data.conversations.length == 0)
			    			{
			    				html += '<li class="message-user"> No Message </li>';
			    			}
			    			html += '</ul>';
			    			$('#leftbar').html(html);

			    			
			    			console.log('new message ' + data.conversations.length);
		            	}
		            	else
		            	{

		            	}
		                
		                setTimeout(function() {
		                    refreshInbox();
		                }, interval);
		            }
		        });
		    };
		    refreshInbox();

		});
		
		

	</script>

	<h1> Message </h1>
	<div class="row mtop30">
		<div class="message-top-wrap">
    		<div class="col-xs-4 col-sm-4 col-md-4 message-wrap-user-head">
                <div class="input-group input-group-sm">
                  	<input type="text" class="form-control" id="filteruser" style="margin-top:1px;">
                  	<span class="input-group-btn">
                    	<button id="buttonFilter" class="btn btn-default glyphicon glyphicon-search search-main" type="button"></button>
                  	</span>
                </div>
            </div>
            <div class="col-xs-8 col-sm-8 col-md-8 message-wrap-content-head">
                <div class="message-head">
                             <h4>Compose New Message</h4>
                </div>
            </div>
        </div>
        <div id="leftbar" class="col-xs-4 col-sm-4 col-md-4 message-user-wrap">
			@if($inbox->count() == 0)
				No Message 
			@else
				<ul class="message-wrap-user">
					@foreach($inbox as $conversation)
					<li class="message-user">
						<a href="{{ url('/message/'.$conversation->otheruser(Auth::user()->id)->id) }}" class="show-conversation" data-fullname="{{ $conversation->otheruser(Auth::user()->id)->getFullname() }}" data-conversationid="{{ $conversation->id }}">
							<span class="image-wrap">
                        		<img src="{{ $conversation->otheruser(Auth::user()->id)->getPhotoUrl() }}" alt="">
                        	</span>
                        	<div class="pull-left content-text-wrap">
                        		<p class="user-name"><b>{{ $conversation->otheruser(Auth::user()->id)->getFullname() }}</b><span class="font-sm pull-right sent-date">{{ date("M d",strtotime($conversation->updated_at)) }}</span></p>
                            	<p class="last-msg">{{ $conversation->lastMessage()->preview() }}</p>
                            </div>
						</a>
					</li>
					@endforeach 
				</ul>
				
			@endif
		</div>
		
		<div id="rightbar" class="col-xs-8 col-sm-8 col-md-8 message-content-wrap">
			<div class="row">
				<div class="message-wrap-body">
					<div  class="message-wrap-in top-pos" style="width:100%;">
						<div class="message-text-box-wrap">
							{{ Form::open(array('url' => 'messages/send', 'method' => 'POST')) }}
								
								<span class="font-sm"></span>

								
								@if($selectedUser != null)
									{{ Form::hidden('recipientid', $selectedUser->id,array('id' => 'recipientid') )}}
									{{ Form::label('recipient', 'To:') }}<br/>
									{{ Form::text('recipient',$selectedUser->getFullname(),array('class'=>'form-control' ,'autocomplete' => 'off', 'required', 'readonly')) }}
								@else
									{{ Form::hidden('recipientid', '',array('id' => 'recipientid') )}}
									{{ Form::label('recipient', 'To:') }}<br/>
									{{ Form::text('recipient',null,array('class'=>'form-control' ,'autocomplete' => 'off', 'required')) }}
								@endif

								
								
								<h4 id="results-text">Showing results for: <b id="search-string">Array</b></h4>
								<ul id="results"></ul>
							
								{{ Form::label('content', 'Content:', ['class' => 'mtop15']) }}<br/>
								{{ Form::text('content',null,array('id' => 'contentreply' ,'class'=>'form-control', 'placeholder' => 'Write a Message', 'rows' => '4','required'))}} <br/>
							
								{{ Form::submit('Send',['class' => 'btn btn-primary btn-sm pull-right mtop10', 'style' => 'display:none']) }}
							

							{{ Form::close() }}
						</div>  
					
					</div>
				</div>
			</div>
		</div>
	</div>
	 	    <script>

// JavaScript
function jsUpdateSize(){
    // Get the dimensions of the viewport
    var width = window.innerWidth ||
                document.documentElement.clientWidth ||
                document.body.clientWidth;
    var height = window.innerHeight ||
                 document.documentElement.clientHeight ||
                 document.body.clientHeight;

    document.getElementById('jsHeight').innerHTML = height;
};
window.onload = jsUpdateSize;       // When the page first loads
window.onresize = jsUpdateSize;     // When the browser changes size

// jQuery
function jqUpdateSize(){
    // Get the dimensions of the viewport
    var height = $(window).height()-235;
	var height2 = $(window).height()-275;
	var height3 = $(window).height()-365;

    $('#messagebox').css("height",height);
	$('#leftbar').css("height",height2);
	$('.message-wrap-user').css("height",height2);
	$('.message-wrap-body').css("height",height2);
	$('.message-body').css("height",height3);
	
	
};
$(document).ready(jqUpdateSize);    // When the page first loads
$(window).resize(jqUpdateSize);     // When the browser changes size

//$('.message-body').scrollTop($('.message-body')[0].scrollHeight);
 </script>
@endsection