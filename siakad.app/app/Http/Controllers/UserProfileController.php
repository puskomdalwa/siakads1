<?php
namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Image;

class UserProfileController extends Controller
{

    private $title = 'Profile User';
    private $redirect = 'userprofile';
    private $folder = 'userprofile';
    private $class = 'userprofile';

    private $rules = [
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    public function index()
    {
        $id = Auth::user()->id;
        $data = User::where('id', $id)->first();
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        return view($folder . '.index',
            compact('title', 'redirect', 'data')
        );
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        $image = $request->file('image');
        $input['imagename'] = $id . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path('/picture_users');
        $img = Image::make($image->getRealPath());

        $img->resize(200, 200, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $input['imagename']);

        $data = User::findOrFail($id);
        $data->picture = $input['imagename'];
        $data->save();

        alert()->success('Update Data Success', $this->title);
        return back();
    }
}
