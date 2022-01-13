@extends('layouts.app')
@section('title', __('My Notes'))
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('My Notes') }}</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if($notes->count() > 0)

                    @foreach($notes as $note)
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col d-flex align-items-center ">

                                        <div class="">
                                            <div><strong>{{ $note->name }}</strong></div>
                                            <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $note->created_at->diffForHumans() }} @if($note->private)&ensp;<i class="fa fa-lock purple" aria-hidden="true"></i>@endif
                                        </div>
                                        {{--
                                        <a href="">
                                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                        </a>--}}
                                    </div>
                                    <div class="col text-right">
                                        {{--
                                        <button class="btn btn-sm btn-outline-dark" title="Share"> <i class="fa fa-share-alt" aria-hidden="true"></i></button>
                                        --}}
                                        <a href="{{ route('notes.edit', $note) }}" class="btn btn-sm btn-outline-dark"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <form action="{{ route('notes.destroy', $note) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-dark"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </form>
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
                               @if($note->files_count) <i class="fa fa-file  purple" aria-hidden="true"></i> {{ $note->files_count }}@endif
                            </div>
                        </div>
                    @endforeach
                    {{ $notes->withQueryString()->links() }}
                @else
                    <h4>{{ __('No found!') }}</h4>
                @endif
            </div>
        </div>
    </div>
@endsection
