@extends('layouts.master')

@section('content')
    <div id="predictions"></div>
    <div id="errors"></div>
    <div id="training"></div>


    {{--CREATE CONTAINERS FOR PATTERNS--}}
    <div class="row">
            <div class="col-md-12">
                @foreach($patterns as $name => $measurements)
                    <div id="{{ $name }}"></div>
                @endforeach
            </div>
        </div>
@endsection

@section('js')
    <script>
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

            yAxis: {
                title: {
                    text: 'Normalized consumption'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            plotOptions: {
                series: {
                    pointStart: 2010
                }
            },

            series: [{
                name: 'Real values',
                data: {!! json_encode($predictions[0]["data"]) !!}
            },{
                name: 'Forecasted',
                data: {!! json_encode($predictions[1]["data"]) !!}
            }]
        });

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

            plotOptions: {
                series: {
                    pointStart: 2010
                }
            },

            series: [{
                name: 'Real values',
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

            plotOptions: {
                series: {
                    pointStart: 2010
                }
            },

            series: [{
                name: 'Values',
                data: {!! json_encode($training) !!}
            }]
        });

        {{--GENERATE CHARTS JS--}}

            @foreach($patterns as $name => $measurements)
                Highcharts.chart('{{ $name }}', {
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

                    plotOptions: {
                        series: {
                            pointStart: 2010
                        }
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

    </script>
@endsection