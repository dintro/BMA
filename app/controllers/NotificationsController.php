<?php

/**
* 
*/
class NotificationsController extends BaseController
{

	public function viewNotifications()
	{
		return View::make('pages.users.notifications');

	}

	public function readNotifications()
	{
		$unreadNotification = Auth::user()->notifications()->where('status', 'unread')
															->where('type', Input::get('type'))
															->get();
		foreach ($unreadNotification as $notification) {
			$notification->status = 'read';
			$notification->save();
		}

		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}

	public function readAllNotifications()
	{
		$unreadNotification = Auth::user()->notifications()->where('status', 'unread')
															->get();
		foreach ($unreadNotification as $notification) {
			$notification->status = 'read';
			$notification->save();
		}

		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}

	public function readNotification()
	{
		$unreadNotification = Notification::find(Input::get('query'));
		$unreadNotification->status = 'read';
		$unreadNotification->save();

		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}

	public function refreshNotification()
	{		    
		if(Auth::user()->notifications()->count() != 0)
		{
			$notifications = Auth::user()->notifications()->orderBy('created_at', 'DESC')->take(5)->get();

		    $notificationArray = array_fill(0, $notifications->count(), null);
		    $unreadNotificationCount = Auth::user()->notifications()->where('status','unread')->count();
		    $i = 0;
		    foreach ($notifications as $notification) {
		        $notificationArray[$i] = $arrayName = array('id' => $notification->id,
		                                                'content' => $notification->content,
		                                                'url' => $notification->url,
		                                                'status' => $notification->status,
		                                                'image' => url('/img/notif-photo.jpg'),
		                                                'type' => $notification->type,
		                                                'updated'=> $notification->created_at);
		        $i++;
		    }

		    $response = array(
		            'notifications' => $notificationArray,
		            'unreadNotificationCount' => $unreadNotificationCount
		    );
		}
		else
		{
			$arr = array();
			$response = array(
		            'notifications' => $arr,
		            'unreadNotificationCount' => 0
		    );
		}
	    
	    return Response::json( $response );
	}
}