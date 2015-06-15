<?php

class OrderDetail extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'order_details';
	
	public static $rules = array(
		'package_id'	=>	'required'/*,
		'posted'=>	array('required', 'date_format:"m-d-Y"'),
		'ended'	=>	array('required', 'date_format:"m-d-Y"')*/
    );	

    public function order()
    {
    	return $this->belongsTo('Order', 'order_id');
    }

	public function package()
    {
    	return $this->belongsTo('Package', 'package_id');
    }    
	
}