<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .login {
            min-height: 100vh;
        }

        .bg-image {
            background-image: url('https://i.ytimg.com/vi/V9jh8hUWvII/maxresdefault.jpg');
            background-size: cover;
            background-position: center;
        }

        .login-heading {
            font-weight: 300;
        }

        .btn-login {
            font-size: 0.9rem;
            letter-spacing: 0.05rem;
            padding: 0.75rem 1rem;
        }

        #copyBtn {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container-fluid ps-md-0">
        <div class="row g-0">
            <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
            <div class="col-md-8 col-lg-6">
                <div class="login d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9 col-lg-8 mx-auto">
                                <h3 class="login-heading mb-4">KRS OTOMATIS</h3>

                                <!-- Sign In Form -->
                                <form id="form" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" placeholder="Start"
                                                    name="start" id="start" value="1" required
                                                    autocomplete="off">
                                                <label for="floatingStart">Start</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" placeholder="Limit"
                                                    name="limit" id="limit" value="5" required
                                                    autocomplete="off">
                                                <label for="floatingEnd">Limit</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" placeholder="Maks"
                                                    id="maks" readonly value="{{ $jumlahMahasiswa }}"
                                                    autocomplete="off">
                                                <label for="floatingMaks">Maks</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2"
                                            type="submit" id="submitBtn">Submit</button>
                                    </div>

                                </form>
                                <!-- Button trigger modal -->
                                <div class="d-grid">
                                    <button type="button"
                                        class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2"
                                        data-bs-toggle="modal" data-bs-target="#modalLaporan">
                                        Laporan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalLaporan" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Laporan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="laporanContent"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Note: Months are zero-based in JavaScript.
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
        }

        function now() {
            return formatDate(new Date());
        }
    </script>
    <script>
        $(document).ready(function() {

            $('#start').change(function(e) {
                cekStart();
            });
            $('#form').submit(function(e) {
                e.preventDefault();
                let start = parseInt($('#start').val());
                let maks = parseInt($('#maks').val());
                let limit = parseInt($('#limit').val());

                if (start > maks) {
                    alert('START idak boleh lebih dari MAKS');
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('testing.krsStore') }}",
                    data: new FormData($(this)[0]),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#submitBtn').attr('disabled', true);
                        $('#submitBtn').html(
                            '<div class="spinner-border spinner-border-sm text-light"></div>'
                        );
                    },
                    success: function(response) {
                        let message = response.message;

                        let laporan = '<span>' + now() + ' - ' +
                            `${start} - ${limit}` + '</span>';
                        laporan += '<ol>';
                        Object.entries(message).forEach(([key, value]) => {
                            laporan += `<li>`;
                            laporan += `<span><b>${value.mahasiswa}</b></span>`;
                            laporan += `<ol>`;
                                if (value.mk != null) {
                                    value.mk.forEach(mk => {
                                        laporan += `<li>${mk}</li>`;
                                    });
                                }
                            laporan += `</ol>`;
                            laporan += `</li>`;
                        });

                        laporan += '</ol>'
                        $('#laporanContent').append(`<li>${laporan}</li><br>`);

                        $('#start').val(start + limit);
                        $('#submitBtn').attr('disabled', false);
                        $('#submitBtn').html('Submit');
                        if (start + limit < maks) {
                            document.getElementById('submitBtn').click();
                        }
                    },
                    error: function() {
                        $('#submitBtn').attr('disabled', false);
                        $('#submitBtn').html('Submit');
                    }
                });

            });

        });

        function cekStart() {
            let start = parseInt($('#start').val());
            let limit = parseInt($('#limit').val());
            let maks = parseInt($('#maks').val());

            if (start + limit > maks) {
                $('#start').val(maks - limit);
            }

            if (start < 0) {
                $('#start').val(0);
            }
        }
    </script>
</body>

</html>
