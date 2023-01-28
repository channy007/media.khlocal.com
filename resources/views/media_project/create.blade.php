@extends('layouts.homepage')

@section('content')
    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="javascript:;">Media Project</a></li>
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

        <form action="{{ route('media-project-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="project_id">Application <em class="em-red">*</em></label>
                    <select name="application_id" class="form-control" id="project-id" required>
                        <option value="" selected>Choose Application..</option>
                        @foreach ($applications as $app)
                            <option value="{{ $app->id }}">{{ $app->name }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Name <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword4">Channel <em class="em-red">*</em></label>
                    <select name="channel" class="form-control">
                        <option value="youtube">Youtube</option>
                        <option value="facebook" selected>Facebook</option>
                        <option value="tiktok">Tiktok</option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="inputPassword4">Resolution</label>
                    <select name="resolution" class="form-control">
                        <option value="16:9">16:9</option>
                        <option value="4:3" selected>4:3</option>
                        <option value="1:1">1:1</option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="inputEmail4">Page ID <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="page_id" placeholder="Page ID" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="inputEmail4">Access Token <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="access_token" placeholder="Access Token" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="tags">Tags</label>
                    <input type="text" class="form-control" name="tags" placeholder="Tags">
                </div>

                <div class="form-group col-md-4">
                    <label for="inputState">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" selected>Active</option>
                        <option value="inactive">InActive</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputState">Channel Sources</label>
                    <select name="channel_source_ids[]" class="channel-sources form-control" multiple="multiple">
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
