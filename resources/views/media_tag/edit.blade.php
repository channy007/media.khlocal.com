@extends('layouts.homepage')
@section('content')
    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="#">Application</a></li>
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

        <form action="{{ route('media-tag-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Tag ID *</label>
                    <input type="text" class="form-control" name="tag_id" placeholder="Tag ID" required value="{{ $data->tag_id }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword4">Tag Name *</label>
                    <input type="text" class="form-control" name="tag_name" placeholder="Tag Name" required value="{{ $data->tag_name }}">
                </div>

                <div class="form-group col-md-4">
                    <label for="inputPassword4">Tag Description</label>
                    <input type="text" class="form-control" name="tag_description" placeholder="Tag Description" value="{{ $data->tag_description }}">
                </div>

                <div class="form-group col-md-4">
                    <label for="channel">Channel</label>
                    <select name="channel" class="form-control" required>
                        <option value="youtube" {{ $data->tag_channel == 'youtube' ? 'selected' : '' }} selected>Youtube
                        </option>
                        <option value="facebook" {{ $data->tag_channel == 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="tiktok" {{ $data->tag_channel == 'tiktok' ? 'selected' : '' }}>Tiktok</option>
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
