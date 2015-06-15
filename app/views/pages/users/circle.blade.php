@extends('layouts.profilemaster') @section('content')

<script type="text/javascript">
    function refreshFriendList() {
        $.ajax({
            type: "GET",
            url: "/circle/ajax/refreshFriendList",
            cache: false,
            success: function (data) {
                var html = '<ul class="inner-circle-list" id="friendList">';
                for (var i = 0; i < data.friends.length; i++) {
                    var friend = data.friends[i];
                    html += '<li class="inner-circle-item col-xs-6 col-sm-6 col-md-4">';
                    html += '<span class="image-wrap-lg">';
                    html += '<a href="' + friend.profileUrl + '"><img src="' + friend.image + '" alt=""></a>';
                    html += '</span>';
                    html += '<div class="pull-left content-text-wrap-lg">';
                    html += '<a href="' + friend.profileUrl + '" class="inner-circle-name">' + friend.fullname + '</a>';
                    html += '<a href="' + friend.messageUrl + '" class="btn btn-xs btn-default btn-icon"><span class="send-personal-message">Add</span><b>Send Message</b></a>';
                    html += '</div></li>';
                }
                html += '</ul>';
                $('#friend-list div').first().html(html);
                console.log('refreshed ');
            }
        });
    }

    function readAllAcceptedRequestNotification() {
        $.ajax({
            type: "POST",
            url: "/circle/ajax/readNotifications",
            data: {
                type: 'Circle Request Accepted'
            },
            cache: false,
            success: function (html) {
                console.log('read request');
            }
        });
    }

    $(document).ready(function () {

        readAllAcceptedRequestNotification()

        $('#circlerequesttabs').click(function (e) {
            $.ajax({
                type: "POST",
                url: "/circle/ajax/readNotifications",
                data: {
                    type: 'Circle Request'
                },
                cache: false,
                success: function (html) {
                    console.log('read ');
                }
            });
        });

        $('.accept-button').each(function () {
            $(this).click(function (e) {
                e.preventDefault();
                var friendRequestID = $(this).data('friendrequestid');
                $.ajax({
                    type: "POST",
                    url: "/circle/ajax/accept",
                    data: {
                        query: friendRequestID
                    },
                    cache: false,
                    success: function (html) {
                        $('.accept-button').first().parent().parent().remove()
                        if ($('#friendrequestList li').length == 0) {
                            $('#circle-request').text('You have No Friend Requests');
                        }
                        refreshFriendList();
                        console.log('accepted ' + friendRequestID);
                    }
                });


            });
        });

        $('.reject-button').each(function () {
            $(this).click(function (e) {
                e.preventDefault();
                var friendRequestID = $(this).data('friendrequestid');
                //console.log(friendRequestID)
                $.ajax({
                    type: "DELETE",
                    url: "/circle/ajax/reject",
                    data: {
                        query: friendRequestID
                    },
                    cache: false,
                    success: function (html) {
                        $('.reject-button').first().parent().parent().remove()
                        if ($('#friendrequestList li').length == 0) {
                            $('#circle-request').text('You have No Friend Requests');
                        }
                        console.log('rejected ' + friendRequestID);
                    }
                });

            });
        });

        $('.cancel-button').each(function () {
            $(this).click(function (e) {
                e.preventDefault();
                var friendRequestID = $(this).data('friendrequestid');
                //console.log(friendRequestID)
                $.ajax({
                    type: "DELETE",
                    url: "/circle/ajax/reject",
                    data: {
                        query: friendRequestID
                    },
                    cache: false,
                    success: function (html) {
                        $('.cancel-button').first().parent().parent().remove()
                        if ($('#sentFriendrequestList li').length == 0) {
                            $('#sent-request').text('You have No Sent Friend Requests');
                        }
                        console.log('canceled ' + friendRequestID);
                    }
                });

            });
        });

        var param = document.URL.split('?');
        if (param.length > 1 && param[1] == 'circle-request') {
            $('#circle-tab li').removeClass('active');
            $('#circlerequesttabs').trigger('click');
            $('#friend-list').removeClass('active');
            $('#circle-request').addClass('active');
            $('#circlerequesttabs').parent().addClass('active');
        }
        //console.log(param);
    });
</script>
<div></div>

