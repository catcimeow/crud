@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="fw-bold text-secondary">Register</h2>
                </div>
                <div class="card-body p-5">
                    <div id="show_success_alert"></div>
                    <form action="{{ route('auth.register') }}" method="POST" id="register_form" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="fname" id="fname" class="form-control rounded-0" placeholder="First Name">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="text" name="lname" id="lname" class="form-control rounded-0" placeholder="Last Name">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="text" name="contact" id="contact" class="form-control rounded-0" placeholder="Contact Number">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="text" name="address" id="address" class="form-control rounded-0" placeholder="Address">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="text" name="barangay" id="barangay" class="form-control rounded-0" placeholder="Barangay">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="text" name="city" id="city" class="form-control rounded-0" placeholder="City">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="text" name="landmark" id="landmark" class="form-control rounded-0" placeholder="Landmark">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="email" name="email" id="email" class="form-control rounded-0" placeholder="Email">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" id="password" class="form-control rounded-0" placeholder="Password">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="cpassword" id="cpassword" class="form-control rounded-0" placeholder="Confirm Password">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="file" name="image" id="image" class="form-control rounded-0" accept="image/*">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <a class="text-decoration-none" href="/forgot">Forgot Password</a>
                        </div>

                        <div class="mb-3 d-grid">
                            <input type="submit" value="Register" class="btn btn-dark rounded-0" id="register_btn">
                        </div>

                        <div class="text-center text-secondary">
                            <div>Already have an account? <a href="/" class="text-decoration-none">Login Here</a></div>
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
    $(function() {
        $("#register_form").submit(function(e) {
            e.preventDefault();
            $("#register_btn").val('Please Wait...');

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route('auth.register') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.status === 422) {
                        showError('fname', res.message.fname);
                        showError('lname', res.message.lname);
                        showError('contact', res.message.contact);
                        showError('address', res.message.address);
                        showError('barangay', res.message.barangay);
                        showError('city', res.message.city);
                        showError('landmark', res.message.landmark);
                        showError('email', res.message.email);
                        showError('password', res.message.password);
                        showError('cpassword', res.message.cpassword);
                        $("#register_btn").val('Register');
                    } else if (res.status === 200) {
                        $("#show_success_alert").html(showMessage('success', res.message));
                        $("#register_form")[0].reset();
                        removeValidationClasses("#register_form");
                        $("#register_btn").val('Register');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred. Please try again later.');
                    $("#register_btn").val('Register');
                }
            });
        });

        function showError(field, message) {
            $('#' + field).addClass('is-invalid');
            $('#' + field).next('.invalid-feedback').text(message);
        }

        function showMessage(type, message) {
            return `<div class="alert alert-${type}">${message}</div>`;
        }

        function removeValidationClasses(form) {
            $(form).find('.is-invalid').removeClass('is-invalid');
            $(form).find('.invalid-feedback').text('');
        }
    });
</script>
@endsection
