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
		<h3 class="col-12 text-center text-secondary py-2">Let Make <span class="font-weight-normal text-info">SUDOKU</span></h3>
		<form method="POST" action="{{ route('questions.store')}}">
			@csrf
			<table class="table col-4 offset-4">
					@php 
						$row=[3,6,9];

					@endphp
					@for($x = 1; $x <= 9; $x++)
						<tr>
							@for($i = 1; $i <= 9; $i++)
							@php
								$cord="question.0{$x}0{$i}";
							@endphp
								<td class="sudoku_input @php if($x === [1,2]){ echo 'bg-danger'; } @endphp" style="padding: 1px;">
									<input type="number" value="{{ config($cord) }}" max="9" min="1" name="cord[{{'0'.$x.'0'.$i }}]" class=" p-1 text-center form-control " onInput="checkLength(1,this)">
								</td>
							@endfor
						</tr>
					@endfor
			</table>
			<div class="form-group col-4 offset-4">
				<button type="submit" class="btn btn-info float-right">Create Question</button>
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