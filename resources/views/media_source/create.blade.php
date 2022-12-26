@extends('layouts.homepage')


@section('content')
    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="#">Media Source</a></li>
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
        @include('includes.alerts.manual_success')

        <form id="form-data" action="{{ route('media-source-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="col-md-4">
                    <div class="form-group col-md-12">
                        <label for="project_id">Thumbnail</label>
                        <div class="containerImg" style="width: 100%;height:100%;">
                            <img class="img-thumb" src="{{ asset('images/default_image.png') }}"
                                style="width: 100%;height: 100%;object-fit: cover" />
                            <input name="thumbnail" type='file' class="input-file" style="display: none"
                                accept=".jpeg,.png" />
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
                            <label for="inputPassword4">Channel Source *</label>
                            <select name="channel_source_id" class="form-control source-from"
                                onchange="channelSourceChange(this)" required id="channel-source-id">
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="source_name">Source Name *</label>
                            <input type="text" class="form-control" name="source_name" placeholder="Source Name"
                                required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="source_url" id="source-label">Source URL *</label>

                            <div class="input-group">
                                <input type="text" class="form-control input-source-url" name="source_url"
                                    placeholder="Source URL">
                                <input type="text" class="form-control input-source-file-path" name="file_path"
                                    placeholder="File Path" style="display: none">

                                <div class="custom-file source-file-container" style="display: none">
                                    <input name="source_file" type='file' class="custom-file-input source-file"
                                        style="display: none" accept=".mp4" />
                                    <label id="label-source-file" class="custom-file-label" for="inputGroupFile01"
                                        style="overflow: hidden;;text-overflow: ellipsis;">Choose
                                        file</label>
                                </div>

                                <div style="width: 22%; margin: 0px;padding: 0px;">
                                    <select class="custom-select source-option" id="inputGroupSelect01" style="text-align: center;">
                                        <option value="url">URL</option>
                                        <option value="path">PATH</option>
                                        <option value="file">FILE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="source_text">Source Text</label>
                            <input type="text" class="form-control" name="source_text" placeholder="Source Text"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tag">Tags</label>
                            <input type="text" class="form-control" name="tags" id="tags" placeholder="Tags">
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
                    <label for="seg_start">Segment Start *</label>
                    <input type="number" class="form-control" name="seg_start" placeholder="Segment Start" required
                        value="5">
                </div>

                <div class="form-group col-md-4">
                    <label for="seg_length">Segment Length *</label>
                    <input type="number" class="form-control" name="seg_length" placeholder="Segment Length" required
                        value="90">

                </div>

                <div class="form-group col-md-4">
                    <label for="seg_gap">Segment Gap *</label>
                    <input type="number" class="form-control" name="seg_gap" placeholder="Segment Gap" required
                        value="25">

                </div>

                <div class="form-group col-md-4">
                    <label for="flip">Flip</label>
                    <select name="flip" class="form-control" id="resolution">
                        <option value="">Choose video flip..</option>
                        <option value="hflip">hflip</option>
                        <option value="vflip">vflip</option>
                    </select>

                </div>

                <div class="form-group col-md-4">
                    <label for="flip">Cut Off</label>
                    <select name="cut_off" class="form-control" id="resolution">
                        @foreach (range(0, 10) as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="form-group col-md-4">
                    <label for="cut_off_side">Cut Off Side</label>
                    <select name="cut_off" class="form-control" id="resolution">
                        <option value="0">Middle/Center</option>
                        <option value="1">Left/Top</option>
                        <option value="2">Right/Bottom</option>
                    </select>

                </div>


                <div class="form-group col-md-4">
                    <label for="custom_crop">Custom Crop</label>
                    <input type="text" class="form-control" name="custom_crop" placeholder="width:hieght:x:y"
                        id="custom-crop">
                </div>

                <div class="form-group col-md-4">
                    <label for="segment_cut">Exclude Segments (second)</label>
                    <input type="text" class="form-control" name="segment_cut" placeholder="Start-End (e.g 10-15;100-105;...)"
                        id="segment-cut">
                </div>

            </div>

            <br>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <button type="submit" class="btn btn-primary btn-submit">Save</button>
                    <a href="javascript:history.back()" class="btn btn-default waves-effect waves-themed">
                        <span class="fal fa-times"></span> Close
                    </a>
                </div>
            </div>
        </form>
    </div>
    <div class="submit-loader">
        <img src="{{ asset('images/spinner.gif') }}" alt="">
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#project-id').change();
        });

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

            var sourceFromOptions = `<option value="" selected>{{ __('Choose source from..') }}</option>`;
            selectedProject.channel_sources.forEach(channelSource => {
                sourceFromOptions +=
                    `<option value="${channelSource.channel_source.id}" data-custom_crop="${channelSource.channel_source.custom_crop}" data-segment-cut="${channelSource.channel_source.segment_cut}">${channelSource.channel_source.name}</option>`;
            });
            $('.source-from').html(sourceFromOptions);

            $('#tags').val(selectedProject.tags)
        }



        function channelSourceChange(obj) {
            var customCrop = obj.options[obj.selectedIndex].getAttribute('data-custom_crop');
            if (customCrop && customCrop != 'null') {
                $('#custom-crop').val(customCrop)
            }

            var segmentCut = obj.options[obj.selectedIndex].getAttribute('data-segment-cut');
            if (segmentCut && segmentCut != 'null') {
                $('#segment-cut').val(segmentCut)
            }


        }

        $('.source-option').on('change', function() {
            var option = this.value;

            switch (option) {
                case "file":
                    $('#source-label').text("Source File *");
                    displaySourceFile(true);
                    displayInputFilePath(false);
                    displayInputFileURL(false);
                    break;
                case "path":
                    $('#source-label').text("Source File Path *");
                    displaySourceFile(false);
                    displayInputFilePath(true);
                    displayInputFileURL(false);
                    break;
                default:
                    $('#source-label').text("Source URL *");
                    displaySourceFile(false);
                    displayInputFilePath(false);
                    displayInputFileURL(true);
            }
        });

        function displaySourceFile(display) {
            $('.source-file-container').css('display', display ? 'block' : 'none');
            $('.source-file').val('');
            $('.source-file').css('display', display ? 'block' : 'none');
        }

        function displayInputFilePath(display) {
            $('.input-source-file-path').css('display', display ? 'block' : 'none');
            $('.input-source-file-path').val("");
        }

        function displayInputFileURL(display) {
            $('.input-source-url').css('display', display ? 'block' : 'none');
            $('.input-source-url').val("");
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

        $(document).on('change', '.source-file', function() {
            var filename = this.files[0].name;
            $('#label-source-file').text(filename);
        });

        $(document).on('click', '.img-thumb', function() {
            var inputPhoto = $(this).parent().find(".input-file");
            inputPhoto.click();
        });

        $(document).on('submit', '#form-data', function(e) {
            e.preventDefault();
            $('.submit-loader').show();
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                timeout: 0,
                success: function(data) {
                    console.log("===================== sucess", data);
                    $('.submit-loader').hide();
                    $('.btn-submit').prop('disabled', true);
                    $('.alert-success').css('display', 'flex').text("Create successfully.");
                },
                failure: function(response) {
                    console.log("===================== failure", response);
                    $('.submit-loader').hide();

                },
                error: function(response) {
                    console.log("===================== error", response);
                    $('.submit-loader').hide();

                }

            });

        });
    </script>
@stop
