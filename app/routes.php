<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//----Pages Controller----//
// Route::get('/', function()
// {
//     return View::make('pages.home');
// });
Route::get('/', array('as' => 'searchPackageHome', 'uses' => 'HomeController@showHome'));
// Route::get('home', function()
// {
//     return View::make('pages.home');
// });
Route::get('home', 'HomeController@showHome');
Route::get('home/{query?}', 'HomeController@showHome');


Route::get('faq', function()
{
    return View::make('pages.faq');
});

Route::get('faq/add', 'FaqsController@showAddFaq');

Route::get('faq/edit/{id}', 'FaqsController@showEditFaq');
Route::post('faq/insert', 'FaqsController@insertFaq');
Route::post('faq/update/{id}', 'FaqsController@updateFaq');
Route::get('faq/delete/{id}', 'FaqsController@deleteFaq');

Route::get('contactus', function()
{
    return View::make('pages.contactus');
});

Route::post('contactus/send', 'HomeController@sendContactMail');

Route::get('login', function()
{
    return View::make('pages.users.login');
});

Route::get('signup', function()
{
    return View::make('pages.users.signup');
});

Route::get('signupfb', function()
{
    return View::make('pages.users.signupfb2');
});

Route::get('dashboard', function()
{
    return View::make('pages.users.dashboard');
});

Route::get('blank', function()
{
    return View::make('pages.blank');
});

Route::get('users/forgotpassword', function()
{
    return View::make('pages.users.forgotpassword');
});
Route::post('users/forgot', 'UsersController@forgotPassword');

Route::get('users/resetpassword', function(){ 
		$token = Input::get('token');	

		$user = DB::table('users')->where('email_verified_token', $token)->first();

		if($user){	
				return View::make('pages.users.forgotconfirm')
				->with('email',$user->email)
				->with('token',$token); 
		}else
		{
			return Redirect::to('users/forgotpassword');
		}
});
Route::post('users/reset', 'UsersController@resetPassword');

Route::get('users/mysweats', function(){ 
    if(Auth::check())
    {
        $user = User::find( Auth::user()->id);
        return View::make('pages.users.mysweat')->with('selectedUser', $user);    
    }
    else
    {
        return Redirect::to('/');
    }
	
});



Route::get('users/mytransactions', function(){ 
    if(Auth::check())
    {
        $user = User::find( Auth::user()->id);
        return View::make('pages.users.mytransactions')->with('selectedUser', $user);     
    }
    else
    {
        return Redirect::to('/');
    }
    
});

Route::get('/users/mytransactions/approve/{id}', 'OrdersController@approve');
Route::get('/users/mytransactions/reject/{id}', 'OrdersController@reject');


Route::get('/users/notifications', 'NotificationsController@viewNotifications');




// ==========================

Route::controller('users', 'UsersController');

Route::get('sign-in-with-facebook', 'UsersController@loginWithFacebook');
Route::get('connect-to-facebook', 'UsersController@connectToFacebook');
Route::get('disconnect-from-facebook', 'UsersController@disconnectFromFacebook');

Route::get('testemail', 'UsersController@testEmail');
Route::get('verify', 'UsersController@verifyEmail');

Route::get('signup-with-facebook', 'UsersController@signupWithFacebook');



Route::get('/profile/{id}', array('as' => 'showprofile', 'uses' => 'UsersController@getUser'));
Route::get('/pastpackages/{id}', array('as' => 'showPastPackages', 'uses' => 'UsersController@getUserForPastPackage'));
Route::get('/sharkscope/{id}', array('as' => 'showSharkscope', 'uses' => 'UsersController@getUserForSharscope'));
Route::get('/about-me/{id}', array('as' => 'showSharkscope', 'uses' => 'UsersController@getUserForAboutMe'));

