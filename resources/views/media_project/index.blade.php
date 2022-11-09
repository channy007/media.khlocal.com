@extends('layouts.homepage')
@section('content')
    <div class="my-container shadow p-3 mb-5 bg-white rounded">
        @if ($message = Session::get('success'))
            @include('includes.alerts.success')
        @endif

        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Resolutions</th>
                        <th scope="col">Channel</th>
                        <th scope="col">Name</th>
                        <th scope="col"></th>
                        <th scope="col">App ID</th>
                        <th scope="col">Page ID</th>
                        <th scope="col">Access Token</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $count => $mediaProject)
                        <tr>
                            <td>{{ $count }}</td>
                            <td>{{ $mediaProject->resolution }}</td>
                            <td>{{ ucfirst($mediaProject->channel) }}</td>
                            <td>{{ $mediaProject->name }}</td>
                            <td style="text-align: center">
                                <a href="{{ route('media-project-edit', $mediaProject->id) }}"
                                    class="btn btn-primary btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
                                    style="height: 25px;width: 25px; text-align: center;display: flex;justify-content: center;">
                                    <i class="far fa-edit"></i>
                                </a>
                            </td>
                            <td>{{ $mediaProject->app_id }}</td>
                            <td>{{ $mediaProject->page_id }}</td>
                            <td>{{ $mediaProject->access_token }}</td>
                            <td>{{ ucfirst($mediaProject->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $datas->appends(request()->except('page'))->links('includes.pagination.custom') !!}
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.toast').toast('show')
        });
    </script>
@stop
