@extends('layouts.homepage')
@section('content')

    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="#">Media Project</a></li>
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

        <form action="{{ route('media-project-update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">

                <div class="form-group col-md-4">
                    <label for="project_id">Application *</label>
                    <select name="application_id" class="form-control" id="project-id" required>
                        <option value="" selected>Choose Application..</option>
                        @foreach ($applications as $app)
                            <option value="{{ $app->id }}" {{ $data->application_id == $app->id ? 'selected' : '' }}>
                                {{ $app->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="form-group col-md-4">
                    <label for="inputEmail4">Name *</label>
                    <input type="text" class="form-control" name="name" value="{{ $data->name }}" placeholder="Name"
                        required>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword4">Channel *</label>
                    <select name="channel" class="form-control">
                        <option value="youtube" {{ $data->channel == 'youtube' ? 'selected' : '' }}>Youtube</option>
                        <option value="facebook" {{ $data->channel == 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="tiktok" {{ $data->channel == 'tiktok' ? 'selected' : '' }}>Tiktok</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword4">Resolution</label>
                    <select name="resolution" class="form-control">
                        <option value="16:9" {{ $data->resolution == '16:9' ? 'selected' : '' }}>16:9</option>
                        <option value="4:3" {{ $data->resolution == '4:3' ? 'selected' : '' }}>4:3</option>
                        <option value="1:1" {{ $data->resolution == '1:1' ? 'selected' : '' }}>1:1</option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="inputEmail4">Page ID *</label>
                    <input type="text" class="form-control" name="page_id" value="{{ $data->page_id }}"
                        placeholder="Page ID" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Access Token *</label>
                    <input type="text" class="form-control" name="access_token" value="{{ $data->access_token }}"
                        placeholder="Access Token" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="tags">Tags</label>
                    <input type="text" class="form-control" name="tags" value="{{ $data->tags }}"
                        placeholder="Tags">
                </div>

                <div class="form-group col-md-4">
                    <label for="inputState">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ $data->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $data->status == 'inactive' ? 'selected' : '' }}>InActive</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputState">Channel Sources</label>
                    <select name="channel_source_ids[]" class="channel-sources form-control" multiple="multiple">
                        @foreach ($data->channel_sources as $projectChannleSource)
                            @if (isset($projectChannleSource->channel_source))
                                <option value="{{ $projectChannleSource->channel_source->id }}" selected>
                                    {{ $projectChannleSource->channel_source->name }}</option>
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
        var url = "{{ route('channel-source-list') }}";
        $('.channel-sources').select2({
            placeholder: "Select channel sources..",
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
                    var newdata = data.data.map(function(channelSource) {
                        return {
                            id: channelSource.id,
                            text: channelSource.name + `(${channelSource.channel})`
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
