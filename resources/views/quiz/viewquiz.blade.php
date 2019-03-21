@extends('layout.app')
@section('title', __('lang.Quiz'))
@section('content')

        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<iframe id="receiver" att="<?=$_GET["id"];?>" width="100%" height="850px" src="https://www.manhal.com/platform/quiz/view/ar/index.php?id=<?=$_GET["id"];?>&lms=1"> </iframe>
</body>
</html>
@endsection
