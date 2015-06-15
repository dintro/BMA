<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	public static $rules = array(
    'first_name'=>'required|alpha|min:2',
    'last_name'=>'required|alpha|min:2',   
    'email'=>'required|email|unique:users',
    'password'=>'required|alpha_num|between:6,12|confirmed',
    'password_confirmation'=>'required|alpha_num|between:6,12',
    'recaptcha_response_field' => 'required|recaptcha',
    'terms_and_conditions'=>'required'
    );

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function getId()
	{
	  return $this->id;
	}

	public function getEmail()
	{
	  return $this->email;
	}

	public function getDisplayname()
	{
	  return $this->displayname;
	}

	public function getFullname()
	{
	  return $this->firstname .' '. $this->lastname;
	}

	public function inbox()
	{
		return $this->belongsToMany('Conversation');
	}

	public function hasInbox()
	{
		return ! is_null(
            DB::table('conversation_user')
              ->where('user_id', $this->id)
              ->first()
        );
	}

	public function getPhotoUrl()
	{
		if($this->photourl == null)
		{
			return url('/img/default.jpg');
		}
		else
		{
			return url('/profilepicture/'.$this->photourl);
		}
	}	

	public function ratings()
	{
		return $this->hasMany('Rating', 'ratee_id');
	}

	public function getTotalRating()
	{
		$count = $this->ratings()->count();
		if($count > 0)
		{
			$sum = $this->ratings()->get()->sum('rate');
			return ($sum / $count);
		}
		else
		{
			return 0;
		}
	}

	public function isRated($rater_id)
	{
		$isRated = $this->ratings()->where('rater_id', $rater_id)->get();
		if($isRated->count() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}

	public function messages()
	{
		return $this->hasMany('Message', 'recipient_id');
	}

	public function notifications()
	{
		return $this->hasMany('Notification', 'recipient_id');
	}

	public function unreadMessages()
	{
		return $this->messages()->where('status', 'unread')->orderBy('updated_at', 'DESC')->get();
	}

	public function unreadNotifications()
	{
		return $this->notifications()->where('status', 'unread')->orderBy('updated_at', 'DESC')->get();
	}

	public function friends()	
	{
		return $this->hasMany('Friend', 'user_id');
	}

	public function friendrequests()	
	{
		return $this->hasMany('Friendrequest', 'target_id')->orderBy('created_at', 'DESC')->get();
	}

	public function sentfriendrequests()	
	{
		return $this->hasMany('Friendrequest', 'user_id')->orderBy('created_at', 'DESC')->get();
	}	
	
	public function checkSentFriendRequest($target_id)
	{
		if($this->hasMany('Friendrequest', 'user_id')->where('target_id', $target_id)->count() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function checkFriendRequest($user_id)
	{
		if($this->hasMany('Friendrequest', 'target_id')->where('user_id', $user_id)->count() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function packages()
	{
		return $this->hasMany('Package','user_id');
	}

	public function screennames()
	{
		return $this->hasMany('Screenname','user_id');
	}

	public function getScreenname($screentype)
	{
		if($this->screennames()->count() == 0)
		{
			return '';
		}
		else
		{
			if($this->screennames()->where('screen_name', $screentype)->count() == 1)
			{
				return $this->screennames()->where('screen_name', $screentype)->first()->username;
			}
			else
			{
				return '';		
			}
		}
	}

	public function orders()
	{
		return $this->hasMany('Order','user_id');
	}

	public function orderToMe()
	{
		return $this->hasMany('Order','seller_id');
	}

	public function getPendingOrder()
	{
		return $this->orderToMe()->where('payment_status', 'Pending');
	}

	public function getPackageBought()	
	{
		$arraypackage = array('count' => 0, 'value' => 0);
		$value = 0;
		$count = 0;
		if($this->orders()->count() == 0)
		{
			return $arraypackage;
		}
		else
		{
			foreach ($this->orders()->get() as $order) {
				if($order->payment_status == "Approved")
				{
					$orderdetail = $order->orderdetails()->first();

					$value += $orderdetail->selling_price;
					$count++;	
				}
				
			}
			$arraypackage['value'] = $value;
			$arraypackage['count'] = $count;

			return $arraypackage;
		}
	}

	public function getPackageSold()	
	{
		$arraypackage = array('count' => 0, 'value' => 0);
		$value = 0;
		$count = 0;
		if($this->packages()->count() == 0)
		{
			return $arraypackage;
		}
		else
		{

			foreach ($this->packages()->get() as $package) {
				if($package->orderdetails()->count() != 0)
				{
					foreach ($package->orderdetails()->get() as $orderdetail) {
						if($orderdetail->order->payment_status == "Approved" )
						{
							$value += $orderdetail->selling_price;
							$count++;	
						}
					}
				}
			}
			$arraypackage['value'] = $value;
			$arraypackage['count'] = $count;

			return $arraypackage;
		}
	}

	public static function GetUserHasActivePackage()
	{
		return User::whereHas('packages', function($q)
		{
			$q->where('ended', '>=', date("Y-m-d H:i:s"))->where('cancel','0');
		});
	}

	public static function GetFriendsHasActivePackage()
	{
		return User::whereHas('packages', function($q)
		{
			$q->where('ended', '>=', date("Y-m-d H:i:s"))->where('cancel','0');
		})->whereHas('friends', function($q2){
			$q2->where('friend_id', Auth::user()->id);
		});
	}
	
	public static function userBlock($user_id, $userblock_id)
	{
		return ! is_null(
            DB::table('userblock')
              ->where('user_id', $user_id)
              ->where('userblock_id', $userblock_id)
              ->first()
        );
	}
}