//----Scrennname Controller----//
Route::post('/settings/addscreenname', 'ScreennameController@addScreenname');
Route::post('/settings/editscreenname', 'ScreennameController@editScreenname');
Route::post('/settings/removescreenname', 'ScreennameController@removeScreenname');
Route::post('/settings/editfirstname', 'ScreennameController@editFirstname');
Route::post('/settings/editlastname', 'ScreennameController@editLastname');
Route::post('/settings/editispublic', 'ScreennameController@editIsPublic');
Route::post('/settings/editemail', 'ScreennameController@editEmail');
Route::post('/settings/editphoto', 'ScreennameController@editPhoto');
Route::post('/settings/editcountry', 'ScreennameController@editCountry');

Route::post('/settings/editpaymentmethod', 'ScreennameController@editPaymentMethod');

Route::post('/settings/editpaypal', 'ScreennameController@editPaypal');
Route::post('/settings/editskrill', 'ScreennameController@editSkrill');
Route::post('/settings/editneteller', 'ScreennameController@editNeteller');
Route::post('/settings/editcash', 'ScreennameController@editCash');
Route::post('/settings/editbankwire', 'ScreennameController@editBankWire');
Route::post('/settings/editaboutme', 'ScreennameController@editAboutMe');
Route::post('/settings/addrating', 'RatingController@rateUser');

//----Circle Controller---//

Route::post('/sendcirclerequest/{id}', 'FriendrequestsController@send');

Route::get('/mycircle', 'FriendsController@index');
Route::post('/circle/ajax/accept', 'FriendrequestsController@accept');
Route::delete('/circle/ajax/reject', 'FriendrequestsController@reject');
Route::get('/circle/ajax/refreshFriendList', 'FriendsController@refreshFriendList');
Route::post('/circle/ajax/readNotifications', 'NotificationsController@readNotifications');
Route::get('/circle/{id}', 'FriendsController@index');
Route::post('/notification/ajax/readNotification', array('uses' => 'NotificationsController@readNotification'));
Route::post('/notification/ajax/readAllNotifications', array('uses' => 'NotificationsController@readAllNotifications'));
Route::post('/notification/ajax/refreshNotification', 'NotificationsController@refreshNotification');


//----Message Controller---//
Route::get('/messages/',array('as' => 'messages', 'uses' => 'ConversationsController@index'));

Route::get('/message/{id}', array('as' => 'readMessage', 'uses' => 'ConversationsController@view'));

Route::get('/messages/compose', array('as' => 'composeMessage', 'uses' => 'ConversationsController@compose'));

Route::get('/messages/compose/{id?}', array('as' => 'composeMessage', 'uses' => 'ConversationsController@compose'));

Route::post('/messages/send', array('uses' => 'ConversationsController@send'));
Route::post('/messages/reply', array('uses' => 'ConversationsController@reply'));


// Route::delete('/message/delete', array( 'uses'=>'MessagesController@destroy'));

// Route::post('/message/ajax/{id}', function($id)
// {
//     $message = Message::find($id);
//     $message->status = 'read';
//     $message->save();
//     $response = array(
//             'title' => $message->title,
//             'displayname' => $message->sender->displayname,
//             'content' => $message->content,
//             'messagetime' => date("d-m-Y", strtotime($message->created_at))
//         );
 
//     return Response::json( $response );
// });



Route::post('/message/ajax/refreshInbox', function()
{
    if(Auth::user()->hasInbox())
    {
        
        $conversations = Auth::user()->inbox()->orderBy('updated_at', 'DESC')->get();

        $conversationArray = array_fill(0, $conversations->count(), null);
        $i = 0;
        foreach ($conversations as $conversation) {
            $message = $conversation->lastMessage();
            $otheruser = $conversation->otheruser(Auth::user()->id);
            $conversationArray[$i] = $arrayName = array('id' => $conversation->id,
                                                    'message' => $message->preview(),
                                                    'messageid' => $message->id,
                                                    'url' => url('/message/'.$otheruser->id),
                                                    'fullname' => $otheruser->getFullname(),
                                                    'image' => $otheruser->getPhotoUrl(),
                                                    'status' => ($message->recipient_id == Auth::user()->id) ? $message->status : 'not yours',
                                                    'updated'=> date("M d",strtotime($conversation->updated_at)));
            $i++;
        }

        $response = array(
                'conversations' => $conversationArray
        );
        return Response::json( $response );
    }
    else
    {
        $arr = array();
        $response = array(
                'conversations' => $arr
        );
        return Response::json( $response );
    }
    
});

