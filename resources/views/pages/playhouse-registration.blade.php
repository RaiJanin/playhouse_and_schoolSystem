@extends('layout.app')

@section('title', 'Playhouse Registration')

@section('styles')

@endsection


@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-6xl mx-auto">
        @include('ui.partials.header')
        @include('ui.partials.progress-bar')
        <div class="bg-teal-100 rounded-lg p-3 shadow-md">
            @include('ui.playhouse-first')
        </div>
    </div>
@endsection


@section('scripts')
    
@endsection