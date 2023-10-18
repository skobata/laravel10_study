<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookPostRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookController extends Controller
{
    //
    public function index(): Response
    {
        $books = Book::with('category')
            ->orderBy('category_id')
            ->orderBy('title')
            ->get();

        return response()
            ->view('admin.book.index', ['books' => $books])
            ->header('Content-Type', 'text/html')
            ->header('Content-Encoding', 'UTF-8');
    }

    public function show(Book $book): View
    {
        return view('admin.book.show', compact('book'));
    }

    public function create(): View
    {
        return View('admin/book/create', ['categories' => Category::all()]);
    }

    public function store(BookPostRequest $request): RedirectResponse
    {
        $book = new Book();
        $book->category_id = $request->category_id;
        $book->title = $request->title;
        $book->price = $request->price;
        $book->save();

        $authors = Author::inRandomOrder()->limit(2)->get();
        $author_book_data = [];
        foreach ($authors as $author) {
            $author_book_data[] = ['book_id' => $book->id, 'author_id' => $author->id];
        }
        DB::table('author_book')->insert($author_book_data);
        return redirect(route('book.index'))->with('message', "「{$book->title}」を追加しました。");
    }
}
