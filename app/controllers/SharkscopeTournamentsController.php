<?php
class SharkscopeTournamentsController extends BaseController {

	public function getTournaments(){

		$network  = Input::get('network');
		$date = Input::get('date');
		$now = date("Y-m-d");
		$tomorrow = date("Y-m-d", strtotime(date("Y-m-d", strtotime($now)) . " +1 days"));
		$keyword = Input::get('keyword');
		if($keyword != '')
		{
			if($now == $date)
			{
				$now = date("Y-m-d H:i:s");
				$tournaments = SharkscopeTournament::where('network','=',$network)
							->where('start_date', '>', "$now%")
							->where('start_date', '<', "$tomorrow%")
							->where('game_name', 'LIKE', "%$keyword%")
							->orderBy('start_date', 'asc')
							->get();
			}
			else
			{
				$tournaments = SharkscopeTournament::where('network','=',$network)
							->where('start_date', 'LIKE', "$date%")
							->where('game_name', 'LIKE', "%$keyword%")
							->orderBy('start_date', 'asc')
							->get();
			}
		}
		else
		{if($now == $date)
			{
				$now = date("Y-m-d H:i:s");
				$tournaments = SharkscopeTournament::where('network','=',$network)
							->where('start_date', '>', "$now%")
							->where('start_date', '<', "$tomorrow%")
							->orderBy('start_date', 'asc')
							->get();
			}
			else
			{
				$tournaments = SharkscopeTournament::where('network','=',$network)
							->where('start_date', 'LIKE', "$date%")
							->orderBy('start_date', 'asc')
							->get();
			}
		}
		

		if($tournaments->count() > 0)
		{
			$tournamentArray = array_fill(0, $tournaments->count(), null);
			$i = 0;
			foreach ($tournaments as $tournament) {
				
	            $tournamentArray[$i] = array('id' => $tournament->id,
	                                        'game_name' => $tournament->game_name,
	                                        'game_id' => $tournament->game_id,
	                                        'game_class' => $tournament->game_class,
	                                        'game_type' => $tournament->game_type,
	                                        'start_date' => $tournament->start_date,
	                                        'structure' => $tournament->structure,
	                                        'stake' => $tournament->stake,
	                                        'rake' => $tournament->rake,
	                                        'overlay' => $tournament->overlay,
	                                        'guarantee' => $tournament->guarantee,
	                                        'flags' => $tournament->flags,
	                                        'currency' => $tournament->currency,
	                                        'total_entrants' => $tournament->total_entrants,
	                                        'network' => $tournament->network);
	            $i++;
			}

			$response = array(
            	'success' => 'success',
            	'tournaments' => $tournamentArray,
            	'count' => $tournaments->count()
        	);
	 
	    	return Response::json( $response );
		}
		else
		{
			$arr = array();

			$response = array(
       	 		'success' => 'empty',
            	'tournaments' => $arr,
            	'count' => $tournaments->count()
	        );
	 
	     	return Response::json( $response );
		}
	}
}
?>