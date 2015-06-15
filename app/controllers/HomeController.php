<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function sendContactMail()
	{
		$validator = Validator::make(Input::all(), array(
                "name"                  	=> "required",
                "subject"                  	=> "required",
                "message"     				=> "required",
                "email"              		=> "required|email",
                "recaptcha_response_field' 	=> 'required|recaptcha"
                
            ));
		if ($validator->passes()) 
		{
			$data = array(
							'name'=>Input::get('name'),
							'subject'=>Input::get('subject'),
							'content'=>Input::get('message'),
							'email'=>Input::get('email')
						);

			Mail::send('emails.contact', $data , function($message) {
			    $message->to('gamer_strezz@yahoo.com', Input::get('name'))->subject('BMA : ' .Input::get('subject'));
			});
			return Redirect::to('contactus')
					->with('message', 'Message Sent!');
		}
		else
		{
			return Redirect::to('contactus')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();
		}
	}

	public function showHome()
	{
		$paging = Input::get('display') == null ? 20 : Input::get('display');
		$query = preg_replace("/[^A-Za-z0-9]/", " ", Input::get('query'));
		if($query == null)
		{			
			$users = User::GetUserHasActivePackage()->paginate($paging);
			if(Auth::check())
			{
				$friendspackage = Auth::user()->GetFriendsHasActivePackage()->paginate($paging);
			}
			else
			{
				$friendspackage = null;
			}
			
		}
		else
		{
			// $users = User::has('packages')->where('firstname', 'LIKE', '%'.$query.'%')
			// 	->orWhere('lastname', 'LIKE', '%'.$query.'%')
			// 	->orWhereHas('packages', function($q) use ($query) 
			// 	{
			// 		$q->where('packages.title', 'LIKE', '%'.$query.'%');
			// 	})
			if(Auth::check())
			{
				$friendspackage = Auth::user()->GetFriendsHasActivePackage()->where(function($q) use ($query)
				{
					$q->where('firstname', 'LIKE', '%'.$query.'%')
					->orWhere('lastname', 'LIKE', '%'.$query.'%')
					->orWhereHas('packages', function($q2) use ($query) 
					{
						$q2->where('ended', '>=', date("Y-m-d H:i:s"))
						->where('cancel','0')
						->where(function($q3) use ($query)
						{
							$q3->where('title', 'LIKE', '%'.$query.'%')
							->orWhereHas('turnaments', function($q4) use ($query)
							{
								$q4->where('turnaments.name', 'LIKE', '%'.$query.'%');
							});
						});
							
					});
				})
	            ->paginate($paging);
			}
			else
			{
				$friendspackage = null;
			}

			$users = User::GetUserHasActivePackage()->where(function($q) use ($query)
			{
				$q->where('firstname', 'LIKE', '%'.$query.'%')
				->orWhere('lastname', 'LIKE', '%'.$query.'%');
			})
			->orWhereHas('packages', function($q) use ($query) 
			{
				$q->where('ended', '>=', date("Y-m-d H:i:s"))
				->where('cancel','0')
				->where(function($q) use ($query)
				{
					$q->where('title', 'LIKE', '%'.$query.'%')
					->orWhereHas('turnaments', function($q2) use ($query)
					{
						$q2->where('turnaments.name', 'LIKE', '%'.$query.'%');
					});
				});
					
			})
            ->paginate($paging);
			
			
			//$users = User::has('packages')->where()paginate($paging);
		}
		return View::make('pages.home')->with('users', $users)->with('display', $paging)->with('query', $query)->with('friendspackage', $friendspackage);
	}

}
