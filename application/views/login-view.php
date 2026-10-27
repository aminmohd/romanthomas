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
        <?php if($this->session->flashdata('message')) { ?>
            <div class="alert alert-danger">
                <?php echo $this->session->flashdata('message')?>
            </div>
        <?php } ?>
        <!-- Section Form-->
        <div class="row">
            <div class="col-lg-4 mx-auto">
                <!-- To configure the login form -->
                <?php echo form_open('login', array('id' => 'loginForm')) ?>
                <div class="form-group">
                    <input type="text" name="email" id="email" class="form-control" placeholder="Email" />
                    <?php echo form_error('email', '<div class="text-danger error">', '</div>') ?>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" />
                    <?php echo form_error('password', '<div class="text-danger error">', '</div>') ?>
                </div>
                <div class="form-group text-center">
                    <input type="submit" name="submit" value="Login" class="btn btn-primary"/>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>