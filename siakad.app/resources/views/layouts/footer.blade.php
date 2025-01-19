 <!-- Get jQuery from Google CDN -->
 <!--[if !IE]> -->
 {{-- <script type="text/javascript"> window.jQuery || 
		document.write('<script src="{{asset('assets/javascripts/jquery.min.js')}}">'+"<"+"/script>"); 
		</script> --}}
 <!-- <![endif]-->

 <!--[if lte IE 9]>
  <script type="text/javascript">
      window.jQuery ||
          document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">' + "<" +
              "/script>");
  </script>
 <![endif]-->

 <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
 <script src="https://unpkg.com/smooth-scrollbar@8.5.2/dist/smooth-scrollbar.js"></script>
 <script>
     var Scrollbar = window.Scrollbar;
     Scrollbar.init(document.querySelector('#main-menu'));
 </script>
 {{-- <script>
     var ScrollbarS = window.Scrollbar;
     ScrollbarS.init(document.querySelector('#content-wrapper'));
 </script> --}}

 <!-- LanderApp's javascripts -->
 <script src="{{ asset('assets/javascripts/jquery.min.js') }}"></script>
 <script src="{{ asset('assets/javascripts/bootstrap.min.js') }}"></script>
 <script src="{{ asset('assets/javascripts/landerapp.min.js') }}"></script>
 <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>

 <!-- DataTables  & Plugins -->
 <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
 <script src="{{ asset('/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
 <script src="{{ asset('/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
 <script src="{{ asset('/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
 <script src="{{ asset('/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
 <script src="{{ asset('/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
 <script src="{{ asset('/plugins/jszip/jszip.min.js') }}"></script>
 <script src="{{ asset('/plugins/pdfmake/pdfmake.min.js') }}"></script>
 <script src="{{ asset('/plugins/pdfmake/vfs_fonts.js') }}"></script>
 <script src="{{ asset('/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
 <script src="{{ asset('/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
 <script src="{{ asset('/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

 {{-- scrollbar --}}
 <script src="{{ asset('/js/simple-scrollbar.min.js') }}"></script>
 <script>
     SimpleScrollbar.initEl(document.querySelector("#main-menu"));
 </script>


 <!-- Intro (Tourguide) -->
 <script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>

 {{-- My script  --}}
 <script src="{{ asset('/js/myscript.js') }}"></script>

 <script type="text/javascript">
     $(window).load(function() {
         $('.preloader').fadeOut('slow');
     });
     window.LanderApp.start(init);

     $(".select2").select2();
     const tour = new Shepherd.Tour({
         useModalOverlay: true,
         defaultStepOptions: {
             cancelIcon: {
                 // enabled: true
             },
             classes: 'shepherd-theme-custom',
             scrollTo: {
                 behavior: 'smooth',
                 block: 'center'
             }
         }
     });

     @php
         $level = !empty(Auth::user()->level->level) ? strtolower(Auth::user()->level->level) : null;
     @endphp
     @if ($level == 'baak (hanya lihat)')
         $('a:contains("Create")').remove();
         $('a:contains("Edit")').remove();
         $('a:contains("Update")').remove();
         $('a:contains("Simpan")').remove();
         $('a:contains("Reset Password")').remove();
         $('button:contains("Simpan")').remove();
         $('button:contains("Update")').remove();
         $('button:contains("Set Non Aktif")').remove();
         $('button:contains("Tambah")').remove();
         $('button:contains("Hapus")').remove();
         $('button:contains("Upload")').remove();

         if ("{{ Route::currentRouteName() }}" != "nilai.index") {
             $.fn.dataTable.defaults.drawCallback = function(settings) {
                 $('.btn-group:contains("Klik")').append(
                     '<div id="hanya_lihat"><span class="badge badge-warning">Hanya Lihat</span></div>');
                 $('.btn-group:has(button:contains("Klik")) > ul').remove();
                 $('.btn-group button:contains("Klik")').remove();
                 $('td:has(#hanya_lihat)').addClass('text-center');

                 $('td:contains("Isi Nilai")').append(
                     '<div id="hanya_lihat"><span class="badge badge-warning">Hanya Lihat</span></div>');
                 $('a:contains("Isi Nilai")').remove();


                 $('td:has(select)').append(
                     '<div id="hanya_lihat"><span class="badge badge-warning">Hanya Lihat</span></div>');
                 $('td.text-center select').remove();
                 $('#' + settings.sTableId).on('responsive-display.dt', function(e, datatable, row, showHide,
                     update) {
                     $('.btn-group:contains("Klik")').append(
                         '<div id="hanya_lihat"><span class="badge badge-warning">Hanya Lihat</span></div>'
                     );
                     $('.btn-group:has(button:contains("Klik")) > ul').remove();
                     $('.btn-group button:contains("Klik")').remove();

                     $('td:contains("Isi Nilai")').append(
                         '<div id="hanya_lihat"><span class="badge badge-warning">Hanya Lihat</span></div>'
                     );
                     $('a:contains("Isi Nilai")').remove();

                     $('span.dtr-data select').remove();
                 });
             };
         }
     @endif

     //Initialize Select2 Elements
     $('select:not(.normal)').each(function() {
         $(this).select2();
     });
 </script>
 @stack('scripts')
 </body>

 </html>
