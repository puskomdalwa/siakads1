@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('timeline/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endpush

<div class="container" id="tracking-status-container">
    <div class="card">
        <div class="order-tracking-wrapper" id="tracking-status">
            <div class="order-tracking">
                @foreach ($listStatus as $item)
                    <div class="step {{ $item['completed'] ? 'completed' : '' }}">
                        <div class="circle"><i class="fa fa-circle" aria-hidden="true"></i></div>
                        <div class="line"></div>
                        <div class="text">{{ $item['status'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        window.addEventListener('scroll', function() {
            var scrollY = window.scrollY;
            var breakPoint = 1 - (scrollY * 0.004);

            if (breakPoint < 0.2) {
                $('#tracking-status-container').addClass('animate__animated animate__fadeIn');
                $('#tracking-status-container').addClass('main-header-scroll');
            } else {
                $('#tracking-status-container').removeClass('animate__animated animate__fadeIn');
                $('#tracking-status-container').removeClass('main-header-scroll');
            }
        });
        $(document).ready(function() {
            Scrollbar.init(document.querySelector('#tracking-status'));
        });
    </script>
@endpush
