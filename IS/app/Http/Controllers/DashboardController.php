<?php

namespace App\Http\Controllers;

use App\Pattern;
use App\Prediction;
use App\Probability;
use App\Training;
use Illuminate\Http\Request;

use App\Http\Requests;

class DashboardController extends Controller
{
    public function show(){
//        dd($this->getPredictions());

        return view('dashboard.show', [
            "predictions"   => $this->getPredictions(),
            "errors"        => $this->getErrors(),
            "patterns"      => $this->getPatterns(),
            "training"      => $this->getTraining(),
            "probabilities" => $this->getProbabilities()
        ]);
    }

    private function getPredictions(){
        $predictions = Prediction::all();

        $realValues = [];
        $forecasted = [];

        foreach($predictions as $key => $attribute){
            $realValues[] = (float)$attribute->real_value;
            $forecasted[] = (float)$attribute->forecasted;

            $predictions[$key]->update([
                'ploted'    => 1
            ]);
            $predictions[$key]->save();
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
        $output = [];

        if(!Prediction::all()->isEmpty()){
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
        }else{
            $output = [
                "name"  => "Error",
                "data"  => [],
                "MAPE"  => 0,
                "AVG"   => 0,
                "COUNT" => 0
            ];
        }

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

    private function getTraining(){
        $training = Training::all();
        $output = [];

        foreach ($training as $data){
            $output[] = (float)$data->value;
        }

        return $output;
    }

    private function getProbabilities() {
        if(!Prediction::all()->isEmpty()) {
            $probabilities = [];
            $group = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            foreach ($group as $day) {
                foreach (Probability::where('day', $day)->get() as $probability) {
                    $probabilities[$day]['data'][] = [$probability->i, $probability->j, (float)$probability->probability];
                }

                $probabilities[$day]['X'] = Probability::where('day', $day)->groupBy('i')->get()->lists('i')->toArray();
                $probabilities[$day]['Y'] = Probability::where('day', $day)->groupBy('j')->get()->lists('j')->toArray();
            }

//        dd($probabilities);
        }
        else{
            $probabilities = [];
        }
        return $probabilities;
    }

    public function updatePredictions(){
        $predictions = Prediction::where('ploted', false)->get();

        $realValues = [];
        $forecasted = [];

        foreach($predictions as $key => $attribute){
            $realValues[] = (float)$attribute->real_value;
            $forecasted[] = (float)$attribute->forecasted;

            $predictions[$key]->update([
                'ploted'    => 1
            ]);
            $predictions[$key]->save();
        }


        $output[] = [
            "name" => "Real values",
            "data"  => $realValues
        ];

        $output[] = [
            "name" => "Forecasted",
            "data"  => $forecasted
        ];

        return json_encode($output);
    }
}
