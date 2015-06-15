<?php

/**
* 
*/
class PagesController extends BaseController
{
	public function editTermsandCondition()
	{
		$type = Input::get('type');
		$page = Page::where('type', $type)->first();
		if(!is_null($page))
		{
			$page->content = Input::get('content');
			$page->save();

			return Redirect::to('/termsandcondition')
	            ->with('message', 'Update Page success');
		}
		else
		{
			$page = new Page;
			$page->type = $type;
			$page->content = Input::get('content');
			$page->save();	

			return Redirect::to('/termsandcondition')
	            ->with('message', 'Update Page success');
		}

		
	}

}