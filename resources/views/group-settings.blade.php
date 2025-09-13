<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | {{ $group->name }} Settings</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
</head>
<body>
    group #{{ $group->id . ' - ' . $group->name }} settings
</body>
</html>