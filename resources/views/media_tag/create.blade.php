@extends('layouts.homepage')
@section('content')

    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="#">Media Tag</a></li>
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

        <form action="{{ route('media-tag-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Tag ID <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="tag_id" placeholder="Tag ID" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword4">Tag Name <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="tag_name" placeholder="Tag Name" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="inputPassword4">Tag Description</label>
                    <input type="text" class="form-control" name="tag_description" placeholder="Tag Description">
                </div>

                <div class="form-group col-md-4">
                    <label for="channel">Channel</label>
                    <select name="tag_channel" class="form-control">
                        <option value="youtube">Youtube</option>
                        <option value="facebook" selected>Facebook</option>
                        <option value="tiktok">Tiktok</option>
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
