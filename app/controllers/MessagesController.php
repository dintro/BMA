<?php

/**
* 
*/
class MessagesController extends BaseController
{
	// public function index()
	// {
	// 	return View::make('pages.messages.index')
	// 		->with('inbox', Auth::user()->inbox()->orderBy('created_at', 'DESC')->get());
			
	// }

	// public function view($id)
	// {
	// 	$viewMessage = Message::find($id);
	// 	if($viewMessage->status == 'unread')
	// 	{
	// 		Message::find($id)->update(array(
	// 			'status' => 'read'
	// 		));
	// 	}
	// 	return View::make('pages.messages.view')
	// 		->with('title', 'View Message')
	// 		->with('viewMessage', Message::find($id));
	// }

	// public function compose()
	// {
	// 	return View::make('pages.messages.compose');
	// }

	// public function send()
	// {
	// 	$validation = Message::validate(Input::all());

	// 	if($validation->fails())
	// 	{
	// 		return Redirect::route('composeMessage')
	// 			->withErrors($validation)
	// 			->withInput();
	// 	}
	// 	else
	// 	{
	// 		$recipient = Input::get('recipient');
	// 		$users = User::where('displayname', 'LIKE', "%$recipient%")->get();
	// 		if($users->count() > 0)
	// 		{
	// 			$user = $users->first();

	// 			$message = new Message;
	// 			$message->title = Input::get('title');
	// 			$message->content = Input::get('content');
	// 			$message->sender_id = Auth::user()->id;
	// 			$message->recipient_id = $user->id;
	// 			$message->status = 'unread';
	// 			$message->save();

	// 			return Redirect::route('messages');
	// 		}
	// 		else 
	// 		{
	// 			return Redirect::route('composeMessage');
	// 		}
	// 	}
	// }

	// public function destroy()
	// {
	// 	Message::find(Input::get('id'))->delete();

	// 	return Redirect::route('messages')
	// 			->with('message','Message deleted successfully');
	// }
}