<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SudokuSolverController extends Controller
{
	protected $question = [];

    public function checkPossibility($cord)
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
    	$sudoku_num=[1,2,3,4,5,6,7,8,9];
    	$guest_numbers =[]; //Valid Numbers

    	foreach ($sudoku_num as $number)
    	{
    		if (!in_array($number, $inValidNumbers)) 
    		{
    				$guest_numbers []=$number;
    		}
    	}
    	$possible_array[$cord] = $guest_numbers;
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

    	$question = $request->cord;
    	$this->question = $question;
    	$n = [1,2,3,4,5,6,7,8,9];
    	$status = False;

    	$test = [1,2,3,4];

    	$solution = [];
    	dd(json_decode($request->prev)->cord);
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
    		// dd(session()->exists('solution'));
    	// dd($solution);
	    	if (!session()->exists('solution')) {
			   session(['solution' => json_encode($solution)]);
			   return redirect()->route('question');
			}

	
    
   


    	foreach ($question as $cord => $value)
    	{
    		if ($value == "") 
    		{
    			$possible_num = $this->checkPossibility($cord);	
    			// dd($possible_num);
				for ($i=0; $i <= count($possible_num) ; $i++) 
				{ 
					if (empty($possible_num[$cord][0]))  // if empty number to fill
					{
						// dd(1);
						$prev_cord = json_decode($request->prev)->cord; //get prev cord
						$used_number = json_decode($request->prev)->number; //get used number for this cord
						$solution = json_decode(Session::get('solution'),1); //get possible numbers for all cords 
						
						//array filter to get numbers grater than used number for prev cord
						$unused_possible_num = array_values(array_filter($solution[$prev_cord],function($num)use($used_number){ return $num > $used_number; })); 
						

						// dd($unused_possible_num);

						if (empty($unused_possible_num)) //if prev cord have empty number to fill 
						{
							//back to the last cord used befor prev cord and try again
							$solution_keys = array_keys($solution); 
							$target_array_key  = array_search($prev_cord, $solution_keys)-1; 
							$target_cord = $solution_keys[$target_array_key];
							$prev_unused_possible_num = array_values(array_filter($solution[$target_cord],function($num)use($question,$target_cord){ return $num > $question[$target_cord]; }));
							// dd($target_cord);

							for($key=$target_array_key+1; $key < count($solution_keys) ; $key++) { 

								$question[$solution_keys[$key]] = "";
								// dd($key);
								// dd($solution_keys[$key]);
							}

							// dd($question);
							$question[$prev_cord] = "";

							$prev_target_cord = $solution_keys[array_search($target_cord, $solution_keys)-1];
							// dd($question[$prev_target_cord]);

							for ($z=0; $z < count($prev_unused_possible_num) ; $z++) 
							{ 
							
										// dd($prev_unused_possible_num[$z],$target_cord);
								// dd($this->isValid([$prev_unused_possible_num[$z]],$target_cord));
								if ($this->isValid([$prev_unused_possible_num[$z]],$target_cord))
								{
				    					$question[$target_cord] = $prev_unused_possible_num[$z];
										$answer['prev'] = json_encode(['cord'=>$prev_target_cord,'number'=>$question[$prev_target_cord]]);
										break;

				    						
									}
										// dd($answer);
							}

								// dd($prev_unused_possible_num);

						}
						elseif (count($unused_possible_num)>0)
						{
							for ($y=0; $y < count($unused_possible_num) ; $y++) 
							{ 
								// dd($prev_cord);
								// dd(count($unused_possible_num));

								if ($this->isValid($unused_possible_num[$y],$prev_cord))
									{
				    					$question[$prev_cord] = $unused_possible_num[$y];
										$answer['prev'] = json_encode(['cord'=>$prev_cord,'number'=>$unused_possible_num[$y]]);


									}
							}
						}

					}
					elseif (!empty($possible_num[$cord][0])) 
					{
						if ($this->isValid($possible_num[$cord][$i],$cord))
						{
	    					$question[$cord] = $possible_num[$cord][$i];
							$answer['prev'] = json_encode(['cord'=>$cord,'number'=>$possible_num[$cord][$i]]);
	    					break;			
						}
						else
						{
						
						}					

					}

					

				}

				break;
    		}
    	}
    


    	$answer['cord'] = $question;
    	// dd($answer);
    	return view('sudoku.question',compact('answer'));



    }
}
