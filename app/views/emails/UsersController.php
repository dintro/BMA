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
	
	public function forgotPassword()
	{
		$email = Input::get('email');	
		$user =	DB::table('users')->where('email', $email)->first();
		
		if($user)
		{
			$token = str_random(40);
			
			if(!$user->email_verified_token) 
			{
				$token = $user->email_verified_token;
			}
			
			$useremail = $user->email;
			$firtsname = $user->firstname;
			$lastname = $user->lastname;
			
			Mail::send('emails.forgot', array('firstname'=>$firstname,'token'=>$token), function($message){
		        $message->to($useremail, $firtsname.' '.$lastname)->subject('Welcome to the Buy My Action!');
		    });
			
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
		    return Redirect::to('users/profile')->with('message', 'You are now logged in!');
		} else {
		    return Redirect::to('login')
		        ->with('message', 'Your username/password combination was incorrect')
		        ->withInput();
		}    
	}

	public function getDashboard() {
    	$this->layout->content = View::make('pages.users.dashboard');
	}

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
		            	return Redirect::to('users/profile')
							->with('selectedUser', $user);
			        	//if (Auth::login($user)) {
					    //return Redirect::to('users/dashboard')->with('message', 'You are now logged in!');
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

			    // return Redirect::to('login')
					  //       ->with('message', 'Thanks for registering! Please login with your facebook account.')
					  //       ->withInput();

				//$arrayUser = array('firstname' => $result['first_name'],'lastname' => $result['last_name'],'displayname' => $result['last_name'],'email' => $result['email'], );
	      		//return View::make( 'pages.users.signupfb', $arrayUser);
			}

	        //$message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'] .' | '. $result['email'];
	        //echo $message. "<br/>";

	        //Var_dump
	        //display whole array().
	        //dd($result);

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

	public function getUser($displayname)
	{
		$user = User::where('displayname', $displayname)->get();
		if(!$user->isEmpty())
		{
			return View::make('pages.users.profile')
				->with('selectedUser', $user->first());
		}
		else
		{
			Redirect::to('/');
		}

	}

	
}
