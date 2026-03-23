@extends('layout.basic')

@section('title', 'Bookings - PlayHouse')

@section('contents')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Bookings</h1>
    <p class="text-gray-600 mt-1">View and manage all playhouse bookings</p>
</div>
@include('ui.order-items')
@endsection