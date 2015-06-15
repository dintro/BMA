<?php

class UsersController extends BaseController 
{
   	protected $layout = 'layouts.master';

   	public function __construct() {
	    $this->beforeFilter('csrf', array('on'=>'post'));
	    $this->beforeFilter('auth', array('only'=>array('getDashboard')));
	}

   	public function getSingup() 
   	{
    	$this->layout->content = View::make('pages.users.signup');
	}

	public function settings() 
   	{
    	$this->layout->content = View::make('pages.users.setting');
	}
	
	public function verifyEmail()
	{
		$token = Input::get('token');	

		$user = DB::table('users')->where('email_verified_token', $token)->first();

		//echo ' | '.$user->id;

		if($user){
			DB::table('users')
	            	->where('email_verified_token', $token)
	            	->update(array('email_verified' => 1,'active'=>1));

	        return Redirect::to('login')->with('message', 'Your account is active now, please login!')->with('success', '1');
        }else{
        	return Redirect::to('blank')->with('message', 'Verification not valid! please check your email verification.');
        }       
        
	}
	
	public function resetPassword()
	{
		$data = Input::all();	
		$validator = Validator::make($data, array(
					'password'=>'required|alpha_num|between:6,12|confirmed',
					'password_confirmation'=>'required|alpha_num|between:6,12',
				));
				
		if ($validator->passes()) 
		{		
			$token = Input::get('token');	
	
			$user = DB::table('users')->where('email_verified_token', $token)->first();
	
			if($user){
				DB::table('users')
				->where('email',Input::get('email'))
				->update( array('password' => Hash::make(Input::get('password')) ));
				
				return Redirect::to('users/login')->with('message','Your password has been changed!')->with('success','1');
			}else{
				return Redirect::to('blank')->with('message', 'Verification not valid! please check your email verification.');
			}  
		}
		else
		{
			return Redirect::to('users/resetpassword?token='.Input::get('token'))
			->with('email',Input::get('email'))
			->with('message', 'The following errors occurred')->withErrors($validator)->withInput();
		}
        
	}
	
	public function forgotPassword()
	{
		$email = Input::get('email');	
		$user =	DB::table('users')->where('email', $email)->first();
		
		if($user)
		{
			$token = str_random(40);
			
			if($user->email_verified_token != '') 
			{
				$token = $user->email_verified_token;
			}else
			{
				DB::table('users')
	            	->where('id', $user->id)	
					->update(array('email_verified_token' => $token));
			}
			
			$useremail = $user->email;
			$firstname = $user->firstname;
			$lastname = $user->lastname;
			
			
			
			
			Mail::send('emails.forgot', array('firstname'=>$firstname,'token'=>$token), function($message){
				
		        $message->to(Input::get('email'), 'firstname')->subject('[Buy My Action] Forgot Password!');
		    });
			/*
			return $useremail.'#'.$firstname.'#'.$lastname.'#'.$token;
			*/
			return Redirect::to('users/forgotpassword')
				->with('message', 'Request change password has been sent to '.$useremail.'!')
				->with('success','1');
			
		}
		else
		{
			return Redirect::to('users/forgotpassword')
				->with('message', 'Email: <b>'.$email.'</b> not found!');
		}
	}


	public function postCreate() 
	{
        $validator = Validator::make(Input::all(), User::$rules);
 
		if ($validator->passes()) 
		{
		    // validation has passed, save user in DB
			$user = new User;
		    $user->firstname = Input::get('first_name');
		    $user->lastname = Input::get('last_name');
		    $user->displayname = Input::get('display_name');
		    $user->email = Input::get('email');
		    $user->password = Hash::make(Input::get('password'));
		    $user->email_verified_token = str_random(40);
		    $user->save();

		    Mail::send('emails.welcome', array('firstname'=>Input::get('first_name'),'token'=>$user->email_verified_token), function($message){
		        $message->to(Input::get('email'), Input::get('firstname').' '.Input::get('last_name'))->subject('Welcome to the Buy My Action!');
		    });
		 
		    return Redirect::to('login')
					->with('message', 'Thanks for registering!<br> Confirmation email has been sent to <b>'. $user->email.'</b>')
					->with('success','1');
		} 
		else
		{
		    // validation has failed, display error messages    
			return Redirect::to('signup')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();
	    }		
	}

	public $fb_state; 

