@extends('layouts.homepage')
@section('content')
    <div class="my-container shadow p-3 mb-5 bg-white rounded">
        @if ($message = Session::get('success'))
            @include('includes.alerts.success')
        @endif

        <form action="{{ route('media-source-index') }}" method="GET" enctype="multipart/form-data">
            <div class="d-flex">
                <div class="p-2" style="align-items: center;justify-content: center;text-align: center;display: flex">
                    <span>Status</span>
                </div>
                <div class="p-2">
                    <select class="form-control" name="status" onchange="selectChange()">
                        <option value="">All</option>
                        @foreach (getAllMediaStatuses() as $mediaStatus)
                            <option value="{{ $mediaStatus }}" {{ $mediaStatus == $status ? 'selected' : '' }}>
                                {{ $mediaStatus }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ml-auto p-2">
                    <a class="btn btn-primary" href="{{ route('media-source-create') }}">
                        <i class="fas fa-plus"></i> <span class="remove-mobile">{{ __('Add New') }}<span>
                    </a>
                </div>
            </div>
            <input type="submit" id="searchBtn" hidden>
        </form>

        <br>

        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Project Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>

                        <th scope="col">Source Name</th>
                        <th scope="col">Source URL</th>
                        <th scope="col">Source Text</th>
                        <th scope="col">Source Channel</th>

                        <th scope="col">Transition</th>
                        <th scope="col">Resolution</th>
                        <th scope="col">Segment Start</th>
                        <th scope="col">Segment Length</th>
                        <th scope="col">Segment Gap</th>
                        <th scope="col">Flip</th>
                        <th scope="col">Cut Off</th>
                        <th scope="col">Cut Off Side</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Error</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $count => $mediaSource)
                        <tr>
                            <td>{{ $count + 1 }}</td>
                            <td>{{ optional($mediaSource->project)->name }}</td>
                            <td>
                                <span class="badge {{ getMediaStatusClassBadge($mediaSource->status) }}">
                                    {{ $mediaSource->status }}</span>
                            </td>

                            <td>
                                <div class="row justify-content-around">

                                    @switch($mediaSource->status)
                                        @case('download_error')
                                            <a data-href="{{ route('media-source-retry-download', $mediaSource->id) }}"
                                                class="btn btn-primary btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
                                                style="height: 25px;width: 25px; text-align: center;display: flex;justify-content: center;"
                                                data-toggle="modal" data-target="#download-modal">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @break

                                        @case('downloaded')
                                        @case('cut_error')
                                            <a data-href="{{ route('media-source-retry-cut', $mediaSource->id) }}"
                                                data-media="{{ $mediaSource }}"
                                                class="btn btn-warning btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
                                                style="height: 25px;width: 25px; text-align: center;display: flex;justify-content: center;"
                                                data-toggle="modal" data-target="#cut-modal">
                                                <i class="fas fa-cut"></i>
                                            </a>
                                        @break

                                        @case('cutted')
                                        @case('upload_error')
                                            <a data-href="{{ route('media-source-retry-cut', $mediaSource->id) }}"
                                                data-media="{{ $mediaSource }}"
                                                class="btn btn-warning btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
                                                style="height: 25px;width: 25px; text-align: center;display: flex;justify-content: center;"
                                                data-toggle="modal" data-target="#cut-modal">
                                                <i class="fas fa-cut"></i>
                                            </a>

                                            <a href="{{ route('media-source-view-video-cutted', $mediaSource->id) }}"
                                                target="__blank"
                                                class="btn btn-light btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
                                                style="height: 25px;width: 25px; text-align: center;display: flex;justify-content: center;">
                                                <i class="fas fa-play"></i>
                                            </a>

                                            <a data-href="{{ route('media-source-retry-upload', $mediaSource->id) }}"
                                                data-media="{{ $mediaSource }}"
                                                class="btn btn-info btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
                                                style="height: 25px;width: 25px; text-align: center;display: flex;justify-content: center;"
                                                data-toggle="modal" data-target="#upload-modal">
                                                <i class="fas fa-upload"></i>
                                            </a>
                                        @break

                                        @default
                                    @endswitch
                                </div>
                            </td>

                            <td>{{ $mediaSource->source_name }}</td>

                            <td>
                                <a class="link" href="{{ $mediaSource->source_url }}"
                                    target="__blank">{{ $mediaSource->source_url }}</a>
                            </td>
                            <td>{{ $mediaSource->source_text }}</td>
                            <td>{{ optional($mediaSource->channel_source)->name }}</td>
                            <td>{{ $mediaSource->transition }}</td>
                            <td>{{ $mediaSource->resolution }}</td>
                            <td>{{ $mediaSource->seg_start }}</td>
                            <td>{{ $mediaSource->seg_length }}</td>
                            <td>{{ $mediaSource->seg_gap }}</td>
                            <td>{{ $mediaSource->flip }}</td>
                            <td>{{ $mediaSource->cut_off }}</td>
                            <td>{{ $mediaSource->cut_off_side = 0 ? 'Middle or Center': ($mediaSource->cut_off_side = 1 ? 'Left or Top' : 'Right or Bottom') }}</td>
                            <td>{{ $mediaSource->created_at ? getDateString($mediaSource->created_at, 'd-M-Y h:i a') : '' }}
                            </td>
                            <td>{{ $mediaSource->error }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            {!! $datas->appends(request()->except('page'))->links('includes.pagination.custom') !!}
        </div>
    </div>

    @include('media_source.modal.retry_download_modal')
    @include('media_source.modal.retry_cut_modal')
    @include('media_source.modal.retry_upload_modal')


@stop

@section('scripts')
    <script type="text/javascript">
        function search(ele) {
            if (event.key === 'Enter') {
                var searchBtn = document.getElementById('searchBtn');
                searchBtn.click();
            }
        }

        function selectChange() {
            var searchBtn = document.getElementById('searchBtn');
            searchBtn.click();
        }

        //##### CUT #####
        $('#cut-form').on('submit', function(e) {
            e.preventDefault();
            url = $('#cut-modal .btn-ok').attr('href');
            $.post(url,
                $('#cut-form').serialize(),
                function(data, status, xhr) {
                    // do something here with response;
                    $('#cut-modal').modal('toggle');
                    location.reload();
                }
            );
        });
        $('#cut-modal').on('show.bs.modal', function(e) {
            var mediaSource = $(e.relatedTarget).data('media');

            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

            $(this).find("#transition option").each(function() {
                if ($(this).val() == mediaSource.transition) { // EDITED THIS LINE
                    $(this).prop("selected", true);
                }
            });

            $(this).find("#resolution option").each(function() {
                if ($(this).val() == mediaSource.resolution) { // EDITED THIS LINE
                    $(this).prop("selected", true);
                }
            });

            $(this).find('#seg-start').val(mediaSource.segment_start);
            $(this).find('#seg-length').val(mediaSource.seg_length);
            $(this).find('#seg-gap').val(mediaSource.seg_gap);
            $(this).find("#flip option").each(function() {
                if ($(this).val() == mediaSource.flip) { // EDITED THIS LINE
                    $(this).prop("selected", true);
                }
            });

            $(this).find("#cut-off option").each(function() {
                if ($(this).val() == mediaSource.cut_off) { // EDITED THIS LINE
                    $(this).prop("selected", true);
                }
            });

            $(this).find("#cut-off-side option").each(function() {
                if ($(this).val() == mediaSource.cut_off_side) { // EDITED THIS LINE
                    $(this).prop("selected", true);
                }
            });

            

        });
        //##### END CUT #####

        //##### UPLOAD #####
        $('#upload-form').on('submit', function(e) {
            e.preventDefault();
            url = $('#upload-modal .btn-ok').attr('href');
            $.post(url,
                $('#upload-form').serialize(),
                function(data, status, xhr) {
                    // do something here with response;
                    $('#upload-modal').modal('toggle');
                    location.reload();
                }
            );
        });
        $('#upload-modal').on('show.bs.modal', function(e) {
            var mediaSource = $(e.relatedTarget).data('media');
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

            $(this).find('#source-name').val(mediaSource.source_name);
            $(this).find('#source-text').val(mediaSource.source_text);
            $(this).find('#tags').val(mediaSource.tags);

        });
        //##### END UPLOAD #####

        //Download Operation
        $('#download-modal').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
        $('#download-modal .btn-ok').on('click', function(e) {
            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data) {
                    $('#download-modal').modal('toggle');
                    location.reload(true);
                }
            });
            return false;
        });
    </script>
@stop