Route::post('/message/ajax/refreshMessageNotification', function()
{

    if(Auth::user()->inbox()->count() != 0)
    {
        
        $conversations = Auth::user()->inbox()->orderBy('updated_at', 'DESC')->take(5)->get();

        $conversationArray = array_fill(0, $conversations->count(), null);
        $i = 0;
        $newConversation = 0;
        foreach ($conversations as $conversation) {
            $message = $conversation->lastMessage();
            $otheruser = $conversation->otheruser(Auth::user()->id);
            $conversationArray[$i] = $arrayName = array('id' => $conversation->id,
                                                    'message' => $message->preview(),
                                                    'messageid' => $message->id,
                                                    'url' => url('/message/'.$otheruser->id),
                                                    'fullname' => $otheruser->getFullname(),
                                                    'image' => $otheruser->getPhotoUrl(),
                                                    'status' => ($message->recipient_id == Auth::user()->id) ? $message->status : 'not yours',
                                                    'updated'=> $message->created_at);
            $i++;

            if($message->status == 'unread' && $message->recipient_id == Auth::user()->id)
            {
                $newConversation++;
            }
        }

        $response = array(
                'messages' => $conversationArray,
                'newConversation' => $newConversation
        );
        return Response::json( $response );
    }
    else
    {
        $arr = array();
        $response = array(
                'messages' => $arr,
                'newConversation' => 0
        );
        return Response::json( $response );
    }
    
});

Route::post('/message/ajax/readAllMessageNotifications', function()
{

    if(Auth::user()->hasInbox())
    {
        
        $conversations = Auth::user()->inbox()->orderBy('updated_at', 'DESC')->get();

        $conversationArray = array_fill(0, $conversations->count(), null);
        $i = 0;
        $newConversation = 0;
        foreach ($conversations as $conversation) {
            $message = $conversation->lastMessage();
            
            if($message->status == 'unread' && $message->recipient_id == Auth::user()->id)
            {
                $message->status = 'read';
                $message->save();
            }
        }

        $response = array(
                'messages' => $conversationArray,
                'newConversation' => $newConversation
        );
        return Response::json( $response );
    }
    else
    {
        $arr = array();
        $response = array(
                'messages' => $arr,
                'newConversation' => 0
        );
        return Response::json( $response );
    }
    
});

Route::post('/message/ajax/{id}', function($id)
{
    $conversation = Conversation::find($id); 

    foreach ($conversation->unreadMessages() as $unread) {
        if($unread->user_id != Auth::user()->id)
        {
            $unread->status = 'read';
            $unread->save();
        }
    }
    $otheruser = $conversation->otheruser(Auth::user()->id)->getFullname();
    $messagesArray = array_fill(0, $conversation->messages()->count(), null);
    $i = 0;
    foreach ($conversation->messages()->get() as $message) {
        $messagesArray[$i] = $arrayName = array('id' => $message->id,
                                                'content' => $message->content,
                                                'status' => $message->status,
                                                'user_id' => $message->user_id,
                                                'profileUrl' => url('/profile/'.$message->user_id),
                                                'userFullname' => $message->user->getFullname(),
                                                'conversation_id' => $message->conversation_id,
                                                'image' => $message->user->getPhotoUrl(),
                                                'created_at' => $message->created_at );
        $i++;
    }
    $response = array(
            'messages' => $messagesArray,
            'otheruser' => $otheruser
    );
 
    return Response::json( $response );
});



