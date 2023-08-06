<?php include('../resources/templates/frontend/header.php'); ?>

<!-- Header End -->

<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="#"><i class="fa fa-home"></i> Home</a>
                    <span>Login</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Form Section Begin -->

<!-- Register Section Begin -->
<div class="register-login-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="login-form">
                    <?php display_msg(); ?>

                    <?php validate_users_login(); ?>
                    <h2>Login</h2>
                    <form action="#" method="post" class="was-validated checkout-form" name="myform">
                        <div class="group-input">
                            <label for="validationTextarea">Email<span>*</span></label>
                            <input type="email" name="email" id="register_email" tabindex="1" class="form-control"
                                placeholder="Email Address" value="" required>
                        </div>
                        <div class="group-input">
                            <label for="validationTextarea">Password<span>*</span></label>
                            <input type="password" name="password" id="register_email" tabindex="1" class="form-control"
                                placeholder="Password" value="" required>
                        </div>
                        <div class="group-input gi-check">
                            <div class="gi-more">
                                <div class="form-group text-center">
                                    <input type="checkbox" tabindex="3" class="" name="remember" id="remember">
                                    <label for="remember"> Remember Me</label>
                                </div>

                                <a href="#" class="forget-pass">Forget your Password</a>
                            </div>
                        </div>
                        <button type="submit" class="site-btn login-btn">Sign In</button>
                    </form>
                    <div class="switch-login">
                        <a href="./register.php" class="or-login">Or Create An Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Register Form Section End -->
<!-- Footer Section Begin -->
<?php include('../resources/templates/frontend/footer.php'); ?>