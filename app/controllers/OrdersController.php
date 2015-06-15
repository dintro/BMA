<?php
class OrdersController extends BaseController {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		//
        $orders = Order::all();
        return View::make('pages.orders.cart', compact('orders'));
    }
	
	public function cart()
    {
		//
        
        return View::make('pages.orders.cart');
    }
	
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
		return View::make('pages.orders.create');
		
    }
	
	public function addtocart()
	{
		if(!Auth::check())
		{
			return Redirect::to('login')
				->with('message','Please Login!');				
		}
		
		$input = Input::all();
        $validation = Validator::make($input, Order::$rules);
		
        if ($validation->passes())
        {
			$package_id = Input::get('package_id');
			$selling = Input::get('selling');
			$selling_price = Input::get('selling_price');
			
			$user_id = Auth::user()->id;
			
			$package = Package::where('id','=',$package_id)->first();
			
			$newcart = array(array('package_name'=>$package->title, 'package_id'=>$package_id, 'selling'=>$selling, 'selling_price'=>$selling_price));
			
			//create a new session var if does not exist
			//$_SESSION["packagecart"] = $newcart;
			Session::forget('packagecart');
			Session::put('packagecart', $newcart);
			
			return Redirect::to('cart');
			//	->with('meesage','Order has been submited')
			//	->with('sussess','1');
		}
	}
	
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
		$input = Input::all();
        $validation = Validator::make($input, Order::$rules);

        if ($validation->passes())
        {
			$now   = new DateTime;
			$user = Auth::user();
			$user_id = $user->id;
			
			$order = new Order;
			
			//$order->package_id = Input::get('package_id');
			$order->payment_method = Input::get('payment_method');
			$order->in_game_name = Input::get('in_game_name');
            $order->seller_id = Input::get('seller_id');
			$order->payment_status = 'Pending';
			
			$order->order_date = $now;	
				
			$order->user_id = $user_id;
			
			$order->save();
			$order_id = $order->id;
			
			$order_detail = new OrderDetail;
			$order_detail->order_id = $order_id;
			$order_detail->package_id = Input::get('package_id');
            $order_detail->package_name = Input::get('package_name');
			$order_detail->selling = Input::get('selling');
			$order_detail->selling_price = Input::get('selling_price');
			$order_detail->save();

            $notification = new Notification;
            $notification->user_id = $user_id;
            $notification->recipient_id = $order->seller_id;
            $notification->content = $user->getFullname().' just order your package';
            $notification->type = 'Package Order';
            $notification->status = 'unread';
            $notification->url = url('/users/mytransactions');
            $notification->save();
			
			$package = Package::find(Input::get('package_id'));
			if (is_null($package))
			{
				$pUser = $package->user();
				
				Mail::send('emails.purchase', array('firstname'=>$user->firstname, 'packageUsername'=> $pUser->firstname), function($message){
					$message->to($user->email, $user->firstname.' '.$user->lastname)->subject('[Buy My Action] Purchases');
				});			
			}
		
            return Redirect::to('users/mysweats')
				->with('meesage','Order has been submited')
				->with('sussess','1');
        }

        return Redirect::to('blank')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    public function approve($id)
    {
        $order = Order::find($id);
        if (is_null($order))
        {
            return Redirect::to('/users/mytransactions')->with('message', 'Approve Order Failed!<br/>Cannot find order!');
        }
        else
        {
            $order->payment_status = 'Approved';
            $order->save();

            $notification = new Notification;
            $notification->user_id = $order->seller_id;
            $notification->recipient_id = $order->user_id;
            $notification->content = $order->seller->getFullname().' approved your order';
            $notification->type = 'Package Order';
            $notification->status = 'unread';
            $notification->url = url('/users/mytransactions');
            $notification->save();

            return Redirect::to('/users/mytransactions')->with('message', 'Approve Order Success!');
        }
    }

    public function reject($id)
    {
        $order = Order::find($id);
        if (is_null($order))
        {
            return Redirect::to('/users/mytransactions')->with('message', 'Reject Order Failed!<br/>Cannot find order!');
        }
        else
        {
            $order->payment_status = 'Rejected';
            $order->save();

            $notification = new Notification;
            $notification->user_id = $order->seller_id;
            $notification->recipient_id = $order->user_id;
            $notification->content = $order->seller->getFullname().' rejected your order';
            $notification->type = 'Package Order';
            $notification->status = 'unread';
            $notification->url = url('/users/mytransactions');
            $notification->save();

            return Redirect::to('/users/mytransactions')->with('message', 'Reject Order Success!');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
       /* //
		$package = Package::find($id);
        if (is_null($package))
        {
            return Redirect::route('pages.packages.index');
        }
        return View::make('pages.packages.edit', compact('package'));*/
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
      /*  //
		$input = Input::all();
        $validation = Validator::make($input, Package::$rules);
        if ($validation->passes())
        {
            $package = Package::find($id);
            $package->update($input);
            return Redirect::route('pages.packages.show', $id);
        }
		return Redirect::route('pages.packages.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');*/
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
		Order::find($id)->delete();
        return Redirect::route('pages.orders.index');
    }
}
   