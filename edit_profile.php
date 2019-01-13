<!-- Include header.php -->
<?php include 'include/header.php'; ?>
<!-- end of header.php  -->

<!-- Check Login -->
<?php Session::checkSession(); ?>
<!-- End Of Check Login -->

<!-- Passed Form Input To Account Class -->
  <?php
    if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['edit'])){

        $updateProfile = $user->updateProfile($_POST);
    }
   ?>
<!-- End of Passing input -->

    <!-- Navbar -->
    <?php include 'include/navbar.php'; ?>

    <div class="container">

        <div class="row">

            <div class="col-md-6 mx-auto">
                <div class='card card-body  bg-light mt-5'>

                    <h2>Update Your account Details</h2>
                    <p>

                    <?php
                    echo $fm->getMsg('msg');

                    //getting errors on form
                    $err = $fm->getMsg('errors');

                    //getting data back which was entered on form
                    $data = $fm->getMsg('form_data');
                    ?>

                    </p>
                    <form action="" method='POST'>
                        <div class="form-group">
                            <label for='name'>Name: <sup>*</sup></label>
                            <input type='name' name="name" value="<?php echo($data['name']); ?>" class="form-control form-control-lg <?php echo(isset($err['name_error'])) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo($err['name_error']); ?></span>
                        </div>







                        <div class="row">

                            <div class='col'>

                                <input type='submit' name='edit' value='Update Now' class='btn color-set btn-block'>

                            </div>


                            <div class='col'>

                                <a href="change_password.php" class="btn btn-light btn-block">Wanna Change Password? </a>

                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>


    </div>



 <!-- Footer -->
<?php include 'include/footer.php'; ?>
