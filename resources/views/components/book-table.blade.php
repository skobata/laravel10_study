<table border="1">
    <tr>
        <th>カテゴリ</th>
        <th>書籍名</th>
        <th>価格</th>
        <th>更新</th>
        <th>削除</th>
    </tr>
    @foreach($books as $book)
        <tr @if($loop->even)style="background: #EEE"@endif>
            <td>{{ $book->category->title }}</td>
            <td>
                @can('example-com-user')
                <a href="{{ route('book.show', $book) }}">
                    {{ $book->title }}
                </a>
                @else
                    {{$book->title}}
                @endcan
            </td>
            <td>{{ $book->price }}</td>
            <td>
                @can('update', $book)
                    <a href="{{ route('book.edit', $book) }}"><button>更新</button></a>
                @else
                    <button disabled>更新</button>
                @endcan
            </td>
            <td>
                @cannot('delete', $book)
                    <button disabled>削除</button>
                @else
                    <form action="{{ route('book.destroy', $book) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="削除">
                    </form>
                @endcannot
            </td>
        </tr>
    @endforeach
</table>
