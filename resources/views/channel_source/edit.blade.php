@extends('layouts.homepage')
@section("style")
    <style>

        .btn-fill-channel-info:hover{
            cursor: pointer;
        }

        @keyframes colorChange {
            30% {
                background: rgb(66, 90, 224);  
                color: white ;  
            }
            20% {
                background: rgb(33, 33, 156);    
                color: white ;
            }
            50% {
                background: rgb(73, 89, 231);
                color: white ;  
            }
        }

    </style>

@stop
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
            <input type="hidden" value="{{url('/')}}" id="base_url">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="channel">Channel <em class="em-red">*</em></label>
                    <select name="channel" class="form-control" required>
                        <option value="youtube" {{ $data->channel == 'youtube' ? 'selected' : '' }} selected>Youtube
                        </option>
                        <option value="facebook" {{ $data->channel == 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="tiktok" {{ $data->channel == 'tiktok' ? 'selected' : '' }}>Tiktok</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="name">Name <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="name" id="channel-name" value="{{ $data->name }}" placeholder="Name"
                        required>
                </div>

                <div class="form-group col-md-4">
                    <label for="url">URL <em class="em-red">*</em></label>
                    
                    <div class="input-group" style="display: flex;flex-wrap: wrap">
                        <input type="text" class="form-control" id="input-channel-url" name="url" style="flex: 70%;" placeholder="URL" value="{{ $data->url }}"  required>
                        <div style="flex: 30%; margin: 0px;min-width: 95px;">
                            <button type="button" class="form-control btn-fill-source-info" onclick="fillChannelInfo()" >Auto Fill</button>
                        </div>
                    </div>

                </div>




                <div class="form-group col-md-4">
                    <label for="custom_crop">Custom Crop</label>
                    <input type="text" class="form-control" name="custom_crop" placeholder="width:hieght:x:y"
                        value="{{ $data->custom_crop }}" id="custom-crop">
                </div>

                <div class="form-group col-md-4">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" id="channel-description" name="description" placeholder="Description" value="{{ $data->description }}">
                </div>

                <div class="form-group col-md-4">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" name="country" placeholder="Country" value="{{ $data->country }}">
                </div>

                <div class="form-group col-md-4">
                    <label for="segment_cut">Exclude Segments (second)</label>
                    <input type="text" class="form-control" name="segment_cut" placeholder="Start-End (e.g 10-15;100-105;...)" value="{{ $data->segment_cut }}"
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
    <script src="{{ asset('js/channel-source.js') }}"></script>
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

