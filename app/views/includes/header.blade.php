
<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<script src="{{ url('/js/dateFormat.js') }}" ></script>
<script src="{{ url('/js/moment.js') }}" ></script>
<script src="{{ url('/js/moment-timezone.js') }}" ></script>
<script type="text/javascript">
    

    $(document).ready(function()
    {

        $('#dropdown-mycircle').click(function(e){
            var count = $('#notificationCount');
            if(count.text() == 0)
            {
                //console.log('empty');
            }
            else
            {
                //console.log('not empty');
                $.ajax({
                    type: "POST",
                    url: "/notification/ajax/readAllNotifications",
                    cache: false,
                    success: function(html){
                        console.log( 'read all');
                    }
                });
                
            }
            

        });

        $('#dropdown-message').click(function(e){
            var count = $('#messageNotificationCount');
            if(count.text() == 0)
            {
                //console.log('empty');
            }
            else
            {
                //console.log('not empty');
                $.ajax({
                    type: "POST",
                    url: "/message/ajax/readAllMessageNotifications",
                    cache: false,
                    success: function(html){
                        console.log( 'read all message');
                    }
                });
            }
            

        });

        $('.datetime-format').each(function() {
            var dateTime = $(this).data('messagetime');
            //$(this).text(DateFormat.format.prettyDate(dateTime));
            $(this).text(moment(dateTime, 'YYYY-MM-DD HH:mm Z').fromNow());

        });

        $('.notificationLink').each(function() {
            $( this ).click(function(e){
                var notificationID = $(this).data('notificationid');
                $.ajax({
                    type: "POST",
                    url: "/notification/ajax/readNotification",
                    data: { query: notificationID },
                    cache: false,
                    success: function(html){
                        console.log( 'read ' + notificationID);
                    }
                });
                

            });

        });

        

        var interval = 5000;   //number of mili seconds between each call
        var refreshMessageNotification = function() {

            $.ajax({
                url: "/message/ajax/refreshMessageNotification",
                method: "POST",
                cache: false,
                success: function(data) {
                    //alert('refresh');
                    
                    if(data.messages.length != 0 && data.messages[0].messageid != $('#dropdownMessage').first().find('a').data('messageid'))
                    {
                        var html = '';
                        for (var i = 0; i < data.messages.length; i++) {
                            var message = data.messages[i];

                            html += '<li>';
                            if(message.status == 'unread')
                            {
                                html += '<a class="new-message" href="'+ message.url +'" data-messageid="' + message.messageid + '">';
                            }
                            else
                            {
                                html += '<a href="'+ message.url +'" data-messageid="' + message.messageid + '">';
                            }
                            
                            html += '<img src="' + message.image + '" alt="">';
                            html += '<div>';
                            html += '<strong>'+ message.fullname +'</strong>';
                            html += '<span data-messagetime="' + message.updated.date + '" class="pull-right text-muted datetime-format"><em>'+ message.updated.date +'</em></span>';
                            html += '</div>';
                            html += '<div><p>'+ message.message +'</p></div>';
                            html += '</a>';
                            html += '</li>';
                        }

                        $('#messageNotificationCount').text(data.newConversation);
                        console.log('new message notification ' + data.messages.length);
                        if(data.messages.length == 0)
                        {
                            html += '<li> <a href="#" > No new message </a> </li>';
                        }

                        html += '<li><a class="text-center" href="{{ url('/messages') }}"><strong>See All </strong><i class="fa fa-angle-right"></i></a></li>';

                        $('#dropdownMessage').html(html);

                        $('.notificationLink').each(function() {
                            $( this ).click(function(e){
                                var notificationID = $(this).data('notificationid');
                                $.ajax({
                                    type: "POST",
                                    url: "/notification/ajax/readNotification",
                                    data: { query: notificationID },
                                    cache: false,
                                    success: function(html){
                                        console.log( 'read ' + notificationID);
                                    }
                                });
                            });
                        });

                    }
                    else
                    {
                        
                    }
                    
                    
                    $('#messageNotificationCount').text(data.newConversation);

                    if($('#messageNotificationCount').text() == 0)
                    {
                        $('#messageNotificationCount').hide();
                    }
                    else
                    {
                        $('#messageNotificationCount').show()
                    }

                    $('.datetime-format').each(function() {
                        var dateTime = $(this).data('messagetime');
                        //$(this).text(DateFormat.format.prettyDate(dateTime));
                        
                        $(this).text(moment(dateTime, 'YYYY-MM-DD HH:mm Z').fromNow());

                    });

                    setTimeout(function() {
                        refreshMessageNotification();
                    }, interval);

                    
                }
            });
        };
        

        var refreshNotification = function() {

            $.ajax({
                url: "/notification/ajax/refreshNotification",
                method: "POST",
                cache: false,
                success: function(data) {
                    //alert('refresh');
                   

                    if(data.notifications.length != 0 && data.notifications[0].id != $('#dropdownNotification').first().find('a').data('notificationid'))
                    {
                        var html = '';
                        for (var p = 0; p < data.notifications.length; p++) {
                            var notification = data.notifications[p];
                            console.log(data.notifications.length);
                             html += '<li>';
                            if(notification.status == 'unread')
                            {
                                html += '<a class="notificationLink new-message" data-notificationid="'+ notification.id +'" href="'+ notification.url +'">';
                            }
                            else
                            {
                                html += '<a class="notificationLink" data-notificationid="'+ notification.id +'" href="'+ notification.url +'">';
                            }
                            
                            html += '<img src="' + notification.image + '" alt="">';
                            html += '<div>';
                            html += '<p class="comment-head"><b>'+ notification.type +'</b></p>'
                            html += '<span class="pull-right text-muted small datetime-format" data-messagetime="'+ notification.updated.date +'">'+ notification.updated.date +'</span>';
                            
                            html += '</div>';
                            html += '<div><p>'+ notification.content +'</p></div>';
                            html += '</a>';
                            html += '</li>';
                        }

                        $('#notificationCount').text(data.unreadNotificationCount);
                        console.log('new notification ' + data.notifications.length);
                        if(data.notifications.length == 0)
                        {
                            html += '<li> <a href="#" > No new notification </a> </li>';
                            
                        }

                        

                        html += '<li><a class="text-center" href="{{ url('/users/notifications') }}"><strong>See All </strong><i class="fa fa-angle-right"></i></a></li>';

                        $('#dropdownNotification').html(html);

                    }
                    else
                    {
                        
                    }
                    $('#notificationCount').text(data.unreadNotificationCount);

                    if(data.unreadNotificationCount == 0)
                    {
                        $('#notificationCount').hide();
                    }
                    else
                    {
                        $('#notificationCount').show()
                    }

                    $('.datetime-format').each(function() {
                        var dateTime = $(this).data('messagetime');
                        //$(this).text(DateFormat.format.prettyDate(dateTime));
                        
                        $(this).text(moment(dateTime, 'YYYY-MM-DD HH:mm Z').fromNow());

                    });

                    setTimeout(function() {
                        refreshNotification();
                    }, interval);

                    
                }
            });
        };
        @if(Auth::check())
        refreshMessageNotification();  
        refreshNotification();  
        @endif
        // $(".resultusers").each(function() {
        //     $( this ).click(function(e)
        //     {
        //         //alert('hei');
        //         e.preventDefault();
        //         //alert($(this).text() + ' - ' + $(this).data('userid'));
        //         $('input#search').val($(this).text());
        //         $("ul#header-results").fadeOut();
        //         $('h4#header-results-text').fadeOut();

        //     });
        // });
        // Live Search
        // On Search Submit and Get Results
        function searchuser() {
            var query_value = $('input#search').val();
            query_value = (query_value.length < 3) ? '' : query_value;
            $('b#header-search-string').html(query_value);
            if(query_value !== ''){
                $.ajax({
                    type: "POST",
                    url: "/ajax/searchuserheader",
                    data: { query: query_value },
                    cache: false,
                    success: function(html){
                        $(".loading").hide();
                        $("ul#header-results").html(html);

                        // $(".header-resultusers").each(function() {
                        //     $( this ).click(function(e)
                        //     {
                        //         //alert('hei');
                        //         e.preventDefault();
                                
                                
                        //         // $('input#recipient').val($(this).text());
                        //         $("ul#header-results").fadeOut();
                        //         $('h4#header-results-text').fadeOut();

                        //     });
                        // });
                    }
                });
            }return false;    
        }

        $("input#search").on("keyup", function(e) {
            // Set Timeout
           
            clearTimeout($.data(this, 'timer'));

            // Set Search String
            var search_string = $(this).val();
            search_string = (search_string.length < 3) ? '' : search_string;
            // Do Search
            if (search_string == '') {
                $(".loading").hide();
                $("ul#header-results").fadeOut();
                $('h4#header-results-text').fadeOut();
                $('#header-search-result').hide();
                $('#header-topuser-list').show();
            }else{
                $(".loading").show();
                $('#header-topuser-list').hide();
                $('#header-search-result').show();
                $("ul#header-results").fadeIn();
                $('h4#header-results-text').fadeIn();
                $(this).data('timer', setTimeout(searchuser, 100));
            };
        
            
        });

        $('#header-search-button').click(function(e){
            e.preventDefault();
            $("input#search").focus();
        });
    });
