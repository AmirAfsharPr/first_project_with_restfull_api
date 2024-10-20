<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckAccessMiddleware;
use App\Http\Requests\NewCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

//    public function __construct()
//    {
//        $this->middleware(CheckAccessMiddleware::class.':create_Categories')->only('store');
//    }

    public function index()
    {
        return response()->json([
            'data' => CategoryResource::collection(Category::paginate(5))
        ])->setStatusCode(200);
    }




    public function store(NewCategoryRequest $request)
    {

        $category = Category::query()->create([
            'title' => $request->get('title')
        ]);

        return response()->json([
            'data' => new CategoryResource($category)
        ])->setStatusCode(201);
    }

    public function update(Category $category , NewCategoryRequest $request )
    {

        $category->update([
            'title' => $request->get('title')
        ]);

        return response()->json([
            'data' => new CategoryResource($category)
        ])->setStatusCode(200);
    }

    public function show(Category $category)
    {
        return response()->json([
            'data' => new CategoryResource($category)
        ])->setStatusCode(200);
    }

    public function destroy(Category $category)
    {
        $category->delete();


        return response()->json([
            'message' => 'category deleted'
        ])->setStatusCode(200);
    }
}
