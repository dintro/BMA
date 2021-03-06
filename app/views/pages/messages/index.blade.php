@extends('layouts.master')

@section('content')
	
	<style type="text/css">
		input#recipient {
		width: 350px;
		height: 25px;
		padding: 5px;
		margin-top: 15px;
		margin-bottom: 15px;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		outline: none;
		border: 1px solid #ababab;
		font-size: 20px;
		line-height: 25px;
		color: #ababab;
		}
		input#recipient:hover, input#recipient:focus {
			color: #3b3b3b;
			border: 1px solid #36a2d2;
			-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) inset, 0 1px 0 rgba(255, 255, 255, 1);
			-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) inset, 0 1px 0 rgba(255, 255, 255, 1);
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) inset, 0 1px 0 rgba(255, 255, 255, 1);
		}
		h4#results-text {
			display: none;
		}
		ul#results {
			display: none;
			width: 360px;
			margin-top: 4px;
			border: 1px solid #ababab;
			-webkit-border-radius: 2px;
			-moz-border-radius: 2px;
			border-radius: 2px;
			-webkit-box-shadow: rgba(0, 0, 0, .15) 0 1px 3px;
			-moz-box-shadow: rgba(0,0,0,.15) 0 1px 3px;
			box-shadow: rgba(0, 0, 0, .15) 0 1px 3px;
		}
		ul#results li {
			padding: 4px;
			cursor: pointer;
			border-top: 1px solid #cdcdcd;
			transition: background-color .3s ease-in-out;
			-moz-transition: background-color .3s ease-in-out;
			-webkit-transition: background-color .3s ease-in-out;
		}
		ul#results li:hover {
			background-color: #F7F7F7;
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
			margin: 10px 0;
			font-size: 14px;
		}
		ul#results li:hover h3, ul#results li:hover h4  {
			color: #3b3b3b;
			font-weight: bold;
		}
	</style>

	<script type="text/javascript">

		function refreshConversation(conversationID)
		{
			$.post("/message/ajax/" + conversationID, function( data ) {
    			//alert(data.messages[0]);
    			//console.log(data.messages[0]);
    			var html = '<ul class="message-body" id="messagebox-body">';
    			var lastDate = null;
    			var currentUser = '';
    			for (var i = 0; i < data.messages.length; i++) {
    				var message = data.messages[i];
    				//moment(dateTime, 'YYYY-MM-DD HH:mm Z')
    				var currentDate = moment(message.created_at.date, 'YYYY-MM-DD HH:mm Z').format('MMM/DD');
    				
    				if(i == 0)
    				{
    					html += '<li class="date-separator">';
    					html += '<span class="pull-right">Conversation started ' + moment(message.created_at.date, 'YYYY-MM-DD HH:mm Z').format("MMMM DD, YYYY") + '</span></li>';
    					//lastDate = currentDate;
    				}

    				if(currentDate != lastDate && i != 0)
    				{
    					html += '<li class="date-separator">';
    					html += '<span >' + moment(message.created_at.date, 'YYYY-MM-DD HH:mm Z').format("MMMM DD, YYYY") + '</span></li>';

    					//lastDate = currentDate;
    				}

    				if(i == data.messages.length-1)
    				{
						html += '<li class="message-content" tabindex="1">';
    				}
    				else
    				{
    					html += '<li class="message-content">';
    				}
    				if(currentUser != message.userFullname)
    				{
    					html += '<span class="image-wrap"><a href="' + message.profileUrl + '"><img src="'+message.image +'" alt=""></a></span>';
    					html += '<p class="user-name"><b><a href="' + message.profileUrl + '">' + message.userFullname + '</a></b>';
    					html += '<span class="font-sm pull-right sent-time">' +  moment(message.created_at.date, 'YYYY-MM-DD HH:mm Z').format("M/DD, hh:mm a") + '</span></p>';
    					currentUser = message.userFullname;
    				}
    				else
    				{
    					if(currentDate != lastDate)
	    				{
	    					html += '<span class="image-wrap"><a href="' + message.profileUrl + '"><img src="'+message.image +'" alt=""></a></span>';
	    					html += '<p class="user-name"><b><a href="' + message.profileUrl + '">' + message.userFullname + '</a></b>';
    						html += '<span class="font-sm pull-right sent-time">' +  moment(message.created_at.date, 'YYYY-MM-DD HH:mm Z').format("M/DD, hh:mm a") + '</span></p>';
    						
	    				}
    				}
    				lastDate = currentDate;
    				html += '<p>' + message.content + '</p>';
    				html += '</li>';
    			};
    			html += '</ul>';
    			$('#message-content').html(html);
    			//$('.message-body li').last().focus()
    			var height3 = $(window).height()-365;
    			$('.message-body').css("height",height3);

    			var height2 = $(window).height()-275;
				var height3 = $(window).height()-365;
				$('#leftbar').css("height",height2);
				$('.message-wrap-user').css("height",height2);
				$('.message-wrap-body').css("height",height2);

    			$('.message-body li').last().focus()
			}, "json");	
		}

		
		$(document).ready(function()
		{

			$(".resultusers").each(function() {
			    $( this ).click(function(e)
		    	{
		    		//alert('hei');
		    		e.preventDefault();
		    		$('input#recipient').val($(this).text());
		    		$("ul#results").fadeOut();
					$('h4#results-text').fadeOut();

		    	});
		    });
			// Live Search
			// On Search Submit and Get Results
			function search() {
				var query_value = $('input#recipient').val();
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
						    		$('input#recipient').val($(this).find('h4').text());
						    		$("ul#results").fadeOut();
									$('h4#results-text').fadeOut();

						    	});
						    });
						}
					});
				}return false;    
			}

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

        	$("#reply").submit(function(e){
                e.preventDefault();
                var conversationid =  $("#conversationhiddenid").val();
                var content = $('#contentreply').val();//.replace(/(<([^>]+)>)/ig,"");
                $('#contentreply').val("");
                //alert(conversationid + '-' + content );
                $.ajax({
                    type: "POST",
                    url : "/messages/reply",
                    data : {conversationid: conversationid, content: content},
                    success : function(data){
                    	$('#contentreply').val("");
                    	$('#contentreply').focus();
                    	refresh();
                    	jqUpdateSize();
                    	//refreshConversation(conversationid);
      //               	var target = "#conversation-" + conversationid;
						// var targetMessage = target + " p.last-msg";
						// var targetDate = target + " span.sent-date";
						// $(target).prependTo('.message-wrap-user');
						// $(targetMessage).text($('#contentreply').val());
						// $(targetDate).text(DateFormat.format.date(new Date(), "MMM dd"));
                     	
                    }
                }).fail(function() {
				    alert( "error" );
				  });

        	});

			$( ".show-conversation" ).each(function() {
			    $( this ).click(function(e)
		    	{
	    		 	e.preventDefault();
	    		 	$(this).removeClass('new-message');
	    		 	var conversationid = $(this).data("conversationid");
	    		 	$("#conversationhiddenid").val($(this).data("conversationid"));
	    		 	refreshConversation(conversationid);
	    		 	$('#nameHeader').text($(this).data("fullname")) ;
		    		//alert(messageID);
		    	});
		  	});

			if($("#leftbar > ul").length != 0)
			{
				//$( ".show-conversation").first().trigger( "click" );
				$('#displayMessage').show();	
			}
			else
			{
				$('#displayMessage').hide();	
			}

			$('#buttonFilter').click(function()
			{
				$("input#filteruser").focus();
			});

			var interval = 3000;   //number of mili seconds between each call
		    var refresh = function() {
		    	var conversationid =  $("#conversationhiddenid").val();
		    	//console.log(conversationid);
		        $.ajax({
		            url: "/message/ajax/" + conversationid,
		            method: "POST",
		            cache: false,
		            success: function(data) {
		            	//alert('refresh');
		            	if(data.messages.length != $('.message-content').length)
		            	{
			                var html = '<ul class="message-body">';
			    			var lastDate = null;
			    			var currentUser = '';
			    			for (var i = 0; i < data.messages.length; i++) {
			    				var message = data.messages[i];
			    				var currentDate = moment(message.created_at.date, 'YYYY-MM-DD HH:mm Z').format('MMM/DD');

			    				if(i == 0)
			    				{
			    					html += '<li class="date-separator">';
			    					html += '<span class="pull-right">Conversation started ' + moment(message.created_at.date, 'YYYY-MM-DD HH:mm Z').format("MMMM DD, YYYY") + '</span></li>';
			    					//lastDate = currentDate;
			    				}

			    				if(currentDate != lastDate && i != 0)
			    				{
			    					//console.log(currentDate + " " + lastDate);
			    					html += '<li class="date-separator">';
			    					html += '<span >' + moment(message.created_at.date, 'YYYY-MM-DD HH:mm Z').format("MMMM DD, YYYY") + '</span></li>';
			    				}
			    				if(i == data.messages.length-1)
			    				{
		    						html += '<li class="message-content" tabindex="1">';
			    				}
			    				else
			    				{
			    					html += '<li class="message-content">';
			    				}
			    				
			    				if(currentUser != message.userFullname)
			    				{
			    					html += '<span class="image-wrap"><a href="' + message.profileUrl + '"><img src="'+message.image +'" alt=""></a></span>';
			    					html += '<p class="user-name"><b><a href="' + message.profileUrl + '">' + message.userFullname + '</a></b>';
			    					html += '<span class="font-sm pull-right sent-time">' +  moment(message.created_at.date, 'YYYY-MM-DD HH:mm Z').format("M/DD, hh:mm a") + '</span></p>';
			    					currentUser = message.userFullname;
			    				}
			    				else
			    				{

			    					if(currentDate != lastDate)
				    				{
				    					html += '<span class="image-wrap"><a href="' + message.profileUrl + '"><img src="'+message.image +'" alt=""></a></span>';
				    					html += '<p class="user-name"><b><a href="' + message.profileUrl + '">' + message.userFullname + '</a></b>';
			    						html += '<span class="font-sm pull-right sent-time">' +  moment(message.created_at.date, 'YYYY-MM-DD HH:mm Z').format("M/DD, hh:mm a") + '</span></p>';
			    						
				    				}
			    				}
			    				lastDate = currentDate;
			    				//html += '<span class="image-wrap"><a href="#"><img src="'+ message.image +'" alt=""></a></span>';
			    				//html += '<p class="user-name"><b><a href="#">' + message.userFullname + '</a></b>';
			    				//html += '<span class="font-sm pull-right sent-time">' + DateFormat.format.date(message.created_at.date, "M/dd, hh:mm p") + '</span></p>';
			    				html += '<p>' + message.content + '</p>';
			    				html += '</li>';
			    			};

			    			html += '</ul>';
			    			$('#message-content').html(html);
			    			$('#nameHeader').text(data.otheruser) ;
			    			var height3 = $(window).height()-365;
			    			$('.message-body').css("height",height3);

			    			var height2 = $(window).height()-275;
							var height3 = $(window).height()-365;
							$('#leftbar').css("height",height2);
							$('.message-wrap-user').css("height",height2);
							$('.message-wrap-body').css("height",height2);

			    			$('.message-body li').last().focus()
			    			$('#contentreply').focus();
			    			console.log('new message content' + data.messages.length);
			    		}
			    		else
			    		{

			    		}
		    			//console.log('refresh' + data.messages.length);

		                setTimeout(function() {
		                    refresh();
		                }, interval);

		            }
		        });
		    };
		    refresh();

		    var refreshInbox = function() {
		    	
		        $.ajax({
		            url: "/message/ajax/refreshInbox",
		            method: "POST",
		            cache: false,
		            success: function(data) {
		            	//alert('refreshInbox');
		            	if(data.conversations.length != 0 && data.conversations[0].messageid != $('.message-user').first().find('a').data('messageid'))
		            	{

		            		var html = '<ul class="message-wrap-user">';
			    			for (var i = 0; i < data.conversations.length; i++) {
			    				var conversation = data.conversations[i];

			    				html += '<li class="message-user" id="conversation-'+ conversation.id +'" >';
			    				html += '<a href="'+ conversation.url +'" class="show-conversation ' + ((conversation.status == 'unread') ? "new-message" : '') + '" data-fullname="'+ conversation.fullname +'" data-messageid="' + conversation.messageid + '"  data-conversationid="'+ conversation.id +'">';
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

			    			$( ".show-conversation" ).each(function() {
							    $( this ).click(function(e)
						    	{
					    		 	e.preventDefault();
					    		 	$(this).removeClass('new-message');
					    		 	var conversationid = $(this).data("conversationid");
					    		 	$("#conversationhiddenid").val($(this).data("conversationid"));
					    		 	refreshConversation(conversationid);
					    		 	$('#nameHeader').text($(this).data("fullname")) ;
						    		//alert(messageID);
						    	});
						  	});

			    			var height2 = $(window).height()-275;
							var height3 = $(window).height()-365;
							$('#leftbar').css("height",height2);
							$('.message-wrap-user').css("height",height2);
							$('.message-wrap-body').css("height",height2);
						  	
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
	<h1>Message</h1>
	
	
	<div class="row mtop30" id="messagebox">
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
                    <h3 >
                    	<span id="nameHeader">
						</span>
                    	<a href="/messages/compose"id="composeMessage" class="btn btn-default btn-sm pull-right" style="margin-top:-2px;">
                    	<span class="glyphicon glyphicon-plus"></span> <b>New Message</b></a>
                    </h3>	
                </div>
            </div>
        </div>
		<div id="leftbar" class="col-xs-4 col-sm-4 col-md-4 message-user-wrap">
			@if($hasInbox == '0')
				Inbox Empty 
			@else
				<ul class="message-wrap-user">
					@foreach($inbox as $conversation)
					<li class="message-user" id="conversation-{{ $conversation->id }}">
						<a href="{{ url('/message/'.$conversation->otheruser(Auth::user()->id)->id) }}" class="show-conversation {{ ($conversation->lastMessage()->status == 'unread' && $conversation->lastMessage()->recipient_id == Auth::user()->id ) ? 'new-message' : '' }}" data-fullname="{{ $conversation->otheruser(Auth::user()->id)->getFullname() }}" data-messageid="{{ $conversation->lastMessage()->id }}" data-conversationid="{{ $conversation->id }}">
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
			<div id="displayMessage" class="row">
				<div class="message-wrap-body">
					<div  class="message-wrap-in">
						@if($hasInbox == '0')

						@else

						<div id="message-content">

						</div>
						<div id="reply" class="message-text-box-wrap" style="padding:15px;">
							
							{{ Form::open(array('', 'id' => 'reply')) }}

								<span class="font-sm"></span>

								{{ Form::hidden('conversationhiddenid', $selectedConversation->id,array('id' => 'conversationhiddenid') )}}
								
							
								
								{{ Form::text('contentreply',null,array('id' => 'contentreply' ,'class'=>'form-control', 'placeholder' => 'Write a reply', 'rows' => '4', 'required', 'tabindex' => '2' ))}} 
							

							
								{{ Form::submit('Send',['class' => 'btn btn-primary btn-sm pull-right mtop10', 'style' => 'display:none']) }}
							

							{{ Form::close() }}
						</div>
						@endif
					</div>
					
				</div>
				
			</div>
		</div>

		<div id="messageContainer" >
			<div id="displayMessage"></div>
			<div class="modal"><!-- Place at bottom of page --></div>
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
