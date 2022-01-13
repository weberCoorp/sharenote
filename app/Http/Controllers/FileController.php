<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','verified'], ['except' => [
            'show',
        ]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\File  $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function show(File $file): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $file->load('note.usersHasAccess');

        $authorization = $this->authorization($file);
        if($authorization){

            if(Storage::exists(config('filesystems.file_dir'). '/' . $file->filename)) {
                return response()->download(storage_path('app/'. config('filesystems.file_dir'). '/' . $file->filename));
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
    }

    protected function authorization(File $file): bool
     {
         $valid = false;

         if($file->note->private === 0 ){
             $valid = true;
         } else {

             $user = auth()->user();
             $usersHasAccessIds = $file->note->usersHasAccess->count() > 0 ? $file->note->usersHasAccess->pluck('id')->toArray() : [];
             if(  ($user && $user->id == $file->user_id )  || ($user && in_array($user->id, $usersHasAccessIds)) ){
                 $valid = true;
             }
         }
         return $valid;
     } // http://sharenote.il/files/5c2788df-41f9-4b1b-8a0a-b077e14da233
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(File $file): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('delete', $file);
        $file->delete();

        return redirect()->back()->with([
            'status' => __('The file has been successfully deleted.'),
        ]);
    }
}
