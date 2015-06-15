<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TournamentGeneratorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tournament';

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
        $now1 = date("Y-m-d H:i:s");
        //echo 'Now : '. $now.' - '.strtotime($now).' - '.strtotime($now1);
        $this->line('Now : '. $now.' - '.strtotime($now).' - '.strtotime($now1));
        //$tomorrow =  date("Y-m-d H:i:s", mktime(23, 59, 59, date("m")  , date("d"), date("Y")));
        $tomorrow = date("Y-m-d", strtotime(date("Y-m-d", strtotime($now1)) . " +1 days"));
        //echo '<br/>Tomorrow : '. $tomorrow.' - '.strtotime($tomorrow);
        $this->line('Tomorrow : '. $tomorrow.' - '.strtotime($tomorrow));
		//----SHARKSCOPE CODE START----//
	    $encoded1 = hash('md5', 'susu1234');
	    $license = 'lekrj5n23nsx8an4kl';
	    $encoded2 = strtolower($encoded1) . strtolower($license);
	    
	    $digest = hash('md5', $encoded2);
	    
	    $sharkuser = "sildeyna@gmail.com";

	    // jSON URL which should be requested
	    //$json_url = 'http://www.sharkscope.com/api/bma/networks/PokerStars/players/SkaiWalkurrr?Username='.$sharkuser.'&Password='.$digest;
	    $unixnow = strtotime($now);
	    $unixTomorrow = strtotime($tomorrow);
	    $json_url = 'http://www.sharkscope.com/api/bma/networks/fulltilt,pokerstars,ipoker,partypoker,adjarabet/activeTournaments?filter=Date:'.$unixnow.'~'.$unixTomorrow.';Class:SCHEDULED';
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
	        //echo curl_error($ch).'aaa';
	    }
	    $responseData = json_decode($result, TRUE);

	    $responseArray = $responseData["Response"];
	    $data["success"] = $responseArray["@success"];
	    $data["timestamp"] = $responseArray["@timestamp"];
	    $data["remainingsearch"] = $responseArray["UserInfo"]["Subscriptions"]["@totalSearchesRemaining"];
	    $tournamentArray = $responseArray["RegisteringTournamentsResponse"]["RegisteringTournaments"]["RegisteringTournament"];
	    
	    $this->line(count($tournamentArray).' tournaments found');
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
