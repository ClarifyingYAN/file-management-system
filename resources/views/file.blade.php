@extends('app')

@section('content')
	<div class="panel-heading">
		<span class="panel-left-name">File</span>
		<span class="panel-left-btns">

			{!! Form::open(['action'=>'FileController@make_dir', 'class'=>'form']) !!}
				{!! Form::text('folderName') !!}
				{!! Form::submit('New Folder', ['class'=>'btns']) !!}
			{!! Form::close() !!}
		</span>
	</div>
	{{--<div class="panel-body">--}}
	{{--You are logged in!--}}
	{{--</div>--}}
	<div class="show-list">
		<table class="bordered">
			<thead>
			<th>#</th>
			<th>file name</th>
			<th>size</th>
			<th>type</th>
			<th>operate</th>
			</thead>
			@foreach($files as $key => $file)
				<tr>
					<td>{{ $key }}</td>
					<td>{{ $file['name'] . $file['ext'] }}</td>
					<td>{{ $file['size'] }}</td>
					<td>
						@if( $file['ext'] != '')
							{{ 'file' }}
						@else
							{{ 'dir' }}
						@endif
					</td>
					<td>
						{!! Form::open(['action'=>'FileController@delete_file', 'class'=>'form']) !!}
							{!! Form::hidden('fileName', $file['name'].$file['ext']) !!}
							{!! Form::submit('Del', ['class'=>'btns']) !!}
						{!! Form::close() !!}
					</td>
				</tr>
			@endforeach
		</table>
	</div>
@endsection
