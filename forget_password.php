<!-- Include header.php -->
<?php include 'include/header.php'; ?>
<!-- end of header.php  -->

<!-- Check Login -->
<?php Session::checkLogin(); ?>
<!-- End Of Check Login -->

<!-- Passed Form Input To Account Class -->
  <?php
    if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['reset_password'])){

        $forgetPassword = $user->forgetPassword($_POST);
    }
   ?>
<!-- End of Passing input -->

    <!-- Navbar -->
    <?php include 'include/navbar.php'; ?>

    <div class="container">


        <div class="row">

            <div class="col-md-6 mx-auto">
                <div class='card card-body  bg-light mt-5'>
                    <h2>Reset Password</h2>
                    <p>Please fill in credentials to get reset link.</p>
                    <?php
                        echo $fm->getMsg('msg');
                        //Getting Errors on Form
                        $err = $fm->getMsg('errors');



                        //Getting Data Back Which Was Entered on From
                        $data = $fm->getMsg('form_data');
                    ?>

                    <form action="" method='POST'>


                      <div class="form-group">
                        <label for='email'>Email: <sup>*</sup></label>
                        <input type='email' name="email" value="<?php echo($data['email']); ?>" class="form-control form-control-lg <?php echo(isset($err['email_error'])) ? 'is-invalid' : ''; ?>">
                         <span class="invalid-feedback"><?php echo($err['email_error']); ?></span>
                    </div>



                        <div class="row">

                            <div class='col'>

                                <input type='submit' name='reset_password' value='Send Reset Link' class='btn  btn-block color-set'>

                            </div>



                        </div>
                        <div class="row">
                            <div class='col'>

                                <a href="" class="btn text-right  btn-block">Go Back to Login </a>

                            </div>

                        </div>


                    </form>

                </div>
            </div>

        </div>


    </div>



 <!-- Footer -->
<?php include 'include/footer.php'; ?>
