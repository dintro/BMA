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
                        	<h4 class="spaced">SharkScope(s)</h4>
                        </div>
                        <div class="col-xs-4 col-sm-6 col-md-6 new-package">
                        	<?php
                                $now = date("Y-m-d H:i:s");
                                $now1 = date("Y-m-d");
                                echo 'Now : '. $now.' - '.strtotime($now).' - '.strtotime($now1);
                                //$tomorrow =  date("Y-m-d H:i:s", mktime(23, 59, 59, date("m")  , date("d"), date("Y")));
                                $tomorrow = date("Y-m-d", strtotime(date("Y-m-d", strtotime($now1)) . " +1 days"));
                                echo '<br/>Tomorrow : '. $tomorrow.' - '.strtotime($tomorrow);
                            ?>
                        	
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
            		<?php
                        //----SHARKSCOPE CODE START----//
                        $encoded1 = hash('md5', 'susu1234');
                        $license = 'lekrj5n23nsx8an4kl';
                        $encoded2 = strtolower($encoded1) . strtolower($license);
                        
                        $digest = hash('md5', $encoded2);
                        
                        $sharkuser = "sildeyna@gmail.com";

                        // jSON URL which should be requested
                        //$json_url = 'http://www.sharkscope.com/api/bma/networks/PokerStars/players/SkaiWalkurrr?Username='.$sharkuser.'&Password='.$digest;
                        $unixnow = strtotime($now);
                        $unixTomorrow = strtotime($tomorrow);
                        $json_url = 'http://www.sharkscope.com/api/bma/networks/fulltilt/activeTournaments?filter=Date:'.$unixnow.'~'.$unixTomorrow.'';
                        //$json_url = 'http://www.sharkscope.com/api/bma/networks/PokerStars/tournaments/1035110188';
                        $username = $sharkuser;  // authentication
                        $password = $digest;  // authentication

                        // jSON String for request

                        // Initializing curl
                        $ch = curl_init( $json_url );
                        $limit = 5;
                        // Configuring curl options
                        $options = array(
                        CURLOPT_RETURNTRANSFER => true,
                        
                        CURLOPT_HTTPHEADER => array('Content-type: application/json', 'Accept: application/json', 'Username: '.$sharkuser, 'Password: '.$digest, 'User-Agent: Mozilla', 'limit: '. $limit)

                        );

                        // Setting curl options
                        curl_setopt_array( $ch, $options );
                        
                        // Getting results
                        $result = curl_exec($ch); // Getting jSON result string
                        if($result === FALSE){
                            echo curl_error($ch);
                        }
                        $responseData = json_decode($result, TRUE);

                        $responseArray = $responseData["Response"];
                        //$data["success"] = $responseArray["@success"];
                        //$data["timestamp"] = $responseArray["@timestamp"];
                        //$data["remainingsearch"] = $responseArray["UserInfo"]["Subscriptions"]["@totalSearchesRemaining"];
                        //$tournamentArray = $responseArray["RegisteringTournamentsResponse"]["RegisteringTournaments"]["RegisteringTournament"];
                        //echo '<h3>'.count($tournamentArray).'</h3>';

                        //echo var_dump($responseData);
                        //echo $result;
                        
                        ?>
            	</div>
            </div>
        </div>
    </div>
</div>
@endsection