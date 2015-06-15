<?php

class Friend extends Eloquent  {

	public function friendInfo()
	{
		return $this->belongsTo('User', 'friend_id');
	}

	public function userInfo()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public static function alreadyFriend($user_id, $friend_id)
	{
		return ! is_null(
            DB::table('friends')
              ->where('user_id', $user_id)
              ->where('friend_id', $friend_id)
              ->first()
        );
	}

	public function messageUrl()
	{
		$possible1 = $this->friendInfo->id.'-'.$this->userInfo->id;
		$possible2 = $this->userInfo->id.'-'.$this->friendInfo->id;
			
		$count = Conversation::where('name', $possible1)
								->orWhere('name', $possible2)->count();
		if($count != 0)
		{
			return url('/message/'.$this->friendInfo->id);
		}
		else
		{
			return url('/messages/compose/'.$this->friendInfo->id);	
		}
	}	
}