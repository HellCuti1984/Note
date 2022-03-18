<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class NotesController extends Controller
{
    public function allNotes() : JsonResponse{
        return response()->json(Notes::all());
    }

    public function oneNote(Request $request) : JsonResponse{
        return response()->json(Notes::findOrFail($request->id));
    }

    public function addNote(Request $request){
        $note = Notes::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'favorites' => $request->favorites
        ]);

        return response()->json($note, Response::HTTP_OK);
    } 

    public function toFavourite(Request $request) : JsonResponse{
        $note = Notes::findOrFail($request->id);
        if($note->favorites === 0)
            $note->favorites=1;
        else
            $note->favorites=0;
        $note->save();
        return response()->json($note, Response::HTTP_OK);
    }

    public function updateNote(Request $request) : JsonResponse{
        $note = Notes::findOrFail($request->id);
        $note->update($request->all());
        return response()->json($note, Response::HTTP_OK);
    }

    public function deleteNote(Request $request){
        Notes::findOrFail($request->id)->delete();
        return response()->json("Успех", Response::HTTP_OK);
    }

    public function filterByFavorites() : JsonResponse{
        $notes = DB::select("select * from notes where favorites = 0");
        return response()->json($notes, Response::HTTP_OK);
    }

    public function filterByDate() : JsonResponse{
        $notes = DB::select("select * from notes ORDER BY created_at DESC");
        return response()->json($notes, Response::HTTP_OK);
    }
}
