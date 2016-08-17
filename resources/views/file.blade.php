@extends('app')

@section('content')
	<div class="panel-heading">
		<span class="panel-left-name">File</span>
	</div>
	<div>
		<!-- upload form -->
		{!! Form::open(['action'=>'FileController@upload', 'files'=>true, 'class'=>'form-inline', 'id'=>'upload-form']) !!}
			<div class="form-group">
				{!! Form::file('file', ['class'=>'exampleInputFile']) !!}
				{!! Form::hidden('shortPath', $dir['shortPath']) !!}
			</div>
		{!! Form::submit('upload', ['class'=>'btn btn-default']) !!}
		{!! Form::close() !!}
		<!-- make folder form -->
		{!! Form::open(['action'=>'FileController@makeFolder', 'class'=>'form-inline']) !!}
			{!! Form::text('folderName', '', ['class'=>'form-control', 'placeholder'=>'folder name', 'id'=>'exampleInputName2']) !!}
			{!! Form::hidden('shortPath', $dir['shortPath']) !!}
			{!! Form::submit('New Folder', ['class'=>'btn btn-default']) !!}
		{!! Form::close() !!}
	</div>
	<!-- breadcrumb -->
	<div>
		<ol class="breadcrumb">
				<li><a href="{{ URL::action('FileController@index') }}">/</a></li>
			<?php
				$arr = '';
			?>
			@for($i = 1; $i < count($dir['shortPathArr']); $i++)
				<?php
					$shortPath = breadcrumbPath($dir['shortPathArr'], $i);
				?>
				<li><a href="{{ URL::action('FileController@index', ['folder'=>$shortPath]) }}">{{ $dir['shortPathArr'][$i] }}</a></li>
			@endfor
		</ol>
	</div>

	@if($errors->any())
		<!-- errors -->
		<div class="errors">
			<ul class="list-group">
				@foreach($errors->all() as $error)
					<li class="list-group-item list-group-item-danger">{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<!-- dir list -->
	<div class="show-list">
		<table class="bordered">
			<thead>
			<th>#</th>
			<th>file name</th>
			<th>size</th>
			<th>type</th>
			<th>last modified time</th>
			<th>operate</th>
			</thead>
			{{-- files list --}}
			@foreach($dir['files'] as $key => $file)
				<tr>
					<td>{{ $key }}</td>
					<td>{{ $file['name'] }}</td>
					<td>{{ humanSize($file['size']) }}</td>
					<td>
						@if($file['type'] == '')
							{{ 'unknown' }}
						@else
							{{ $file['type'] }}
						@endif
					</td>
					<td>{{ date('Y-m-d H:i:s', $file['lastModified']) }}</td>
					<td>
						{!! Form::open(['url'=>'/file/download', 'method'=>'post', 'class'=>'form']) !!}
							{!! Form::hidden('pathName', $file['pathName']) !!}
							{!! Form::submit('Download', ['class'=>'btn btn-primary']) !!}
						{!! Form::close() !!}
						{!! Form::open(['url'=>'/file/deleteFile', 'method'=>'delete','class'=>'form']) !!}
							{!! Form::hidden('fileName', $file['pathName']) !!}
							{!! Form::submit('Delete', ['class'=>'btn btn-danger']) !!}
						{!! Form::close() !!}
					</td>
				</tr>
			@endforeach
			{{-- folders list --}}
			@foreach($dir['folders'] as $key => $folder)
				<tr>
					<td>{{ $key }}</td>
					<td><a href="{{ URL::action('FileController@index', ['folder'=>$folder['pathName']]) }}">{{ $folder['name'] }}</a></td>
					<td>{{ humanSize($folder['size']) }}</td>
					<td>dir</td>
					<td>{{ date('Y-m-d H:i:s', $folder['lastModified']) }}</td>
					<td>
						{!! Form::open(['url'=>'/file/deleteFolder', 'method'=>'delete','class'=>'form']) !!}
							{!! Form::hidden('folderName', $folder['pathName']) !!}
							{!! Form::submit('Delete', ['class'=>'btn btn-danger']) !!}
						{!! Form::close() !!}
					</td>
				</tr>
			@endforeach
		</table>
	</div>
@stop

