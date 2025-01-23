<?php
namespace App\Http\Controllers\site_admin;

use App\PT;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Kota;
use Auth;

class PTController extends Controller
{
  private $title = 'Identitas Perguruan Tinggi';
  private $redirect = 'pt';
  private $folder = 'pt';
  private $class = 'pt';

  private $rules = [
      'kode' => 'required|string|max:20',
      'judul' => 'required|string|max:100',
      'nama' => 'required|string|max:255',
      'kota_id' => 'required',
      'alamat' => 'required|string|max:255',
      'email' => 'required|email|string|max:50',
      'website' => 'required|string|max:50',
  ];

  public function index()
  {
      $title = $this->title;
      $redirect = $this->redirect;
      $folder = $this->folder;
      $data = PT::findOrFail(1);
      $list_kota = Kota::orderBy('province_id')->get();
      return view($folder.'.index',compact('title','redirect','folder','data','list_kota'));
  }

  public function store(Request $request)
  {
 
    $this->validate($request,$this->rules);

    $destinationPath = 'img';



    $data = PT::findOrFail($request->id);
    $data->kode = $request->kode;
    $data->judul = strtoupper($request->judul);
    $data->nama = $request->nama;
    $data->sk = $request->sk;
    $data->alamat = $request->alamat;
    $data->kota_id = $request->kota_id;
    $data->telp = $request->telp;
    $data->email = $request->email;
    $data->website = $request->website;
    $data->nidn_ketua = $request->nidn_ketua;
    $data->nama_ketua = $request->nama_ketua;
    $data->user_id = Auth::user()->id;

    if(!empty($request->logo))
    {
      $extLogo = $request->logo->getClientOriginalExtension();
      $LogoName = 'logo.'.$extLogo;
      $data->logo = $LogoName;
      $request->logo->move($destinationPath, $LogoName);
    }

    if(!empty($request->favicon))
    {
      $extFav = $request->favicon->getClientOriginalExtension();
      $FavName = 'favicon.'.$extFav;
      $data->favicon = $FavName;
      $request->favicon->move($destinationPath, $FavName);
    }

    if(!empty($request->background))
    {
      $extBg = $request->background->getClientOriginalExtension();
      $BgName = 'background.'.$extBg;
      $data->background = $BgName;
      $request->background->move($destinationPath, $BgName);
    }

    $data->save();

    alert()->success('Update Data Success',$this->title);
    return redirect($this->redirect);
  }

}
