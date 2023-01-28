@extends('layouts.homepage')
@section('content')

    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="#">Channel Source</a></li>
            <li class="breadcrumb-item"><a href="#">Create</a></li>

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

        <form action="{{ route('channel-source-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="channel">Channel</label>
                    <select name="channel" class="form-control">
                        <option value="youtube" selected>Youtube</option>
                        <option value="facebook">Facebook</option>
                        <option value="tiktok">Tiktok</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="name">Name <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="url">URL <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="url" placeholder="URL" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="custom_crop">Custom Crop</label>
                    <input type="text" class="form-control" name="custom_crop" placeholder="width:hieght:x:y"
                        id="custom-crop">
                </div>

                <div class="form-group col-md-4">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" placeholder="Description">
                </div>

                <div class="form-group col-md-4">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" name="country" placeholder="Country">
                </div>

                <div class="form-group col-md-4">
                    <label for="segment_cut">Exclude Segments (second)</label>
                    <input type="text" class="form-control" name="segment_cut" placeholder="Start-End (e.g 10-15;100-105;...)"
                        id="segment-cut">
                </div>

            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputState">Media Projects</label>
                    <select name="media_project_ids[]" class="media-project form-control" multiple="multiple">
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