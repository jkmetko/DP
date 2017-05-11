@extends('layouts.master')

@section('content')
    <div id="predictions"></div>
    {{--<div id="predictions-update"></div>--}}
    <div id="errors"></div>
    <div id="training"></div>


    {{--CREATE CONTAINERS FOR PATTERNS--}}
            @foreach($patterns as $name => $measurements)
                <div class="row">
                    <div class="col-md-4">
                        <div id="pattern-{{ $name }}"></div>
                    </div>
                    <div class="col-md-4">
                        <div id="multiplicities-{{ $name }}"></div>
                        <p>AVG: {{ $multiplicities[$name]['AVG'] }}</p>
                        <p>COUNT: {{ $multiplicities[$name]['COUNT'] }}</p>
                    </div>
                    <div class="col-md-4">
                        <div id="probabilities-{{ $name }}"></div>
                        <p>AVG: {{ $probabilities[$name]['AVG'] }}</p>
                        <p>COUNT: {{ $probabilities[$name]['COUNT'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/Highcharts-5.0.10/code/js/modules/heatmap.js') }}"></script>
    <script src="{{ asset('assets/plugins/Highcharts-5.0.10/code/js/modules/exporting.js') }}"></script>
    <script src="{{ asset('assets/plugins/Highmaps-5.0.11/code/modules/map.js') }}"></script>
    <script src="{{ asset('assets/plugins/Highmaps-5.0.11/code/modules/data.js') }}"></script>


    <script>
        {{--SET GLOBAL TIME--}}
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });

        {{--PREDICTIONS CHART--}}
        Highcharts.chart('predictions', {
            chart: {
                zoomType: 'x',
                spacingBottom: 50
            },

            title: {
                text: 'Forecast vs Real data'
            },

            subtitle: {
                text: 'Source: BA-sum'
            },

            xAxis: {
                title: {
                    text: 'Time'
                }
            },

            yAxis: {
                title: {
                    text: 'Consumption'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            series: [{
                name: 'Real values',
                data: {!! json_encode($predictions[0]["data"]) !!}
            },{
                name: 'Forecasted',
                data: {!! json_encode($predictions[1]["data"]) !!}
            }]
        });

        {{--PREDICTIONS UPDATE CHART--}}
        // Create the chart
        {{--var chart = Highcharts.stockChart('predictions-update', {--}}
            {{--chart: {--}}
                {{--events: {--}}
                    {{--load: function () {--}}

                        {{--// set up the updating of the chart each 15 seconds--}}
                        {{--setInterval(function () {--}}
                            {{--updatePredictions('{{ csrf_token() }}', '{{ url('updatePredictions') }}');--}}
                        {{--}, 15000);--}}
                    {{--}--}}
                {{--}--}}
            {{--},--}}

            {{--rangeSelector: {--}}
                {{--buttons: [{--}}
                    {{--count: 1,--}}
                    {{--type: 'minute',--}}
                    {{--text: '1H'--}}
                {{--}, {--}}
                    {{--count: 5,--}}
                    {{--type: 'minute',--}}
                    {{--text: '5M'--}}
                {{--}, {--}}
                    {{--type: 'all',--}}
                    {{--text: 'All'--}}
                {{--}],--}}
                {{--inputEnabled: false,--}}
                {{--selected: 0--}}
            {{--},--}}

            {{--title: {--}}
                {{--text: 'Live random data'--}}
            {{--},--}}

            {{--exporting: {--}}
                {{--enabled: true--}}
            {{--},--}}

            {{--series: [{--}}
                {{--name: 'Real values',--}}
                {{--data: {!! json_encode($predictions[0]["data"]) !!}--}}
            {{--},{--}}
                {{--name: 'Forecasted',--}}
                {{--data: {!! json_encode($predictions[1]["data"]) !!}--}}
            {{--}]--}}
        {{--});--}}

        {{--ERRORS CHART--}}
        Highcharts.chart('errors', {
            chart: {
                zoomType: 'x',
                spacingBottom: 50
            },
            title: {
                text: 'Errors chart'
            },

            subtitle: {
                text: 'Source: BA-sum'
            },

            xAxis: {
                title: {
                    text: 'Time'
                }
            },

            yAxis: {
                title: {
                    text: 'Error'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            series: [{
                name: 'Error',
                data: {!! json_encode($errors["data"]) !!}
            }]
        }, function(chart){
            var label = this.renderer.label("MAPE: {{ $errors["MAPE"] }} <br>AVG Error: {{ $errors["AVG"] }}<br>COUNT: {{ $errors["COUNT"] }}")
                    .css({
                        width: '450px',
                        color: '#222',
                        fontSize: '16px'
                    })
                    .attr({
                        'stroke': 'silver',
                        'stroke-width': 2,
                        'r': 5,
                        'padding': 10
                    })
                    .add();
        });

        {{--TRAINING CHART--}}
        Highcharts.chart('training', {
            chart: {
                zoomType: 'x',
                spacingBottom: 50
            },
            title: {
                text: 'Training data'
            },

            subtitle: {
                text: 'Source: BA-sum'
            },

            yAxis: {
                title: {
                    text: 'Real consumption'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            series: [{
                name: 'Values',
                data: {!! json_encode($training) !!}
            }]
        });

        {{--GENERATE CHARTS JS--}}
        @foreach($patterns as $name => $measurements)
            Highcharts.chart('pattern-{{ $name }}', {
                chart: {
                    zoomType: 'x',
                    spacingBottom: 50
                },
                title: {
                    text: '{{ $name }} patterns'
                },

                subtitle: {
                    text: 'Source: BA-sum'
                },

                yAxis: {
                    title: {
                        text: 'Nrmalized value'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                series: [{
                    name: 'X',
                    data: {!! json_encode($measurements["X"]) !!}
                }, {
                    name: 'Y',
                    data: {!! json_encode($measurements["Y"]) !!}
                }]
            });
        @endforeach

        {{--GENERATE MULTIPLICITIES JS--}}
        @foreach($multiplicities as $name => $multiplicity)
            Highcharts.chart('multiplicities-{{ $name }}', {

            chart: {
                type: 'heatmap',
                margin: [60, 10, 80, 50]
            },

            title: {
                text: 'Multiplicity Heat Map',
                align: 'left',
                x: 40
            },

            subtitle: {
                text: 'Source: BA-sum',
                align: 'left',
                x: 40
            },

            xAxis: {
                type: 'category',
                tickPixelInterval: 20
            },

            yAxis: {
                title: {
                    text: null
                },
                labels: {
                    format: '{value}'
                },
                minPadding: 0,
                maxPadding: 0,
                startOnTick: false,
                endOnTick: false,
                tickWidth: 1
            },

            colorAxis: {
                stops: [
                    [0, '#e01515'],
                    [0.25, '#e07915'],
                    [0.5, '#e0cb15'],
                    [0.75, '#e0cb15'],
                    [1, '#37e015']
                ],
                startOnTick: false,
                endOnTick: false,
                {{--min: 0,--}}
                {{--max: {{ sqrt(count($multiplicity['data'])) }},--}}
                labels: {
                    format: '{value}'
                }
            },

            series: [{
                borderWidth: 1,
                nullColor: '#EFEFEF',
                colsize: 1, // one day
                tooltip: {
                    headerFormat: 'Multiplicity<br/>',
                    pointFormat: '{point.x} <br> {point.y}: <br><b>{point.value}</b>'
                },
                turboThreshold: Number.MAX_VALUE
                @if(count($multiplicity['data']) > 0)
                    ,data: {{ json_encode($multiplicity['data']) }}
                @endif
            }]
        });
        @endforeach

        {{--GENERATE PROBABILITIES JS--}}
        @foreach($probabilities as $name => $probability)
            Highcharts.chart('probabilities-{{ $name }}', {

            chart: {
                type: 'heatmap',
                margin: [60, 10, 80, 50]
            },

            title: {
                text: 'Probability Heat Map',
                align: 'left',
                x: 40
            },

            subtitle: {
                text: 'Source: BA-sum',
                align: 'left',
                x: 40
            },

            xAxis: {
                type: 'category',
                tickPixelInterval: 20
            },

            yAxis: {
                title: {
                    text: null
                },
                labels: {
                    format: '{value}'
                },
                minPadding: 0,
                maxPadding: 0,
                startOnTick: false,
                endOnTick: false,
                tickWidth: 1
            },

            colorAxis: {
                stops: [
                    [0, '#e01515'],
                    [0.25, '#e07915'],
                    [0.5, '#e0cb15'],
                    [0.75, '#e0cb15'],
                    [1, '#37e015']
                ],
                startOnTick: false,
                endOnTick: false,
                labels: {
                    format: '{value}%'
                }
            },

            series: [{
                borderWidth: 1,
                nullColor: '#EFEFEF',
                colsize: 1, // one day
                tooltip: {
                    headerFormat: 'Probability<br/>',
                    pointFormat: '{point.x} <br> {point.y}: <br><b>{point.value}%</b>'
                },
                turboThreshold: Number.MAX_VALUE
                @if(count($probability['data']) > 0)
                    ,data: {{ json_encode($probability['data']) }}
                @endif
            }]
        });
        @endforeach

    </script>

    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

@endsection