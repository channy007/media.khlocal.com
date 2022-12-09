@extends('layouts.homepage')
@section('content')

    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="#">Application</a></li>
        </ol>
    </nav>

    <div class="my-container shadow p-3 mb-5 bg-white rounded">

        @if ($message = Session::get('success'))
            @include('includes.alerts.success')
        @endif
        
        <form action="{{ route('app-index') }}" method="GET" enctype="multipart/form-data">
            <div class="d-flex" >
                
                <div class="p-2">
                    <div class="input-group">
                        <input class="form-control" name="search" type="search" placeholder="search" value="{{ $search }}" id="example-search-input">
                        <span class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="ml-auto p-2">
                    <a class="btn btn-primary" href="{{ route('app-create') }}">
                        <i class="fas fa-plus"></i> <span class="remove-mobile">{{ __('Add New') }}<span>
                    </a>
                </div>
            </div>
        </form>

        <br>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">App ID</th>
                        <th scope="col">Client Secret</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $count => $application)
                        <tr>
                            <td>{{ $count + 1 }}</td>
                            <td>{{ $application->name }}</td>
                            <td>{{ $application->app_id }}</td>
                            <td class="hidetext">{{ $application->client_secret }}</td>
                            <td style="text-align: center">
                                <a href="{{ route('app-edit', $application->id) }}"
                                    class="btn btn-primary btn-sm btn-icon rounded-circle waves-effect waves-themed btn-edit"
                                    style="height: 25px;width: 25px; text-align: center;display: flex;justify-content: center;">
                                    <i class="far fa-edit"></i>
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $datas->appends(request()->except('page'))->links('includes.pagination.custom') !!}
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.toast').toast('show')
        });
    </script>
@stop
