@if(Auth::check())
<div class="sidebar_user_info center arial_bold">
    <h4>{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</h4>
</div>
<div class="sidebar_menu center">
    <img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="profile_img_sidebar" onerror="this.src = '{{URL::asset('images/sidebar/care_coordinator.png')}}'">
</div>
<ul class="sidebar_item_list arial">
    <?php $menus = \myocuhub\Models\Menu::renderForUser(Auth::user())?> @foreach($menus as $menu)
    <li id="menu-{{ $menu->name }}" class="sidebar_menu_item {{ array_key_exists($menu->name, $data) ? 'active' : '' }}" onclick="location.href = '{{$menu->url}}'">
        <a class="sidebar_item" href="{{ $menu->url }}">
            <span class="menu_item_icon"><img src="{{URL::asset($menu->icon_path.'.png')}}" class="image"></span>
            <span class="menu_item_label">{{ $menu->display_name }}</span>
        </a>
    </li>
    @endforeach
</ul>
@endif

{{-- TODO : add active button for current menu item : {{ array_key_exists('directmail_active', $data) ? 'active' : '' }} --}}
