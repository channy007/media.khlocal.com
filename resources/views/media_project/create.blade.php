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

        <form action="{{ route('media-project-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Name *</label>
                    <input type="text" class="form-control" name="name" placeholder="Name"
                        required>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword4">Channel *</label>
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

            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputPassword4">App ID</label>
                    <input type="text" class="form-control" name="app_id"
                        placeholder="App ID">
                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Page ID *</label>
                    <input type="text" class="form-control" name="page_id"
                        placeholder="Page ID" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Access Token *</label>
                    <input type="text" class="form-control" name="access_token"
                        placeholder="Access Token" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputState">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" selected>Active</option>
                        <option value="inactive">InActive</option>
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
