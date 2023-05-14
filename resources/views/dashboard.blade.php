@extends('layouts.master')
@include('layouts.inc.nav')

@section('content')
    <section class="container mt-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 col-md-6">
                    <form id="save_task" class="form-container" method="POST" action="{{route('save_task')}}">
                        <h2 class="text-center bg-dark p-2 text-white">Add Task Listing</h2>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title"
                                placeholder="Enter Title">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" id="description" placeholder="Enter Description"></textarea>
                        </div>

                        <button type="submit" class="btn btn-dark btn-block save_btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('javascript')
    <script>
        $(document).ready(function() {

            var pusher = new Pusher('c72c8b078db2e523645b', {
                cluster: 'ap2'
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {
                if (data.from) {
                    let pending = parseInt($('#' + data.from).find('.pending').html());
                    if (pending) {
                        $('#' + data.from).find('.pending').html(pending + 1);
                    } else {
                        $('#' + data.from).html(
                            '<a href="#" class="nav-link" data-toggle="dropdown"><i  class="fa fa-bell text-white"><span class="badge badge-danger pending">1</span></i></a>'
                        );
                    }
                }
            });

            $('#save_task').submit(function(e) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();

                $.ajax({
                    url: 'save_task',
                    method: 'POST',
                    data: $(this).serializeArray(),
                    success: function(data) {

                        if (!data.exists) {
                            increaseCartCount(1);
                        }

                        $.toaster({
                            priority: 'success',
                            message: data.msg,
                            'timeout': 3000,
                        });
                    },
                    error: function(data) {

                    },
                });
            });

        });
    </script>
@endpush
