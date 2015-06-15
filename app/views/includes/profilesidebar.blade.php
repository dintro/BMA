<div class="profile-sidebar">
    <div class="row">
    	  <div class="col-xs-4 col-sm-3 col-md-12 profile-image-wrap">
            <img src="{{ $selectedUser->getPhotoUrl() }}" alt=""/>
            @if(Auth::check() && $selectedUser->id == Auth::user()->id)
            <button class="btn btn-default edit-avatar" data-toggle="modal" data-target=".bs-example-modal-sm"><b><span class="glyphicon glyphicon-edit"></span> Edit Avatar</b></button>
            <div class="modal fade bs-example-modal-sm change-photo-wrap" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content change-photo">
                        {{ Form::open(array('url' => url('/settings/editphoto'), 'class'=>'form-signin', 'files'=>true)) }}
                        <input type="file" class="btn-primary" data-filename-placement="inside" name="photo" id="photo" accept="image/*" required>
                        <button class="btn btn-primary" type="submit">Upload</button>
                        <input class="form-control" id="user_id" name="user_id" type="hidden" value="{{ $selectedUser->id }}">
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        {{ Form::close() }}
                        
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="col-xs-8 col-sm-4 col-md-12 profile-detail-wrap">
            
            <h3> <span id="user-firstname">{{$selectedUser->firstname}}</span> <span id="user-lastname">{{$selectedUser->lastname}}</span></h3>
            <h4><span id="user-country">{{$selectedUser->country}}</span></h4>
            @if(Auth::check())
            <div class="user-btn-wrap">
             
                  @if(Auth::user()->id == $selectedUser->id)
                      
                  @else
                      @if(Friend::alreadyFriend($selectedUser->id, Auth::user()->id))
                          
                      @elseif($sentFriendRequest != null)
                          <a href="#" class="btn btn-primary"><span class="checks">Add</span><b>Request Sent</b></a>
                      @elseif($friendRequest != null)
                          <a href="#" id="accept-button" data-friendrequestid="{{ $friendRequest->id }}" class="btn btn-primary"><span class="add-circle">Add</span><b>Accept Request</b></a>
                          <a href="#" id="reject-button" data-friendrequestid="{{ $friendRequest->id }}" class="btn btn-primary"><span class="cancel">Ignore</span><b>Ignore Request</b></a>
                      @else
                          <a href="#" id="addcircle" data-userid="{{ $selectedUser->id }}" class="btn btn-primary"><span class="add-circle">Add</span><b>Add to My Circle</b></a>
                      @endif
                          <a href="{{ url('/messages/compose/'.$selectedUser->id) }}"  data-userid="{{ $selectedUser->id }}" class="btn btn-primary"><span class="send-personal-message">Add</span><b>Send Message</b></a>
                          
                      @if(User::userBlock(Auth::user()->id,$selectedUser->id))
                      		<a href="#" id="unblock-button" class="btn btn-primary" data-userid="{{ Auth::user()->id }}"  data-userblockid="{{ $selectedUser->id }}"><span class="unblockuser">Unblock</span><b>Unblock this user</b></a>	
                      @else
                      		<a href="#" id="block-button" class="btn btn-primary"  data-userid="{{ Auth::user()->id }}"  data-userblockid="{{ $selectedUser->id }}"><span class="blockuser">Block</span><b>Block this user</b></a>
                      @endif
                  @endif
              
          	</div>
       	    @endif
            
        </div>
        <ul class="profile-sidebar-menu">
            <li><a href="{{ url('/about-me/'. $selectedUser->id)}}" class="smenu-settings">About Me</a></li>
            <li><a href="{{ url('/profile/'. $selectedUser->id)}}" class="smenu-spade">Active Package</a></li>
            <li><a href="{{ url('/pastpackages/'. $selectedUser->id)}}" class="smenu-past">Past Package</a></li>
            
            @if(Auth::check() && $selectedUser->id == Auth::user()->id)
            <li><a href="/users/mysweats" class="smenu-settings">My Sweats</a></li>
             <li><a href="/blocklist" class="smenu-settings">Block Lists</a></li>
            <li><a href="/users/settings" class="smenu-settings">Settings</a></li>
            @endif
      	</ul>
        <div class="col-sm-5 col-md-12 pull-left">
            <div class="circle-wrap">
            <p>Circle</p>
            @if($selectedUser->friends()->count() == 0)
                @if(Auth::check() && Auth::user()->email == $selectedUser->email)
                    You Have No Friends
                    <a class="font-sm" href="{{ url('/mycircle') }}"><b>Full Circle List</b></a>
                @else
                    This User Have No Friends
                    <a class="font-sm" href="{{ url('/circle/'.$selectedUser->id) }}"><b>Full Circle List</b></a>
                @endif
            @else
            <ul class="circle-list">
                @foreach($selectedUser->friends()->take(12)->get() as $friend)
            		    <li><a href="{{ url('/profile/'.$friend->friendInfo->id) }}" title="{{ $friend->friendInfo->getFullname() }}" ><img src="{{ $friend->friendInfo->getPhotoUrl() }}" alt=""></a></li>
                @endforeach
            </ul>
            <a class="font-sm" href="{{ url('/circle/'.$selectedUser->id) }}"><b>Full Circle List</b></a>
            @endif
            
            </div>
        </div>
    </div>
