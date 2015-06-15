<?php

class Payment extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'payments';

	public static $rules = array(
    'payment_name'	=>	'required',
 
   
    );

	public function package()
    {
        return $this->belongsTo('Package');
    }


}
