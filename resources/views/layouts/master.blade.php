<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>जिल्ला सरकारी वकील कार्यालय, काठमाण्डौ</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="Bootstrap News Template - Free HTML Templates" name="keywords">
        <meta content="Bootstrap News Template - Free HTML Templates" name="description">

        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap" rel="stylesheet">

        <!-- CSS Libraries -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="{{ asset('frontend/lib/slick/slick.css') }}" rel="stylesheet">
        <link href="{{ asset('frontend/lib/slick/slick-theme.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('frontend/css/nepaliDatePicker.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/toastr.min.css') }}">
        <!-- Template Stylesheet -->
        <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <!-- Buttons CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />

        @stack('styles')
    </head>

    <body>
        <!-- Top Bar Start -->
        @include('frontend.partials.topbar')
        <!-- Top Bar Start -->

        <!-- Nav Bar Start -->
       @include('frontend.partials.navbar')
        <!-- Nav Bar End -->

        <!-- Top News Start-->
       @yield('content')
        <!-- Top News End-->

        <!-- Footer Start -->
        @include('frontend.partials.footer')
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('frontend/lib/easing/easing.min.js') }}"></script>
        <script src="{{ asset('frontend/lib/slick/slick.min.js') }}"></script>

        <!-- Template Javascript -->
        <script src="{{ asset('frontend/js/main.js') }}"></script>
        <script src="{{ asset('frontend/js/nepaliDatePicker.min.js') }}"></script>
        <script src="{{ asset('frontend/js/custom-js.js') }}"></script>
        <script src="{{ asset('frontend/js/toastr.min.js') }}"></script>
        <script>
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "showDuration": "1000",
                "hideDuration": "1000",
                "timeOut": "1000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            @if(Session::has('success'))
            toastr.success('{{ Session::get('success')}}')
            @endif
            @if(Session::has('info'))
            toastr.info('{{ Session::get('info')}}')
            @endif
        </script>
        <!-- jQuery and DataTables JS -->
        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
        <!-- DataTables CSS -->
        <!-- jQuery -->
        {{-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> --}}

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <!-- Buttons JS and dependencies -->
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>

        <!-- For Excel export -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

        <!-- For PDF export -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

        <!-- Buttons for HTML5 export -->
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

        <!-- Print button -->
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
        $(document).ready(function() {

            const engToNep = s => String(s).replace(/[0-9]/g, d => '०१२३४५६७८९'[d]);

            $(document).ready(function () {
            $(".nep-number").on("keyup", function() {
                    $converted_string = engToNep($(this).val());
                    $(this).val($converted_string);
            })
            });
                $('.custom-select2').select2({
                    tags: true,
                    placeholder: "विकल्प खोज्नुहोस् वा नयाँ टाइप गर्नुहोस्"
                });

            $('.custom-select2').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();

                    let select = $(this);
                    let input = select.data('select2').dropdown.$search || select.data('select2').$selection.find('input.select2-search__field');
                    let value = input.val().trim();

                    if (value !== '') {
                        let exists = select.find('option').filter(function() {
                            return $(this).text().toLowerCase() === value.toLowerCase();
                        }).length;

                        if (!exists) {
                            let newOption = new Option(value, value, true, true);
                            select.append(newOption).trigger('change');
                        }
                        input.val('');
                    }
                }
            });
        });
             pdfMake.fonts = {
                Devnagari: {
                    normal: '{{ asset("frontend/fonts/NotoSansDevanagari-Regular.ttf") }}',
                    bold: '{{ asset("frontend/fonts/NotoSansDevanagari-Bold.ttf") }}'
                }
            };
        </script>
        @stack('datatable_scripts')
        @stack('scripts')
    </body>
</html>
