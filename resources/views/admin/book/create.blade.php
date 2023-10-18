<x-layout.book-manager>
    <x-slot:title>
        書籍登録
    </x-slot:title>
    <h1>書籍登録</h1>
    @if($errors->any())
        <x-alert class="danger">
            <x-error-messages :$errors />
        </x-alert>
    @endif
    <form action="{{route('book.store')}}" method="post">
        @csrf
        <div>
            <label>カテゴリ</label>
            <select name="category_id">
                @foreach($categories as $category)
                    <option value="{{$category->id}}" @selected(old('category_id') == $category->id)>
                        {{$category->title}}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>title</label>
            <input type="text" name="title" value="{{old('title')}}"/>
        </div>
        <div>
            <label>price</label>
            <input type="text" name="price" value="{{old('price')}}"/>
        </div>
        <input type="submit" value="send">
    </form>
</x-layout.book-manager>
