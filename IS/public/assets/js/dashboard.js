function updatePredictions(token, url) {
    $.ajax({
        type: "GET",
        headers: {
            'X-CSRF-Token': '"' + token + "'"
        },
        url: url,
        dataType:'json',
        success: function (output) {
            handleData(output);
        }
    });

    function handleData(data){


        //UPDATE REAL VALUES
        series = chart.series[0];
        realValues = data[0].data;

        if(realValues.length > 0){
            realValues.forEach(function(value) {
                series.addPoint(value, true, true);
            });
        }

        //UPDATE FORECASTED VALUES
        series = chart.series[1];
        forecastedValues = data[1].data;

        if(forecastedValues.length > 0){
            realValues.forEach(function(value) {
                series.addPoint(value, true, true);
            });
        }
    }
}