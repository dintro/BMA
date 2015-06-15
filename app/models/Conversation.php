<?php

class Conversation extends Eloquent  {

	public function users()
	{
		return $this->belongsToMany('User');
	}

	public function messages()
	{
		return $this->hasMany('Message');
	}

	public function otheruser($id)	
	{
		return $this->users()->where('user_id','!=',$id)->first();
	}

	public function lastMessage()
	{
		return $this->messages->last();
	}

	public function unreadMessages()
	{
		return $this->messages()->where('status', 'unread')->get();
	}

}