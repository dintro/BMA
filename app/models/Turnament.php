<?php

class Turnament extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'turnaments';

	public static $rules = array(
    'name'	=>	'required',
 	'buyin'	=>	'required',
   
    );

	public function package()
    {
        return $this->belongsTo('Package');
    }

}
