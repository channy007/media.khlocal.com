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
                        <th scope="col">Action</th>
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
                        <th scope="col">Flip</th>
                        <th scope="col">Cut Off</th>
                        <th scope="col">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $count => $mediaSource)
                        <tr>
                            <td>{{ $count }}</td>
                            <td>{{ optional($mediaSource->project)->name }}</td>
                            <td>{{ $mediaSource->status }}</td>
                            <td style="width: 10%;">
                                <div class="row justify-content-center">

                                    @switch($mediaSource->status)
                                        @case('download_error')
                                            <a data-href="{{ route('media-source-retry-download', $mediaSource->id) }}"
                                                class="btn btn-primary btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
                                                style="height: 25px;width: 25px; text-align: center;display: flex;justify-content: center;">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @break

                                        @case('downloaded')
                                        @case('cut_error')
                                            <a data-href="{{ route('media-source-retry-cut', $mediaSource->id) }}"
                                                class="btn btn-warning btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
                                                style="height: 25px;width: 25px; text-align: center;display: flex;justify-content: center;"
                                                data-toggle="modal" data-target="#cut-modal">
                                                <i class="fas fa-cut"></i>
                                            </a>
                                        @break

                                        @case('cutted')
                                            <a data-href="{{ route('media-source-retry-upload', $mediaSource->id) }}"
                                                class="btn btn-dark btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
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

                            <td><a class="link" href="{{ $mediaSource->source_url }}"
                                    target="__blank">{{ $mediaSource->source_url }}</a></td>
                            <td>{{ $mediaSource->source_from }}</td>
                            <td>{{ $mediaSource->source_channel }}</td>
                            <td>{{ $mediaSource->source_text }}</td>
                            <td>{{ $mediaSource->transition }}</td>
                            <td>{{ $mediaSource->resolution }}</td>
                            <td>{{ $mediaSource->seg_start }}</td>
                            <td>{{ $mediaSource->seg_length }}</td>
                            <td>{{ $mediaSource->seg_gap }}</td>
                            <td>{{ $mediaSource->segment }}</td>
                            <td>{{ $mediaSource->flip }}</td>
                            <td>{{ $mediaSource->cut_off }}</td>
                            <td>{{ $mediaSource->created_at ? getDateString($mediaSource->created_at, 'd-M-Y h:i a') : '' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            {!! $datas->appends(request()->except('page'))->links('includes.pagination.custom') !!}
        </div>
    </div>


    <!-- Modal Cut -->
    <div class="modal fade" id="cut-modal" tabindex="-1" role="dialog" aria-labelledby="cut-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="cut-modal">Do you want to retry to cut this file again?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    The file will be cut soon!
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-ok">I'm Sure</button>
                </div>

            </div>
        </div>
    </div>
    {{-- End Modal Cut --}}

    <!-- Modal Upload -->
    <div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="upload-modal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="upload-modal">Do you want to upload this file?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    The file will be upload soon!
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">I'm Sure</button>
                </div>

            </div>
        </div>
    </div>
    {{-- End Modal Upload --}}


@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.toast').toast('show')
        });

        //Cut Operation
        $('#cut-modal').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
        $('#cut-modal .btn-ok').on('click', function(e) {

            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data) {
                    $('#cut-modal').modal('toggle');
                    if (typeof table !== 'undefined') {
                        table.ajax.reload();
                    } else {
                        location.reload(true);
                    }
                }
            });
            return false;
        });
    </script>
@stop
