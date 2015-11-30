<div class="row height header">
    <div class="col-sm-3 hidden-xs"></div>
    <div class="col-sm-2 header-logo">
        <img src="{{URL::asset('images/ocuhub-logo.png')}}" class="img-responsive">
    </div>
    <div class="col-sm-6 header-menu">
        @if(Auth::check())
        <div class="col-sm-4 col-md-3 menu-item active" data-id="care-console">
           <span>Care Console</span>
        </div>
        <div class="col-sm-4 col-md-3 menu-item" data-id="payer-console">
            <span>Payer Console</span>
        </div>
        <div class="col-sm-4  col-md-3 menu-item" data-id="admin-console">
            <span>Administration</span>
        </div>
        @endif
    </div>
</div>
