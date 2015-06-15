<?php

class PackageComment extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'package_comments';

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}
	
}