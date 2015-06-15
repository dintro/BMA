<?php

class Rating extends Eloquent  {

	public function ratee()
	{
		return $this->belongsTo('User', 'ratee_id');
	}

	public function rater()
	{
		return $this->belongsTo('User', 'rater_id');
	}
}