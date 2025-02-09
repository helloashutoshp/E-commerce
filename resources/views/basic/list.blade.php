@extends('basic.layout.app')
@section('content')
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Sl No</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Age</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Language</th>
                    <th scope="col">Image</th>
                    <th scope="col">Action</th>

                </tr>
            </thead>
            <tbody>
                @if ($data->isNotEmpty())
                    @php
                        $i = ($data->currentpage() - 1) * $data->perpage();
                        $i = $i + 1;
                    @endphp
                    @foreach ($data as $date)
                        @php
                            $image = $date->image->first();
                        @endphp
                        <tr>
                            <th scope="row">{{ $i }}</th>
                            <td>{{ $date->fname }} {{ $date->lname }}</td>
                            <td>{{ $date->email }}</td>
                            <td>{{ $date->age }}</td>
                            <td>{{ $date->phone }}</td>
                            <td>{{ $date->gender }}</td>
                            <td>{{ implode(',', $date->description->toArray()) }}</td>
                            @if (!empty($image->name))
                                <td><img src="{{ asset('/basic/' . $image->name) }}" alt="" width="50px"></td>
                            @else
                                <td>No image</td>
                            @endif
                            <td><a href="{{ route('edit', [$date->id]) }}" class="btn btn-warning">Edit</a>
                                <button class="btn btn-danger" onclick="deleteRecord({{ $date->id }})">Delete</button>
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                @endif
            </tbody>
        </table>
        {{ $data->links() }}
    </div>
@endsection

@section('script')
    <script>
        function deleteRecord(id) {
            $.ajax({
                url: `{{ url('/basic/delete') }}/${id}`,
                method: 'get',
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                }
            })
        }
    </script>
@endsection
