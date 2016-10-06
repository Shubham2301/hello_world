@if(Auth::check())
<div class="sidebar_user_info center arial_bold">
    <h4>{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</h4>
</div>
<div class="sidebar_menu center">
    <a href="/editprofile"><img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg?q='.str_random(3))}}" class="profile_img_sidebar" onerror="this.src = '{{URL::asset('images/sidebar/care_coordinator.png')}}'"></a>
</div>
<ul class="sidebar_item_list arial">
    <?php $menus = \myocuhub\Models\Menu::renderForUser(Auth::user())?>
    @foreach($menus as $menu)
    @if($menu->name == "schedule-patient" && Auth::user()->isSuperAdmin())
        <?php continue; ?>
    @endif
    @if($menu->name == "administration" && !Auth::user()->administrationAccess())
        <?php continue; ?>
    @endif
    @if($menu->name == "care-console" && !policy(new \myocuhub\Models\Careconsole)->accessConsole())
        <?php continue; ?>
    @endif
    <li id="menu-{{ $menu->name }}" class="sidebar_menu_item @if(isset($data)){{ array_key_exists($menu->name, $data) ? 'active' : '' }}@endif menu-{{ $menu->name }}" onclick="location.href = '{{$menu->url}}'">
        <a class="main_sidebar_menu_item" href="{{ $menu->url }}">
            <span class="sidebar_img"><img src="{{elixir($menu->icon_path.'.png')}}" class="image"></span>
            <span class="sidebar_title">
                <span class="sidebar_title_text">
                    {{ $menu->display_name }}
                </span>
                @if($menu->name == "care-console")
                <span class="notification arial_bold care_console_notification active" id="menu-notification-{{ $menu->name }}">
                    <img src="{{elixir('images/sidebar/priority-icon-small.png')}}" class="sidebar_priority_image">
                </span>
                @else
                <span class="notification arial_bold" id="menu-notification-{{ $menu->name }}">
                    <span class="notification_text" data-toggle="tooltip" data-placement="bottom" title="You have unread notifications">
                        0
                    </span>
                </span>
                @endif
            </span>
        </a>
    </li>
    @endforeach
</ul>
@endif
