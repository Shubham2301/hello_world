@extends('layouts.sidebar-mini')
@section('siderbar-title')
Files Exchange
@endsection
@section('sidebar-content')
<ul class="sidebar_item_list arial">
    <li>
        <a class="files_sidebar_menu_item {{ array_key_exists('my_files', $active_link) ? 'active' : '' }}" href="/file_exchange">
            <span class="sidebar_img"><img src="{{elixir('images/sidebar/myfiles-icon.png')}}"></span>
            <span class="sidebar_title">My Files</span>
        </a>
    </li>
    <li>
        <a class="files_sidebar_menu_item {{ array_key_exists('shared_with_me', $active_link) ? 'active' : '' }}" href="/sharedWithMe">
            <span class="sidebar_img"><img src="{{elixir('images/sidebar/sharedwithme-icon.png')}}"></span>
            <span class="sidebar_title">Shared With Me</span>
        </a>
    </li>
    <li>
        <a class="files_sidebar_menu_item {{ array_key_exists('recent_share_changes', $active_link) ? 'active' : '' }}" href="/recentShareChanges">
            <span class="sidebar_img"><img src="{{elixir('images/sidebar/sharechanges-icon.png')}}"></span>
            <span class="sidebar_title">Recent Share Changes</span>
        </a>
    </li>
    <li>
        <a class="files_sidebar_menu_item {{ array_key_exists('trash', $active_link) ? 'active' : '' }}" href="/trash">
            <span class="sidebar_img"><img src="{{elixir('images/sidebar/trash-icon.png')}}"></span>
            <span class="sidebar_title">Trash</span>
        </a>
    </li>
</ul>
@endsection