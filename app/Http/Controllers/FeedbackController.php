<?php namespace App\Http\Controllers;

use App\Feedback;
use Request;

class FeedbackController extends Controller {
	
	public function getIndex()
	{
		$fs = Feedback::orderBy('created_at','desc')->paginate(15);
		return view('feedback')->withFs($fs);
	}
	
	public function anyDelete($id)
	{
		Feedback::where('id','=',$id)->delete();
		return redirect('index');
	}
}

?>