@extends('app')

@section('content')
<div class="container">
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>图片</th>
					<th>意见</th>
					<th>时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				@foreach ( $fs as $f )
					<tr>
						<td>
							@if ($f->img != null && strlen($f->img) > 1)
								<img src="/{{ $f->img }}" class="img-responsive">
							@else
								无图片
							@endif
						</td>
						<td>{{ $f->body }}</td>
						<td>{{ $f->created_at }}</td>
						<td>
							<a href="{{ url('/feedback/delete') }}/{{ $f->id }}" class="btn btn-danger">删除</a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		{{ $fs->render() }}
	</div>
</div>
@endsection