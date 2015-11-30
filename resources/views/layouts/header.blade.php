<div class="row height header">
    <div class="col-sm-3 hidden-xs"></div>
    <div class="col-sm-2 header-logo">
        <img src="{{URL::asset('images/ocuhub-logo.png')}}" class="img-responsive">
    </div>
    <div class="col-sm-6 header-menu">
        @if (Auth::check())
        <div class="col-sm-3 menu-item">
           <span>Care Console</span>
        </div>
        <div class="col-sm-3 menu-item">
            <span>Payer Console</span>
        </div>
        <div class="col-sm-3 menu-item">
            <span>Administration</span>
        </div>
        @endif
    </div>
</div>
