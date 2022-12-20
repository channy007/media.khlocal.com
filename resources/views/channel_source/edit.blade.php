@extends('layouts.homepage')
@section('content')
    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="#">Channel Source</a></li>
            <li class="breadcrumb-item"><a href="#">Edit</a></li>

        </ol>
    </nav>
    <div class="my-container shadow p-3 mb-5 bg-white rounded">
        @if ($errors->any())
            <div class="alert alert-danger">
                There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('channel-source-update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="channel">Channel *</label>
                    <select name="channel" class="form-control" required>
                        <option value="youtube" {{ $data->channel == 'youtube' ? 'selected' : '' }} selected>Youtube
                        </option>
                        <option value="facebook" {{ $data->channel == 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="tiktok" {{ $data->channel == 'tiktok' ? 'selected' : '' }}>Tiktok</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="name">Name *</label>
                    <input type="text" class="form-control" name="name" value="{{ $data->name }}" placeholder="Name"
                        required>
                </div>

                <div class="form-group col-md-4">
                    <label for="url">URL *</label>
                    <input type="text" class="form-control" name="url" value="{{ $data->url }}" placeholder="URL"
                        required>
                </div>

                <div class="form-group col-md-4">
                    <label for="custom_crop">Custom Crop</label>
                    <input type="text" class="form-control" name="custom_crop" placeholder="Custom Crop"
                        value="{{ $data->custom_crop }}" id="custom-crop">
                </div>

                <div class="form-group col-md-4">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" placeholder="Description" value="{{ $data->description }}">
                </div>

                <div class="form-group col-md-4">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" name="country" placeholder="Country" value="{{ $data->country }}">
                </div>

                <div class="form-group col-md-4">
                    <label for="segment_cut">Segments Cut</label>
                    <input type="text" class="form-control" name="segment_cut" placeholder="Segment Cut (00:05:00,00:06:25)" value="{{ $data->segment_cut }}"
                        id="segment-cut">
                </div>

            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputState">Media Projects</label>
                    <select name="media_project_ids[]" class="media-project form-control" multiple="multiple">
                        @foreach ($data->media_projects as $mediaProject)
                            @if (isset($mediaProject->project))
                                <option value="{{ $mediaProject->project->id }}" selected>
                                    {{ $mediaProject->project->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <br>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="javascript:history.back()" class="btn btn-default waves-effect waves-themed">
                        <span class="fal fa-times"></span> Close
                    </a>
                </div>
            </div>
        </form>
    </div>
@stop
@section('scripts')

    <script type="text/javascript">
        var url = "{{ route('media-project-list') }}";
        $('.media-project').select2({
            placeholder: "Select media projects..",
            ajax: {
                url: url,
                data: function(params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    };
                    return query;
                },
                processResults: function(data) {
                    var newdata = data.data.map(function(mediaProject) {
                        return {
                            id: mediaProject.id,
                            text: mediaProject.name
                        };
                    });
                    newdata.unshift({
                        id: '',
                        text: 'Select channel sources..'
                    });
                    return {
                        results: newdata
                    };
                }
            }
        });
    </script>
@stop

