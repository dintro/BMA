<?php

/**
* 
*/
class FaqsController extends BaseController
{
	public function showAddFaq()
	{
		if(Auth::check() && Auth::user()->isadmin)
		{
			return View::make('pages.addfaqs');
		}
		else
		{
			return Redirect::to('/');
		}
	}

	public function showEditFaq($id)
	{
		if(Auth::check() && Auth::user()->isadmin)
		{
			$faq = Faq::find($id);
			if(!is_null($faq))
			{
				return View::make('pages.editfaqs')
					->with('selectedFaq', $faq);
			}
			else
			{
				return Redirect::to('/faq');
			}
			
		}
		else
		{
			return Redirect::to('/');
		}
	}

	public function insertFaq()
	{
		$faq = new Faq;
		$faq->question = Input::get('question');
		$faq->answer = Input::get('answer');
		$faq->save();

		return Redirect::to('/faq')
            ->with('message', 'Add new FAQ success');
	}

	public function updateFaq($id)
	{
		$faq = Faq::find($id);
		if(!is_null($faq))
		{
			$faq->question = Input::get('question');
			$faq->answer = Input::get('answer');
			$faq->save();

			return Redirect::to('/faq')
	            ->with('message', 'Update FAQ success');
		}
		else
		{
			return Redirect::to('/faq');
		}
		
	}

	public function deleteFaq($id)
	{
		$faq = Faq::find($id);
		if(!is_null($faq))
		{
			$faq->delete();

			return Redirect::to('/faq')
	            ->with('message', 'Delete FAQ success');
		}
		else
		{
			return Redirect::to('/faq');
		}
	}
}