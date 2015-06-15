<?php

/**
* 
*/
class ConversationsController extends BaseController
{
	public function index()
	{
		if(Auth::check())
		{
			
			
			if(Auth::user()->hasInbox())
			{
				$inbox = Auth::user()->inbox();
				$conversation = $inbox->orderBy('updated_at', 'DESC')->get()->first();
				$myArray = explode('-', $conversation->name);
				$otheruserid = ($myArray[0] == Auth::user()->id ? $myArray[1] : $myArray[0]);
				return Redirect::to('/message/'.$otheruserid);
			}
			else
			{
				return View::make('pages.messages.index')
				->with('inbox', null)
				->with('hasInbox', '0')
				->with('selectedConversation', null);	
			}
		}
		else
		{
			return Redirect::to('/');
		}
	}

	public function view($id)
	{
		if(Auth::check())
		{
			//$users = User::find($id);
			
		
			$user = User::find($id);

			$possible1 = $user->id.'-'.Auth::user()->id;
			$possible2 = Auth::user()->id.'-'.$user->id;
			
			$inbox = Auth::user()->inbox();
			$selectedConversation = $inbox->where('name', $possible1)
									->orWhere('name', $possible2)
									->first();

			foreach ($selectedConversation->unreadMessages() as $unread) {
				if($unread->user_id != Auth::user()->id)
				{
					$unread->status = 'read';
					$unread->save();
				}
			}
			
			return View::make('pages.messages.index')
			->with('inbox', Auth::user()->inbox()->orderBy('updated_at', 'DESC')->get())
			->with('hasInbox', '1')
			->with('selectedConversation', $selectedConversation);

			
			
		}
		else
		{
			return Redirect::to('/');
		}
		
	}

	public function compose($id = null)
	{
		if(Auth::check())
		{
			$inbox = Auth::user()->inbox();
			$selectedUser = null;
			if($id != null)
			{
				$selectedUser = User::find($id);
				$possible1 = $selectedUser->id.'-'.Auth::user()->id;
				$possible2 = Auth::user()->id.'-'.$selectedUser->id;
					
				$count = Conversation::where('name', $possible1)
										->orWhere('name', $possible2)->count();
				if($count != 0)
				{
					return Redirect::to('/message/'.$selectedUser->id);
				}
			}
			return View::make('pages.messages.compose')
			->with('inbox', $inbox->orderBy('updated_at', 'DESC')->get())
			->with('selectedUser', $selectedUser);	
			
		}
		else
		{
			return Redirect::to('/');
		}

		
	}

	public function send()
	{
		
		// $recipient = strtolower(Input::get('recipientid'));
		// $users = User::where('displayname', 'LIKE', "%$recipient%")->get();

		// if($users->count() > 0)
		// {
			$user = User::find(Input::get('recipientid'));//$users->first();
			
			$possible1 = $user->id.'-'.Auth::user()->id;
			$possible2 = Auth::user()->id.'-'.$user->id;

			$alreadyExist = Auth::user()->inbox()->where('name', $possible1)
							->orWhere('name', $possible2)
							->get();

			$conversation = new Conversation;
			if($alreadyExist->count() > 0)
			{
				$conversation = $alreadyExist->first();
				$conversation->touch();
			}
			else
			{
				$conversation->name = $user->id.'-'.Auth::user()->id;
				$conversation->save();	
				
				Auth::user()->inbox()->save($conversation);
				$user->inbox()->save($conversation);
			}
			
			$message = new Message;
			$message->content = htmlentities(Input::get('content'), ENT_QUOTES, 'UTF-8', false);//Input::get('content');//preg_replace("/[^A-Za-z0-9?!$\/_|+ .-]/", " ", Input::get('content'));
			$message->user_id = Auth::user()->id;
			$message->recipient_id = $user->id;
			$message->conversation_id = $conversation->id;
			$message->status = 'unread';
			$message->save();

			return Redirect::to('/message/'.$user->id);

		    // $response = array(
	     //        'success' => 'success',
    		// );
 
   		 	//return Response::json( $response );
			//return Redirect::route('messages');
		// }
		
	}

	public function reply()
	{
		$conversation = Conversation::find(Input::get('conversationid'));

		$conversation->touch();

	    foreach ($conversation->unreadMessages() as $unread) {
	        if($unread->user_id != Auth::user()->id)
	        {
	            $unread->status = 'read';
	            $unread->save();
	        }
	    }

		$message = new Message;
		$message->content = htmlentities(Input::get('content'), ENT_QUOTES, 'UTF-8', false);//Input::get('content');//preg_replace("/[^A-Za-z0-9?!$\/_|+ .-;]/", " ", Input::get('content'));
		$message->user_id = Auth::user()->id;
		$message->recipient_id = $conversation->otheruser(Auth::user()->id)->getId();
		$message->conversation_id = $conversation->id;
		$message->status = 'unread';
		$message->save();

		$response = array(
	            'success' => 'success',
		);
	}

}