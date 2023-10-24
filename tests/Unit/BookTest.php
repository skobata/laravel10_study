<?php

namespace Tests\Unit;

use App\Models\Book;
use Tests\TestCase;

class BookTest extends TestCase
{
     /**
      * @test
      */
     public function 書籍のタイトルが10文字で省略される(): void
     {
         $book1 = Book::factory()->make(['title' => 'PHPer Book']);
         $this->assertSame('PHPer Book', $book1->abbreviatedTitle());

         $book2 = Book::factory()->make(['title' => 'PHPer Book2']);
         $this->assertSame('PHPer Book...', $book2->abbreviatedTitle());

     }
}
