@extends('layouts.homepage')
@section('content')

    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="#">User</a></li>
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

        <form action="{{ route('user-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Name *</label>
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="inputPassword4">Username *</label>
                    <input type="text" class="form-control" name="username" placeholder="Username">
                </div>

                <div class="form-group col-md-4">
                    <label for="inputPassword4">Password *</label>
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="form-group col-md-4">
                    <label for="type">Type *</label>
                    <select name="type" class="form-control" id="type">
                        <option value="editor" selected>Editor</option>
                        <option value="admin">Admin</option>
                    </select>

                </div>

                <div class="form-group col-md-4">
                    <label for="inputPassword4">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Email">
                </div>


            </div>

            <div class="form-row media-project-row">
                <div class="form-group col-md-4">
                    <label for="inputState">Media Projects</label>
                    <select name="project_ids[]" class="projects form-control" multiple="multiple">
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
        $('.projects').select2({
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
                        text: 'Select media project..'
                    });
                    return {
                        results: newdata
                    };
                }
            }
        });

        $('#type').on('change', function() {
            var type = this.value;

            switch (type) {
                case 'admin':
                    $('.media-project-row').css('display', 'none');
                    break;
                case 'editor':
                    $('.media-project-row').css('display', 'block');
                    break;
                default:
            }

        });

        $(document).ready(function() {
            $('#type').trigger('change');
        });
    </script>
@stop
