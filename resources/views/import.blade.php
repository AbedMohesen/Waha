<!DOCTYPE html>
<html>

<head>
    <title>Import Excel</title>
</head>

<body>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Import</button>
    </form>

</body>

</html>