Route::post('/ajax/searchuser', function(){
    // Define Output HTML Formating
    $html = '';
    $html .= '<li class="result">';
    $html .= '<a class="resultusers" href="urlString" data-userid="user_id">';
    $html .= '<h3>fullnameString</h3>';
    $html .= '<span style="display:none">user_fullname</span>';
    $html .= '<h4>displaynameString</h4>';
    $html .= '</a>';
    $html .= '</li>';
    $search_string = preg_replace("/[^A-Za-z0-9]/", " ", Input::get('query'));
    //$search_string = $tutorial_db->real_escape_string($search_string);
    //$keyword = Input::get('query');
    
    // Check Length More Than One Character
    if (strlen($search_string) >= 1 && $search_string !== ' ') {
        
        // $result_array = User::where('displayname', 'LIKE', "%$search_string%")
        //                 ->orWhere('firstname', 'LIKE', "%$search_string%")
        //                 ->orWhere('lastname', 'LIKE', "%$search_string%")
        //                 ->take(5)
        //                 ->get();
        $result_array = User::where('firstname', 'LIKE', "%$search_string%")
                        ->orWhere('lastname', 'LIKE', "%$search_string%")
                        ->take(5)
                        ->get();

        // Check If We Have Results
        if ($result_array->count() != 0 ) {
            foreach ($result_array as $result) {

                // Format Output Strings And Hightlight Matches
                $displaynameArray = explode('@', $result->email);
                $display_displayname = '-'. substr($displaynameArray[0], -3).'@'.$displaynameArray[1];
                $display_fullname = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $result->firstname." ".$result->lastname );
                $userid = $result->id;
                $userfullname = $result->firstname." ".$result->lastname; 
                $display_url = '#';

                // Insert Name
                $output = str_replace('fullnameString', $display_fullname, $html);

                // Insert Function
                $output = str_replace('displaynameString', $display_displayname, $output);
                $output = str_replace('user_id', $userid, $output);
                $output = str_replace('user_fullname', $userfullname, $output);

                // Insert URL
                $output = str_replace('urlString', $display_url, $output);

                // Output
                echo($output);
            }
        }else{

            // Format No Results Output
            $output = str_replace('urlString', 'javascript:void(0);', $html);
            $output = str_replace('user_id', '', $output);
            $output = str_replace('user_fullname', '', $output);
            $output = str_replace('fullnameString', '<b>No Results Found.</b>', $output);
            $output = str_replace('displaynameString', '', $output);

            // Output
            echo($output);
        }
    }           
});

Route::post('/ajax/searchuserheader', function(){
    // Define Output HTML Formating
    $html = '';
    $html .= '<li class="result">';
    $html .= '<a class="resultusers" href="urlString" data-userid="user_id">';
    $html .= '<span class="image-wrap"><img src="profilePicture" /></span>';
    $html .= '<span class="result-wrap"><h3>fullnameString</h3>';
    $html .= '<span style="display:none">user_fullname</span>';
    $html .= '<h4>displaynameString</h4></span>';
    $html .= '</a>';
    $html .= '</li>';
    $search_string = preg_replace("/[^A-Za-z0-9]/", " ", Input::get('query'));
    //$search_string = $tutorial_db->real_escape_string($search_string);
    //$keyword = Input::get('query');
    
    // Check Length More Than One Character
    if (strlen($search_string) >= 1 && $search_string !== ' ') {
        
        // $result_array = User::where('displayname', 'LIKE', "%$search_string%")
        //                 ->orWhere('firstname', 'LIKE', "%$search_string%")
        //                 ->orWhere('lastname', 'LIKE', "%$search_string%")
        //                 ->take(5)
        //                 ->get();
        $userquery = DB::table('users')
                    ->select('users.id as id', 'users.firstname', 'users.lastname', DB::raw('Null as screenname'), DB::raw('Null as playsite'))
                    ->orWhere('users.firstname', 'LIKE', "%$search_string%")
                    ->orWhere('users.lastname', 'LIKE', "%$search_string%");
        $screennamequery = DB::table('screen_names')
                    ->select('screen_names.user_id as id', DB::raw('Null as firstname'), DB::raw('Null as lastname'), 'screen_names.username', 'screen_names.screen_name')
                    ->orWhere('screen_names.username', 'LIKE', "%$search_string%");

        $result_array = $userquery->union($screennamequery)->get();

        

        // Check If We Have Results
        if (count($result_array) != 0) {
            $userArray = array();
            foreach ($result_array as $result) {
                
                if(empty($userArray) || !in_array($result->id, $userArray))
                {

                    array_push($userArray, $result->id);

                    $user = User::find($result->id);
                    $screennameString = '';
                    $screennames = $user->screennames();
                    
                    foreach ($screennames->get() as $screenname) {
                        if($screenname->username != '')
                        {
                            $username = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $screenname->username);
                            $screennameString .= $screenname->screen_name.' : '.$username.', ';
                            
                        }
                        
                    }
                    if($screennameString == '')
                    {
                        $screennameString = "This user doesn't have screen name.";
                    }
                    else
                    {
                        $screennameString = rtrim($screennameString, " ");
                        $screennameString = rtrim($screennameString, ",");   
                    }
                    // Format Output Strings And Hightlight Matches
                    //$displaynameArray = explode('@', $user->email);
                    //$display_displayname = '-'. substr($displaynameArray[0], -3).'@'.$displaynameArray[1];
                    $display_fullname = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $user->firstname." ".$user->lastname );
                    $userid = $user->id;
                    $userfullname = $user->firstname." ".$user->lastname; 
                    $display_url = url('/profile/'.$result->id);
                    $profileUrl = $user->getPhotoUrl();
                    // Insert Name
                    $output = str_replace('fullnameString', $display_fullname, $html);

                    // Insert Function
                    $output = str_replace('displaynameString', $screennameString, $output);
                    $output = str_replace('user_id', $userid, $output);
                    $output = str_replace('user_fullname', $userfullname, $output);
                    $output = str_replace('profilePicture', $profileUrl, $output);

                    // Insert URL
                    $output = str_replace('urlString', $display_url, $output);

                    // Output
                    echo($output);
                }
            }
        }else{

            // Format No Results Output
            $output = str_replace('urlString', 'javascript:void(0);', $html);
            $output = str_replace('user_id', '', $output);
            $output = str_replace('user_fullname', '', $output);
            $output = str_replace('fullnameString', '<b>No Results Found.</b>', $output);
            $output = str_replace('displaynameString', '', $output);
            $output = str_replace('profilePicture', '', $output);

            // Output
            echo($output);
        }
    }           
});

