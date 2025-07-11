<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('assets/css-mine/core.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>
<body>
    <table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($brands as $brand)
        <tr>
            <td data-label="Name">{{ $brand->name }}</td>
            <td data-label="Description">{{ $brand->description }}</td>
            <td data-label="Image"><img src="{{ asset('uploads/' . $brand->image) }}" width="60"></td>
            <td data-label="Action">
                <a href="#" class="btnEdit"><img src="{{asset('assets/icon/edit.png')}}" alt=""></a>
                <form method="POST" action="#" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <a  class="btnEdit"><img src="{{asset('assets/icon/delete.png')}}" alt=""></a>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>