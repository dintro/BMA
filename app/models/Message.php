<?php

class Message extends Eloquent  {

	// protected $table = 'messages';
	

	// public static function validate($data) 
	// {
	// 	return Validator::make($data, array(
	// 	'title' => 'required',
	// 	'content' => 'required'
	// 	));
	// }

	public function preview()
	{
		return str_limit($this->content, $limit = 55, $end = '...');
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function recipient()
	{
		return $this->belongsTo('User', 'recipient_id');
	}

	public function conversation()
	{
		return $this->belongsTo('Conversation');
	}

	// public function sender()
	// {
	// 	return $this->belongsTo('User', 'sender_id');
	// }

	// public static function getInbox($recipient_id)
	// {
	// 	return Message::where('recipient_id', '=', $recipient_id)
	// 		->orderBy('created_at', 'DESC')
	// 		->paginate(5);
	// }
	// public static function getUnreadMessages($recipient_id)
	// {
	// 	return Message::where('recipient_id', '=', $recipient_id)
	// 		->where('status', '=', 'unread')
	// 		->count();
	// }

	// public static function getOutbox($sender)
	// {
	// 	return Message::where('sender', '=', $recipient)
	// 		->get();
	// }

}
