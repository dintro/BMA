<?php

class Order extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'orders';
	
	public static $rules = array(
		'package_id'	=>	'required'/*,
		'posted'=>	array('required', 'date_format:"m-d-Y"'),
		'ended'	=>	array('required', 'date_format:"m-d-Y"')*/
    );	

	public function orderdetails()
	{
		return $this->hasMany('OrderDetail','order_id');
	}

	public function orderdetail()
	{
		return $this->hasOne('OrderDetail','order_id');
	}

	public function buyer()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function seller()
	{
		return $this->belongsTo('User', 'seller_id');
	}

	public function package()
	{
		$orderdetail = $this->orderdetails()->first();
		return Package::find($orderdetail->package_id);
	}
}