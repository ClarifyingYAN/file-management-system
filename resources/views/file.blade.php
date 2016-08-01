@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>
				{{--<div class="panel-body">--}}
					{{--You are logged in!--}}
				{{--</div>--}}
				<div class="show-list">
					<table class="bordered">
						<thead>
							<th>#</th>
							<th>file name</th>
							<th>ext</th>
							<th>size</th>
						</thead>
						{{--@foreach($files as $file)--}}
							{{--<tr>--}}
								{{--<td></td>--}}
								{{--<td></td>--}}
								{{--<td></td>--}}
								{{--<td></td>--}}
							{{--</tr>--}}
						{{--@endforeach--}}
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
