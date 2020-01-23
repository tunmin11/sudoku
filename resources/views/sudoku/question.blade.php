<!DOCTYPE html>
<html>
<head>
	<title>Sudoku Solved!</title>

	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
</head>
<style type="text/css">
	tr>:nth-child(3), tr>:nth-child(6){ 
		border-right: 2px solid #adadad;
	}

	tr:nth-of-type(3), tr:nth-of-type(6){
		border-bottom: 2px solid #adadad;
	}


	
</style>
<body class="">
	<div class="container pt-5">
		<h3 class="col-12 text-center text-secondary py-2"><span class="font-weight-normal text-info">SUDOKU</span> Solver</h3>
		<form method="POST" action="{{ route('solution')}}">
			@csrf

			<input type="text" name="prev" value="{{ isset($answer['prev'])?$answer['prev']:''}}" >
			<table class="table col-4 offset-4">

					@for($x = 1; $x <= 9; $x++)
						<tr>
							@for($i = 1; $i <= 9; $i++)
								@php
									$cord="question2.0{$i}0{$x}";
								@endphp
								<td class="sudoku_input @php if($x === [1,2]){ echo 'bg-danger'; } @endphp" style="padding: 1px;">
									@if(isset($answer['cord']))
										<input type="number" value='{{ $answer["cord"]["0{$i}0{$x}"] }}' class=" p-1 text-center form-control " name="cord[{{'0'.$i.'0'.$x }}]" onInput="checkLength(1,this)" readonly="">	
									@else
									<input type="number" value="{{ config($cord) }}" max="9" min="1" name="cord[{{'0'.$i.'0'.$x }}]" class=" p-1 text-center form-control " onInput="checkLength(1,this)">
									@endif
								</td>
							@endfor
						</tr>
					@endfor


			</table>
			<div class="form-group col-4 offset-4">
				<button type="submit" class="btn btn-info float-right">Solve it</button>
			</div>
		</form>	
	</div>
</body>
<script type="text/javascript">
 
    function checkLength(len,ele){
    var fieldLength = ele.value.length;
    if(fieldLength <= len){
        return true;
    }
    else
    {
        var str = ele.value;
        str = str.substring(0, str.length - 1);
    	ele.value = str;
    }
    }
</script>
</html>