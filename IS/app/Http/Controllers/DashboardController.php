<?php

namespace App\Http\Controllers;

use App\Pattern;
use App\Prediction;
use Illuminate\Http\Request;

use App\Http\Requests;

class DashboardController extends Controller
{
    public function show(){
//        dd($this->getPredictions());

        return view('dashboard.show', [
            "predictions"   => $this->getPredictions(),
            "errors"        => $this->getErrors(),
            "patterns"      => $this->getPatterns()
        ]);
    }

    private function getPredictions(){
        $predictions = Prediction::all();

        $realValues = [];
        $forecasted = [];

        foreach($predictions as $key => $attribute){
            $realValues[] = (float)$attribute->real_value;
            $forecasted[] = (float)$attribute->forecasted;
        }


        $output[] = [
            "name" => "Real values",
            "data"  => $realValues
        ];

        $output[] = [
            "name" => "Forecasted",
            "data"  => $forecasted
        ];

        return $output;
    }

    private function getErrors()
    {
        $errors = [];
        $mape = 0;
        $sum = 0;
        foreach (Prediction::all() as $key => $value){
            $error = abs(($value->real_value - $value->forecasted) / $value->real_value) * 100;
            $errors[] = $error;
            $mape += $error / 100;
            $sum += $error;
        }

        $MAPE = (100/count($errors)) * $mape;
        $AVG = $sum / count($errors);
        $COUNT = count($errors);

        $output = [
            "name"  => "Error",
            "data"  => $errors,
            "MAPE"  => round($MAPE, 2),
            "AVG"   => round($AVG, 2),
            "COUNT" => $COUNT
        ];

        return $output;
    }

    private function getPatterns(){
        $patterns = [];

        foreach (Pattern::all() as $pattern){
            foreach ($pattern->cells as $cell){
                foreach ($cell->measurements as $measurement){
                    $patterns[$pattern->day][$pattern->name][] = (float)$measurement->consumption;
                }
            }
        }

//        dd($patterns);
        $XGroup = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $YGroup = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $output = [];

        //SORT DAYS
        foreach($XGroup as $v)
        {
            //If the value in the template exists as a key in the actual array.. (condition)
            if(array_key_exists($v, $patterns))
            {
                $output[$v]=$patterns[$v]; //The value is assigned to the new array and the key of the actual array is assigned as a value to the new array
            }
        }

//        dd($output);

        return $output;
    }
}