//Static Pages
Route::get('about', function(){ return View::make('pages.about'); });
Route::get('contact', function(){ return View::make('pages.contact'); });
Route::get('privacy', function(){ return View::make('pages.privacy'); });
Route::get('terms', function(){ return View::make('pages.terms'); });
Route::post('termsandcondition/edit', 'PagesController@editTermsandCondition');
Route::get('termsandcondition', function(){ return View::make('pages.termsandcondition'); });



Route::get('packs', 'PackagesController@index');
Route::post('packs/create', 'PackagesController@store');
//Route::get('packs/{$id}/cancel', 'PackagesController@cancel');
Route::get('packs/cancel/{id}', array('as' => 'cancelpackages', 'uses' => 'PackagesController@cancel'));
Route::post('packages/ajax/getcomment', 'PackageCommentsController@getComments' );
Route::post('packages/ajax/retrievepackage', 'PackagesController@retrievePackage' );
Route::post('packages/ajax/sendcomment', 'PackageCommentsController@sendComments' );
Route::post('packages/ajax/gettournaments', 'SharkscopeTournamentsController@getTournaments' );
/*Route::post('packs/{$id}/cancel', function($id)
{
		$package = Package::find($id);
		$package->cancel = 1;
		$package->update();
		 
        return Redirect::to('users/profile');	
});*/


Route::get('cart', 'OrdersController@cart');
Route::post('cart/addtocart', 'OrdersController@addtocart');
Route::post('cart/order', 'OrdersController@store');


Route::get('users/userblock', 'UserblockController@getuserblock');

Route::post('/adduserblock/{id}', 'UserblockController@adduserblock');
Route::post('/canceluserblock/{id}/{blockid}', 'UserblockController@canceluserblock');

Route::get('blocklist', function()
{
	if(Auth::check())
    {
        $user = User::find( Auth::user()->id);
		$userblocks = Userblock::where('user_id','=',Auth::user()->id);	
        return View::make('pages.users.userblock')->with('selectedUser', $user)->with('userblocks', $userblocks);    
    }
    else
    {
        return Redirect::to('/');
    }
    //return View::make('pages.users.userblock');
});

