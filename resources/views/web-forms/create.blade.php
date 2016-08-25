
    <div class="web_form_section">
        <form action="/save_records" id="patient_record_form" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="patient_id" id="patient_id" value="">
            <input type="hidden" name="template_id" id="template_id" value="">



            <?php $i=1; ?> @foreach(array_chunk($template['rows'], 6) as $chunk)
            <div class="form_chunk_{{$i}} form_chunk {{($i != 1)? '':'active'}} " data-index="{{$i}}">
               @if($i ==1)
                <div class="row">
                    <div class="col-xs-4">
                        <p>DOB
                            <input type="text" value="{{ date('m/d/Y', strtotime($patient->birthdate))}}" class="unit_input_text field_date" style="width:40%">
                        </p>
                    </div>
                    <div class="col-xs-4"></div>
                    <div class="col-xs-4">
                        <p>Date
                            <input type="text" value="{{date(config('constants.date_format'))}}" class="unit_input_text field_date" style="width:30%" name="creation_date">
                        </p>
                    </div>
                </div>
                @endif
                <div class="row config('webforms.class.header')">
                    @foreach($template['header_row']['cols'] as $col)
                    <div class="col-xs-12 col-sm-{{ 12/sizeof($template['header_row']['cols']) }} {{ config('webforms.class.col')}}">
                        @foreach($col['elements'] as $element)

                        @if($element['type'] == 'p')
                        <p style="{{$element['style']}}" >{{$element['display-name']}}</p>
                        @endif

                        @endforeach
                    </div>
                    @endforeach
                </div>
                <?php $i++; ?> @foreach($chunk as $row)
                <div class="row {{ config('webforms.class.row') }} ">
                    @foreach($row['cols'] as $col)
                    <div class="col-xs-12 col-sm-{{ 12/sizeof($row['cols']) }} {{ config('webforms.class.col')}}">
                        @foreach($col['elements'] as $element)

                        @if($element['type'] == 'full_length_input')
                        <div class="row">
                            <div class="col-xs-4">
                                <p>{{ $element['display-name'] }}</p>
                            </div>
                            <div class="col-xs-7">
                                <p> <span> {{isset($element['pre-text'])? $element['pre-text']:''}} </span>
                                    <input type="text" value="" name="{{$element['id']}}" class="unit_input_text" style="{{isset($element['style'])?$element['style']:'width:100%'}}">
                                </p>
                            </div>
                        </div>

                        @elseif($element['type'] == 'h2')
                        <h1>{{ $element['display-name'] }}</h1> @elseif($element['type'] == 'p')
                        <p>{{ $element['display-name'] }}</p>

                        @elseif($element['type'] == 'input:date')
                        {!! Form::text($element['id'], '', array('class' => config('webforms.class.input-text').' '.'field_date', 'placeholder' => $element['display-name'], 'id' => $element['id'] , 'data-toggle' => 'tooltip', 'title' => $element['display-name'], 'data-placement' => 'right', 'style'=>'margin:0em')) !!}

                        @elseif($element['type'] == 'input:text:other')
                        {!! Form::text($element['id'], '', array('class' => 'other_option_input', 'placeholder' => $element['display-name'], 'id' => $element['id'] , 'data-toggle' => 'tooltip', 'title' => $element['display-name'], 'data-placement' => 'right')) !!}

                        @elseif($element['type'] == 'input:checkbox')
                        <div>
                           <p>
                            <label class= "input_checkbox_lable">
                               {!! Form::checkbox($element['id'], ($element['display-name'] != "")?$element['display-name'] :true, null, array('id' =>     $element['id'], 'class' => 'input_checkbox')); !!}
                                <sapn> {{ $element['display-name'] }}</sapn>
                             </label>
                            </p>
                        </div>

                        @elseif($element['type'] == 'input:radio_checkbox')
                        <div>
                            <label class= "radio_checkbox_lable">
                                {!! Form::radio($element['id'], ($element['value'] != "")?$element['value'] :true, null, array('id' =>     $element['id'], 'class' => 'radio_checkbox')); !!}
                                <sapn> {{ $element['display-name'] }}</sapn>
                            </label>
                        </div>

                        @elseif($element['type'] == 'input:unit_box')

                        <span>
                            <span> {{$element['pre-text']}}</span>
                            <span>
                                <input type="text" name='{{$element['id']}}' class="
                                {{config('webforms.class.unit_input')}}
                                {{isset($element['class'])?' '.$element['class']:''}}
                                " style="{{isset($element['style'])?$element['style']:''}}">
                                {{$element['post-text']}}
                            </span>
                      </span>

                            @if(isset($element['direction']) && $element['direction'] != "H" )
                            <br>
                        @endif


                        @elseif($element['type'] == 'p:category')
                        <p class="{{config('webforms.class.category_name')}}">{{ $element['display-name'] }}</p>

                        @elseif($element['type'] == 'h1')
                        <h1>{{ $element['display-name'] }}</h1>

                        @elseif($element['type']=='input:checkbox:wrap')
                        <label class="tgl_text" style="{{isset($element['style'])?$element['style']:''}}">
                            <input type=checkbox name="{{$element['id']}}" id="{{$element['id']}}" class="tgl" value="{{isset($element['value'])? $element['value']:''}}"> {{$element['display-name']}}
                        </label>

                        @if(isset($element['direction']) && $element['direction'] == "H" )</br>@endif

                        @elseif($element['type']=='input:multi:checkbox:wrap')

                        <div class="row">
                            <div class="col-xs-3">
                                <span> {{$element['display-name']}}</span>
                            </div>
                            <div class="col-xs-8">
                                <span>
                                            @foreach($element['option'] as $option)
                                            <label class="tgl_text">
                                                <input type=checkbox  id = "{{$element['id']}}" name="{{$element['id']}}" class="tgl" value="{{$option['value']}}">
                                                {{$option['value']}}
                                            </label>

                                           @endforeach
                                        </span>
                            </div>
                        </div>

                        @elseif($element['type']=='input:multi:checkbox:wrap-inline')
                        <span>

                            <span> {{$element['delimiter'].' '.$element['display-name']}}</span>
                        <span>
                                @foreach($element['option'] as $option)
                                <label class="tgl_text">
                                    <input type=checkbox  id = "{{$element['id']}}" name="{{$element['id']}}" class="tgl" value="{{$option['value']}}">
                                    {{$option['value']}}
                                </label>

                                @endforeach
                            </span>
                        </span>


                        @elseif($element['type'] == 'signature')
                        <div class="row">
                            <div class="col-xs-4">
                            <p>{{ $element['display-name'] }}</p>
                            </div>
                            <div class="col-xs-7">
                                <div id="signature" class="sigPad">
                                    <div class="sig sigWrapper">
                                        <div class="typed"></div>
                                        <canvas class="pad" width="380" height="100"></canvas>
                                        <input type="hidden" name="sigoutput" class="output">
                                    </div>
                                    <ul class="sigNav">
                                        <li class="clearButton"><a href="#clear">Clear</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        @elseif($element['type']=='input:radio:wrap')
                        <label class="tgl_radio" style="{{isset($element['style'])?$element['style']:''}}">
                        <input type=radio name="{{$element['id']}}" id="{{$element['id']}}" class="tgl" value="{{isset($element['value'])? $element['value']:''}}"> {{$element['display-name']}}
                        </label>

                            @if(isset($element['direction']) && $element['direction'] == "H" )
                            </br>
                            @endif
                        @endif

                    @endforeach
                    </div>
                    @endforeach
                </div>

                @endforeach

            </div>
            @endforeach

        </form>
    </div>
    <div class="row form_footer">
        <div class="col-xs-4 btn text-left">
            <p id="previous_btn" style="display:none;">Previous</p>
        </div>
        <div class="col-xs-4 ">
            <button class="btn btn-primary" type="button" id="create_record" data-id="" style="margin-top:8px;margin-left:20%;">Save</button>
        </div>
        <input type="hidden" value="{{$i-1}}" id="count_form_sections"></input>
        <div class="col-xs-4 btn text-right">
            <p id="continue_btn">Continue</p>
        </div>
    </div>
    <div class="row" id="footer_loader_section" style="display:none;">
        <div class="col-xs-4"></div>
        <div class="col-xs-4">
            <div >
                <div class="footer_loader" class="hidden-xs"></div>
                <p id="loading_text" class="text-center"><span>Please wait...</span><br> It may take some time</p>
            </div>
        </div>
        <div class="col-xs-4"></div>
    </div>

