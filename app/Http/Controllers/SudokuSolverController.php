<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SudokuSolverController extends Controller
{
    protected $question = [];

    public function checkPossibility($cord, $greaterThan = 1)
    {
        $possible_array = [];
        $question = $this->question;
        $xCord = substr($cord,0,2); //x position
        $yCord = substr($cord,2,2); //y position

        $column = []; //numbers in its column

         for ($y=1; $y < 10 ; $y++)
         {
             $column[]=$question["{$xCord}0{$y}"];
         };

        $row = []; //numbers in its row
        for ($x=1; $x < 10 ; $x++)
        {
            $row[]=$question["0{$x}{$yCord}"];
        }

        $XYlines=array_merge($column,$row); //Numbers in XY line

        if($xCord >= 7 && $xCord <= 9) //Get x cord of 3x3 Grid
        {
            $gridx = [7,8,9];
        }
        elseif($xCord >= 4 && $xCord <= 6)
        {
            $gridx = [4,5,6];
        }
        elseif($xCord >= 1 && $xCord <= 3)
        {
            $gridx = [1,2,3];
        }

        if($yCord >= 7 && $yCord <= 9) //Get y cord of 3x3 Grid
        {
            $gridy = [7,8,9];
        }
        elseif($yCord >= 4 && $yCord <= 6)
        {
            $gridy = [4,5,6];
        }
        elseif($yCord >= 1 && $yCord <= 3)
        {
            $gridy = [1,2,3];
        }

        $grid = []; //Numbers in its 3x3 grids

        foreach ($gridx as $x) {
            foreach ($gridy as $y)
            {
                $grid[] = $question["0{$x}0{$y}"];
            }
        }

        $inValidNumbers =array_unique(array_merge($column,$row,$grid)); //Invalid numbers for this cord
        // echo "greaterThan => " . $greaterThan . "<br>";
        if($greaterThan >= 9) {
            $possible_array[$cord] = [];
            return $possible_array;
        }

        $sudoku_num = range($greaterThan, 9);
        $guessNumber = []; //Valid Numbers
        foreach ($sudoku_num as $number)
        {
            if (!in_array($number, $inValidNumbers))
            {
                $guessNumber[] = $number;
            }
        }
        $possible_array[$cord] = $guessNumber;
        return $possible_array;
    }


    public function isValid($possivle_num,$cord)
    {

        $question = $this->question;
        $xCord = substr($cord,0,2); //x position
        $yCord = substr($cord,2,2); //y position

        $column = []; //numbers in its column

         for ($y=1; $y < 10 ; $y++)
         {
             $column[]=$question["{$xCord}0{$y}"];
         };

        $row = []; //numbers in its row
        for ($x=1; $x < 10 ; $x++)
        {
            $row[]=$question["0{$x}{$yCord}"];
        }

        $XYlines=array_merge($column,$row); //Numbers in XY line

        if($xCord >= 7 && $xCord <= 9) //Get x cord of 3x3 Grid
        {
            $gridx = [7,8,9];
        }
        elseif($xCord >= 4 && $xCord <= 6)
        {
            $gridx = [4,5,6];
        }
        elseif($xCord >= 1 && $xCord <= 3)
        {
            $gridx = [1,2,3];
        }

        if($yCord >= 7 && $yCord <= 9) //Get y cord of 3x3 Grid
        {
            $gridy = [7,8,9];
        }
        elseif($yCord >= 4 && $yCord <= 6)
        {
            $gridy = [4,5,6];
        }
        elseif($yCord >= 1 && $yCord <= 3)
        {
            $gridy = [1,2,3];
        }

        $grid = []; //Numbers in its 3x3 grids

        foreach ($gridx as $x) {
            foreach ($gridy as $y)
            {
                $grid[] = $question["0{$x}0{$y}"];
            }
        }

        $inValidNumbers =array_unique(array_merge($column,$row,$grid)); //Invalid numbers for this cord

        if(!in_array($possivle_num,$inValidNumbers)){
            return true;
        }
        return false;
    }

    public function checkFillableNumber($question)
    {
        foreach ($question as $cord => $value)
        {
            if(in_array("",$question))
            {
                $solution = $this->question;
                return view('sudoku.question',compact('solution'));
            }
        }
    }


    public function solveIt(Request $request)
    {
        ini_set('max_execution_time', 0);

        $question = $request->cord;
        $this->question = $question;

        $guessPossibilityForAllCells = $this->guessPossibilityForAllCells();

        $toSolveCord = isset($request->toSolveCord) ? $request->toSolveCord : array_keys($guessPossibilityForAllCells)[0];

        if($this->countAnsweredCell() >= 81) {
            $answer = $question;
            $nextToSolveCord = "";
        }

        $i = 0;
        while ($this->countAnsweredCell() < 81) {
            $i++;
            if($i > 100000) {
                break;
            }

            do {
                $solutionForCell = $this->solutionForCell($toSolveCord);
                $toSolveCord = $solutionForCell['prevCord'];
                $possibleNumber = $solutionForCell['possibleNumber'];
                $currentCord = $solutionForCell['currentCord'];
                $question[$currentCord] = $possibleNumber;

                // print_r($solutionForCell);
                // echo $currentCord;
                // echo "<br>";
            } while ( is_null($possibleNumber) );

            $toSolveCord = $nextToSolveCord = $solutionForCell['nextToSolveCord'];

            $this->question = $answer['cord'] = $question;
        }

        $answeredCell = $this->countAnsweredCell();

        return view('sudoku.question',compact('answer', 'nextToSolveCord', 'answeredCell'));
    }

    public function countAnsweredCell()
    {
        return collect($this->question)->filter(function($question) {
            return !is_null($question);
        })->count();
    }

    public function solutionForCell($toSolveCord)
    {
        // echo "<br>solutionForCell => $toSolveCord <br>";
        $question = $this->question;

        $guessPossibilityForAllCells = $this->guessPossibilityForAllCells();

        $greaterThan = $question[$toSolveCord] + 1;

        $possible_num = $this->checkPossibility($toSolveCord, $greaterThan);

        $guessSolutionCordsArray = array_keys($guessPossibilityForAllCells);
        $index = array_search($toSolveCord, $guessSolutionCordsArray);

        $prevIndex = $index > 0 ? $index - 1 : $index;
        $prevCord = $guessSolutionCordsArray[$prevIndex];

        if( count($possible_num[$toSolveCord]) <= 0 ) {
            $possibleNumber = null;
            $nextToSolveCord = null;
            $this->question[$toSolveCord] = null;
        } else {
            $possibleNumber = $possible_num[$toSolveCord][0];
            $nextIndex = $index < (count($guessSolutionCordsArray) - 1)  ? $index + 1 : $index;
            $nextToSolveCord = $guessSolutionCordsArray[$nextIndex];
        }

        return ['prevCord' => $prevCord,
                'currentCord' => $toSolveCord,
                'nextToSolveCord' => $nextToSolveCord,
                'possibleNumber' => $possibleNumber];
    }

    public function guessPossibilityForAllCells()
    {
        if (session()->exists('solution')) {
           return json_decode(session('solution'), 1);
        }

        $solution = [];
        foreach ($this->question as $cord => $value)
        {
            if ($value == "")
            {
                $possivle_num = $this->checkPossibility($cord);
                foreach ($possivle_num as $key => $arr)
                {
                    $solution[$key] = $arr;
                }
            }
        }

        session(['solution' => json_encode($solution)]);
        return $solution;
    }
}
