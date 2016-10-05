<div class="no-padding">
    <div class="row sidebar_header center">
        <div>
            <div class="dropdown" >
                <span class="dropdown-toggle admin_button sidebar_user_img_dropdown" type="button" data-toggle="dropdown" >
                    <img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="profile_img_sidebar_mini" onerror="this.src = '{{elixir('images/sidebar/care_coordinator.png')}}'">
                    <span class="caret"></span>
                </span>
                <?php $menus = \myocuhub\Models\Menu::renderForUser(Auth::user())?>
                <ul class="dropdown-menu sidebar" >
                @foreach($menus as $menu)
                    @if($menu->name == "schedule-patient" && Auth::user()->isSuperAdmin())
                        <?php continue; ?>
                    @endif
                    @if($menu->name == "administration" && !Auth::user()->administrationAccess())
                        <?php continue;?>
                    @endif
                    @if($menu->name == "care-console" && !policy(new \myocuhub\Models\Careconsole)->accessConsole())
                        <?php continue; ?>
                    @endif
                    <li id="menu-{{ $menu->name }}" ><a href="{{ $menu->url }}" data-toggle="tooltip" title="{{ $menu->display_name }}" data-placement="right"><img src="{{ elixir($menu->icon_path.'.png') }}" class="drop_image"></a></li>
                @endforeach
                </ul>
            </div>
        </div>
        <div>
            <h3 class="arial_bold title">@yield('siderbar-title')</h3>
            @yield('siderbar-subtitle')
        </div>
    </div>
    @yield('sidebar-content')
</div>
