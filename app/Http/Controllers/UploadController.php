<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadStoreRequest;
use App\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    public function store(UploadStoreRequest $request)
    {
        $file = $request->file('filepond');
        $folder = uniqid() . '-' . now()->timestamp;
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $file->storeAs('tmp/' . $folder, $filename);

        TemporaryFile::create([
            'folder'    => $folder,
            'filename'  => $filename,
            'extension' => $extension,
        ]);
        return response($folder, 200)
            ->header('Content-Type', 'text/plain');
        
    }
    public function  destroy(Request $request)
    {
        $temporaryFile = TemporaryFile::where('folder',$request->getContent() )->first();
        Storage::deleteDirectory("tmp/{$temporaryFile->folder}");
        $temporaryFile->delete();

        return response($request->getContent(), 200)
            ->header('Content-Type', 'text/plain');
    }
}
