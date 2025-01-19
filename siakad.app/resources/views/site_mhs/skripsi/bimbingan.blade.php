@extends('layouts.app')
@section('title',$title)
@section('content')

    <div class="panel-heading panel-danger panel-dark">
        <span class="panel-title">@yield('title')</span>
    </div>
    <div class="panel-body">
        <div class="note note-success">
            <h4 class="note-title"> {!! @$judul->judul !!} </h4>
            Tanggal di ACC : {{ @tgl_jam($judul->updated_at) }}
        </div>
        <p>Pembimbing : </p>
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">NIDN</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">HP</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                $no=1;    
                @endphp
                @foreach ($data_pembimbing as $pembimbing)
                    <tr>
                        <td class="text-center"> {{ $no++ }} </td>
                        <td class="text-center">{{ @$pembimbing->dosen->nidn }}</td>
                        <td>{{ @$pembimbing->dosen->nama }}</td>
                        <td class="text-center"> {{ @$pembimbing->dosen->hp }} </td>
                        <td class="text-center"> {{ @$pembimbing->dosen->email }} </td>
                        <td class="text-center"> {{ @$pembimbing->jabatan }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
    <div class="panel-footer text-center text-success">
        Status Pengajuan Skripsi Proses {{ $pengajuan->status }}.
    </div>


@endsection


@push('scripts')
<script>
   
</script>
@endpush