	public function postCreatefb() 
	{
        $validator = Validator::make(Input::all(), User::$rules);		
 		

		if ($validator->passes()) 
		{
		    // validation has passed, save user in DB
			$user = new User;
		    $user->firstname = Input::get('first_name');
		    $user->lastname = Input::get('last_name');
		    $user->displayname = Input::get('display_name');
		    $user->email = Input::get('email');
		    $user->facebook_email = Input::get('facebook_email');
		    $user->password = Hash::make(Input::get('password'));
		    $user->email_verified_token = str_random(40);
		    $user->save();

		    Mail::send('emails.welcome', array('firstname'=>Input::get('first_name'),'token'=>$user->email_verified_token), function($message){
		        $message->to(Input::get('email'), Input::get('firstname').' '.Input::get('last_name'))->subject('Welcome to the Buy My Action!');
		    });
		 
		    return Redirect::to('login')->with('message', 'Thanks for registering!<br> Confirmation email has been sent to '. $user->email);
		} 
		else
		{
			// Hack for Facebook: Sessions get destroyed with every page call, 
            // thus Redirect::back()->withErrors() won't work
	      	// if ($this->fb_state) {
	      	// 	$view_errors = new Illuminate\Support\ViewErrorBag;
	      	// 	$view_errors->put('default', $validator->getMessageBag());
	                
	       //      return $this->postRegister(array('errors'=> $view_errors, 'input'	=> Input::all()));
	      	// }
          
        //     return Redirect::back()->withErrors($validator)->withInput();
			//dd($result);
		    // validation has failed, display error messages    
			return Redirect::to('signupfb')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();
	    }		
	}


	public function getLogin() {
    	$this->layout->content = View::make('pages.users.login');
	}

	public function getLogout() {
	    Auth::logout();
	    return Redirect::to('login')->with('message', 'Your are now logged out!')->with('success', '1');
	}

	public function postSignin() {
        if (Auth::attempt(array('email'=>Input::get('email'), 'password'=>Input::get('password'), 'active' => 1))) {
			$user = Auth::user();
		    //return Redirect::to('users/profile')
			return Redirect::to('circle/'. $user->id)
				->with('message', 'You are now logged in!')
				->with('selectedUser', $user);
		} else {
		    return Redirect::to('login')
		        ->with('message', 'Your username/password combination was incorrect')
		        ->withInput();
		}    
	}

	//Settings	
	public function getSettings() {
    	if (Auth::check())
		{
			$user = Auth::user();
		   	return View::make('pages.users.setting')
				->with('selectedUser', $user);
		}else
		{
			return Redirect::to('login')->with('message', 'Please log-in!');
		}
	}
	public function getScreenname($id){
		Screenname::where('id', '=', $id)->delete();
		$user = Auth::user();
		   	return Redirect::to('users/settings')
				->with('selectedUser', $user);
	}

	public function postScreenname(){
		
		$pokerstars  = Input::get('pokerstars');
		$fulltilt  = Input::get('fulltilt');
		$code888 = Input::get('888');
		$partypoker  = Input::get('partypoker');
		$titanpoker = Input::get('titanpoker');
		$user_id = Input::get('user_id');
		
		Screenname::where('user_id', '=', $user_id)->delete();
		
		$screenname = new Screenname;
		$screenname->user_id = $user_id;
		$screenname->screen_name = 'Pokerstars';
		$screenname->username = $pokerstars;
		$screenname->Save();
		
		$screenname = new Screenname;
		$screenname->user_id = $user_id;
		$screenname->screen_name = 'Fulltilt';
		$screenname->username = $fulltilt;
		$screenname->Save();
		
		$screenname = new Screenname;
		$screenname->user_id = $user_id;
		$screenname->screen_name = '888';
		$screenname->username = $code888;
		$screenname->Save();
		
		$screenname = new Screenname;
		$screenname->user_id = $user_id;
		$screenname->screen_name = 'Party Poker';
		$screenname->username = $partypoker;
		$screenname->Save();
		
		$screenname = new Screenname;
		$screenname->user_id = $user_id;
		$screenname->screen_name = 'Titan Poker';
		$screenname->username = $titanpoker;
		$screenname->Save();
		
		$user = Auth::user();
		   	return Redirect::to('users/settings')
				->with('selectedUser', $user);
		
	}
	
	public function postEditscreenname()
	{
		$screenname = Screenname::find(Input::get('screennameid'));
		$screenname->username = Input::get('screenname');
		$screenname->Save();

		$user = Auth::user();
		   	return Redirect::to('users/settings')
				->with('selectedUser', $user);
	}

