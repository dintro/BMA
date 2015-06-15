<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MonthlyGenerator extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'monthlygenerator';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->line('Welcome to the tournament generator.');

		$now = date("Y-m-d H:i:s");
        $now1 = date("Y-m-d");
        
        $this->line('Now : '. $now.' - '.strtotime($now).' - '.strtotime($now1));
        
        $nextMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($now1)) . " +1 month"));
        $this->line('Next Month : '. $nextMonth.' - '.strtotime($nextMonth));

        $time_start = microtime(true);

		//----SHARKSCOPE CODE START----//
	    $encoded1 = hash('md5', 'susu1234');
	    $license = 'lekrj5n23nsx8an4kl';
	    $encoded2 = strtolower($encoded1) . strtolower($license);
	    
	    $digest = hash('md5', $encoded2);
	    
	    $sharkuser = "sildeyna@gmail.com";

	    // jSON URL which should be requested
	    //$json_url = 'http://www.sharkscope.com/api/bma/networks/PokerStars/players/SkaiWalkurrr?Username='.$sharkuser.'&Password='.$digest;
	    $unixnow = strtotime($now);
	    $unixnextMonth = strtotime($nextMonth);
	    
	    $json_url = 'http://www.sharkscope.com/api/bma/networks/fulltilt,pokerstars,ipoker,partypoker,adjarabet/activeTournaments?filter=Date:'.$unixnow.'~'.$unixnextMonth.';Class:SCHEDULED';
	    //$json_url = 'http://www.sharkscope.com/api/bma/networks/PokerStars/tournaments/1035110188';
	    $username = $sharkuser;  // authentication
	    $password = $digest;  // authentication

	    // jSON String for request

	    // Initializing curl
	    $ch = curl_init( $json_url );
	    $limit = 5;
	    // Configuring curl options
	    $options = array(
	    CURLOPT_RETURNTRANSFER => true,
	    
	    CURLOPT_HTTPHEADER => array('Content-type: application/json', 'Accept: application/json', 'Username: '.$sharkuser, 'Password: '.$digest, 'User-Agent: Mozilla', 'limit: '. $limit)

	    );

	    // Setting curl options
	    curl_setopt_array( $ch, $options );
	    
	    // Getting results
	    $result = curl_exec($ch); // Getting jSON result string
	    if($result === FALSE){
	    	$this->line(curl_error($ch));
	        //echo curl_error($ch).'aaa';
	    }
	    $responseData = json_decode($result, TRUE);

	    

	    $responseArray = $responseData["Response"];
	    $data["success"] = $responseArray["@success"];
	    $data["timestamp"] = $responseArray["@timestamp"];
	    $data["remainingsearch"] = $responseArray["UserInfo"]["Subscriptions"]["@totalSearchesRemaining"];
	    $tournamentArray = $responseArray["RegisteringTournamentsResponse"]["RegisteringTournaments"]["RegisteringTournament"];
	    $newTournament = 0;
	    $tournamentFound = count($tournamentArray);
	    $this->line($tournamentFound.' tournaments found.');
	    for($x=0; $x <= $tournamentFound - 1; $x++)
        {
            $tournamentData = $tournamentArray[$x];
            
            $tournament = SharkscopeTournament::where('game_id','=',$tournamentData["@id"])->first();
            if (is_null($tournament)) {
			    // It does not exist - add to favorites button will show
			    $newTournament++;
			    $tournament = new SharkscopeTournament;
			    $tournament->game_id = $tournamentData["@id"];
            	$tournament->game_name = $tournamentData["@name"];
            	$tournament->game_class = $tournamentData["@gameClass"];
            	$tournament->game_type = $tournamentData["@game"];
            	$tournament->start_date = date("Y-m-d H:i:s",$tournamentData["@scheduledStartDate"]);
            	
            	if(is_array($tournamentData) && array_key_exists('@lateRegEndDate', $tournamentData))
            	{
                    $tournament->reg_end_date = date("Y-m-d H:i:s",$tournamentData["@lateRegEndDate"]);
                }
                $tournament->total_entrants = $tournamentData["@totalEntrants"];
                $tournament->structure = $tournamentData["@structure"];
                $tournament->stake = $tournamentData["@stake"];
                $tournament->rake = $tournamentData["@rake"];
                if( is_array($tournamentData) && array_key_exists('@overlay', $tournamentData))
                {
                    $tournament->overlay = $tournamentData["@overlay"];
                }
                else
                {
                    $tournament->overlay = 0;
                }
                $tournament->network = $tournamentData["@network"];
                if(is_array($tournamentData) && array_key_exists('@guarantee', $tournamentData))
                {
                    $tournament->guarantee = $tournamentData["@guarantee"];
                }
                else
                {
                    $tournament->guarantee = 0;
                }
                if( is_array($tournamentData) && array_key_exists('@flags', $tournamentData))
                {
	                $tournament->flags = $tournamentData["@flags"];
	            }
	            else
	            {
	                $tournament->flags = '-';
	            }
	            $tournament->currency = $tournamentData["@currency"];
	            $tournament->Save();
                                
			} else {
			    // It exists - remove from favorites button will show
			}

        }

        $time_end = microtime(true);
    	$time = $time_end - $time_start;
    	$log = new TournamentLog;
		$log->log_type = 'Monthly Log';
		$log->tournaments_found = $tournamentFound;
		$log->new_tournaments = $newTournament;
		$log->Save();
    	$this->line($newTournament.' new tournaments inserted. Execution time : ' .$time . ' seconds');

    	$expiredTournaments = SharkscopeTournament::where('start_date', '<', date("Y-m-d"));
    	$this->line('Expired tournaments found ' . $expiredTournaments->count());
    	if($expiredTournaments->count() != 0)
    	{
    		$expiredTournaments->delete();	
    	}
    	
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
