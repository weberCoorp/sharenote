@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ $note->name }}<br>
                        <label>@if($note->private){{__('private')}}@endif</label>
                    </div>
                    <div class="card-body">
                        <div class="">
                            @markdown($note->description)
                        </div>
                        @if($note->files->count())
                            <div class="form-group">
                                <h5>{{ __('Files') }}</h5>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>{{ __('Type') }}</th>

                                            <th>{{ __('Filename') }}</th>
                                            <th width="70px">{{ __('Size') }}(Kb)</th>
                                            <th width="70px">{{ __('Delete') }}</th>
                                        </tr>
                                        </thead>
                                        @foreach($note->files as $file)
                                            <tr>
                                                <td>@fileicon($file->extension)</td>
                                                <td><a href="{{ route('files.show', $file) }}">{{ $file->filename }}</a></td>
                                                <td>{{ number_format($file->filesize / 1024) }}</td>
                                                <td>
                                                    <button type="button"class="btn btn-sm btn-outline-dark" onclick="removeFile('{{ route('files.destroy', $file) }}')">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection
