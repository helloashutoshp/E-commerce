@extends('basic.layout.app')

@section('title')
    Register
@endsection

@section('content')
    <div class="card container">
        <div class="card-header">
            <h4><strong>Register</strong></h4>
        </div>
        <div class="card-body">
            <form enctype="multipart/form-data" id="register" action="">
                <div class="form-group mb-3">
                    <label for="fname"><b>First Name</b></label>
                    <input type="text" class="form-control" id="fname" placeholder="Enter first name" name="fname">
                    <p class="error"></p>

                </div>
                <div class="form-group mb-3">
                    <label for="lname"><b>Last Name</b></label>
                    <input type="text" class="form-control" id="lname" placeholder="Enter last name" name="lname">
                    <p class="error"></p>

                </div>

                <div class="form-group mb-3">
                    <label for="email"><b>Email address</b></label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                    <p class="error"></p>

                </div>

                <div class="form-group mb-3">
                    <label for="phone"><b>Phone Number</b></label>
                    <input type="text" class="form-control" id="phone" placeholder="Enter phone number"
                        name="phone">
                    <p class="error"></p>

                </div>

                <div class="form-group mb-3">
                    <label for="age"><b>Age</b></label>
                    <input type="number" class="form-control" id="age" placeholder="Enter age" name="age">
                    <p class="error"></p>
                </div>

                <div class="form-group mb-3">
                    <label for="gender"><b>Gender</b></label>
                    <select name="gender" id="gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="language"><b>Language Known</b></label><br>
                    @if ($language->isNotEmpty())
                        @foreach ($language as $lan)
                            <input type="checkbox" value="{{ $lan->id }}" name="language[]">
                            <label for="java">{{ $lan->name }}</label><br>
                        @endforeach
                    @endif
                    <p class="error"></p>

                </div>
                <div class="form-group mb-3">
                    <label for="images"><b>Upload Pictures</b></label>
                    <input type="file" class="images" name="images[]" id="images" multiple>
                    <p class="error"></p>

                </div>
                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" name="password">

                    <p class="error" id="passError"></p>
                    <div class="form-group">
                        <input type="checkbox" name="pass" id="pass">
                        <span class="ml-5">
                            Show password
                        </span>
                    </div>
                </div>
                <div id="message">
                    <h3>Password must contain the following:</h3>
                    <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                    <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                    <p id="number" class="invalid">A <b>number</b></p>
                    <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                </div>
                <div class="form-group mb-3">
                    <label for="cnf_password"><b>Confirm Password</b></label>
                    <input type="password" class="form-control" id="cpassword" placeholder="Confirm your password"
                        name="cpassword">
                    <p class="error"></p>
                </div>






                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // function showPassword() {
        $('#pass').on('change', function() {
            var pass = $("#password");
            if ($(this).is(':checked')) {
                pass.attr("type", 'text');
            } else {
                pass.attr("type", 'password');

            }
        })

        // }

        function chkPassword() {

            var myInput = document.getElementById("password");
            var letter = document.getElementById("letter");
            var capital = document.getElementById("capital");
            var number = document.getElementById("number");
            var length = document.getElementById("length");

            // When the user clicks on the password field, show the message box
            myInput.onfocus = function() {
                document.getElementById("message").style.display = "block";
            }

            // When the user clicks outside of the password field, hide the message box
            myInput.onblur = function() {
                document.getElementById("message").style.display = "none";
            }

            // When the user starts to type something inside the password field
            myInput.onkeyup = function() {
                // Validate lowercase letters
                var lowerCaseLetters = /[a-z]/g;
                if (myInput.value.match(lowerCaseLetters)) {
                    letter.classList.remove("invalid");
                    letter.classList.add("valid");
                } else {
                    letter.classList.remove("valid");
                    letter.classList.add("invalid");
                }

                // Validate capital letters
                var upperCaseLetters = /[A-Z]/g;
                if (myInput.value.match(upperCaseLetters)) {
                    capital.classList.remove("invalid");
                    capital.classList.add("valid");
                } else {
                    capital.classList.remove("valid");
                    capital.classList.add("invalid");
                }

                // Validate numbers
                var numbers = /[0-9]/g;
                if (myInput.value.match(numbers)) {
                    number.classList.remove("invalid");
                    number.classList.add("valid");
                } else {
                    number.classList.remove("valid");
                    number.classList.add("invalid");
                }

                // Validate length
                if (myInput.value.length >= 8) {
                    length.classList.remove("invalid");
                    length.classList.add("valid");
                } else {
                    length.classList.remove("valid");
                    length.classList.add("invalid");
                }
            }

        }
        $('#register').submit(function(event) {
            event.preventDefault();
            chkPassword();
            var formData = new FormData(this);
            $.ajax({
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{ route('store') }}",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == true) {
                        $("input[type='text'],input[type='number'],input[type='password'],input[type='file'],input[type='email']")
                            .removeClass('is-invalid');
                        $('.error').removeClass('invalid-feedback').html('');
                        alert("data inserted");

                    } else {
                        var errors = response['errors'];
                        // console.log('errors'+response);
                        $("input[type='text'],input[type='number'],input[type='password'],input[type='file'],input[type='email']")
                            .removeClass('is-invalid');
                        $('.error').removeClass('invalid-feedback').html('');
                        $.each(errors, function(key, value) {
                            if (errors.language) {
                                $("input[name='language[]']").addClass('is-invalid')
                                    .closest('.form-group')
                                    .find('.error')
                                    .addClass('invalid-feedback')
                                    .html(errors.language[0]);
                            }
                            // $("#images").addClass('is-invalid')
                            //     .siblings('.error')
                            //     .addClass('invalid-feedback')
                            //     .html(images[0]);
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(value);
                        })
                    }
                }
            });
        });
    </script>
@endsection

