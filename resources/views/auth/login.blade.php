@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="fw-bold text-secondary">Login</h2>
                </div>
                <div class="card-body p-5">
                    <div id="login_alert"></div>
                    <form action="{{ route('login') }}" method="POST" id="login_form">
                        @csrf
                        <div class="mb-3">
                            <input type="email" name="email" id="email" class="form-control rounded-0"
                                placeholder="E-mail">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" id="password" class="form-control rounded-0"
                                placeholder="Password">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <a class="text-decoration-none" href="/forgot">Forgot Password</a>
                        </div>

                        <div class="mb-3 d-grid">
                            <button type="submit" class="btn btn-dark rounded-0" id="login_btn">Login</button>
                        </div>

                        <div class="text-center text-secondary">
                            <div>Don't have an account? <a href="{{ route('auth.register') }}"
                                    class="text-decoration-none">Register Here</a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function () {
        $('#login_form').submit(function (e) {
            e.preventDefault();
            $("#login_btn").val('Please Wait...');

            $.ajax({
                url: '{{ route('login') }}',
                method: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status == 400) {
                        showError('email', res.message.email);
                        showError('password', res.message.password);
                        $("#login_btn").val('Login');
                    } else if (res.status == 401) {
                        $("#login_alert").html(showMessage('danger', res.message));
                        $("#login_btn").val('Login');
                    } else if (res.status == 200 && res.message == 'Login Successful') {
                        window.location = '{{ route('profile') }}';
                    }
                }
            });
        });

        // Function to show error message for form fields
        function showError(field, errors) {
            $('#' + field).addClass('is-invalid');
            $('#' + field).siblings('.invalid-feedback').html(errors);
        }

        // Function to show alert message
        function showMessage(type, message) {
            return '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>';
        }
    });
</script>
@endsection
