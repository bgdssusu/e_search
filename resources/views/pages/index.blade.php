

@foreach ($data['brand_all'] as $item=>$value)
@section('active'.$value)<?= ($data['brand']===$value) ? "class='active'" : "";?>@stop
@endforeach

@foreach ($data['gender_all'] as $index=>$item)
@section('active'.$item)<?= ($data['gender']===$item) ? "active" : "";?>@stop
@endforeach

@foreach ($data['category_all'] as $index=>$item)
@section('active'.$item)<?= ($data['category']===$item) ? "active" : "";?>@stop
@endforeach

@foreach ($data['color_all'] as $index=>$item)
@section('active'.$item)<?= ($data['color']===$item) ? "bordered" : "";?>@stop
@endforeach

@extends('layout')