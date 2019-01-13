<!-- Include header.php -->
<?php include 'include/header.php'; ?>
<!-- end of header.php  -->

<!-- Check Session -->
<?php Session::checkSession(); ?>
<!-- End Of Check Login -->

    <!-- Navbar -->
    <?php include 'include/navbar.php'; ?>

    <div class="container">

        <div class='jumbotron jumbotron-fluid text-center color-set'>
            <div class="container">
                <h1 class='display-3'>
                    Profile Managment
                </h1>

            </div>
        </div>

        <div class="col-md-6 mx-auto">

            <div class='card'>


                <div class="card-header color-set">

                    Your Profile Data
                </div>
                <div class='card-body '>
                    <?php
                    echo $fm->getMsg('msg_notify');
                    ?>

                    <div class="row">
                        <?php
                        $getUserData = $user->getUserData();
                        if ($getUserData){
                            while ($result=$getUserData->fetch_assoc()) { ?>


                        <div class="col-md-8 mx-auto">
                            <div class='detail-text'>
                                <label for="name"><strong>Name:</strong></label>
                                <span class='text-data'> <?php echo $result['name']; ?></span>
                            </div>

                            <div class='detail-text'>
                                <label for="name"><strong>Email:</strong></label>
                                <span class='text-data'> <?php echo $result['email']; ?></span>
                            </div>

                            <div class='detail-text'>
                                <label for="name"><strong>Account Status:</strong></label>
                                <span class='text-data'> <?php echo $result['is_active'] == 0 ? 'Not Verified': 'Verified'; ?> </span>
                            </div>

                            <hr/>
                            <div class='detail-text'>
                                <label for="name"><strong>Created at:</strong></label>
                                <span class='text-data'><?php echo $result['created_at']; ?></span>
                            </div>

                        </div>
                        <?php }  } ?>

                    </div>

                </div>
                <div class='card-footer'>
                    <a href='' data-toggle="modal" data-target="#myModal"><i class='fa fa-trash-o'></i></a>
                    <a href="edit_profile.php" class='pull-right'><i class='fa fa-pencil-square-o'></i></a>
                </div>

            </div>
        </div>

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" style='cursor:pointer;'>&times;</button>

                    </div>
                    <div class="modal-body text-center">
                        <p>Do you really want to deactive your account?</p>
                    </div>
                    <div class="modal-footer">
                        <a href="" class="btn btn-danger">Yes</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal" style='cursor:pointer;'>No</button>
                    </div>
                </div>

            </div>
        </div>



    </div>



<!-- Footer -->
<?php include 'include/footer.php'; ?>
