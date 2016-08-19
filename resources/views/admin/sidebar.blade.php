@extends('layouts.sidebar-mini')
@section('siderbar-title')
Administration
@endsection
@section('sidebar-content')
<ul class="sidebar_item_list">
    @if(Auth::user()->isSuperAdmin() || Auth::user()->hasRole('patient-admin'))
    <li class="admin_sidebar_menu_item">
        <a class="sidebar_button_subsection subsection_admin_add" href="/administration/patients/create">
            <span class="img_not_hover"><img src="{{ elixir('images/sidebar/admin-patient-icon.png') }}" ></span>
            <span class="img_on_hover"><img src="{{ elixir('images/sidebar/admin-patient-icon-hover.png') }}" ></span>
            <span class="add_text">add<span class="arial_bold" style="color:#de3c4b;">+</span></span>
        </a>
        <a class="sidebar_button_subsection subsection_admin_title patients" href="/administration/patients" id="{{ array_key_exists('patient_active', $data) ? 'button_active' : '' }}">
            <span>Patients</span>
        </a>
    </li>
    @endif
    @if(Auth::user()->isSuperAdmin() || Auth::user()->hasRole('practice-admin'))
    <li class="admin_sidebar_menu_item">
        <a class="sidebar_button_subsection subsection_admin_add {{ Auth::user()->checkUserLevel('Practice') ? 'disabled' : '' }}" href="{{ Auth::user()->checkUserLevel('Practice') ? '#' : '/administration/practices/create' }}">
            <span class="img_not_hover"><img src="{{ elixir('images/sidebar/admin-practice-icon.png') }}" ></span>
            <span class="img_on_hover"><img src="{{ elixir('images/sidebar/admin-practice-icon-hover.png') }}" ></span>
            <span class="add_text">add<span class="arial_bold" style="color:#7e6551;">+</span></span>
        </a>
        <a class="sidebar_button_subsection subsection_admin_title practices" href="/administration/practices" id="{{ array_key_exists('practice_active', $data) ? 'button_active' : '' }}">
            <span>Practices</span>
        </a>
    </li>
    @endif
    @if(Auth::user()->isSuperAdmin())
    <li class="admin_sidebar_menu_item">
        <a class="sidebar_button_subsection subsection_admin_add" href="/administration/networks/create">
            <span class="img_not_hover"><img src="{{ elixir('images/sidebar/admin-network-icon.png') }}" ></span>
            <span class="img_on_hover"><img src="{{ elixir('images/sidebar/admin-network-icon-hover.png') }}" ></span>
            <span class="add_text">add<span class="arial_bold" style="color:#808080;">+</span></span>
        </a>
        <a class="sidebar_button_subsection subsection_admin_title networks" href="/administration/networks" id="{{ array_key_exists('network_active', $data) ? 'button_active' : '' }}">
            <span>Networks</span>
        </a>
    </li>
    @endif
    @if(Auth::user()->isSuperAdmin() ||  Auth::user()->hasRole('user-admin') )
    <li class="admin_sidebar_menu_item">

        <a class="sidebar_button_subsection subsection_admin_add" href="/administration/users/create">
            <span class="img_not_hover"><img src="{{ elixir('images/sidebar/admin-user-icon.png') }}" ></span>
            <span class="img_on_hover"><img src="{{ elixir('images/sidebar/admin-user-icon-hover.png') }}" ></span>
            <span class="add_text">add<span class="arial_bold" style="color:#6b31d7;">+</span></span>
        </a>
        <a class="sidebar_button_subsection subsection_admin_title users" href="/administration/users" id="{{ array_key_exists('user_active', $data) ? 'button_active' : '' }}">
            <span>Users</span>
        </a>
    </li>
    @endif
</ul>
@if(Auth::user()->isSuperAdmin())
<div class="admin_report_button_container">
    <a class="admin_report_button @if(isset($data)){{ array_key_exists('audit_report', $data) ? 'active' : '' }}@endif"  data-name="recall" style="color:black;text-decoration:none;" href="/auditreports">
        <img src="{{ elixir('images/sidebar/audit_icon.png') }}" alt="">
        <p>Reports</p>
    </a>
</div>
@endif
@endsection
