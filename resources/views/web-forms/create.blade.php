<div>

    <div class="web-form-section" style="height:26em;" >
		<form action="/save_records" id="patient_record_form" method="POST">
			{{ csrf_field() }}
			<input type="hidden" name="patient_id" id="patient_id" value= "">
			<input type="hidden" name="template_id" id="template_id" value= "">
            <?php $i=1; ?>
            @foreach(array_chunk($template['rows'], 5) as $chunk)
            <div class = "form_chunk_{{$i}} form_chunk {{($i != 1)? '':'active'}} " data-index = "{{$i}}">

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

               <?php  $i++; ?>
                @foreach($chunk as $row)
                    <div class="row {{ config('webforms.class.row') }} " style="margin-top:1em;">
                        @foreach($row['cols'] as $col)
                        <div class="col-xs-12 col-sm-{{ 12/sizeof($row['cols']) }} {{ config('webforms.class.col')}}">
                            @foreach($col['elements'] as $element)
                                @if($element['type'] == 'h2')
                                    <h1>{{ $element['display-name'] }}</h1> @elseif($element['type'] == 'p')
                                    <p>{{ $element['display-name'] }}</p>

                                @elseif($element['type'] == 'input:text')
                                    {!! Form::text($element['id'], '', array('class' => config('webforms.class.input-text').' '.'field_date', 'placeholder' => $element['display-name'], 'id' => $element['id'] , 'data-toggle' => 'tooltip', 'title' => $element['display-name'], 'data-placement' => 'right', 'style'=>'margin:0em')) !!}

                                @elseif($element['type'] == 'input:text:other')
                                    {!! Form::text($element['id'], '', array('class' => 'other_option_input', 'placeholder' => $element['display-name'], 'id' => $element['id'] , 'data-toggle' => 'tooltip', 'title' => $element['display-name'], 'data-placement' => 'right')) !!}

                                @elseif($element['type'] == 'input:checkbox')
                                    {!! Form::checkbox($element['id'], $element['display-name'], null, array('id' => $element['id'], 'class' => 'user_roles input_checkbox ')); !!}
                                    {!! Form::label($element['display-name'], $element['display-name']); !!}
                                <br>
                                @elseif($element['type'] == 'input:unit_box')
                                    <span>{{$element['pre-text']}}<input type="text" name='{{$element['id']}}' class="unit_input_text text-center">{{$element['post-text']}} </span><br>

                                @elseif($element['type'] == 'p:category')
                                    <p style="padding: 0px;font-weight: bold;color: #fff;">{{ $element['display-name'] }}</p>

                                @elseif($element['type'] == 'h1')
                                    <h1>{{ $element['display-name'] }}</h1>

                                @elseif($element['type']=='input:checkbox:wrap')
                                <label class="tgl_text">
									<input type=checkbox name="{{$element['id']}}" id = "{{$element['id']}}" class="tgl" value="{{$element['id']}}">
                                        {{$element['display-name']}}
                                </label>

                                @elseif($element['type']=='input:multi:checkbox:wrap')
                                   <div class= "row">
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


                             @endif @endforeach
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
          <p id = "previous_btn" style="display:none;">Previous</p>
        </div>
        <div class="col-xs-4 ">
            <button class="btn btn-primary" type="button" id="create_record" data-id="" style="margin-top:8px;margin-left:20%;">Save</button>
        </div>
        <input type="hidden" value = "{{$i-1}}" id="count_form_sections" ></input>
        <div class="col-xs-4 btn text-right" >  <p id ="continue_btn">Continue</p>
        </div>
    </div>
</div>
