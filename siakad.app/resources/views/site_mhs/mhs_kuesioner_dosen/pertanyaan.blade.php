<div class="panel-body no-padding-hr">
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-condensed table-striped">
            <thead>
                <tr>
                    <th class="text-left col-md-1">Kode Dosen</th>
                    <input type="hidden" name="dosen_id" value="{{ $dosen->id }}">
                    <th class="text-left col-md-3">: {{ $dosen->kode }}</th>
                </tr>

                <tr>
                    <th class="text-left col-md-1">Nama Dosen</th>
                    <th class="text-left col-md-3">: {{ $dosen->nama }}</th>
                </tr>
            </thead>
        </table>

        <label for="" class="col-md-2">Mata Kuliah :</label>
        <br><br>
        <ol>
            @foreach ($matakuliah as $mk)
                <li>{{ $mk->nama_mk }}</li>
            @endforeach
        </ol>
        <hr>
        <br />

        @php $no=1; @endphp

        <ol style="font-size:16px;">
            @foreach ($pertanyaan as $tanya)
                <li>
                    {{-- <input type="hidden" name="pertanyaan[]" value="{{$tanya->id}}"> --}}
                    <span class="lbl">{{ $tanya->pertanyaan }}</label>
                        <ol>
                            @foreach ($tanya->pilihan as $pilih)
                                <li> <label class="radio">
                                        {{-- <input type="hidden" name="pertanyaan_id_{{$tanya->id}}[jawaban_id_{{$tanya->id}}]" value="{{$pilih->id}}">
					<input type="hidden" name="pertanyaan_id_{{$tanya->id}}[jawab_{{$tanya->id}}]" value="{{$pilih->pilihan}}"> --}}
                                        <input type="radio" name="pertanyaan[nilai_{{ $tanya->id }}]"
                                            value="{{ $tanya->id }}#{{ $pilih->id }}#{{ $pilih->nilai }}#{{ $pilih->pilihan }}"
                                            class="px">
                                        <span class="lbl">{{ $pilih->pilihan }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ol>
                </li>
            @endforeach
        </ol>
    </div>
</div>
