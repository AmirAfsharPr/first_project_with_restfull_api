<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewConcertRequest;
use App\Http\Requests\UpdateConcertRequest;
use App\Http\Resources\ConcertResource;
use App\Models\Concert;
use Illuminate\Http\Request;

class ConcertController extends Controller
{

    public function index()
    {
        return response()->json([
            'data' => [
                'concerts' => ConcertResource::collection(Concert::paginate(5))->response()->getData()
            ]
        ]);
    }

    public function store(NewConcertRequest $request)
    {
        // چک کردن این که آیا کنسرت دیگری در این بازه زمانی وجود دارد یا خیر
        $otherActiveConcerts = Concert::query()->where('artist_id',$request->get('artist_id'))
            ->where(function ($query) use ($request) {

                $query->where(function ($query) use ($request) {
                    $query->where('starts_at','>=',$request->get('starts_at'))
                        ->orWhere('starts_at','=<',$request->get('ends_at'));
                })->orWhere(function ($query) use ($request) {
                    $query->where('ends_at','>=',$request->get('starts_at'))
                        ->orWhere('ends_at','=<',$request->get('ends_at'));
                });

            })->exists();

        if ($otherActiveConcerts){
            return response()->json([
                'data' => [
                    'message' => 'this artist already got plans'
                ]
            ])->setStatusCode(400);
        }

        $concert = Concert::query()->create([
            'artist_id' => $request->get('artist_id'),
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'starts_at' => $request->get('starts_at'),
            'ends_at' => $request->get('ends_at'),
            'is_published' => (boolean)$request->get('is_published',false),
        ]);

        return response()->json([
            'data' => [
                'concert' => new ConcertResource($concert)
            ]
        ])->setStatusCode(200);
    }

    public function show(Concert $concert)
    {
        return response()->json([
            'data' => [
                'concert' => new ConcertResource($concert)
            ]
        ])->setStatusCode(200);
    }

    public function update(Concert  $concert , UpdateConcertRequest $request)
    {

        $otherActiveConcerts = Concert::query()->where('artist_id',$request->get('artist_id'))
            ->where('id','!=',$concert->id)
            ->where(function ($query) use ($request) {

                $query->where(function ($query) use ($request) {
                    $query->where('starts_at','>=',$request->get('starts_at'))
                        ->orWhere('starts_at','=<',$request->get('ends_at'));
                })->orWhere(function ($query) use ($request) {
                    $query->where('ends_at','>=',$request->get('starts_at'))
                        ->orWhere('ends_at','=<',$request->get('ends_at'));
                });

            })->exists();

        if ($otherActiveConcerts){
            return response()->json([
                'data' => [
                    'message' => 'this artist already got plans'
                ]
            ])->setStatusCode(400);
        }

        $concert->update([
            'artist_id' => $request->get('artist_id',$concert->artist_id),
            'title' => $request->get('title',$concert->title),
            'description' => $request->get('description',$concert->description),
            'starts_at' => $request->get('starts_at',$concert->starts_at),
            'ends_at' => $request->get('ends_at',$concert->ends_at),
            'is_published' => (boolean)$request->get('is_published',$concert->is_published),
        ]);

        return response()->json([
            'data' => [
                'concert' => new ConcertResource($concert)
            ]
        ])->setStatusCode(200);
    }

    public function destroy(Concert $concert)
    {
        $concert->delete();

        return response()->json([
            'data' => [
                'message' => 'concert been deleted successfully'
            ]
        ])->setStatusCode(200);
    }
}
