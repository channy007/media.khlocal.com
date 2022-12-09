@extends('layouts.homepage')
@section('content')
    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="#">Channel Source</a></li>

        </ol>
    </nav>
    <div class="my-container shadow p-3 mb-5 bg-white rounded">
        @if ($message = Session::get('success'))
            @include('includes.alerts.success')
        @endif
        <div class="d-flex justify-content-end">
            <a class="btn btn-primary" href="{{ route('channel-source-create') }}">
                <i class="fas fa-plus"></i> <span class="remove-mobile">{{ __('Add New') }}<span>
            </a>
        </div>
        <br>

        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Channel</th>
                        <th scope="col">Name</th>
                        <th scope="col">URL</th>
                        <th scope="col">Custom Crop</th>
                        <th scope="col">Country</th>
                        <th scope="col">Description</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $count => $channelSource)
                        <tr>
                            <td>{{ $count + 1 }}</td>
                            <td>{{ ucfirst($channelSource->channel) }}</td>
                            <td>{{ $channelSource->name }}</td>
                            <td><a class="link" href="{{ $channelSource->url }}"
                                    target="__blank">{{ $channelSource->url }}</a></td>
                            <td>{{ $channelSource->custom_crop }}</td>
                            <td>{{ $channelSource->country }}</td>
                            <td>{{ $channelSource->description }}</td>

                            <td style="text-align: center">
                                <a href="{{ route('channel-source-edit', $channelSource->id) }}"
                                    class="btn btn-primary btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
                                    style="height: 25px;width: 25px; text-align: center;display: flex;justify-content: center;">
                                    <i class="far fa-edit"></i>
                                </a>
                            </td>
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