</div>
<div class="content-top-in">
    <div class="col-xs-7 col-sm-9 col-md-8 ingames">
        <h3 class="spaced title-sm">Screen Names</h3>
          
          <div class="col-xs-6 col-sm-3 col-md-3 scrname-1" style="display:{{ ($selectedUser->getScreenname('Pokerstars') != '') ? "block" : "none" }}">
              <div class="thumbnail ingamename">
                <img src="/img/pokerstars-logo-sm.jpg" alt="">
                <div class="caption">
                  <p>{{ $selectedUser->getScreenname('Pokerstars') }}</p>
                </div>
              </div>
          </div>

          <div class="col-xs-6 col-sm-3 col-md-3 scrname-2" style="display:{{ ($selectedUser->getScreenname('Fulltilt') != '') ? "block" : "none" }}">
              <div class="thumbnail ingamename">
                <img src="/img/adjarabet-logo-sm.jpg" alt="">
                <div class="caption">
                  <p>{{ $selectedUser->getScreenname('Fulltilt') }}</p>
                </div>
              </div>
          </div>

          <div class="col-xs-6 col-sm-3 col-md-3 scrname-3" style="display:{{ ($selectedUser->getScreenname('888') != '') ? "block" : "none" }}">
              <div class="thumbnail ingamename">
                <img src="/img/888-logo-sm.jpg" alt="">
                <div class="caption">
                  <p>{{ $selectedUser->getScreenname('888') }}</p>
                </div>
              </div>
          </div>
          
          <div class="col-xs-6 col-sm-3 col-md-3 scrname-4" style="display:{{ ($selectedUser->getScreenname('Party Poker') != '') ? "block" : "none" }}">
              <div class="thumbnail ingamename">
                <img src="/img/ipoker-logo-sm.jpg" alt="">
                <div class="caption">
                  <p>{{ $selectedUser->getScreenname('Party Poker') }}</p>
                </div>
              </div>
          </div>
          
          <div class="col-xs-6 col-sm-3 col-md-3 scrname-5" style="display:{{ ($selectedUser->getScreenname('Titan Poker') != '') ? "block" : "none" }}">
              <div class="thumbnail ingamename">
                <img src="/img/ipoker-logo-sm.jpg" alt="">
                <div class="caption">
                  <p>{{ $selectedUser->getScreenname('Titan Poker') }}</p>
                </div>
              </div>
          </div>

          <div class="col-xs-6 col-sm-3 col-md-3 scrname-5" style="display:{{ ($selectedUser->getScreenname('Americas Card Room') != '') ? "block" : "none" }}">
              <div class="thumbnail ingamename">
                <img src="/img/ipoker-logo-sm.jpg" alt="">
                <div class="caption">
                  <p>{{ $selectedUser->getScreenname('Americas Card Room') }}</p>
                </div>
              </div>
          </div>
          
    </div>
    <div class="col-xs-5 col-sm-3 col-md-3 col-md-offset-1 profile-personal-stats">
        @if(Auth::check() && $selectedUser->id == Auth::user()->id)
        <h3 class="spaced title-sm">Your Stats</h3>
        @else
        <h3 class="spaced title-sm">{{ $selectedUser->getFullname() }}'s Stats</h3>
        @endif
        <?php
            $packages_sold = $selectedUser->getPackageSold();
            $packages_bough = $selectedUser->getPackageBought();
        ?>
        <p class="font-sm">Pieces Bought / Value</p>
        <p><b>{{ $packages_bough['count'] }} Pieces / $ {{ $packages_bough['value'] }}</b></p>
        <p class="font-sm">Pieces Sold / Value</p>
        <p><b>{{ $packages_sold['count'] }} Pieces / $ {{ $packages_sold['value'] }}</b></p>
    </div>
</div>