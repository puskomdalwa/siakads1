<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class EditPasswordController extends Controller {
	private $title	  = 'Edit Password';
	private $redirect = 'editpassword';
	private $folder	  = 'editpassword';
	private $class 	  = 'editpassword';

	private $rules = [
		'password' => 'required|string|min:6|confirmed',
	];

	public function index(){
		$id		  = Auth::user()->id;
		$data	  = User::where('id',$id)->first();
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;
		
		return view($folder.'.index',compact('title','redirect','data'));
	}

	public function update(Request $request, $id){
		$this->validate($request,$this->rules);

		$data = User::findOrFail($id);
		$data->password = bcrypt($request->password);
		$data->keypass  = $request->password;
		$data->save();

		alert()->success('Edit Password Success',$this->title);
		return back();
	}
}
