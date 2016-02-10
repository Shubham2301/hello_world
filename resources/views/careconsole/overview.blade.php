<div class="overview row before_drilldown">
    @foreach($overview['stages'] as $stage)
    <div class="overview_section col-xl-2 col-lg-3 col-md-4 col-xs-6">
        <div class="info_box" id="{{ $stage['name'] }}" data-id="{{ $stage['id'] }}" data-name="{{ $stage['display_name'] }}">
            <div class="top">
                @if(isset($stage['kpis']))
                @for($i = 0; $i < $stage['kpi_count']; $i++)
                <div class="info_section" id="{{ $stage['kpis'][$i]['name'] }}" data-indicator="{{ $stage['kpis'][$i]['color_indicator'] }}" data-name="{{ $stage['kpis'][$i]['display_name'] }}">
                    <div class="info_section_title">{{ $stage['kpis'][$i]['display_name'] }}</div>
                    <div class="right">
                        <div class="circle" style="background-color:{{ $stage['kpis'][$i]['color_indicator'] }}"></div>
                        <div class="info_section_number">{{ $stage['kpis'][$i]['count'] }}</div>
                    </div>
                </div>
                @if($i < $stage['kpi_count'] - 1)
                <div class="section_break"></div>
                @endif
                @endfor
                @endif
            </div>
            <div class="bottom stage" data-id="{{ $stage['id'] }}" data-name="{{ $stage['display_name'] }}">
                <p>{{ $stage['display_name'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>
