<?php

namespace App\Http\Controllers;

use App\Events\NoteShared;
use App\Http\Requests\NoteShareRequest;
use App\Http\Requests\NoteStoreRequest;
use App\Http\Requests\NoteUpdateRequest;
use App\Note;
use App\TemporaryFile;
use App\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class NoteController extends Controller
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $notes = Note::where( 'user_id',auth()->user()->id )->latest()->withCount('files')->paginate(20);
        return view('notes.index', compact(['notes']));
    }
    public function shareWithMe()
    {
        $notes = Note::whereHas( 'usersHasAccess',function($query){
            return $query->where('user_id', auth()->user()->id);
        })->latest()->with('user')->withCount('files')->paginate(20);

        return view('notes.share-with-me', compact(['notes']));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('notes.create');
    }


    /**
     * @param NoteStoreRequest $request
     * @return RedirectResponse
     */
    public function store(NoteStoreRequest $request): RedirectResponse
    {
        $note = Note::create($request->validated());
        if ($request->filepond) {
            foreach ($request->filepond as $temporaryFileFolder) {
                $this->storeFile($temporaryFileFolder, $note);
            }
        }

        return redirect()->route('notes.edit', $note)->with([
            'status' => __('The information has been successfully saved.'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Note $note
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Note $note)
    {
        $this->authorize('view', $note);
        $note->load('files');
        return view('notes.show', compact(['note']));
    }

    /**
     * Add user to a note
     *
     * @param NoteShareRequest $request
     * @param Note $note
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function share(NoteShareRequest $request, Note $note): RedirectResponse
    {
        //$users = User::where('id', '!=', 1)->inRandomOrder()->limit(3)->get();

        $this->authorize('update', $note);
        $user = User::where('email', $request->email)->first();
        $note->usersHasAccess()->attach($user);

        event(new NoteShared($note, $user));

        return redirect()->route('notes.edit',$note)->with([
            'status' => __('The User has been successfully added.'),
        ]);
    }

    public function removeAccess(Request $request, Note $note)
    {
        $this->authorize('update', $note);
        $note->usersHasAccess()->detach($request->user_id);

        return redirect()->route('notes.edit',$note)->with([
            'status' => __('The access has been successfully removed.'),
        ]);
    }
    // Remove users from shared notes
    // Remove Access
    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Note $note
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Note $note)
    {
        $note->load('usersHasAccess:id,name,email','files');
        $this->authorize('update', $note);

        return view('notes.edit', compact(['note']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Note  $note
     * @return RedirectResponse
     */
    public function update(NoteUpdateRequest $request, Note $note): RedirectResponse
    {
        if($request->filepond){
           foreach($request->filepond as $temporaryFileFolder){
               $this->storeFile($temporaryFileFolder, $note);
           }
        }

        $note->update($request->validated());
        if(!$note->private){
            $note->usersHasAccess()->sync([]);
        }
        return redirect()->route('notes.edit',$note)->with([
            'status' => __('The information has been successfully updated.'),
        ]);
    }

    protected function storeFile(String $temporaryFileFolder,Note $note): \Illuminate\Database\Eloquent\Model
    {
        $temporaryFile = TemporaryFile::where('folder',$temporaryFileFolder )->first();

        $file = new File(Storage::path("tmp/{$temporaryFile->folder}/{$temporaryFile->filename}"));
        $filesize = $file->getSize();
        $filename = uniqid() . '-' . now()->timestamp. '.' . $temporaryFile->extension;

        Storage::putFileAs(config('filesystems.file_dir'), $file, $filename);
        Storage::deleteDirectory("tmp/{$temporaryFile->folder}");
        $temporaryFile->delete();

        return $note->files()->create([
            'user_id'   => auth()->user()->id,
            'filename'  => $filename,
            'extension' => $temporaryFile->extension,
            'filesize'  => $filesize,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $note
     * @return RedirectResponse
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return redirect()->route('notes.index')->with([
            'status' => __('The Note has been successfully deleted.'),
        ]);
    }
}
