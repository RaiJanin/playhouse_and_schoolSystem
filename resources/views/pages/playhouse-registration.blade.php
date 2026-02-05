@extends('layout.app')

@section('title', 'Playhouse Registration')

@section('styles')

@endsection


@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-6xl mx-auto">
        @include('ui.partials.header')
        @include('ui.partials.progress-bar', ['current' => 1])
        <div class="bg-teal-100 rounded-lg p-3 shadow-md">
            @if(Route::is('playhouse.phone'))
                @include('ui.playhouse-phone')
            @endif
            @if(Route::is('playhouse.otp'))
                @include('ui.playhouse-otp')
            @endif
            @if(Route::is('playhouse.parent'))
                @include('ui.playhouse-parent')
            @endif
            @if(Route::is('playhouse.children'))
                @include('ui.playhouse-children')
            @endif
            @if(Route::is('playhouse.duration'))
                @include('ui.playhouse-duration')
            @endif
            @if(Route::is('playhouse.done'))
                @include('ui.playhouse-done-prompt')
            @endif
        </div>
    </div>
@endsection

@section('scripts')

@endsection


@section('scripts')
    
@endsection