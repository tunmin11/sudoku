<?php

namespace App\Http\Controllers;

use App\Sudoku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SolutionController extends Controller
{

	public function test()
	{
  //   	if (!session()->exists('test')) {
		//    session(['test' => "new"]);
		// }
		// dd(session()->exists('test'));

		Session::forget('solution');

		   return redirect()->route('questions.index');
		
	
	}
    public function byCell(Request $request)
    {

   //  	// dd($request->all());

   //  	$question = $request->cord;
   //  	$cords = array_keys($question); 

   //  	// $cord = "0505";
   //  	foreach ($cords as $cord) {
   //  		if($question[$cord] == "")
   //  			// dd($cord);
   //  		{





   //  	$xCord = substr($cord,0,2);
   //  	$yCord = substr($cord,2,2);

   //  	// dd($xCord,$yCord);

   //  	$column = [];


   //  	for ($y=1; $y < 10 ; $y++) { 
   //  		if ($question["{$xCord}0{$y}"] === "") {
   //  			continue;
   //  		}
   //  		$column[]=$question["{$xCord}0{$y}"];
   //  	}

   //  	$row = [];
   //  	for ($x=1; $x < 10 ; $x++) { 
   //  		if ($question["0{$x}{$yCord}"] === "") {
   //  			continue;
   //  		}
   //  		$row[]=$question["0{$x}{$yCord}"];
   //  	}

   //  	$array_num=array_merge($column,$row);

   //  	// dd($row);
   //  	// dd($xCord);


   //  	if($xCord >= 7 && $xCord <= 9)
   //  	{
   //  		$testingx = [7,8,9];
   //  	}
   //  	elseif($xCord >= 4 && $xCord <= 6)
   //  	{
   //  		$testingx = [4,5,6];
   //  	}
   //  	elseif($xCord >= 1 && $xCord <= 3)
   //  	{
   //  		$testingx = [1,2,3];
   //  	}

   //  	// dd($testingx);

   //  	if($yCord >= 7 && $yCord <= 9)
   //  	{
   //  		$testingy = [7,8,9];
   //  	}
   //  	elseif($yCord >= 4 && $yCord <= 6)
   //  	{
   //  		$testingy = [4,5,6];
   //  	}
   //  	elseif($yCord >= 1 && $yCord <= 3)
   //  	{
   //  		$testingy = [1,2,3];
   //  	}

   //  	// dd($testingy);


   //  	$square = [];
   //  	foreach ($testingx as $x) {
   //  		foreach ($testingy as $y) {

   //  			if ($question["0{$x}0{$y}"] === "") {
			// 		continue;
			// 	}
   //  			$square[] = $question["0{$x}0{$y}"];
   //  			// dd("0{$x}0{$y}");
   //  			// dd($question["0{$x}0{$y}"]);

   //  		}
   //  	}



   //  	$result =array_unique(array_merge($column,$row,$square));
   //  	// dd($result);

    	


    	// $sudoku_num=[1,2,3,4,5,6,7,8,9];
   //  	$guest_numbers =[];

   //  	foreach ($sudoku_num as $number) {
   //  		if (!in_array($number, $result)) {
   //  				// dd($number);
   //  				$guest_numbers []=$number;
   //  		}
   //  	}
   //  	// dd($guest_numbers);

   //  	if(count($guest_numbers) == 1)
   //  	{
			// $question[$cord] = $guest_numbers[0];
   //  	}





   //  	// if (in_array("",$question)) 
   //  	// {
	  //   // 	foreach ($guest_numbers as $guest_number) 
	  //   // 	{
		 //   //  	$question[$cord] = strval($guest_number);
	  //   // 	}
	  //   // 	if(in_array("",$question)){

	  //   // 	}
	  //  	// }

    	
   //  	// dd($question[$cord]);
   //  	// foreach ($guest_numbers as $guest_number) {
	  //   //         $solution[$cord] = "$guest_number";
	  //   // }

   //  	 	}
   //  	}

   //  	$solution = $question;
   //  	// dd($solution);	
	 	return view('sudoku.question',compact('solution'));


    }
}
