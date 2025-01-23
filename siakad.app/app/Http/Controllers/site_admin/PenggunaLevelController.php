<?php
namespace App\Http\Controllers\site_admin;

use App\Http\Controllers\Controller;
use App\Level;
use Yajra\Datatables\Datatables;

class PenggunaLevelController extends Controller
{
    private $title = 'Level Pengguna';
    private $redirect = 'penggunalevel';
    private $folder = 'penggunalevel';
    private $class = 'penggunalevel';

    private $rules = [
        'level' => 'required',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        return view($folder . '.index', compact('title', 'redirect'));
    }

    public function getData()
    {
        $row = Level::select('*');
        return Datatables::of($row)
            ->make(true);
    }
}
