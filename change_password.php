<!-- Include header.php -->
<?php include 'include/header.php'; ?>
<!-- end of header.php  -->
<!-- Check Login -->
<?php Session::checkSession(); ?>
<!-- End Of Check Login -->

<!-- Passed Form Input To Account Class -->
  <?php
    if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['reset_password'])){

        $resetPassword = $user->resetPassword($_POST);
    }
   ?>
<!-- End of Passing input -->

    <!-- Navbar -->
    <?php include 'include/navbar.php'; ?>

    <div class="container">


        <div class="row">

            <div class="col-md-6 mx-auto mt-5">
                <div class='card card-body  bg-light mt-2 mb-5'>
                    <h2>Change your Password</h2>
                    <p>Please fill in credentials to Change Password.</p>
                    <?php
                    echo $fm->getMsg('msg');

                    echo $fm->getMsg('msg_notify');
                    //getting errors on form
                    $err = $fm->getMsg('errors');

                    //getting data back which was entered on form
                    $data = $fm->getMsg('form_data');
                    ?>
                    <form action="" method='POST'>

                        <div class="form-group">
                        <label for='password'>Old Password: <sup>*</sup></label>
                        <input type='password' name="old_password"  value="<?php echo($data['old_password']); ?>" class="form-control form-control-lg <?php echo(isset($err['old_password_error'])) ? 'is-invalid' : ''; ?>">
                      <span class="invalid-feedback"><?php echo($err['old_password_error']); ?></span>
                    </div>

                     <div class="form-group">
                        <label for='password'>Password: <sup>*</sup></label>
                        <input type='password' name="password" value="<?php echo($data['password']); ?>" class="form-control form-control-lg <?php echo(isset($err['password_error'])) ? 'is-invalid' : ''; ?>">
                         <span class="invalid-feedback"><?php echo($err['password_error']); ?></span>
                    </div>


                    <div class="form-group">
                        <label for='confirm_password'>Confirm Password: <sup>*</sup></label>
                        <input type='password' name="confirm_password" value="<?php echo($data['confirm_password']); ?>" class="form-control form-control-lg <?php echo(isset($err['confirm_password_error'])) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo($err['confirm_password_error']); ?></span>
                    </div>



                        <div class="row">

                            <div class='col'>

                                <input type='submit' name='reset_password' value='Reset Password' class='btn  btn-block color-set'>

                            </div>



                        </div>



                    </form>

                </div>
            </div>

        </div>


    </div>



<!-- Footer -->
<?php include 'include/footer.php'; ?>