</script>

  		
<div class="container head-relative">
    
    <ul class="jetmenu blue">
       	<li>
        	<h1 id="logo">
            <a href="/">Buy My Action</a>
            </h1>
        </li>
		
		<li>
			<a href="/">Home</a>
		</li>
		
		<li><a href="/users/profile">Dashboard</a></li>

        <li class="sub-head">
		  	
			
            <a href="#">Player Search</a>

            <div class="megamenu full-width row">
            	<div>
            		<form class="form-search">
                          <div class="">
                            <input type="text" id="search" autocomplete="off" class="width-lrg head-search" />
                        	
                            <button type="submit" id='header-search-button' class="btn width-sml head-search-btn"><span class="glyphicon glyphicon-search"</button>
                          </div>
                    </form>
                    <div class="top-list-main-wrap" id="header-search-result" style="display:none">
                        <div class="loading" style="text-align: center;margin-top: 20px;">
                            <img src="{{url('/img/loading.GIF')}}" style="width:30px;height:30px;" >
                        </div>
                        
                        <h4 id="header-results-text">Showing results for: <b id="header-search-string">Array</b></h4>
                        <ul id="header-results"></ul>
                    </div>
                    <br><br>
                		
                </div>
                
            </div>
        </li>
        <li>
            <a href="/faq">Faq</a>
        </li>
        <li>
            <a href="/contactus">Contact Us</a>
        </li>
        @if(!Auth::check())   
        <li class="btn-join-wrap">
            <a class="btn-join" href="/signup">Join Now !</a>
        </li>        
        @endif
    </ul>
    @if(!Auth::check())  
    <div class="col-md-3 pull-right login-wrap">
        <div class="login-top">
            {{ Form::open(array('url'=>'users/signin', 'role'=>'form')) }}
        <!-- <form class="form-signin" role="form"> -->
                <input type="text" name="email" id="email" class="form-control email-form" placeholder="Email address" required autofocus>
                <input type="password" name="password" id="password" class="form-control password-form" placeholder="Password" required>
              	<div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <button class="btn btn-xs btn-warning" type="submit" value="login"><span class="glyphicon glyphicon-lock glyph-sm"></span>Log in</button>                            
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <a class="head-forgot-password" href="/users/forgotpassword">Forgot<br>Password ?</a>
                    </div>
                </div>
          <!-- </form> -->
            {{ Form::close() }}
        </div>
        <div class="login-bottom">
            <a href="/sign-in-with-facebook" class="btn btn-block btn-social btn-facebook"><i class="fa fa-facebook"></i>Sign in with Facebook</a>
		    </div>
    </div>
    @else
    <div class="col-md-4 pull-right logged-in-wrap">
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown user-display-top">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <span id="header-firstname">{{Auth::user()->firstname}}</span> <span id="header-lastname">{{Auth::user()->lastname}}</span><i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="/users/profile"><i class="fa fa-user fa-fw"></i> Profile</a>
                    </li>
                    <li><a href="/users/settings"><i class="fa fa-gear fa-fw"></i> Settings</a>
                    </li>
                     
                    <li><a href="/users/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle my-circle" data-toggle="dropdown" id="dropdown-mycircle" href="#">
                    My Circle
                    <span class="badge-sm pull-right" id="notificationCount">0</span>
                </a>
                <ul class="dropdown-menu dropdown-alerts" id="dropdownNotification">
                    @if(Auth::user()->notifications()->count() == 0)
                    <li>
                        <a href="#">
                           No new notification
                        </a>
                    </li>
                    @else
                    @foreach(Auth::user()->notifications()->orderBy('created_at', 'DESC')->take(5)->get() as $notification)
                    <li>
                        <a class="notificationLink {{ $notification->status == 'unread' ? 'new-message' : '' }}" class="new-message" data-notificationid="{{ $notification->id }}" href="{{ $notification->url }}">
                            <img src="{{ $notification->user->getPhotoUrl()}}" alt="">
                            <div>
                                <p class="comment-head">
                                <b>{{ $notification->type }}</b>
                                </p>
                                <span class="pull-right text-muted small datetime-format" data-messagetime="{{ $notification->created_at }}">{{ $notification->created_at }}</span>
                            </div>
                            <div><p>{{ $notification->content }}</p></div>
                        </a>
                    </li>
                    @endforeach
                    @endif
                    
                    
                    <li>
                        <a class="text-center" href="/users/notifications">
                            <strong>See All </strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
                <!-- /.dropdown-alerts -->
            </li>
            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle chat-icon" data-toggle="dropdown" id="dropdown-message" href="#">
                    Messages
                    <span class="badge-sm pull-right" id="messageNotificationCount">0</span>
                </a>
                <ul class="dropdown-menu dropdown-messages" id="dropdownMessage">
                    @if(!Auth::user()->hasInbox())
                    <li>
                        <a href="#">
                           No new message
                        </a>
                    </li>
                    @else
                    @foreach(Auth::user()->inbox()->orderBy('updated_at', 'DESC')->take(5)->get() as $conversation)
                    
                    <li>
                        @if($conversation->lastMessage()->status == 'unread' && $conversation->lastMessage()->recipient_id == Auth::user()->id)
                        <a href="{{ url('/message/'.$conversation->otheruser(Auth::user()->id)->id) }}" class="new-message" data-messageid="{{ $conversation->lastMessage()->id }}">
                        @else
                        <a href="{{ url('/message/'.$conversation->otheruser(Auth::user()->id)->id) }}" data-messageid="{{ $conversation->lastMessage()->id }}">
                        @endif
                            <img src="{{ $conversation->otheruser(Auth::user()->id)->getPhotoUrl()}}" alt="">
                            <div>
                                <strong>{{ $conversation->otheruser(Auth::user()->id)->getFullname() }}</strong>
                                <span class="pull-right text-muted datetime-format" data-messagetime="{{ $conversation->lastMessage()->created_at }}">
                                    <em>{{ $conversation->lastMessage()->created_at }}</em>
                                </span>
                            </div>
                            <div><p>{{ $conversation->lastMessage()->preview() }}</p></div>
                        </a>
                    </li>
                    @endforeach
                    
                    @endif
                    
                     
                    <li>
                        <a class="text-center" href="{{ url('/messages') }}">
                            <strong>See All</strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
                <!-- /.dropdown-messages -->
            </li>
            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle spade" data-toggle="dropdown" href="#">
                    My Sweat
                </a>
                <ul class="dropdown-menu dropdown-tasks">
                    
				<?php
				$myorders = Order::where('user_id','=',Auth::user()->id)->orderBy('order_date','DESC')->take(3)->get();							
				?>
				
				@foreach($myorders as $order)	
					<?php
						$orderdetail =  OrderDetail::where('order_id','=',$order->id)->first();
						$package = Package::find($orderdetail->package_id);
						$userp = User::find($package->user_id); 
					?>
                    
                    <li>
                        <a href="#">
                            <div class="wdth10">
                            <img src="{{$userp->getPhotoUrl()}}" alt="">
                            </div>
                            <div class="wdth90">
                                <div class="wdth70">
                                <p>
                                    <b>{{$userp->firstname}} {{$userp->lastname}}</b>
                                </p>
                                <p>
                                    {{$orderdetail->package_name}}
                                </p>
                                </div>
                                <div class="wdth30">
                                    <span class="my-sweats-percent">{{$orderdetail->selling}}%</span>
                                </div>
                            </div>
                        </a>
                    </li>
                     	@endforeach
                    <li>
                        <a class="text-center" href="/users/mysweats">
                            <strong>See All</strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
                <!-- /.dropdown-tasks -->
            </li>
            <!-- /.dropdown -->
            
        </ul>
    </div>
      <!--  <div class="login-top">
        
        <a href="{{ url('/messages') }}" > My Message </a> {{ '0' }}<!-- Auth::user()->unreadMessages()->count() -->
        <!-- </div>
    <div class="login-bottom">
        <a href="/users/logout" class="btn btn-block">Logout</a>
    </div> -->
    @endif
      
</div>
