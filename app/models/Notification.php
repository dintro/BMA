<?php

class Notification extends Eloquent  {

	
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function recipient()
	{
		return $this->belongsTo('User', 'recipient_id');
	}

}