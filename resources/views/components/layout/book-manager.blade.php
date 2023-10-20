<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>
<body>
<header>
    書籍管理システム
    <form method="POST" action="{{route('admin.destroy')}}">
        @csrf
        <input type="submit" value="ログアウト" />
    </form>
    <hr>
</header>
<main>
{{ $slot }}
</main>
<footer>
    <hr>
    @LaravelTest
</footer>
</body>
</html>
