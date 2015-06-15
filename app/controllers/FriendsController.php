<?php

/**
* 
*/
class FriendsController extends BaseController
{
	public function index($id = null)
	{
		if($id == null)
		{
			if(Auth::check())
			{
				$selectedUser = Auth::user();
			}
			else
			{
				return Redirect::to('/');
			}
			
		}
		else
		{
			$selectedUser = User::find($id);
			if($selectedUser == null)
			{
				return Redirect::to('/');
			}
		}
		return View::make('pages.users.circle')
					->with('selectedUser', $selectedUser);
	}

	public function refreshFriendList()
	{
		$friends = Auth::user()->friends()->get();

        $friendArray = array_fill(0, $friends->count(), null);
        $i = 0;
        foreach ($friends as $friend) {
            $friendInfo = $friend->friendInfo;
            $friendArray[$i] = $arrayName = array('id' => $friend->id,
                                                    'profileUrl' => url('/profile/' . $friendInfo->id),
                                                    'messageUrl' => $friend->messageUrl(),
                                                    'fullname' => $friendInfo->getFullname(),
                                                    'image' => url($friendInfo->getPhotoUrl()));
            $i++;
        }

        $response = array(
                'friends' => $friendArray
        );
        return Response::json( $response );
	}
}
