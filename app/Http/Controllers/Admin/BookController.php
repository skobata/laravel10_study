<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    //
    public function index(): Collection
    {
        $books = Book::all();
        return $books;
    }

    public function show(string $id): Book
    {
        return Book::findOrFail($id);
    }

    public function create(): View
    {
        return View('admin/book/create', ['categories' => Category::all()]);
    }

    public function store(Request $request): Book
    {
        $book = new Book();
        $book->category_id = $request->category_id;
        $book->title = $request->title;
        $book->price = $request->price;
        $book->save();

        return $book;
    }
}
