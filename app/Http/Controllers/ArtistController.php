<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewArtistRequest;
use App\Http\Requests\UpdateArtistRequest;
use App\Http\Resources\ArtistResource;
use App\Http\Resources\SingleArtistResource;
use App\Http\Resources\SingleRoleResource;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    public function index()
    {

        return response()->json([

                'data' => [
                    'artists' => ArtistResource::collection(Artist::paginate(5))->response()->getData(),
                ]

        ])->setStatusCode(200);

    }



    public function store(NewArtistRequest $request)
    {

        //  برای رفع مشکل همنام بودن بعضی موارد از عکس های آپلود شده
        $artistDirectory = $request->get('full_name').now()->timestamp;

        $avatar = $this->uploader($request, "public/artist/$artistDirectory",'avatar');

        $background = $this->uploader($request, "public/artist/$artistDirectory",'background');


        $artist = Artist::query()->create([
            'full_name' => $request->get('full_name'),
            'category_id' => $request->get('category_id'),
            'avatar' => $avatar,
            'background' => $background,
        ]);

        return response()->json([
            'data' =>[
                'artist' => new SingleArtistResource($artist)
            ]
        ])->setStatusCode(201);

    }


    public function update(UpdateArtistRequest $request, Artist $artist)
    {

        // به دست آوردن دایرکتوری فایل آپلود شده
        $directory = explode('/',$artist->avatar);
        array_pop($directory);
        $artistDirectory = implode('/',$directory);

        if ($request->hasFile('avatar')){
            $avatar = $this->uploader($request, $artistDirectory, 'avatar');
            Storage::delete($artist->avatar);
        }else{
            $avatar = $artist->avatar;
        }

        if ($request->hasFile('background')){
            $background = $this->uploader($request, $artistDirectory, 'background');
            Storage::delete($artist->background);
        }else{
            $background = $artist->background;
        }


        $artist->update([
            'full_name' => $request->get('full_name',$artist->full_name),
            'category_id' => $request->get('category_id',$artist->category_id),
            'avatar' => $avatar,
            'background' => $background
        ]);

        return response()->json([
            'data' =>[
                'artist' => new SingleArtistResource($artist)
            ]
        ])->setStatusCode(201);
    }

    public function show(Artist $artist)
    {
        return response()->json([
            'data' => new ArtistResource($artist)
        ]);
    }

    public function destroy(Artist $artist)
    {

        Storage::delete($artist->avatar);
        Storage::delete($artist->background);

        $artist->delete();

        return response([
            'massage' => 'artist deleted successfully'
        ]);
    }
}
