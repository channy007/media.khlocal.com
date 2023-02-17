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
                    <label for="project_id">Application <em class="em-red">*</em></label>
                    <select name="application_id" class="form-control" id="project-id" required>
                        <option value="" selected>Choose Application..</option>
                        @foreach ($applications as $app)
                            <option value="{{ $app->id }}" {{ $data->application_id == $app->id ? 'selected' : '' }}>
                                {{ $app->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="form-group col-md-4">
                    <label for="inputEmail4">Name <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="name" value="{{ $data->name }}" placeholder="Name"
                        required>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword4">Channel <em class="em-red">*</em></label>
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
                    <label for="inputEmail4">Page ID <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="page_id" value="{{ $data->page_id }}"
                        placeholder="Page ID" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Access Token</label>
                    <input type="text" class="form-control" name="short_user_access_token" value="{{ $data->short_user_access_token }}"
                        placeholder="Access Token">
                </div>
                
                <div class="form-group col-md-4">
                    <label for="inputState">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ $data->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $data->status == 'inactive' ? 'selected' : '' }}>InActive</option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="inputState">Tags</label>
                    <select name="tags[]" class="tags form-control" multiple="multiple">
                        @foreach ($data->media_tags as $tag)
                        <option value="{{ $tag->tag_id }}" selected>
                            {{ $tag->tag_name }}</option>
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
    
        var url = "{{ route('tags-list') }}";
        $('.tags').select2({
            placeholder: "Select Tags..",
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
                    var newdata = data.data.map(function(tag) {
                        return {
                            id: tag.tag_id,
                            text: tag.tag_name
                        };
                    });
                    newdata.unshift({
                        id: '',
                        text: 'Select Tags..'
                    });
                    return {
                        results: newdata
                    };
                }
            }
        });
    </script>
@stop
