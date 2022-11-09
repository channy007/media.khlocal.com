@extends('layouts.homepage')

@section('style')
    <style type="text/css">
        .img-container {
            /* width: 50px; */
            margin: 10px 0px 10px 0px;
        }

        .form-group {
            margin-top: 25px;
        }
    </style>
@stop

@section('content')
    <div class="content">
        <form action="">
            <div class="container" style="width: 50%; margin-top: 5%;background-color: #e3f2fd;border-radius: 25px;">
                <div class="row text-black">
                    <div class="col-lg-5">
                        <div class="img-container">
                            <img src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg">
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="mx-auto form p-4">
                            <h2 class="justify-content-center text-start">
                                <b>Welcom to Codelab!</b>
                            </h2>

                            <form action="" class="justify-content-start">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="username" placeholder="username">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="email" placeholder="password">
                                </div>
                                <button type="submit" class="btn btn-primary" style="width: 150px;">Login</button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@stop
