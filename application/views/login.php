<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-BK4BK9SW23"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-BK4BK9SW23');
        </script>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>include/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>include/dist/css/custom.css">
        <title> :: FEAST 3.0 ::</title>
    </head>

    <body class="bgx">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container pt-4">
                <a class="navbar-brand" href="#">
                <img src="<?php echo base_url(); ?>include/dist/img/ilri_logo.png" width="70%" height="60%" alt="Logo icrisat">
                    <!-- <img src="<?php echo base_url(); ?>include/dist/img/munichRe.svg"  class="img-fluid" alt="Logo"> -->
                    <!-- <h1 style="margin-top: 40px">FEAST 3.0</h1> -->
                </a>
            </div>

        </nav>
        <section class="content-height">
            <div class="container vertical-center">
                <div class="row">
                    <div class="col-md-5 mr-auto">
                        <div class="card shadow" id="loginCard">
                            <nav class="nav-justified">
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active p-3 text-left" id="nav-login-tab" data-toggle="tab"
                                    href="#nav-login" role="tab" aria-controls="nav-login"
                                    aria-selected="false">FEAST 3.0 Login</a>
                                    <a class="nav-item nav-link active p-3 text-center" id="nav-login-tab"
                                    href="<?php echo base_url(); ?>userregistration/create"
                                    aria-selected="false" style="flex: 1; text-align: center; padding: 10px 20px; background: #28a745; color: #fff;  text-decoration: none; font-weight: 600;">Register</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active pt-2" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
                                    <?php echo form_open('', array('class' => 'form-horizontal form-simple')); ?>
                                    <div class="form-group">
                                        <label for="email">Email Address / Username</label>
                                        <input type="text" class="form-control" id="email" placeholder="Email / Username" name="email" value="<?php echo $email; ?>" autocomplete="off">
                                        <span class="text-danger email error" id="email-error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" autocomplete="off">
                                        <span class="text-danger password error" id="password-error"></span>
                                    </div>
                                    <!-- <div class="form-check d-inline"> -->
                                        <!-- <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                        <small class="form-check-label font-css" for="exampleCheck1">Remember
                                        Me</small> -->
                                        <!-- <a class="float-right" href="<?php echo base_url(); ?>password/lostpassword/"><small class="form-check-label font-css float-right" for="exampleCheck2">Forgot Password?</small></a> -->
                                    <!-- </div> -->
                                    <div class="my-3 mt-4">
                                        <a style="float: right;" href="<?php echo base_url(); ?>userregistration/forgotpassword">Forgot Password</a>
                                        <button type="submit" class="btn btn-size btn-success py-2 px-4">SIGN IN</button>
                                    </div>
                                    <span class="text-danger form error" id="form-error"></span>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 ml-auto text-center" style="margin-top:-90px;">
                        <div class="mt-3">
                            <img src="<?php echo base_url(); ?>include/dist/img/illustration.png" width="100%" class="img-fluid" alt="vector" style="margin-top: 30px">
                        </div>
                        <!-- <div class="mt-3">
                            <img src="<?php echo base_url(); ?>include/dist/img/ilri_logo.png" width="50%" height="70%" alt="Logo icrisat" style="margin-top: 30px">
                        </div> -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="<?php echo base_url(); ?>include/js/jquery-3.5.1.min.js"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script> -->
        <script src="<?php echo base_url(); ?>include/dist/js/bootstrap.min.js"></script>

        <!-- Page Script -->
        <script type="text/javascript">
            $(function(){
                $('form').on('submit', function(event) {
                    event.preventDefault();
                    $('.error').html('');
                    $('button[type="submit"]').attr('disabled', 'disabled').html('Please Wait...');

                    initLogin($(this), false);
                });
            });

            function initLogin(form, email) {
                var url = '<?php echo base_url(); ?>auth/login/';
                
                fromData = new FormData(form[0]);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: fromData,
                    processData: false,
                    contentType: false,
                    complete: function(data) {
                        var csrfData = JSON.parse(data.responseText);
                        if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
                            $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
                        }
                    },
                    error: function() {
                        $('#form-error').html('Could not establish connection to server. Please refresh the page and try again.');
                        $('button[type="submit"]').removeAttr('disabled').html('Sign in');
                    },
                    success: function(data) {
                        var data = JSON.parse(data);
                        if(data.status == 0) {
                            $('button[type="submit"]').removeAttr('disabled').html('LOGIN');
                            $('#email-error').html(data.email);
                            $('#password-error').html(data.password);
                            $('#form-error').html(data.form);
                        } else {
                            window.location.href = data.redirect;
                        }
                    }
                });
            }
        </script>
    </body>

</html>