@extends('layouts.homepage')
@section('content')

    <nav aria-label="breadcrumb" style="margin-left: 10px;">
        <ol class="breadcrumb" style="background: none">
            <li class="breadcrumb-item"><a href="#">User</a></li>
        </ol>
    </nav>

    <div class="my-container shadow p-3 mb-5 bg-white rounded">

        @if ($message = Session::get('success'))
            @include('includes.alerts.success')
        @endif


        <form action="{{ route('user-index') }}" method="GET" enctype="multipart/form-data">
            <div class="d-flex" style="flex-wrap: wrap;">
                
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
                    <a class="btn btn-primary" href="{{ route('user-create') }}">
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
                        <th scope="col">Email</th>
                        <th scope="col">Username</th>
                        <th scope="col">Type</th>
                        <th scope="col">Projects</th>
                        <th scope="col">Created At</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $count => $user)
                        <tr>
                            <td>{{ $count + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ ucfirst($user->type) }}</td>
                            <td>
                                @if ($user->type == 'admin')
                                    <span class="badge badge-info">All</span>
                                @else
                                    @foreach ($user->projects as $project)
                                        <span class="badge badge-info">{{ $project->media_project->name }}</span><br>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $user->created_at ? getDateString($user->created_at, 'd-M-Y') : '' }}</td>
                            <td style="text-align: center">
                                <a href="{{ route('user-edit', $user->id) }}"
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
    <script type="text/javascript"></script>
@stop
