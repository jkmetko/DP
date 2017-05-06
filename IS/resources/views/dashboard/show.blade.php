@extends('layouts.master')

@section('content')
    <div id="predictions"></div>
    <div id="predictions-update"></div>
    <div id="errors"></div>
    <div id="training"></div>


    {{--CREATE CONTAINERS FOR PATTERNS--}}
            @foreach($patterns as $name => $measurements)
                <div class="row">
                    <div class="col-md-6">
                        <div id="pattern-{{ $name }}"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="probabilities-{{ $name }}"></div>
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

            rangeSelector: {
                buttons: [{
                    count: 1,
                    type: 'minute',
                    text: '1M'
                }, {
                    count: 5,
                    type: 'minute',
                    text: '5M'
                }, {
                    type: 'all',
                    text: 'All'
                }],
                inputEnabled: false,
                selected: 0
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

        {{--PREDICTIONS UPDATE CHART--}}
        // Create the chart
        var chart = Highcharts.stockChart('predictions-update', {
            chart: {
                events: {
                    load: function () {

                        // set up the updating of the chart each 15 seconds
                        /*setInterval(function () {
                            updatePredictions('{{ csrf_token() }}', '{{ url('updatePredictions') }}');
                        }, 15000);*/
                    }
                }
            },

            rangeSelector: {
                buttons: [{
                    count: 1,
                    type: 'minute',
                    text: '1H'
                }, {
                    count: 5,
                    type: 'minute',
                    text: '5M'
                }, {
                    type: 'all',
                    text: 'All'
                }],
                inputEnabled: false,
                selected: 0
            },

            title: {
                text: 'Live random data'
            },

            exporting: {
                enabled: true
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

        {{--GENERATE PROBABILITIES JS--}}
        @foreach($probabilities as $name => $probability)
            Highcharts.chart('probabilities-{{ $name }}', {
                chart: {
                    type: 'heatmap',
                    marginTop: 40,
                    marginBottom: 80,
                    plotBorderWidth: 1
                },


                title: {
                    text: 'Sales per employee per weekday'
                },

                xAxis: {
                    categories: {!! json_encode($probability['X']) !!}
                },

                yAxis: {
                    categories: {!! json_encode($probability['Y']) !!},
                    title: null
                },

                colorAxis: {
                    min: 0,
                    minColor: '#FFFFFF',
                    maxColor: '#00b6ff'
                },

                legend: {
                    align: 'right',
                    layout: 'vertical',
                    margin: 0,
                    verticalAlign: 'top',
                    y: 25,
                    symbolHeight: 280
                },

                tooltip: {
                    formatter: function () {
                        return '<b>' + this.series.xAxis.categories[this.point.x] + '</b> sold <br><b>' +
                                this.point.value + '</b> items on <br><b>' + this.series.yAxis.categories[this.point.y] + '</b>';
                    }
                },

                series: [{
                    name: 'Sales per employee',
                    borderWidth: 1,
                    data: {!! json_encode($probability['data']) !!},
                    dataLabels: {
                        enabled: true,
                        color: '#000000'
                    }
                }]
            });
        @endforeach

    </script>

    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

@endsection