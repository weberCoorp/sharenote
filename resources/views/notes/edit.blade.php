@extends('layouts.app')
@section('title', __('Edit Note'))
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('notes.index') }}">{{ __('My Notes') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Note') }}</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-lg-4 col-md5">
                @if($note->private)

                    <form action="{{ route('notes.share',$note) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="descriptionInput"><h5>{{ __('Share') }}</h5></label>
                            <input type="text" name="email" class="form-control @error('email') is-invalid  @enderror" placeholder="{{ __('Enter the user\'s email address') }}" autocomplete="off">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button class="btn btn-outline-dark btn-block">{{ __('Share with User') }}</button>
                        </div>
                    </form>
                    <div class="form-group">
                            <h5>{{ __('list of users who has access to this note') }}</h5>
                            <div class="table-responsive">
                                <table class="table">
                                @foreach($note->usersHasAccess as $user)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                        </div>
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        <form action="{{ route('notes.remove-access',$note) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit"class="btn btn-sm btn-outline-dark"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            </div>
                        </div>

                @else
                    <h5>{{ __('You can  share private notes only!') }}</h5>
                @endif
            </div>
            <div class="col-lg-8 col-md7">
                @php  $noteFilesTotal = $note->files? $note->files->count() : 0 @endphp
                <form action="{{ route('notes.update',$note) }}" method="POST" enctype="multipart/form-data" name="noteFormSubmit">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="descriptionInput"><h5>{{ __('Note Name') }}<span class="required-field">*</span></h5></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid  @enderror"  autocomplete="off" value="{{ $note->name }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="private" @if($note->private) checked @endif id="privateCheck">
                            <label class="form-check-label" for="privateCheck">{{ __('Private') }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descriptionInput"><h5>{{ __('Description') }}<span class="required-field">*</span></h5></label>
                        <textarea name="description" id="descriptionInput" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror" required>{{ $note->description }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Only JPG, PNG, PDF and DOCX files are allowed. Maximum upload file size:2 MB. Maximum Files Count: {{ 10 - $noteFilesTotal }}</label>
                        <input id="filepond" type="file" name="filepond" />
                    </div>

                    @if($noteFilesTotal)
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
                    <div class="form-group">
                        <button type="submit" class="btn  btn-outline-dark btn-block">{{ __('Update Note') }}</button>
                    </div>
                </form>
                <form action="{{ route('notes.destroy', $note) }}" method="post" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <button class="btn  btn-outline-danger btn-block">{{ __('Delete Note') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <form id="removeFile"  method="post" style="display: none">
            @csrf
            @method('DELETE')
        </form>

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
            /*maxParallelUploads:1,*/
            maxFiles: {{ 10 - $noteFilesTotal }},
            server: {
                process: {
                    url: '/upload',
                    onerror: function (response) {
                        //console.log(JSON.parse(response))
                        serverResponse = (JSON.parse(response)).errors['filepond'][0];
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
        });//filepond--data
    </script>
@endsection

