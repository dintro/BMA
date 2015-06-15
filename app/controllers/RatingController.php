<?php

/**
* 
*/
class RatingController extends BaseController
{
	public function rateUser()
	{
		$rating = new Rating;
		$rating->rater_id = Input::get('raterid');
		$rating->ratee_id = Input::get('rateeid');
		$rating->rate = Input::get('rate');
		$rating->save();

		$user = User::find(Input::get('rateeid'));


		$response = array(
            'success' => 'success',
            'rating' => $user->getTotalRating()
        );
 
    	return Response::json( $response );
	}
}