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

        <form action="{{ route('channel-source-index') }}" method="GET" enctype="multipart/form-data">
            <div class="d-flex" >
                <div class="p-2" style="align-items: center;justify-content: center;text-align: center;display: flex">
                    <span>Channel</span>
                </div>
                <div class="p-2">
                    <select name="channel" class="form-control">
                        <option value="" selected>All</option>
                        <option value="youtube" {{ $channel == 'youtube' ? 'selected' : '' }}>Youtube
                        </option>
                        <option value="facebook" {{ $channel == 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="tiktok" {{ $channel == 'tiktok' ? 'selected' : '' }}>Tiktok</option>
                    </select>
                </div>
                <div class="p-2">
                    <div class="input-group">
                        <input class="form-control" name="search" type="search" placeholder="search" value="{{ $search }}" id="example-search-input">
                        <span class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="ml-auto p-2">
                    <a class="btn btn-primary" href="{{ route('channel-source-create') }}">
                        <i class="fas fa-plus"></i> <span class="remove-mobile">{{ __('Add New') }}<span>
                    </a>
                </div>
            </div>
        </form>
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
                        <th scope="col">Exclude Segnments</th>
                        <th scope="col">Country</th>
                        <th scope="col">Description</th>
                        <th scope="col">Created At</th>
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
                            <td>{{ $channelSource->segment_cut }}</td>
                            <td>{{ $channelSource->country }}</td>
                            <td>{{ $channelSource->description }}</td>
                            <td>{{ $channelSource->created_at ? getDateString($channelSource->created_at, 'd-M-Y') : '' }}</td>
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
