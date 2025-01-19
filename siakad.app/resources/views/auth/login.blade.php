@php $pt = App\PT::first(); @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - {{config('app.name')}} - {{!empty($pt->judul) ? $pt->judul:''}}</title>
    <!-- Bootstrap 5 CDN Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
	<link rel="icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <!-- Custom CSS -->
    <style>
		/* Google Poppins Font CDN Link */
		@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");

		* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
		}

		/* Variables */
		:root {
		--primary-font-family: "Poppins", sans-serif;
		--light-white: #f5f8fa;
		--gray: #5e6278;
		--gray-1: #e3e3e3;
		}
		body {
		font-family: var(--primary-font-family);
		font-size: 14px;
		background-image: url("{{ asset('img/'.$pt->background)}}");
		background-size: cover;
		height: 100vh;
		background-repeat: no-repeat;
		display: flex;
		justify-content: center;
		align-items: center;
		}

		/* Main CSS */
		.wrapper .logo img {
		max-width: 100%;
		margin-left: 0px;
		}
		.wrapper input {
		background-color: var(--light-white);
		border-color: var(--light-white);
		color: var(--gray);
		}
		.wrapper input:focus {
		box-shadow: none;
		}
		.wrapper .submit_btn {
		padding: 15px;
		font-weight: 500;
		}
		.wrapper .login_with {
		padding: 15px;
		font-size: 15px;
		font-weight: 500;
		transition: 0.3s ease-in-out;
		}
		.wrapper .submit_btn:focus,
		.wrapper .login_with:focus {
		box-shadow: none;
		}
		.wrapper .login_with:hover {
		background-color: var(--gray-1);
		border-color: var(--gray-1);
		}
		.wrapper .login_with img {
		max-width: 8%;
		}
		#signin-form_id{
			width: max-content;
		}
		.logo{
			width: 300px !important;
			text-align: center;
		}
	</style>
</head>

<body>
    <section class="wrapper">
        <div class="container">
                <form id="signin-form_id" class="rounded bg-white transparent shadow py-4 px-4" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

					<div class="logo">
                        <img decoding="async" src="{{asset('/assets/demo/uiidalwa.png')}}" id="logo" class="img-fluid" alt="Logo" />
                        {{-- <img decoding="async" src="{{asset('img/logo.png')}}" class="img-fluid" alt="Logo" /> --}}
                    </div>

                    <div class="form-floating my-3">
                        <input type="text" class="form-control {{ $errors->has('email') ? ' has-error' : '' }}" name="email" id="email" value="{{ old('email') }}" 
						required autofocus placeholder="Username/Kode Dosen/NIM" />
                        <label for="floatingInput">Username</label>
						@if ($errors->has('email'))
							<span class="text-danger">
							<strong>{{ $errors->first('email') }}  </strong></span>
						@endif
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control {{ $errors->has('password') ? ' has-error' : '' }}" id="password" name="password" 
						required placeholder="Password" />
                        <label for="floatingPassword">Password</label>
						@if ($errors->has('password'))
							<span class="text-danger">
							<strong>{{ $errors->first('password') }}</strong></span>
						@endif
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" onclick="myFunction()" id="flexCheckDefault" />
                        <label class="form-check-label" for="flexCheckDefault">
                            Show Password
                        </label>
                    </div>
                    <button type="submit" style="background-color: #143464; color: #fff"
                        class="btn submit_btn w-100 my-4">
                        Masuk
                    </button>
                    {{-- <a href="index.html" style="text-decoration: none">Kembali ke halaman utama</a> --}}
					<div class="row text-center">
						<div class="col-12">
							<span>Copyright &copy; {{!empty($pt->judul) ? $pt->judul : ''}}; 2020-<?php echo date('Y');?>. <br/>
								Development : Dalwa-IT</span>
						</div>
					</div>
                </form>
        </div>
    </section>
    <script>
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
	<script>
		var getEmail = "{{request()->get('email')}}"
		var getPass  = "{{request()->get('pass')}}"
		
		@php
		if(isset($_GET['email']) && isset($_GET['pass'])){ @endphp
			document.getElementById('email').value	  = "{{$_GET['email']}}"
			document.getElementById('password').value = "{{$_GET['pass']}}"
			document.getElementById('signin-form_id').submit()
			@php
		}
		@endphp
	</script>
</body>

</html>
