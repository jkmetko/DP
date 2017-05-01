<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <title>DP - Big Data in Energetics</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}"/>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-3.3.7-dist/bootstrap-3.3.7-dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-3.3.7-dist/bootstrap-3.3.7-dist/css/bootstrap-theme.css') }}">

    <!-- HighCharts -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/Highcharts-5.0.10/code/css/highcharts.css') }}">

    <!-- PER PAGE CSS -->
    @yield('css')
<body>
    <main class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @yield('content')
            </div>
        </div>
    </main>


    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jQuery-3.2.1/jquery-3.2.1.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/plugins/bootstrap-3.3.7-dist/bootstrap-3.3.7-dist/js/bootstrap.min.js') }}"></script>

    <!-- HighCharts -->
    <script src="{{ asset('assets/plugins/Highcharts-5.0.10/code/js/highcharts.js') }}"></script>

    @yield('js')
</body>
</html>