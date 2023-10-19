<x-layout.book-manager>
    <x-slot:title>
        書籍更新
    </x-slot:title>
    <h1>書籍更新</h1>
    @if($errors->any())
        <x-alert class="danger">
            <x-error-messages :$errors />
        </x-alert>
    @endif
    <form action="{{ route('book.update', $book) }}" method="post">
        @csrf
        @method('PUT')
        <x-book-form :$categories :$authors :$book :$authorIds />
        <input type="submit" value="send">
    </form>
</x-layout.book-manager>
