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

        <form action="{{ route('app-update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">App Name <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="name" value="{{ $data->name }}" placeholder="Name"
                        required>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword4">App ID <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="app_id" value="{{ $data->app_id }}"
                        placeholder="App ID" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="inputPassword4">Client Secret <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="client_secret" value="{{ $data->client_secret }}"
                        placeholder="Client Secret" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="inputPassword4">User ID <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="user_id" placeholder="User Id" value="{{ $data->user_id }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="inputPassword4">User Name</label>
                    <input type="text" class="form-control" name="user_name" placeholder="User Name" value="{{ $data->user_name }}">
                </div>

                <div class="form-group col-md-4">
                    <label for="inputEmail4">Access Token <em class="em-red">*</em></label>
                    <input type="text" class="form-control" name="short_access_token" value="{{ $data->short_access_token }}"
                        placeholder="Access Token" required>
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