<div class="content-top">
    <div class="container pos-relative">
        <h3 class="spaced mbtm30">
    		@if(Auth::check() && $selectedUser->id == Auth::user()->id )
    		My Circle
    		@else
    		{{ $selectedUser->getFullname() }}'s Circle
    		@endif
    	</h3>
    </div>
</div>
<div class="container">
    <div class="row mtop30">
        @if(Auth::check() && $selectedUser->id == Auth::user()->id )
        <ul id="circle-tab" class="" role="tablist">
            <li class="active"><a href="#friend-list" role="tab" data-toggle="tab">My Inner Circle</a>
            </li>
            <li><a id="circlerequesttabs" href="#circle-request" role="tab" data-toggle="tab">Circle Request</a>
            </li>
            <li><a href="#sent-request" role="tab" data-toggle="tab">Sent Request</a>
            </li>
        </ul>
        @endif
        <div id="TabContent" class="tab-content">
            <div class="tab-pane fade in active" id="friend-list">
                <div class="pad0">
                    @if($selectedUser->friends()->count() == 0) @if(Auth::check() && $selectedUser->id == Auth::user()->id ) You have No Friend @else This user have no Friend @endif @else

                    <ul class="inner-circle-list" id="friendList">
                        @foreach($selectedUser->friends()->get() as $friend)
                        <li class="inner-circle-item col-xs-6 col-sm-6 col-md-4">
                            <span class="image-wrap-lg">
                        	<a href="{{ url('/profile/'.$friend->friendInfo->id) }}"><img src="{{ $friend->friendInfo->getPhotoUrl() }}" alt=""></a>
                        	</span>
                            <div class="pull-left content-text-wrap-lg">
                                <a href="{{ url('/profile/'.$friend->friendInfo->id) }}" class="inner-circle-name">{{ $friend->friendInfo->getFullname() }}</a>
                                <a href="{{ $friend->messageUrl() }}" class="btn btn-xs btn-default btn-icon"><span class="send-personal-message">Add</span><b>Send Message</b></a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            @if(Auth::check() && $selectedUser->id == Auth::user()->id )
            <div class="tab-pane fade in" id="circle-request">
                <div class="pad0">
                    @if($selectedUser->friendrequests()->count() == 0) You have No Friend Requests @else
                    <ul class="inner-circle-list" id="friendrequestList">
                        @foreach($selectedUser->friendrequests() as $friendRequest)
                        <li class="inner-circle-item col-xs-6 col-sm-6 col-md-4">
                            <span class="image-wrap-lg">
                        	<a href="{{ url('/profile/'.$friendRequest->userInfo->id) }}"><img src=" {{ $friendRequest->userInfo->getPhotoUrl() }}" alt=""></a>
                        	</span>
                            <div class="pull-left content-text-wrap-lg">
                                <a href="{{ url('/profile/'.$friendRequest->userInfo->id) }}" class="inner-circle-name">{{ $friendRequest->userInfo->getFullname() }}</a>
                                <a href="#" class="btn btn-xs btn-default btn-icon accept-button" data-friendrequestid="{{ $friendRequest->id }}"><span class="add-sign">Add</span><b>Approve Request</b></a>
                                <a href="#" class="btn btn-xs btn-default btn-icon reject-button" data-friendrequestid="{{ $friendRequest->id }}"><span class="cancel">Ignore</span><b>Ignore Request</b></a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>

            <div class="tab-pane fade in" id="sent-request">
                <div class="pad0">
                    @if($selectedUser->sentfriendrequests()->count() == 0) You have No Sent Friend Requests @else
                    <ul class="inner-circle-list" id="sentFriendrequestList">
                        @foreach($selectedUser->sentfriendrequests() as $sentFriendRequest)
                        <li class="inner-circle-item col-xs-6 col-sm-6 col-md-4">
                            <span class="image-wrap-lg">
                        	<a href="{{ url('/profile/'.$sentFriendRequest->targetInfo->id) }}"><img src="{{ $sentFriendRequest->targetInfo->getPhotoUrl() }}" alt=""></a>
                        	</span>
                            <div class="pull-left content-text-wrap-lg">
                                <a href="{{ url('/profile/'.$sentFriendRequest->targetInfo->id) }}" class="inner-circle-name">{{ $sentFriendRequest->targetInfo->getFullname() }}</a>
                                <a href="#" class="btn btn-xs btn-default btn-icon cancel-button" data-friendrequestid="{{ $sentFriendRequest->id }}"><span class="cancel">Add</span><b>Cancel Request</b></a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection