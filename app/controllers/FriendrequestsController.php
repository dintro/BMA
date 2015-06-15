<?php

/**
* 
*/
class FriendrequestsController extends BaseController
{
	public function send($id)
	{
		$friendrequest = new Friendrequest;
		$friendrequest->user_id = Auth::user()->id;
		$friendrequest->target_id = $id;
		$friendrequest->status = 'waiting';
		$friendrequest->save();

		$notification = new Notification;
		$notification->user_id = Auth::user()->id;
		$notification->recipient_id = $id;
		$notification->content = 'You have circle request from '.Auth::user()->getFullname();
		$notification->type = 'Circle Request';
		$notification->status = 'unread';
		$notification->url = url('/mycircle');
		$notification->save();

		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}

	public function accept()
	{
		$friendrequest = Friendrequest::find(Input::get('query'));
		$friendrequest->status = 'accepted';
		$friendrequest->save();

		$friend = new Friend;
		$friend->user_id = $friendrequest->user_id;
		$friend->friend_id = $friendrequest->target_id;
		$friend->save();

		$friend2 = new Friend;
		$friend2->user_id = $friendrequest->target_id;
		$friend2->friend_id = $friendrequest->user_id;
		$friend2->save();

		$notification = new Notification;
		$notification->user_id = $friendrequest->target_id;
		$notification->recipient_id = $friendrequest->user_id;
		$notification->content = $friendrequest->targetInfo->getFullname().' accepted your circle request';
		$notification->type = 'Circle Request Accepted';
		$notification->status = 'unread';
		$notification->url = url('/mycircle?circle-request');
		$notification->save();

		$friendrequest->delete();

		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}



	public function reject()
	{
		$friendrequest = Friendrequest::find(Input::get('query'));
		$friendrequest->delete();

		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}	
}
