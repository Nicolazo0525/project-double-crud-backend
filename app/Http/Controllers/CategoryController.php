<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::latest()->paginate(7);
        return CategoryResource::collection($category);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'status' => 'required|numeric',
            'userId' => 'required|numeric',
        ]);
        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        }
        /* dd($image_path); */
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_path,
            'status' => $request->status,
            'user_id' => $request->userId,
        ]);

        return response()->json(['status'=>'Category successfully created.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function search($user_id, Request $request)
    {
        $user = User::findOrfail($user_id);
        $query = $request->input('q');
        $perPage = $request->input('perPage', 1);

        if (!is_null($query)) {
            $category = Category::where('user_id', $user->id)
                                    ->where('name', 'LIKE', '%'.$query.'%')
                                    ->orWhere('description', 'LIKE', '%'.$query.'%')
                                    ->latest()
                                    ->paginate($perPage);
            return CategoryResource::collection($category);
        }
        elseif (is_null($query)) {
            $category = Category::where('user_id', $user->id)->latest()->paginate($perPage);
            return CategoryResource::collection($category);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrfail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required|numeric',
            'userId' => 'required|numeric',
        ]);

        $image_path = "";

        if ($request->hasFile('newImage')) {
            $request->validate([
                'newImage' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            Storage::delete('public/image/'.$category->image);
            $image_path = $request->file('newImage')->store('image', 'public');
        }else{
            $image_path = $request->image;
        }
        /* $book->update($request->validated()); */
        $category->name = $request->name;
        $category->image = $image_path;
        $category->description = $request->description;
        $category->status = $request->status;
        $category->user_id = $request->userId;

        $category->save();
        sleep(1);
        return response()->json(['status'=>'Category successfully edited.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        sleep(1);
        return response()->json(['status'=>'Category successfully delete.']);
    }
}
