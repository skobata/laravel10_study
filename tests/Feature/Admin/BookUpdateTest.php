<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookUpdateTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private $categories;
    private Book $book;
    private $authors;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create([
            'login_id' => 'hoge',
            'password' => Hash::make('hogehoge'),
        ]);

        $this->categories = Category::factory(3)->create();

        $this->book = Book::factory()->create([
            'title' => 'Laravel Book',
            'admin_id' => $this->admin->id,
            'category_id' => $this->categories[1]->id,
        ]);

        $this->authors = Author::factory(4)->create();

        $this->book->authors()->attach([
            $this->authors[0]->id,
            $this->authors[2]->id,
        ]);
    }

    /**
     * @test
     */
    public function 画面アクセス制限(): void
    {
        $url = route('book.edit', $this->book);
        $this->get($url)
            ->assertRedirect(route('admin.create'));

        $other = Admin::factory()->create();
        $this->actingAs($other, 'admin');

        $this->get($url)
            ->assertForbidden();

        $this->actingAs($this->admin, 'admin');

        $this->get($url)
            ->assertOk();
    }

    /**
     * @test
     */
    public function 更新処理のアクセス制限(): void
    {
        $url = route('book.update', $this->book);

        $param = [
            'category_id' => $this->categories[0]->id,
            'title' => 'New Laravel Book',
            'price' => '10000',
            'author_ids' => [
                $this->authors[1]->id,
                $this->authors[2]->id,
            ],
        ];

        $this->put($url, $param)
            ->assertRedirect(route('admin.create'));

        $other = Admin::factory()->create();
        $this->actingAs($other, 'admin');

        $this->put($url, $param)
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function 更新のアクセス制御(): void
    {
        $url = route('book.update', $this->book);

        $param = [
            'category_id' => $this->categories[0]->id,
            'title' => 'New Laravel Book',
            'price' => '10000',
            'author_ids' => [
                $this->authors[1]->id,
                $this->authors[2]->id,
            ],
        ];

        $this->put($url, $param)
            ->assertRedirect(route('admin.create'));

        $other = Admin::factory()->create();
        $this->actingAs($other, 'admin');

        $this->put($url, $param)
            ->assertForbidden();

        $this->assertSame('Laravel Book', $this->book->fresh()->title);
    }

    /**
     * @test
     */
    public function バリデーション(): void
    {
        $this->actingAs($this->admin, 'admin');

        $url = route('book.update', $this->book);

        $this->from(route('book.edit', $this->book))
            ->put($url, ['category_id' => ''])
            ->assertRedirect(route('book.edit', $this->book));

        $this->put($url, ['category_id' => ''])
            ->assertInvalid(['category_id' => 'カテゴリ は必須']);

        $this->put($url, ['category_id' => '0'])
            ->assertInvalid(['category_id' => '正しい カテゴリ']);

        $this->put($url, ['category_id' => $this->categories[2]->id])
            ->assertValid('category_id');

        $this->put($url, ['title' => ''])
            ->assertInvalid(['title' => 'タイトル は必須入力']);

        $this->put($url, ['title' => 'a'])
            ->assertValid('title');

        $this->put($url, ['title' => str_repeat('a', 100)])
            ->assertValid('title');

        $this->put($url, ['title' => str_repeat('a', 101)])
            ->assertInvalid(['title' => 'タイトル は 100 文字以内']);

        $this->put($url, ['price' => 'a'])
            ->assertInvalid(['price' => '価格 は数値']);

        $this->put($url, ['price' => '0'])
            ->assertInvalid(['price' => '価格 は 1 以上']);

        $this->put($url, ['price' => '1'])
            ->assertValid('price');

        $this->put($url, ['price' => '999999'])
            ->assertValid('price');

        $this->put($url, ['price' => '1000000'])
            ->assertInvalid(['price' => '価格 は 999999 以下']);

        $this->put($url, ['author_ids' => []])
            ->assertInvalid(['author_ids' => '著者 は必須']);

        $this->put($url, ['author_ids' => ['0']])
            ->assertInvalid(['author_ids.0' => '正しい 著者']);

        $this->put($url, ['author_ids' => [$this->authors[2]->id]])
            ->assertValid('author_ids.0');
    }

    /**
     * @test
     */
    public function 更新処理(): void
    {
        $url = route('book.update', $this->book);

        $param = [
            'category_id' => $this->categories[0]->id,
            'title' => 'New Laravel Book',
            'price' => '10000',
            'author_ids' => [
                $this->authors[1]->id,
                $this->authors[2]->id,
            ],
        ];

        $this->actingAs($this->admin, 'admin');

        $this->put($url, $param)
            ->assertRedirect(route('book.index'));

        $updatedBook = [
            'id' => $this->book->id,
            'category_id' => $param['category_id'],
            'title' => $param['title'],
            'price' => $param['price'],
        ];

        $this->assertDatabaseHas('books', $updatedBook);

        foreach ($this->authors as $author) {

            $authorBook = [
                'book_id' => $this->book->id,
                'author_id' => $author->id,
            ];

            if (in_array($author->id, $param['author_ids'])) {
                $this->assertDatabaseHas('author_book', $authorBook);
            } else {
                $this->assertDatabaseMissing('author_book', $authorBook);
            }
        }

        $this->get(route('book.index'))
            ->assertSee('「New Laravel Book」を変更しました');
    }
}
