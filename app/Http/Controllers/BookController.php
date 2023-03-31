<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list($categories_id)
    {
        $books = Book::where('categories_id', $categories_id)
            ->latest()
            ->paginate(7);
        return BookResource::collection($books);
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
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'author' => 'required|string|max:255',
            'editorial' => 'required|string|max:255',
            'edition' => 'required|string|max:255',
            'pages' => 'required|numeric',
            'status' => 'required|numeric',
            'categories_id' => 'required|numeric',
        ]);
        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        }
        /* dd($image_path); */
        $book = Book::create([
            'name' => $request->name,
            'image' => $image_path,
            'author' => $request->author,
            'editorial' => $request->editorial,
            'edition' => $request->edition,
            'pages' => $request->pages,
            'status' => $request->status,
            'categories_id' => $request->categories_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return new BookResource($book);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrfail($id);
        $request->validate([
            'name' => 'string|max:255',
            'author' => 'string|max:255',
            'editorial' => 'string|max:255',
            'edition' => 'string|max:255',
            'pages' => 'numeric',
            'status' => 'numeric',
            'categories_id' => 'numeric',
        ]);

        $image_path = "";

        if ($request->hasFile('newImage')) {
            $request->validate([
                'newImage' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            Storage::delete('public/image/'.$book->image);
            $image_path = $request->file('newImage')->store('image', 'public');
        }else{
            $image_path = $request->image;
        }
        /* $book->update($request->validated()); */
        $book->name = $request->name;
        $book->image = $image_path;
        $book->author = $request->author;
        $book->editorial = $request->editorial;
        $book->edition = $request->edition;
        $book->pages = $request->pages;
        $book->status = $request->status;

        $book->save();
        sleep(1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->status = 0;
        $book->save();
        sleep(1);
    }
}
