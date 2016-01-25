@extends('layouts.master')

@section('title', 'My Ocuhub - Select Provider')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{asset('css/practice.css')}}">
<script type="text/javascript" src="{{asset('js/practice.js')}}"></script>
@endsection

@section('sidebar')
@include('layouts.sidebar')
@endsection

@section('content')




<div class="container">
    <div class="content"></div>
    @foreach($u as $p)
    <h4>{{$p->name}}</h4>
    @endforeach
    {{$u->links()}}
</div>
<div style="display:flex; margin-left:22em;">
   <span class="glyphicon glyphicon-chevron-left aleft"  aria-hidden="true"></span>
   <sapn class="acenter">1</sapn>
    <span class="glyphicon glyphicon-chevron-right aright"  aria-hidden="true"></span>

</div>



@endsection
