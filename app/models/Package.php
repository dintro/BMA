<?php

class Package extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'packages';

	public static $rules = array(
    'title'	=>	'required'/*,
    'posted'=>	array('required', 'date_format:"m-d-Y"'),
    'ended'	=>	array('required', 'date_format:"m-d-Y"')*/
   
    );
	
	public function sellingPercent()
	{
		$orderdetails = $this->orderdetails()->get();
		$percent=0;
		foreach($orderdetails as $orderdetail)
		{
			if($orderdetail->order->payment_status == "Approved")
			{
				$percent= $percent + floatval($orderdetail->selling);	
			}
			
		}
		
		return $percent;
	}

	public function getPackageStatus()
	{
		$status = 'Active';
		if($this->ended < date("Y-m-d H:i:s") || $this->cancel == 1)
		{
			$status = 'Not Active';
		}
		return $status;
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function turnaments()
	{
		return $this->hasMany('Turnament','package_id');
	}
	
	public function payments()
	{
		return $this->hasMany('Payment','package_id');
	}

	public function orderdetails()
	{
		return $this->hasMany('OrderDetail','package_id');
	}

	public function comments()
	{
		return $this->hasMany('PackageComment','package_id');
	}
}
