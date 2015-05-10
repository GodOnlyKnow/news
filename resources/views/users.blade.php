@extends('app')

@section('content')
<div class="container">
	<hr>
	<div class="">
		<form action="/user/index" class="form-inline">
			<div class="form-group">
				<label for="">用户名：</label>
				<input type="text" class="form-control" name="username" id="username">
			</div>
			<button class="btn btn-primary" type="submit">搜索</button>
		</form>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>用户名</th>
					<th>用户等级</th>
					<th>注册时间</th>
					<th>最后登录</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($users as $user)
					<tr>
						<td>{{ $user->username }}</td>
						<td>{{ $groups[$user->group_id - 1]['name'] }}</td>
						<td>{{ $user->created_at }}</td>
						<td>{{ $user->login_at }}</td>
						<td>
							@if ($user->is_lock == 0)
								<a href="/user/change?id={{ $user['id'] }}&username={{ $username }}&page={{ $page }}" class="btn btn-default">禁言</a>
							@else
								<a href="/user/change?id={{ $user['id'] }}&username={{ $username }}&page={{ $page }}" class="btn btn-primary">取消禁言</a>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="">
		<?php echo $users->appends(array('username' => $username,'page' => $page))->render(); ?>
	</div>
</div>
@endsection