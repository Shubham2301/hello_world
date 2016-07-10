<div class="no-padding">
	<div class="row sidebar_header center">
		<div>
			<div class="dropdown">
				<span class="dropdown-toggle admin_button sidebar_user_img_dropdown" type="button" data-toggle="dropdown">
					<img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="profile_img_sidebar_mini" onerror="this.src = '{{elixir('images/sidebar/care_coordination_icon_small.png')}}'">
					<span class="caret"></span></span>
				<ul class="dropdown-menu sidebar">
					@can('access-directmail')
					<li class="hello">
						<a href="/directmail" data-toggle="tooltip" title="Direct Mail" data-placement="right"><img src="{{elixir('images/sidebar/messages.png')}}" class="drop_image"></a>
					</li>
					@endcan
					<li>
						<a href="/file_exchange" data-toggle="tooltip" title="File Exchange" data-placement="right"><img src="{{elixir('images/sidebar/file_update.png')}}" class="drop_image"></a>
					</li>
					<li>
						<a href="#" id="menu-announcements" data-toggle="tooltip" title="Announcements" data-placement="right"><img src="{{elixir('images/sidebar/announcements.png')}}" class="drop_image menu-announcements"></a>
					</li>
					<li>
						<a href="/referraltype" data-toggle="tooltip" title="Schedule Patients" data-placement="right"><img src="{{elixir('images/sidebar/schedule.png')}}" class="drop_image"></a>
					</li>

					@can('care-cordination')
					<li>
						<a href="/careconsole" data-toggle="tooltip" title="Care Console" data-placement="right"><img src="{{elixir('images/sidebar/care-coordination.png')}}" class="drop_image"></a>
					</li>
					@endcan @if(2 == Auth::user()->usertype_id)
					<li>
						<a href="/administration" data-toggle="tooltip" title="Administration" data-placement="right"><img src="{{elixir('images/sidebar/administration.png')}}" class="drop_image"></a>
					</li>
					@endif @can('view-reports')
					<li>
						<a href="/careconsole_reports" data-toggle="tooltip" title="Reports" data-placement="right"><img src="{{elixir('images/sidebar/reports.png')}}" class="drop_image"></a>
					</li>
					@endcan
				</ul>
			</div>
		</div>
		<div>
			<h3 class="title"> Patient Records</h3></div>
	</div>

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

</div>
