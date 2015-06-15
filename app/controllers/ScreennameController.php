<?php
class ScreennameController extends BaseController {

	public function getScreenname()
	{
		
	}

	public function addScreenname(){

		$screennametype  = Input::get('screennametype');
		$username  = Input::get('username');

		$screenname = new Screenname;
		$screenname->user_id = Auth::user()->id;
		$screenname->screen_name = $screennametype;
		$screenname->username = $username;
		$screenname->Save();

		$response = array(
            'success' => 'success',
            'screennameid' => $screenname->id.''
        );

        return Response::json( $response );
	}

	public function editFirstname()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('firstname' => Input::get('firstname')));
		
		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}
	public function editLastname()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('lastname' => Input::get('lastname')));			
				
		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}
	public function editIsPublic()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('ispublic' => Input::get('ispublic')));			
				
		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}
	public function editEmail()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('email' => Input::get('email')));
		
		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}

	public function editCountry()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('country' => Input::get('country')));
		
		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}

	public function editPaypal()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('paypal_account' => Input::get('paypal')));
	
		$response = array(
            'success' => 'success'
        );

        return Response::json( $response );
	}	

	public function editSkrill()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('skrill_account' => Input::get('skrill')));
	
		$response = array(
            'success' => 'success'
        );

        return Response::json( $response );
	}

	public function editNeteller()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('neteller_account' => Input::get('neteller')));
	
		$response = array(
            'success' => 'success'
        );

        return Response::json( $response );
	}

	public function editCash()
	{
		if(Input::get('enable') == 'true')
		{
			DB::table('users')
	            ->where('id', Input::get('user_id'))
	            ->update(array('cash' => Input::get('cash'), 'cash_enabled' => 1));
		
			$response = array(
	            'success' => 'success',
	            'debug' => Input::get('enable')
	        );
		}
		else
		{
			DB::table('users')
	            ->where('id', Input::get('user_id'))
	            ->update(array('cash' => '', 'cash_enabled' => 0));
		
			$response = array(
	            'success' => 'success'
	        );
		}

		return Response::json( $response );
	}

	public function editBankWire()
	{
		if(Input::get('enable') == 'true')
		{
			DB::table('users')
	            ->where('id', Input::get('user_id'))
	            ->update(array('bank_wire' => Input::get('bankwire'), 'bank_wire_enabled' => 1));
		
			$response = array(
	            'success' => 'success'
	        );
		}
		else
		{
			DB::table('users')
	            ->where('id', Input::get('user_id'))
	            ->update(array('bank_wire' => '', 'bank_wire_enabled' => 0));
		
			$response = array(
	            'success' => 'success'
	        );
		}

		return Response::json( $response );
	}

	public function editPaymentMethod()
	{
		$type = Input::get('type');
		if($type == 'paypal')
		{
			DB::table('users')
	            ->where('id', Input::get('user_id'))
	            ->update(array('paypal_account' => Input::get('newvalue')));
		
			$response = array(
	            'success' => 'success'
	        );
		}
		else if($type == 'skrill')
		{
			DB::table('users')
	            ->where('id', Input::get('user_id'))
	            ->update(array('skrill_account' => Input::get('newvalue')));
		
			$response = array(
	            'success' => 'success'
	        );
		}
		else if($type == 'neteller')
		{
			DB::table('users')
	            ->where('id', Input::get('user_id'))
	            ->update(array('neteller_account' => Input::get('newvalue')));
		
			$response = array(
	            'success' => 'success'
	        );
		}
		else if($type == 'cash')
		{

			if(Input::get('enabled') == 1)
			{
				DB::table('users')
		            ->where('id', Input::get('user_id'))
		            ->update(array('cash' => Input::get('newvalue'), 'cash_enabled' => 1));
			
				$response = array(
		            'success' => 'success'
		        );
			}
			else
			{
				DB::table('users')
		            ->where('id', Input::get('user_id'))
		            ->update(array('cash' => '', 'cash_enabled' => 0));
			
				$response = array(
		            'success' => 'success'
		        );
			}
			
		}
		else if($type == 'bankwire')
		{
			if(Input::get('enabled') == 1)
			{
				DB::table('users')
		            ->where('id', Input::get('user_id'))
		            ->update(array('bank_wire' => Input::get('newvalue'), 'bank_wire_enabled' => 1));
			
				$response = array(
		            'success' => 'success'
		        );
			}
			else
			{
				DB::table('users')
		            ->where('id', Input::get('user_id'))
		            ->update(array('bank_wire' => '', 'bank_wire_enabled' => 0));
			
				$response = array(
		            'success' => 'success'
		        );
			}
		}
		else
		{
			$response = array(
		            'success' => 'failed'
		        );
		}
		
 
    	return Response::json( $response );
	}

	public function editAboutMe()
	{
		DB::table('users')
            ->where('id', Input::get('user_id'))
            ->update(array('about_me' => Input::get('about_me')));
		
		$response = array(
            'success' => 'success',
            'about_me' => Input::get('about_me')
        );
 
    	return Response::json( $response );
	}

	public function editPhoto()
	{
		$rules = array(
             'photo' => 'max:1000',
        );
        $input = Input::all();
        $validation = Validator::make($input, $rules);

        if ($validation->fails())
        {
        	Session::flash('uploadInformation', 'Edit Avatar Failed! '. $validation->messages()->first('photo'));
           
           	return Redirect::to('/users/settings');
        }
        else
        {
        	if (Input::hasFile('photo')) {
        		$id = Input::get('user_id');
        		$user = User::find($id);
        		$oldphotourl = $user->photourl;
			    $file            = Input::file('photo');
			    $mime = $file->getMimeType();
			    $destinationPath = public_path().'/profilepicture/';
			    $filename        = $id . '_' . $file->getClientOriginalName();
			    $uploadSuccess   = $file->move($destinationPath, $filename);
			    $fullname = $destinationPath.$filename;
			    //compress_image($fullname, $fullname, 80);
			    $info = getimagesize($fullname);

				if ($info['mime'] == 'image/jpeg')
				{
					$image = imagecreatefromjpeg($fullname);
					imagejpeg($image, $fullname, 80);
				}
				elseif ($info['mime'] == 'image/gif')
				{
					$image = imagecreatefromgif($fullname);
					imagejpeg($image, $fullname, 80);
				}
				elseif ($info['mime'] == 'image/png')
				{
					$image = imagecreatefrompng($fullname);
					imagejpeg($image, $fullname, 80);
				}
			    $user->photourl = $filename;
			    $user->save();
			    Session::flash('uploadInformation', 'Edit Avatar Success!');
			    if($oldphotourl != null)
			    {
			    	unlink(public_path().'/profilepicture/'.$oldphotourl);	
			    }
			    
			    
			    return Redirect::to('/users/settings');
        	}	
        }
	}

	function compress_image($source_url, $destination_url, $quality) {

		$info = getimagesize($source_url);

		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($source_url);

		elseif ($info['mime'] == 'image/gif')
			$image = imagecreatefromgif($source_url);

		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($source_url);

    	imagejpeg($image, $destination_url, $quality);
		return $destination_url;
	}

	// public function createScreenname(){
		
	// 	$pokerstars  = Input::get('pokerstars');
	// 	$fulltilt  = Input::get('fulltilt');
	// 	$code888 = Input::get('888');
	// 	$partypoker  = Input::get('partypoker');
	// 	$titanpoker = Input::get('titanpoker');
	// 	$user_id = Input::get('user_id');
		
	// 	Screenname::where('user_id', '=', $user_id)->delete();
		
	// 	$screenname = new Screenname;
	// 	$screenname->user_id = $user_id;
	// 	$screenname->screen_name = 'Pokerstars';
	// 	$screenname->username = $pokerstars;
	// 	$screenname->Save();
		
	// 	$screenname = new Screenname;
	// 	$screenname->user_id = $user_id;
	// 	$screenname->screen_name = 'Fulltilt';
	// 	$screenname->username = $fulltilt;
	// 	$screenname->Save();
		
	// 	$screenname = new Screenname;
	// 	$screenname->user_id = $user_id;
	// 	$screenname->screen_name = '888';
	// 	$screenname->username = $code888;
	// 	$screenname->Save();
		
	// 	$screenname = new Screenname;
	// 	$screenname->user_id = $user_id;
	// 	$screenname->screen_name = 'Party Poker';
	// 	$screenname->username = $partypoker;
	// 	$screenname->Save();
		
	// 	$screenname = new Screenname;
	// 	$screenname->user_id = $user_id;
	// 	$screenname->screen_name = 'Titan Poker';
	// 	$screenname->username = $titanpoker;
	// 	$screenname->Save();
		
	// 	$user = Auth::user();
	// 	   	return Redirect::to('users/settings')
	// 			->with('selectedUser', $user);
		
	// }
	
	public function editScreenname()
	{
		$screenname = Screenname::find(Input::get('screennameid'));
		$screenname->username = Input::get('screenname');
		$screenname->Save();

		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}

	public function removeScreenname()
	{
		$screenname = Screenname::find(Input::get('screennameid'));
		$screenname->username = '';
		$screenname->Save();

		$response = array(
            'type' => 'success'
        );
 
    	return Response::json( $response );
	}
}