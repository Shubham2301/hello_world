@extends('layouts.sidebar-mini')
@section('siderbar-title')
Files Exchange
@endsection
@section('sidebar-content')
<ul class="sidebar_item_list">
	<li class="admin_sidebar_menu_item">
		<a class="sidebar_button_subsection subsection_admin_title practices" href="/records" id="button_active" >
			<span style="color:#4d4d4d">Patient Records</span>
		</a>
	</li>
	<li class="admin_sidebar_menu_item">
		<a class="sidebar_button_subsection subsection_admin_title users" href="/webform" id="button_active">
			<span style="color:#4d4d4d">Health Records</span>
		</a>
	</li>
</ul>
@endsection