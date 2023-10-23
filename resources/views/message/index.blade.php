<! DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Message Sample</title>
</head>
<body>
<main>
    <h1>メッセージ</h1>
    <form action="/messages" method="post">
        @csrf
        <input type="text" name="body">
        <input type="submit" value="投稿">
    </form>
    <ul>
        @foreach($messages as $message)
            <li>
                <form method="POST" action="{{route('message.destroy', ['id' => $message->id])}}">
                    {{$message->body}}
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="削除">
                </form>
            </li>
        @endforeach
    </ul>
</main>
</body>
</html>
