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
                    <label for="name">Name *</label>
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="url">URL *</label>
                    <input type="text" class="form-control" name="url" placeholder="URL" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="custom_crop">Custom Crop</label>
                    <input type="text" class="form-control" name="custom_crop" placeholder="Custom Crop"
                    id="custom-crop">
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
