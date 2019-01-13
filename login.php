<!-- Include header.php -->
<?php include 'include/header.php'; ?>
<!-- end of header.php  -->

<!-- Check Login -->
<?php Session::checkLogin(); ?>
<!-- End Of Check Login -->

<!-- Passed Form Input To Account Class -->
  <?php
    if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['login'])){

        $userLogin = $user->userLogin($_POST);
    }
   ?>
<!-- End of Passing input -->

    <!-- Navbar -->
    <?php include 'include/navbar.php'; ?>

    <div class="container">


        <div class="row">

            <div class="col-md-6 mx-auto">
                <div class='card card-body  bg-light mt-5'>
                    <h2>Login</h2>
                    <p>Please fill in credentials to log in.</p>
                    <?php
                       echo $fm->getMsg('msg_notify');

                    //getting errors on form
                    $err = $fm->getMsg('errors');

                    //getting data back which was entered on form
                    $data = $fm->getMsg('form_data');
                    ?>
                    <form action="" method='POST'>


                    <div class="form-group">
                        <label for='email'>Email: <sup>*</sup></label>
                        <input type='text' name="email" value="<?php echo($data['email']); ?>" class="form-control form-control-lg <?php echo(isset($err['email_error'])) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo($err['email_error']); ?></span>
                    </div>

                    <div class="form-group">
                        <label for='password'>Password: <sup>*</sup></label>
                        <input type='password' name="password" value="<?php echo($data['password']); ?>" class="form-control form-control-lg <?php echo(isset($err['password_error'])) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo($err['password_error']); ?></span>
                    </div>


                    <div class="form-check mb-2 text-center">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember-me">
                        <label class="form-check-label text-primary" for="exampleCheck1">Remember Me</label>
                    </div>


                <div class="row">
                    <div class='col'>
                        <input type='submit' name='login' value='Login' class='btn  btn-block color-set'>
                    </div>
                </div>
                <div class="row">
                    <div class='col'>
                        <a href="<?php echo(URLROOT); ?>/forget_password.php" class="btn  btn-block">Forget Passsword?</a>

                    </div>

                    <div class='col'>
                        <a href="<?php echo(URLROOT); ?>/register.php" class="btn  btn-block">No account? Register</a>
                    </div>
                </div>


                    </form>

                </div>
            </div>

        </div>


    </div>



<!-- Footer -->
<?php include 'include/footer.php'; ?>
