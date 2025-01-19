@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">@yield('title')</span>
        </div><br>

        <div class="note {{ $tgl >= $buka_form->tgl_mulai && $tgl <= $buka_form->tgl_selesai ? 'note-success' : 'note-danger' }}">
            <h3 class="note-title">
                <center><b>Perhatian !!! </b></center>
            </h3>
			
            @if ($tgl >= $buka_form->tgl_mulai && $tgl <= $buka_form->tgl_selesai)
                <h3 class="note-title"> <b>
					<center><?= str_replace('Input', 'Pengisian', $buka_form->nama) ?> Telah Dibuka <br>
						Mulai Tanggal {{ date('d F Y', strtotime($buka_form->tgl_mulai)) }} s/d
						{{ date('d F Y', strtotime($buka_form->tgl_selesai)) }}
					</center></b>
				</h3>
            @else
                @if ($tgl >= $buka_form->tgl_selesai)
                    <h3>
						<b><center> Mohon Maaf, Pengisian KRS Sudah Ditutup !!! </center></b>
					</h3>
                @else
                    <h3>
						<b><center> Mohon Maaf, Pengisian KRS Belum Dibuka !!! </br>
						Mulai Dibuka Tanggal {{ date('d F Y', strtotime($buka_form->tgl_mulai)) }} 
						s/d {{ date('d F Y', strtotime($buka_form->tgl_selesai)) }} </center></b>
					</h3>
                @endif
            @endif

            <!--
                Mulai Tanggal {{ tgl_nojam($buka_form->tgl_mulai) }} (00:00:00) s/d
                {{ tgl_nojam($buka_form->tgl_selesai) }} (00:00:00)</h3>
            -->

        </div>

        <!-- <h4>@yield('title') -->

        {!! Form::open([
            'route' => $redirect . '.store',
            'class' => 'form-horizontal form-bordered',
            'autocomplete' => 'off',
        ]) !!}
		
        {{ csrf_field() }}

        @include($folder . '.form')
        {!! Form::close() !!}
    </div>
@endsection
