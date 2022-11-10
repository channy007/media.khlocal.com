@extends('layouts.homepage')
@section('content')
    <div class="my-container shadow p-3 mb-5 bg-white rounded">
        @if ($message = Session::get('success'))
            @include('includes.alerts.success')
        @endif

        <div class="d-flex justify-content-end">
            <a class="btn btn-primary" href="{{ route('media-source-create') }}">
                <i class="fas fa-plus"></i> <span class="remove-mobile">{{ __('Add New') }}<span>
            </a>
        </div>
        <br>

        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Project Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Source Name</th>
                        <th scope="col">Source URL</th>
                        <th scope="col">Source From</th>
                        <th scope="col">Source Channel</th>
                        <th scope="col">Source Text</th>
                        <th scope="col">Transition</th>
                        <th scope="col">Resolution</th>
                        <th scope="col">Segment Start</th>
                        <th scope="col">Segment Length</th>
                        <th scope="col">Segment Gap</th>
                        <th scope="col">Segment</th>
                        <th scope="col">Flip H</th>
                        <th scope="col">Flip V</th>
                        <th scope="col">Created At</th>
    
    
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $count => $mediaSource)
                        <tr>
                            <td>{{ $count }}</td>
                            <td>{{ optional($mediaSource->project)->name }}</td>
                            <td>{{ $mediaSource->status }}</td>
                            <td>{{ $mediaSource->source_name }}</td>
                            <td>{{ $mediaSource->source_url }}</td>
                            <td>{{ $mediaSource->source_from }}</td>
                            <td>{{ $mediaSource->source_channel }}</td>
                            <td>{{ $mediaSource->source_text }}</td>
                            <td>{{ $mediaSource->transition }}</td>
                            <td>{{$mediaSource->resolution }}</td>
                            <td>{{ $mediaSource->seg_start }}</td>
                            <td>{{ $mediaSource->seg_length }}</td>
                            <td>{{ $mediaSource->seg_gap }}</td>
                            <td>{{ $mediaSource->segment }}</td>
                            <td>{{ $mediaSource->flip_h }}</td>
                            <td>{{ $mediaSource->flip_v }}</td>
                            <td>{{ $mediaSource->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
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
