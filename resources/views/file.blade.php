@extends('app')

@section('content')
	<div class="panel-heading">
		<span class="panel-left-name">File</span>
		<span class="panel-left-btns">
			{!! Form::open(['action'=>'FileController@upload', 'files'=>true, 'class'=>'form']) !!}
				{!! Form::file('file') !!}
				{!! Form::hidden('shortPath', $dir['shortPath']) !!}
				{!! Form::submit('upload', ['class'=>'btns']) !!}
			{!! Form::close() !!}
			{!! Form::open(['action'=>'FileController@make_folder', 'class'=>'form']) !!}
				{!! Form::text('folderName') !!}
				{!! Form::hidden('shortPath', $dir['shortPath']) !!}
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
			@foreach($dir['files'] as $key => $file)
				<tr>
					<td>{{ $key }}</td>
					<td>{{ $file['name'] }}</td>
					<td>{{ human_size($file['size']) }}</td>
					<td>
						@if($file['type'] == '')
							{{ 'unknown' }}
						@else
							{{ $file['type'] }}
						@endif
					</td>
					<td>
						{!! Form::open(['url'=>'/file/deleteFile', 'method'=>'delete','class'=>'form']) !!}
							{!! Form::hidden('fileName', $file['pathName']) !!}
							{!! Form::submit('Del', ['class'=>'btns']) !!}
						{!! Form::close() !!}
						{!! Form::open(['url'=>'/file/download', 'method'=>'post', 'class'=>'form']) !!}
							{!! Form::hidden('pathName', $file['pathName']) !!}
							{!! Form::submit('Download', ['class'=>'btns']) !!}
						{!! Form::close() !!}
{{--						<a href="{{ URL::action('FileController@download', ['pathName'=>$file['pathName']]) }}">download</a>--}}
					</td>
				</tr>
			@endforeach
			@foreach($dir['folders'] as $key => $folder)
				<tr>
					<td>{{ $key }}</td>
					<td><a href="{{ URL::action('FileController@index', ['folder'=>$folder['pathName']]) }}">{{ $folder['name'] }}</a></td>
					<td>{{ human_size($folder['size']) }}</td>
					<td>
						{{ 'dir' }}
					</td>
					<td>
						{!! Form::open(['url'=>'/file/deleteFolder', 'method'=>'delete','class'=>'form']) !!}
							{!! Form::hidden('folderName', $folder['pathName']) !!}
							{!! Form::submit('Del', ['class'=>'btns']) !!}
						{!! Form::close() !!}
						{{--<a href="{{ URL::action('FileController@download', ['pathName'=>$folder['pathName']]) }}">download</a>--}}
					</td>
				</tr>
			@endforeach
		</table>
	</div>
@endsection
