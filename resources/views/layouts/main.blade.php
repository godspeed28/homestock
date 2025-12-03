<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('styles.css') }}" rel="stylesheet">
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />


    <!-- Custom styles for this template-->
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">

</head>

<body id="page-top">

    <div id="wrapper">

        @include('components.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                @include('components.topbar')

                <div class="container-fluid">
                    @yield('content')
                </div>

            </div>

            @include('components.footer')

        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('sbadmin/js/demo/datatables-demo.js') }}"></script>

    <script>
        function setupSearch(inputId, resultsId, formId) {
            const searchInput = document.getElementById(inputId);
            const resultsContainer = document.getElementById(resultsId);
            const formElement = document.getElementById(formId);

            if (!searchInput) return; // supaya ga error di mobile/desktop

            searchInput.addEventListener('keyup', function() {
                let query = this.value;

                if (query.length > 0) {
                    fetch(`/ajax-search?query=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsContainer.innerHTML = data.html;
                            resultsContainer.style.display = 'block';
                        });
                } else {
                    resultsContainer.style.display = 'none';
                }
            });

            // klik di luar untuk sembunyikan
            document.addEventListener('click', function(e) {
                if (!formElement.contains(e.target) && !resultsContainer.contains(e.target)) {
                    resultsContainer.style.display = 'none';
                }
            });
        }

        // Desktop
        setupSearch('globalSearchInput', 'searchResults', 'globalSearchForm');

        // Mobile
        setupSearch('globalSearchInputMobile', 'searchResultsMobile', 'globalSearchFormMobile');
    </script>


    @yield('scripts')

</body>

</html>
