@extends('emails.master')

@section('to')
<p>{{ $to['name'] }}</p>
@endsection

@section('content')
<p>{{ $content }}</p>
@endsection
