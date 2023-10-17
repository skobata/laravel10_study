<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>書籍登録</title>
</head>
<body>
<main>
    <h1>書籍登録</h1>
    <form action="{{route('book.store')}}" method="post">
        @csrf
        <div>
            <label>カテゴリ</label>
            <select name="category_id">
                @foreach($categories as $category)
                    <option value="{{$category->id}}">
                        {{$category->title}}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>title</label>
            <input type="text" name="title"/>
        </div>
        <div>
            <label>price</label>
            <input type="text" name="price"/>
        </div>
        <input type="submit" value="send">
    </form>
</main>
</body>
</html>
