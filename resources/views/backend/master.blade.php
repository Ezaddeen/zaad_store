<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @yield('title', 'Dashboard') | {{ readConfig('site_name') }}
    </title>

    <link rel="shortcut icon" href="{{ assetImage(readconfig('favicon_icon')) }}" type="image/svg+xml">

    <link href="{{ assetImage(readconfig('favicon_icon_apple')) }}" rel="apple-touch-icon">
    <link href="{{ assetImage(readconfig('favicon_icon_apple')) }}" rel="apple-touch-icon" sizes="72x72">
    <link href="{{ assetImage(readconfig('favicon_icon_apple')) }}" rel="apple-touch-icon" sizes="114x114">
    <link href="{{ assetImage(readconfig('favicon_icon_apple')) }}" rel="apple-touch-icon" sizes="144x144">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">
    {{-- datatable --}}
    <link rel="stylesheet" href="{{ asset('assets/css/datatable/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable/buttons.dataTables.min.css') }}">
    {{-- custom style --}}
    <link rel="stylesheet" href="{{ asset('css/custom-style.css') }}">

    <style>
        .image-upload-container {
            border: 2px dashed #8b9ee9;
            /* Dashed border color */
            border-radius: 8px;
            background-color: #f8f9fa;
            /* Light background color */
            display: flex;
            justify-content: center;
            /* Center the content */
            align-items: center;
            /* Center the content vertically */
            width: 100%;
            /* Make the container full width of its parent */
            height: 200px;
            /* Fixed height */
            cursor: pointer;
            /* Indicate clickability */
        }

        .thumb-preview {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            /* Prevents overflow */
        }

        #thumbnailPreview {
            max-width: 100%;
            max-height: 100%;
            /* Ensure it fits within the container */
            object-fit: cover;
            /* Maintain aspect ratio while covering the box */
        }

        .upload-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #8b9ee9;
            /* Text color */
            text-align: center;
        }

        .upload-text i {
            font-size: 24px;
            /* Icon size */
            margin-bottom: 5px;
            /* Space between icon and text */
        }
    </style>
    @stack('style')

    
    @viteReactRefresh
    @vite('resources/js/app.jsx')

</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed">

    <x-simple-alert />

    <div class="wrapper">

        @include('backend.layouts.navbar')
        <aside class="main-sidebar elevation-4 sidebar-light-lightblue text-right">
            <a href="{{ route('frontend.home') }}" class="brand-link">
                <img src="{{ assetImage(readconfig('site_logo')) }}" alt="Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ readConfig('site_name') }}</span>
            </a>

            @include('backend.layouts.sidebar')
            </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                @yield('title')
                            </h1>
                        </div></div></div></div>
            <section class="content">
                <div class="container-fluid">

                    @yield('content')
                    </div>
                </section>
            </div>
        @include('backend.layouts.footer')

    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
    <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    {{-- custom script --}}
    <script src="{{ asset('js/custom-script.js') }}"></script>
    <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>

    {{-- datatable --}}
    <script src="{{ asset('assets/js/datatable/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/dataTables.buttons.min.js') }}"></script>

    @stack('script')
</body>

</html>