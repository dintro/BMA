<?php 
class UserblockController extends BaseController 
{
   	protected $layout = 'layouts.master';

   	public function __construct() {

	}

   	public function getuserblock() 
   	{
    	$this->layout->content = View::make('pages.users.userblock');
	}
	
	
	public function adduserblock($id)
	{
		$userblock = new Userblock;
		$userblock->user_id = Auth::user()->id;
		$userblock->userblock_id = $id;
		$userblock->save();
		
		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}
	public function canceluserblock($id, $blockid)
	{
		$userblock = Userblock::where('user_id',$id)->where('userblock_id',$blockid)->first();
		if(is_null($userblock))
		{
		 	$response = array(
            	'success' => 'not found'
        	);
		}else{
			$userblock->delete();
		}
			
		$response = array(
            'success' => 'success'
        );
 
    	return Response::json( $response );
	}
}

?>