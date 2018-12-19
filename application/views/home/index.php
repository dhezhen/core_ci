<html>
<head>
<title></title>
</head>
<body style="background-url(base_url().'uploads/photo/back.jpg')">

    <div class="login-box">
      <div class="login-logo">
        <a href="../../index2.html"><b><?php echo Property::get_abbr(); ?></b><?php echo Property::get_name(); ?></a>
      </div><!-- /.login-logo -->
      <div class="row-fluid">
      <?php
      if(isset($message)) {
        foreach($message as $key => $value) {
          echo "<div class='alert alert-$key alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  $value
                </div>";
        }
      }
      ?>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <?php echo form_open(base_url()."home/login"); ?>
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name="email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        <?php echo form_close(); ?>
        <a href="#">I forgot my password</a><br>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
	</body>
	</html>