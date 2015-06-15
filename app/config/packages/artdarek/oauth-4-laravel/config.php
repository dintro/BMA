<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
           // 'client_id'     => '1474169292853900',
           // 'client_secret' => '42a89825c64335948307a95780fb1276',
	    'client_id'     => '1454396131497883',
            'client_secret' => '31b43458d3777068d04b749bd5447259',
            'scope'         => array('email'),//,'user_online_presence'),
        ),		

	)

);
