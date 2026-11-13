<br/>
<!-- Section-->
<section class="page-section" id="contact">
    <div class="container">
        <!-- Section Heading-->
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Login</h2>
        <!-- Icon Divider-->
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
            <div class="divider-custom-line"></div>
        </div>
        <?php if (session()->getFlashdata('message')) { ?>
            <div class="alert alert-danger">
                <?php echo session()->getFlashdata('message'); ?>
            </div>
        <?php } ?>
        <!-- Section Form-->
        <div class="row">
            <div class="col-lg-4 mx-auto">
                <!-- To configure the login form -->
                <?php echo form_open('login', ['id' => 'loginForm']); ?>
                <div class="form-group">
                    <input type="text" name="email" id="email" class="form-control" placeholder="Email" />
                    <?php if (isset($validation) && $validation->hasError('email')) { ?>
                        <div class="text-danger error"><?php echo esc($validation->getError('email')); ?></div>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" />
                    <?php if (isset($validation) && $validation->hasError('password')) { ?>
                        <div class="text-danger error"><?php echo esc($validation->getError('password')); ?></div>
                    <?php } ?>
                </div>
                <div class="form-group text-center">
                    <input type="submit" name="submit" value="Login" class="btn btn-primary"/>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>
