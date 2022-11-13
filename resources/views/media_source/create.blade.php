@extends('layouts.homepage')
@section('content')
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

        <form action="{{ route('media-source-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="col-md-4">
                    <div class="form-group col-md-12">
                        <label for="project_id">Thumbnail</label>
                        <div class="containerImg" style="width: 100%;height:100%;">
                            <img class="img-thumb" src="{{ asset('images/default_image.png') }}"
                                style="width: 100%;height: 100%;object-fit: cover" />
                            <input name="thumbnail" type='file' class="input-file" style="display: none" />
                        </div>

                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="project_id">Project Name *</label>
                            <select name="project_id" class="form-control" id="project-id"
                                onchange="projectChange({{ $projects }})">
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Source From *</label>
                            <select name="source_from" class="form-control">
                                <option value="youtube">Youtube</option>
                                <option value="facebook">Facebook</option>
                                <option value="tiktok">Tiktok</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="source_channel">Source Channel *</label>
                            <input type="text" class="form-control" name="source_channel" placeholder="Source Channel">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="source_name">Source Name *</label>
                            <input type="text" class="form-control" name="source_name" placeholder="Source Name">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="source_url">Source URL *</label>
                            <input type="text" class="form-control" name="source_url" placeholder="Source URL">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="source_text">Source Text</label>
                            <input type="text" class="form-control" name="source_text" placeholder="Source Text"
                                required>
                        </div>
                    </div>
                </div>




            </div>

            <div class="form-row">

                <div class="form-group col-md-4">
                    <label for="trainsition">Transition *</label>
                    <select name="transition" class="form-control">
                        <option value="circleclose">
                            circleclose
                        </option>
                        <option value="circlecrop">
                            circlecrop
                        </option>
                        <option value="circleopen">
                            circleopen
                        </option>
                        <option value="custom">
                            custom
                        </option>
                        <option value="diagbl">
                            diagbl
                        </option>
                        <option value="diagbr">
                            diagbr
                        </option>
                        <option value="diagtl">
                            diagtl
                        </option>
                        <option value="diagtr">
                            diagtr
                        </option>
                        <option value="dissolve">
                            dissolve
                        </option>
                        <option value="distance">
                            distance
                        </option>
                        <option value="fade" selected="">
                            fade
                        </option>
                        <option value="fadeblack">
                            fadeblack
                        </option>
                        <option value="fadefast">
                            fadefast
                        </option>
                        <option value="fadegrays">
                            fadegrays
                        </option>
                        <option value="fadeslow">
                            fadeslow
                        </option>
                        <option value="fadewhite">
                            fadewhite
                        </option>
                        <option value="hblur">
                            hblur
                        </option>
                        <option value="hlslice">
                            hlslice
                        </option>
                        <option value="horzclose">
                            horzclose
                        </option>
                        <option value="horzopen">
                            horzopen
                        </option>
                        <option value="hrslice">
                            hrslice
                        </option>
                        <option value="pixelize">
                            pixelize
                        </option>
                        <option value="radial">
                            radial
                        </option>
                        <option value="rectcrop">
                            rectcrop
                        </option>
                        <option value="slidedown">
                            slidedown
                        </option>
                        <option value="slideleft">
                            slideleft
                        </option>
                        <option value="slideright">
                            slideright
                        </option>
                        <option value="slideup">
                            slideup
                        </option>
                        <option value="smoothdown">
                            smoothdown
                        </option>
                        <option value="smoothleft">
                            smoothleft
                        </option>
                        <option value="smoothright">
                            smoothright
                        </option>
                        <option value="smoothup">
                            smoothup
                        </option>
                        <option value="squeezeh">
                            squeezeh
                        </option>
                        <option value="squeezev">
                            squeezev
                        </option>
                        <option value="vdslice">
                            vdslice
                        </option>
                        <option value="vertclose">
                            vertclose
                        </option>
                        <option value="vertopen">
                            vertopen
                        </option>
                        <option value="vuslice">
                            vuslice
                        </option>
                        <option value="wipebl">
                            wipebl
                        </option>
                        <option value="wipebr">
                            wipebr
                        </option>
                        <option value="wipedown">
                            wipedown
                        </option>
                        <option value="wipeleft">
                            wipeleft
                        </option>
                        <option value="wiperight">
                            wiperight
                        </option>
                        <option value="wipetl">
                            wipetl
                        </option>
                        <option value="wipetr">
                            wipetr
                        </option>
                        <option value="wipeup">
                            wipeup
                        </option>
                        <option value="zoomin">
                            zoomin
                        </option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="resolution">Resolution *</label>
                    <select name="resolution" class="form-control" id="resolution">
                        <option value="16:9">16:9</option>
                        <option value="4:3" selected>4:3</option>
                        <option value="1:1">1:1</option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="segment_start">Segment Start *</label>
                    <input type="number" class="form-control" name="segment_start" placeholder="Segment Start" required
                        value="5">
                </div>

                <div class="form-group col-md-4">
                    <label for="segment_length">Segment Length *</label>
                    <input type="number" class="form-control" name="segment_length" placeholder="Segment Length"
                        required value="90">

                </div>

                <div class="form-group col-md-4">
                    <label for="sagment_gap">Segment Gap *</label>
                    <input type="number" class="form-control" name="segment_gap" placeholder="Segment Gap" required
                        value="25">

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
        function projectChange(projects) {
            var projectId = document.getElementById('project-id').value;
            var selectedProject = projects.find(function(e) {
                return e.id == projectId;
            });
            $("#resolution option").each(function() {
                if ($(this).val() == selectedProject.resolution) { // EDITED THIS LINE
                    $(this).prop("selected", true);
                }
            });
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $(input).parent().find('.img-thumb').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('change', '.input-file', function() {
            readURL(this);
        });
        $(document).on('click', '.img-thumb', function() {
            var inputPhoto = $(this).parent().find(".input-file");
            inputPhoto.click();
        });
    </script>
@stop
