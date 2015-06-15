<?php

class Userblock extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'userblock';

	public static $rules = array(
    'user_id'	=>	'required',
 	'userblock_id'	=>	'required',
   
    );

	public function user()
    {
        return $this->hasMany('user','user_id');
    }
	
	public function userblock()
    {
		return $this->hasMany('user', 'userblock_id');
	}
}	
