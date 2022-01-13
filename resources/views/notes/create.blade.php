@extends('layouts.app')
@section('title', __('Create Note'))
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('notes.index') }}">{{ __('My Notes') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Create Note') }}</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-8">

                <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data" name="noteFormSubmit">
                    @csrf

                    <div class="form-group">
                        <label for="descriptionInput"><h5>{{ __('Note Name') }}<span class="required-field">*</span></h5></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid  @enderror"  autocomplete="off" value="{{ old('name') }}" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="private" @if(old('private')) checked @endif id="privateCheck">
                            <label class="form-check-label" for="privateCheck">{{ __('Private') }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descriptionInput"><h5>{{ __('Description') }}<span class="required-field">*</span></h5></label>
                        <textarea name="description" id="descriptionInput" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Only JPG, PNG, PDF and DOCX files are allowed. Maximum upload file size:2 MB. Maximum Files Count: 10</label>
                        <input id="filepond" type="file" name="filepond"/>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn-outline-dark btn-block">{{ __('Save Note') }}</button>
                    </div>
                </form>

            </div>
        </div>


    </div>

@endsection
@section('scripts')
    <script>

        let form = document.forms['noteFormSubmit'];
        form.onsubmit = function (event){
            event.preventDefault();
            var inputs = form.elements;
            for (i = 0; i < inputs.length; i++) {
                if (inputs[i].nodeName === "INPUT" &&  inputs[i].type === "hidden" && inputs[i].name === "filepond" && inputs[i].value) {
                    // Update text input
                    console.log(inputs[i]);
                    inputs[i].setAttribute('name', "filepond[]");

                }
            }
            return form.submit();
        }
        function removeFile(action){
            let form = document.getElementById('removeFile');
            form.setAttribute('action', action);
            form.submit();
        }
        const inputElement = document.querySelector('input[id="filepond"]');

        // Create a FilePond instance
        const pond = FilePond.create(inputElement);
        let serverResponse = '';
        FilePond.setOptions({
            allowMultiple: true,
            maxParallelUploads:1,
            maxFiles: 10,
            server: {
                process: {
                    url: '/upload',
                    onerror: function (response) {
                        serverResponse = (JSON.parse(response)).errors.filepond[0];
                    }
                },
                revert: {
                    url: '/upload',
                    method: 'DELETE',
                },
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            },
            labelFileProcessingError: () => {
                // replaces the error on the FilePond error label
                return serverResponse;
            }
        });
    </script>
@endsection
