<?php
class PackagesController extends BaseController {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		//
        $packages = Package::all();
        return View::make('pages.packages.index', compact('packages'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
		return View::make('pages.packages.create');
		
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
        $validation = Validator::make($input, Package::$rules);

        if ($validation->passes())
        {
			$now   = new DateTime;
			
			$package = new Package;
			
			$package->title = Input::get('title');
			$package->posted = new DateTime;
			$package->ended = $now->add(new DateInterval('PT'. Input::get('ended') .'H'));
			$package->notes = Input::get('notes');
			$package->user_id = Input::get('user_id');
			$package->selling = Input::get('add-selling');
			$package->markup = Input::get('markup');
			$package->selling_amount = Input::get('selling_amount_form');
			$package->total = Input::get('total_form');
			$package->button1 = Input::get('button1');	
			$package->button2 = Input::get('button2');	
			$package->button3 = Input::get('button3');	
			
			$package->save();
			$packageid = $package->id;
			
			$turnaments = Input::get('turnament');
			$buyins = Input::get('buyin');			
			$payments = Input::get('payment');
			$gameids = Input::get('gameid');
			$indexpayment = 0;
			foreach($payments as $payment) {
				$pay = new Payment;
				
				$pay->package_id = $packageid;
				$pay->payment_name = $payments[$indexpayment];
				$pay->save();
				$indexpayment++;
			}
			
			$indexturnament = 0;
			foreach($turnaments as $turnament) {

                $gameid = $gameids[$indexturnament];
				$tur = new Turnament;
				
                $sharkscopeData = SharkscopeTournament::where('game_id','=',$gameid)->first();
                if (!is_null($sharkscopeData)) {
                    $tur->package_id = $packageid;
                    $tur->name = $turnaments[$indexturnament];
                    $tur->buyin = $buyins[$indexturnament];
                    $tur->game_name = $sharkscopeData->game_name;
                    $tur->game_id = $sharkscopeData->game_id;
                    $tur->game_class = $sharkscopeData->game_class;
                    $tur->game_type = $sharkscopeData->game_type;
                    $tur->start_date = $sharkscopeData->start_date;
                    $tur->reg_end_date = $sharkscopeData->reg_end_date;
                    $tur->structure = $sharkscopeData->structure;
                    $tur->stake = $sharkscopeData->stake;
                    $tur->rake = $sharkscopeData->rake;
                    $tur->overlay = $sharkscopeData->overlay;
                    $tur->guarantee = $sharkscopeData->guarantee;
                    $tur->flags = $sharkscopeData->flags;
                    $tur->currency = $sharkscopeData->currency;
                    $tur->total_entrants = $sharkscopeData->total_entrants;
                    $tur->network = $sharkscopeData->network;
                    $tur->save();
                }
				
				$indexturnament++;
			}
			
            //Package::create($input);
			// Send Email to Circle
			$friends = Auth::user()->friends()->get();
		
			foreach ($friends as $friend) {
				$friendInfo = $friend->friendInfo;
				$fEmail = $friend->email;
				$fFullname = $friendInfo->getFullname();										
				
				Mail::send('emails.newpackage', array('firstname'=>Auth::user()->firstname,'userid'=>Auth::user()->id), function($message){
					$message->to($fEmail, $fFullname)->subject('[Buy My Action] New Package');
				});
			}
			

            return Redirect::to('users/profile')
				->with('message','Package has been created')
				->with('sussess','1');
        }

        return Redirect::to('blank')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
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
        //
		$package = Package::find($id);
        if (is_null($package))
        {
            return Redirect::route('pages.packages.index');
        }
        return View::make('pages.packages.edit', compact('package'));
    }
	
	public function cancel($id)
    {
        //
		$package = Package::find($id);
		$package->cancel = 1;
		$package->update();
		 
        return Redirect::to('users/profile');	
		
	}
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
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
            ->with('message', 'There were validation errors.');
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
		Package::find($id)->delete();
        return Redirect::route('pages.packages.index');
    }

    public function retrievePackage()
    {
        $package = Package::find(Input::get('packageid'));

        $_payment = '';                                             
        foreach($package->payments()->get() as $payment)
        {
            
            if($_payment!='')
            {
                $_payment .= ', '.$payment->payment_name;
            }else
            {
                $_payment = $payment->payment_name;
            }   
        }

        $tournamentsArray = array_fill(0, $package->turnaments()->count(), null);

        $i = 0;
        foreach ($package->turnaments()->get() as $turnament) {
            
            $tournamentsArray[$i] = array('url' => '<a target="_blank" href="http://www.sharkscope.com/#Find-Tournament//networks/'.$turnament->network.'/tournaments/'.$turnament->game_id.'" > '.$turnament->name.'</a>',
                                        'buyin' => money_format('%(#10n', $turnament->buyin));
            $i++;
        }

        $response = array(
                'success' => 'success',
                'title' => $package->title,
                'posted' => strtoupper(date("j M, Y",strtotime($package->posted))),
                'payments' => $_payment,
                'tournaments' => $tournamentsArray,
                'total' => money_format('%(#10n', $package->total),
                'markup' => $package->markup,
                'sold' => $package->sellingPercent()
            );
        // $response = array(
        //         'success' => 'success',
        //         'title' => Input::get('packageid')
        //     );

        return Response::json( $response );
    }
	
	// public function cancel($id)
 //    {
 //        //
	// 	$package = Package::find($id);
	// 	$package->cancel = 1;
	// 	$package->update();
		 
 //        return Redirect::to('users/profile');
 //    }
}
   