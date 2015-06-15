<?php
class PackageCommentsController extends BaseController {

	public function getComments()
	{
		$package = Package::find(Input::get('packageid'));

		$comments = $package->comments();
		if($comments->count() != 0)
		{
			$commentArray = array_fill(0, $comments->count(), null);
			$i = 0;
			foreach ($comments->get() as $comment) {
				$user = $comment->user;
	            $commentArray[$i] = array('id' => $comment->id,
	                                        'comment' => $comment->comments,
	                                        'packageid' => $package->id,
	                                        'url' => url('/profile/'.$user->id),
	                                        'fullname' => $user->getFullname(),
	                                        'image' => $user->getPhotoUrl(),
	                                        'created'=> $comment->created_at);
	            $i++;
			}

			$response = array(
       	 		'success' => 'success',
            	'comments' => $commentArray
	        );
	 
	    	return Response::json( $response );
		}
		else
		{
			$arr = array();

			$response = array(
       	 		'success' => 'empty',
            	'comments' => $arr
	        );
	 
	    	return Response::json( $response );
		}
	}

	public function sendComments()
	{
		$comment = new PackageComment;

		$comment->comments = htmlentities(Input::get('comment'), ENT_QUOTES, 'UTF-8', false);
		$comment->user_id = Input::get('userid');
		$comment->package_id = Input::get('packageid');
		$comment->save();

		$package = Package::find(Input::get('packageid'));
		$comments = $package->comments();
		$commentArray = array_fill(0, $comments->count(), null);
		$i = 0;
		foreach ($comments->get() as $comment) {
			$user = $comment->user;
            $commentArray[$i] = array('id' => $comment->id,
                                        'comment' => $comment->comments,
                                        'packageid' => $package->id,
                                        'url' => url('/profile/'.$user->id),
                                        'fullname' => $user->getFullname(),
                                        'image' => $user->getPhotoUrl(),
                                        'created'=> $comment->created_at);
            $i++;
		}

		$filteredComments = $package->comments()->groupBy('user_id')->get();;
		$senderid = Input::get('userid');
		$sender = User::find($senderid);
		$owner = $package->user;
		foreach ($filteredComments as $filtered) {
			$user = $filtered->user;
			if($senderid != $user->id && $user->id != $owner->id)
			{
				$notification = new Notification;
				$notification->user_id = $senderid;
				$notification->recipient_id = $user->id;
				// if($user->id == $owner->id)
				// {
				// 	$notification->content = $sender->getFullname().' post comment on your package';	
				// }

				// else
				// {
					$notification->content = $sender->getFullname().' post comment on '. $owner->getFullname().'\'s package';	
				//}
				$notification->type = 'Package Comments';
				$notification->status = 'unread';
				if($package->ended >= date("Y-m-d H:i:s"))
				{
					$notification->url = url('/profile/'.$owner->id);	
				}
				else
				{
					$notification->url = url('/pastpackages/'.$owner->id);		
				}
				
				$notification->save();
			}
		}

		if($senderid != $owner->id)
		{
			$notification = new Notification;
			$notification->user_id = $senderid;
			$notification->recipient_id = $owner->id;
			$notification->content = $sender->getFullname().' post comment on your package';	
			$notification->type = 'Package Comments';
			$notification->status = 'unread';
			if($package->ended >= date("Y-m-d H:i:s"))
			{
				$notification->url = url('/profile/'.$owner->id);	
			}
			else
			{
				$notification->url = url('/pastpackages/'.$owner->id);		
			}
			
			$notification->save();
		}

		$response = array(
   	 		'success' => 'success',
        	'comments' => $commentArray,
        	'count' => $comments->count(),
        	'debug' => $senderid.'-'.$user->id

        );
 
    	return Response::json( $response );

		
	}

}