	public function postRemovescreenname()
	{
		$screenname = Screenname::find(Input::get('screennameid'));
		$screenname->username = '';
		$screenname->Save();

		$user = Auth::user();
		   	return Redirect::to('users/settings')
				->with('selectedUser', $user);
	}

	public function postSettingfirstname()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('firstname' => Input::get('firstname')));
		
		$user = Auth::user();
		   	return Redirect::to('users/settings')
				->with('selectedUser', $user);	
	}
	public function postSettinglastname()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('lastname' => Input::get('lastname')));			
				
		$user = Auth::user();
		   	return Redirect::to('users/settings')
				->with('selectedUser', $user);
	}
	
	public function postSettingIsPublic()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('ispublic' => Input::get('ispublic')));			
				
		$user = Auth::user();
		   	return Redirect::to('users/settings')
				->with('selectedUser', $user);
	}
	
	public function postSettingemail()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('email' => Input::get('email')));
		
		$user = Auth::user();
		   	return Redirect::to('users/settings')
				->with('selectedUser', $user);		
	}
	
	public function postSettingPublic()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('ispublic' => Input::get('ispublic')));			
				
		$user = Auth::user();
		   	return Redirect::to('users/settings')
				->with('selectedUser', $user);
	}
	
	//End Settings

	public function getProfile() {

    	if (Auth::check())
		{
			$user = Auth::user();
		   	return View::make('pages.users.profile')
				->with('selectedUser', $user);
		}else
		{
			return Redirect::to('login')->with('message', 'Please log-in!');
		}

	}

	/**
	 * Login user with facebook
	 *
	 * @return void
	 */

	public function loginWithFacebook() {

	    // get data from input
	    $code = Input::get( 'code' );

	    // get fb service
	    $fb = OAuth::consumer( 'Facebook' );

	    // check if code is valid

	    // if code is provided get user data and sign in
	    if ( !empty( $code ) ) {

	        // This was a callback request from facebook, get the token
	        $token = $fb->requestAccessToken( $code );

	        // Send a request with it
	        $result = json_decode( $fb->request( '/me' ), true );

	        $facebook_email = $result['email'];

	       	$userx = DB::table('users')->where('facebook_email', $facebook_email)->first();

	       	if($userx)
	       	{
		       	$user = User::find($userx->id);

		       	if($user->active){

					Auth::login($user);
					
					if (Auth::check())
		            {
		            	//return Redirect::to('users/profile')
						return Redirect::to('circle/'.$user->id)
							->with('selectedUser', $user);
			  		} else {
					    return Redirect::to('login')
					        ->with('message', 'Your facebook account is not connected to this site!')
					        ->withInput();
					}   
				}else{
					 return Redirect::to('login')
					        ->with('message', 'Your account is not active yet!')
					        ->withInput();
				}
			}else
			{
				$user = new User;
			    $user->firstname = $result['first_name'];
			    $user->lastname = $result['last_name'];
			    $user->displayname = $result['last_name'];
			    $user->email = $result['email'];
			    $user->facebook_email = $result['email'];
			    $user->facebook_id = $result['id'];
			    $user->active = 1;
			    $user->save();

			    Auth::login($user);
					
				if (Auth::check())
		        {
		        	return Redirect::to('users/profile')
						->with('selectedUser', $user);

		        	//return Redirect::to('users/dashboard')->with('message', 'You are now logged in!');
		        }else{
					 return Redirect::to('login')
					        ->with('message', 'Login failed! please try again.')
					        ->withInput();
				}

			}

	      

	    }
	    // if not ask for permission first
	    else {
	        // get fb authorization
	        $url = $fb->getAuthorizationUri();

	        // return to facebook login url
	         return Redirect::to( (string)$url );
	    }
	}


	public function signupWithFacebook() {

	    // get data from input
	    $code = Input::get( 'code' );

	    // get fb service
	    $fb = OAuth::consumer( 'Facebook' );

	    // check if code is valid

	    // if code is provided get user data and sign in
	    if ( !empty( $code ) ) {

	        // This was a callback request from facebook, get the token
	        $token = $fb->requestAccessToken( $code );

	        // Send a request with it
	        $result = json_decode( $fb->request( '/me' ), true );
         		
	        //Var_dump
	        //display whole array().
	        //dd($result);

	        $arrayUser = array('firstname' => $result['first_name'],'lastname' => $result['last_name'],'displayname' => $result['last_name'],'email' => $result['email'], );
	      	
	      	return View::make( 'pages.users.signupfb', $arrayUser);
	        // return View::make( 'pages.users.signupfb', )
						   //      ->with( 'firstname', $result['first_name'] )
						   //      ->with( 'lastname', $result['last_name'] )
						   //      ->with( 'email', $result['email'] );
	    }
	    // if not ask for permission first
	    else {
	        // get fb authorization
	        $url = $fb->getAuthorizationUri();

	        // return to facebook login url
	         return Redirect::to( (string)$url );
	    }
	}

	public function getUser($id)
	{
		$user = User::find($id);
		if(!is_null($user))
		{
			$friendRequest = null;
			$sentFriendRequest = null;
			if(Auth::check())
			{
				$friendRequest = Friendrequest::where('user_id',$user->id)
												->where('target_id', Auth::user()->id)
												->first();
				$sentFriendRequest = Friendrequest::where('user_id',Auth::user()->id)
												->where('target_id', $user->id)
												->first();
			}
			return View::make('pages.users.profile')
				->with('selectedUser', $user)
				->with('friendRequest', $friendRequest)
				->with('sentFriendRequest', $sentFriendRequest);
		}
		else
		{
			Redirect::to('/');
		}

	}

	public function getUserForPastPackage($id)
	{
		$user = User::find($id);
		if(!is_null($user))
		{
			$friendRequest = null;
			$sentFriendRequest = null;
			if(Auth::check())
			{
				$friendRequest = Friendrequest::where('user_id',$user->id)
												->where('target_id', Auth::user()->id)
												->first();
				$sentFriendRequest = Friendrequest::where('user_id',Auth::user()->id)
												->where('target_id', $user->id)
												->first();
			}
			return View::make('pages.users.pastpackages')
				->with('selectedUser', $user)
				->with('friendRequest', $friendRequest)
				->with('sentFriendRequest', $sentFriendRequest);
		}
		else
		{
			Redirect::to('/');
		}

	}

	public function getUserForAboutMe($id)
	{
		$user = User::find($id);
		if(!is_null($user))
		{
			$friendRequest = null;
			$sentFriendRequest = null;
			if(Auth::check())
			{
				$friendRequest = Friendrequest::where('user_id',$user->id)
												->where('target_id', Auth::user()->id)
												->first();
				$sentFriendRequest = Friendrequest::where('user_id',Auth::user()->id)
												->where('target_id', $user->id)
												->first();
			}
			return View::make('pages.users.aboutme')
				->with('selectedUser', $user)
				->with('friendRequest', $friendRequest)
				->with('sentFriendRequest', $sentFriendRequest);
		}
		else
		{
			Redirect::to('/');
		}

	}

	public function getUserForSharscope($id)
	{
		$user = User::find($id);
		if(!is_null($user))
		{
			$friendRequest = null;
			$sentFriendRequest = null;
			if(Auth::check())
			{
				$friendRequest = Friendrequest::where('user_id',$user->id)
												->where('target_id', Auth::user()->id)
												->first();
				$sentFriendRequest = Friendrequest::where('user_id',Auth::user()->id)
												->where('target_id', $user->id)
												->first();
			}
			// //----SHARKSCOPE CODE START----//
			// $encoded1 = hash('md5', 'susu1234');
   //          $license = 'lekrj5n23nsx8an4kl';
   //          $encoded2 = strtolower($encoded1) . strtolower($license);
            
   //          $digest = hash('md5', $encoded2);
            
   //          $sharkuser = "sildeyna@gmail.com";

   //          // jSON URL which should be requested
   //          $json_url = 'http://www.sharkscope.com/api/bma/networks/PokerStars/players/SkaiWalkurrr?Username='.$sharkuser.'&Password='.$digest;
   //          $username = $sharkuser;  // authentication
   //          $password = $digest;  // authentication

   //          // jSON String for request

   //          // Initializing curl
   //          $ch = curl_init( $json_url );

   //          // Configuring curl options
   //          $options = array(
   //          CURLOPT_RETURNTRANSFER => true,
            
   //          CURLOPT_HTTPHEADER => array('Content-type: application/json', 'Accept: application/json', 'Username: '.$sharkuser, 'Password: '.$digest, 'User-Agent: Mozilla')

   //          );

   //          // Setting curl options
   //          curl_setopt_array( $ch, $options );
            
   //          // Getting results
   //          $result = curl_exec($ch); // Getting jSON result string
   //          if($result === FALSE){
   //              //echo curl_error($ch).'aaa';
   //          }
   //          $responseData = json_decode($result, TRUE);
            //----SHARKSCOPE CODE END----//
            
          //   $responseArray = $responseData["Response"];
				$data = array();
          //   $data["success"] = $responseArray["@success"];
          //   $data["timestamp"] = $responseArray["@timestamp"];
          //   $data["remainingsearch"] = $responseArray["UserInfo"]["Subscriptions"]["@totalSearchesRemaining"];

          //   $playerArray = $responseArray["PlayerResponse"]["PlayerView"]["Player"];

          //   $playerData["network"] = $playerArray["@network"];
          //   $playerData["name"] = $playerArray["@name"];
          //   $playerData["lastActivity"] = $playerArray["@lastActivity"];
          //   $statisticsArray = array();
          //   foreach ($playerArray["Statistics"]["Statistic"] as $row) {
          //   	$array = array_values($row);
        		// $col = $array[0];
        		// $val = $array[1];
        		// $statisticsArray[$col] = $val;
          //   }

          //   $playerData["statistics"] = $playerArray["Statistics"];
          //   $playerData["RecentTournaments"]= $playerArray["RecentTournaments"];
          //   $data["player"] = $playerData;
          //   $data["statistics"] = $statisticsArray;
          //   $statisticalDataSet = array();
          //   $dataSetArray = array();
          //   $dataYArray = array();
		    // foreach($playerArray["Statistics"]["StatisticalDataSet"] as $firstRow)
		    // {
		    // 	$dataSetName = $firstRow["@id"];

      //           foreach($firstRow["Data"] as $secondRow)
      //           {
      //               $dataX = $secondRow["@x"];
      //               foreach($secondRow["Y"] as $thirdRow)
      //               {
      //                   $dataYName = $thirdRow["@id"];
      //                   $dataYValue = $thirdRow["$"];
      //                   $dataYArray[$dataYName] = $dataYValue;
      //               }

      //               $dataSetArray[$dataX] = $dataYArray;
      //               $dataYArray = array();
      //       	}
      //       	$statisticalDataSet[$dataSetName] = $dataSetArray;
      //       	$dataSetArray = array();
		    // }
		    // $data["statisticalDataSet"] = $statisticalDataSet;

		   //  foreach($playerArray["Statistics"]["Timeline"]["Event"] as $event)
		   //  {
     //            $EventData = array();
     //            $EventData["Category"] = $event["@category"];
     //            $EventData["Name"] = '';
     //            $EventData["Description"] = '';
     //            $EventData["Points"] = '';
     //            $EventData["Code"] = '';

     //            $EventData["Entrant"] = '';
     //            $EventData["Prize"] = '';
     //            $EventData["Position"] = '';
     //            $EventData["GameID"] = '';
     //            $EventData["Currency"] = '';

     //            if($event["@category"] == "Achievement")
     //            {
					// $EventData["Name"] = $event["@name"];
	    //             $EventData["Description"] = $event["@description"];
	    //             $event["@points"]
	    //             $event["@code"]
	                
     //            }
     //            else
     //            {
     //            	<br/>
		   //          Entrant : {{$event["@entrants"]}}
		   //          <br/>
		   //          Prize : {{$event["@prize"]}}
		   //          <br/>
		   //          Position : {{$event["@position"]}}
		   //          <br/>
		   //          GameID : {{$event["@gameID"]}}
		   //          <br/>
		   //          Currency : {{$event["@currency"]}}
		   //          <br/>
     //            }
                
                
     //            URL : {{$event["@url"]}}
     //            <br/>
     //            Date : {{$event["@date"]}}
     //            <br/>
     //            Player : {{$event["@player"]}}
     //            <br/>
     //            Network : {{$event["@network"]}}
     //            <br/>
     //            Priority : {{$event["@priority"]}}
     //            <br/>
     //            isNegative : {{$event["@isNegative"]}}
     //            <br/>
     //            id : {{$event["@id"]}}
     //            <br/>
     //        }

			return View::make('pages.users.sharkscope')
				->with('selectedUser', $user)
				->with('friendRequest', $friendRequest)
				->with('sentFriendRequest', $sentFriendRequest)
				->with("sharkscopedata", $data);
		}
		else
		{
			Redirect::to('/');
		}

	}

	
}
