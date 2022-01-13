@extends('layouts.app')
@section('title', __('Home'))
@section('content')

<div class="intro-banner mb-5">
        <div class="container">

            <!-- Intro Headline -->
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-headline">
                        <h3>
                            <strong>Lorem Ipsum is simply dummy text.</strong>
                            <br>
                            <span class="d-none d-sm-block">The point of using <strong class="color">Lorem Ipsum</strong> is that it has a more-or-less normal distribution of letters.</span>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="row">
                <div class="col-md-12">
                    <div class="intro-banner-search-form margin-top-95">


                        <!-- Search Field -->
                        <div class="intro-search-field with-label">
                            <label for="intro-keywords" class="field-title ripple-effect">What note are you looking for?</label>
                            <input id="intro-keywords" type="text" placeholder="Note Title or Keywords, at least 3 letters"  value="{{ request()->query('q') }}">
                        </div>

                        <!-- Button -->
                        <div class="intro-search-button">
                            <button class="button ripple-effect" onclick="search()">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="background-image-container" style="background-image: url(/img/share.jpg);"></div></div>

<div class="container">
    @if($notes->count() > 0)
    <div class="row">
        @foreach($notes as $note)
        <div class="col-md-6 note-block mb-3">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col d-flex align-items-center ">
                            <div class="">
                                <div><strong>{{ $note->name }}</strong></div>
                                <i class="fa fa-clock-o" aria-hidden="true"></i>&ensp;{{ $note->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('notes.show', $note) }}" class="btn btn-sm btn-outline-dark"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="note-block">
                        <div class="note-block__content">
                            @markdown($note->description)
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col d-flex align-items-center ">
                            <i class="fa fa-user-circle-o purple" aria-hidden="true"></i>&ensp;<b>{{ $note->user->name }}</b>
                        </div>
                        <div class="col d-flex align-items-center justify-content-end">
                            @if($note->files_count) <i class="fa fa-file  purple" aria-hidden="true"></i> {{ $note->files_count }}@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
     {{ $notes->withQueryString()->links() }}
    @else
      <h4>{{ __('No found!') }}</h4>
    @endif
</div>
@endsection
