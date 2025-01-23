<?php
namespace App\Http\Controllers\site_admin;

use App\KuesionerPertanyaan;
use App\KuesionerPertanyaanPilihan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;


class KuesionerPertanyaanController extends Controller
{
  private $title = 'Kuesioner Pertanyaan';
  private $redirect = 'kuesionerpertanyaan';
  private $folder = 'kuesionerpertanyaan';
  private $class = 'kuesionerpertanyaan';

  private $rules = [
      'pertanyaan' => 'required|string|max:100',
  ];

  public function index()
  {
      $title = $this->title;
      $redirect = $this->redirect;
      $folder = $this->folder;
      return view($folder.'.index',compact('title','redirect'));
  }

  public function getData()
  {
    $row = KuesionerPertanyaan::select('*');
    return Datatables::of($row)
    ->addColumn('action', function ($row) {
      return '
      <div class="btn-group">
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right">
            <li><a onclick="copyData('.$row->id.')" >Copy</a></li>
            <li class="divider"></li>
            <li><a href="' .url('/'.$this->class.'/'.$row->id.'/edit'). '">Edit</a></li>
            <li class="divider"></li>
            <li><a onclick="deleteForm(' .$row->id. ')">Delete</a></li>
        </ul>
      </div>';
    })
    ->rawColumns(['action','txt_aktif'])
    ->make(true);
  }

  public function create()
  {
    $title = $this->title;
    $redirect = $this->redirect;
    $folder = $this->folder;

    return view($folder.'.create',compact('title','redirect','folder'));
  }

  public function edit($id)
  {
    $data = KuesionerPertanyaan::findOrFail($id);
    $title = $this->title;
    $redirect = $this->redirect;
    $folder = $this->folder;
    return view($folder.'.edit',compact('data','title','redirect','folder'));
  }

  public function store(Request $request)
  {
    $this->validate($request,$this->rules);
    $aktif = !empty($request->aktif)?'Y':'T';

    $data = new KuesionerPertanyaan;
    $data->pertanyaan = $request->pertanyaan;
    $data->aktif = $aktif;
    $data->user_id = Auth::user()->id;
    $data->save();

    $count = count($request->input['pilihan']);
    if($count>0)
    {
      $i = 0;
      for($i=0;$i<$count;$i++)
      {
        $pilihan = new KuesionerPertanyaanPilihan;
        $pilihan->kuesioner_pertanyaan_id = $data->id;
        $pilihan->pilihan = $request->input['pilihan'][$i];
        $pilihan->nilai = $request->input['nilai'][$i];
        $pilihan->user_id = Auth::user()->id;
        $pilihan->save();
      }
    }
    alert()->success('Create Data Success',$this->title);
    return redirect($this->redirect);
  }

  public function update(Request $request, $id)
  {

    $this->validate($request,$this->rules);

    $aktif = !empty($request->aktif)?'Y':'T';

    $data =  KuesionerPertanyaan::where('id',$id)->first();
    $data->pertanyaan = $request->pertanyaan;
    $data->aktif = $aktif;
    $data->user_id = Auth::user()->id;
    $data->save();

    $count = count($request->input['pilihan']);

    if($count>0)
    {
      KuesionerPertanyaanPilihan::where('kuesioner_pertanyaan_id',$data->id)->delete();
      for($i=0;$i<$count;$i++)
      {
        $pilihan = new KuesionerPertanyaanPilihan;
        $pilihan->kuesioner_pertanyaan_id = $data->id;
        $pilihan->pilihan = $request->input['pilihan'][$i];
        $pilihan->nilai = $request->input['nilai'][$i];
        $pilihan->user_id = Auth::user()->id;
        $pilihan->save();
      }
    }
	
    alert()->success('Update Data Success',$this->title);
    return redirect($this->redirect);
  }

  public function destroy($id)
  {
    $data = KuesionerPertanyaan::findOrFail($id);
    KuesionerPertanyaanPilihan::where('kuesioner_pertanyaan_id',$data->id)->delete();
    $data->delete();
    return response()->json([
      'title' => 'Delete Data Success',
      'text' => $this->title.' '.$data->nama,
      'type' => 'success'
    ]);
  }

  public function copyData($id)
  {
    $source = KuesionerPertanyaan::findOrFail($id);

    $data = new KuesionerPertanyaan;
    $data->pertanyaan = $source->pertanyaan;
    $data->aktif = $source->aktif;
    $data->user_id = Auth::user()->id;
    $data->save();

    $source_pilihan = KuesionerPertanyaanPilihan::where('kuesioner_pertanyaan_id',$id)->get();
    foreach ($source_pilihan as $row) {
      $pilihan = new KuesionerPertanyaanPilihan;
      $pilihan->kuesioner_pertanyaan_id = $data->id;
      $pilihan->pilihan = $row->pilihan;
      $pilihan->nilai = $row->nilai;
      $pilihan->user_id = Auth::user()->id;
      $pilihan->save();
    }

    return response()->json([
      'title' => 'Copy Data Success',
      'text' => $this->title.' '.$data->nama,
      'type' => 'success'
    ]);
  }
}