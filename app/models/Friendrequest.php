<?php

class Friendrequest extends Eloquent  {

	public function targetInfo()
	{
		return $this->belongsTo('User', 'target_id');
	}

	public function userInfo()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public static function alreadySentFriendRequest($user_id, $target_id)
	{
		return ! is_null(
            DB::table('friends')
              ->where('user_id', $user_id)
              ->where('target_id', $target_id)
              ->first()
        );
	}	
}