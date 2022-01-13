<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        $q = $request->q;
        $notes = Note::public()
            ->when($q, function($query) use($q){
                return $query->whereRaw("MATCH(name, description) AGAINST(? IN BOOLEAN MODE)", array($q));

            })
            ->with('user')
            ->withCount('files')
            ->latest()
            ->paginate(20);

        return view('home', compact(['notes']));
    }
}
