<div class="row">
	<div class="col-md-12">
		<h1>Users</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		@if($users->count() > 0)
			<table>
				<thead>
					<tr>
						<th>Title</th>
						<th>First Name</th>
						<th>Middle Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>NPI</th>
						<th>Cellphone</th>
						<th>Direct Address (SES?)</th>
						<th>Address 1</th>
						<th>Address 2</th>
						<th>City</th>
						<th>State</th>
						<th>Zip</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
					<tr>
						<td>{{ $user->title }}</td>
						<td>{{ $user->firstname }}</td>
						<td>{{ $user->middlename }}</td>
						<td>{{ $user->lastname }}</td>
						<td>{{ $user->email }}</td>
						<td>{{ $user->npi }}</td>
						<td>{{ $user->cellphone }}</td>
						<td>{{ $user->sesemail }}</td>
						<td>{{ $user->address1 }}</td>
						<td>{{ $user->address2 }}</td>
						<td>{{ $user->city }}</td>
						<td>{{ $user->state }}</td>
						<td>{{ $user->zip }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		@endif
	</div>
</div>
