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
							<th>size</th>
							<th></th>
						</thead>
						@foreach($files as $key => $file)
							<tr>
								<td>{{ $key }}</td>
								<td>{{ $file['name'] . $file['ext'] }}</td>
								<td>{{ $file['size'] }}</td>
								<td></td>
							</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
