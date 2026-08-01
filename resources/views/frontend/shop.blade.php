@extends('frontend.layouts.store')
@section('title', 'Shop — '.storeName())

@section('content')
    @livewire('shop-catalog')
@endsection
