@extends('layouts.app')
@section('title', $title)

@section('content')

    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">{{ $mahasiswa->nim }} {{ $mahasiswa->nama }}</span>
            <div class="panel-heading-controls">
                <a href="{{ route('kompre_mahasiswa') }}" class="btn btn-sm btn-primary"><i
                        class="fa fa-chevron-circle-left"></i> Kembali</a>
            </div>
        </div>

        <form method="POST" accept-charset="UTF-8" class="form-horizontal form-bordered" autocomplete="off">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <div class="panel-body no-padding-hr">
                @for ($i = 1; $i <= $jumlahPenguji; $i++)
                    @if ($penguji[$i]['status'])
                        <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                            <div class="row">
                                <label class="col-sm-4 control-label">Penguji {{ $i }} ({{ $penguji[$i]['dosen'] }}) :</label>
                                <div class="col-sm-4">
                                    <input class="form-control" id="penguji_{{ $i }}" required="true"
                                        autofocus="true" name="penguji_{{ $i }}" type="number"
                                        value="{{ $penguji[$i]['nilai'] }}">
                                </div>
                            </div>
                        </div>
                    @endif
                @endfor
                <div class="row">
                    <label class="col-sm-4 control-label"></label>
                    <div class="col-sm-4" style="margin-left: 10px">
                        <button type="submit" name="save" id="save" class="btn btn-success btn-flat">
                            <i class="fa fa-floppy-o"></i> Simpan</button>
                    </div>
                </div>
            </div>



        </form>
    </div>
@endsection
