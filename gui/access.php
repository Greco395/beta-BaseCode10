<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; BaseCode </title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="./node_modules/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/components.css">
</head>
<?php 
function signup($username,$password,$repassword){
  if(strlen($username) < 3 or $username > 20){
    return array(false,"Invalid username length (>3 and <20)");
    exit();
  }
  if(strlen($password) < 3 or $password > 20){
    return array(false,"Invalid pasword length (>3 and <20)");
    exit();
  }
  if($password != $repassword){
    return array(false,"Passwords Mismatch");
    exit();
  }
  $username = urlencode(htmlspecialchars($username));
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $userdata = fopen("userdata.php", "w") or die("Unable to write file!<br>Check permission");
  fwrite($userdata, "<?php \$real_username='{$username}'; \$real_password='{$hashed_password}'; ?>");
  fclose($userdata);
  return array(true, true);
}
function login($username,$password){
  include("userdata.php");
  if(urlencode(htmlspecialchars($username)) == $real_username){
    if(password_verify($password,$real_password)){
      session_name("secure");
      session_start();
      $_SESSION['logged'] = true;
      $_SESSION['username'] = $username;
      return array(true,true);
    }else{
      return array(false,"Incorrect password!");
    }
  }else{
    return array(false,"Username not found!");
  }

}
$msg = null;
  if(isset($_POST['case'])){
    if($_POST['case'] == "signup"){
      $rq = signup($_POST['username'],$_POST['password'],$_POST['repassword']);
      if($rq[0]){
        header("Location: ?signed=show_message");
      }else{
        $msg = '<div class="alert alert-danger">
        <strong>Error!</strong><br>
          '.$rq[1].'
      </div>';
      }
    }elseif($_POST['case'] == "login"){
      $rq = login($_POST['username'],$_POST['password']);
      if($rq[0]){
        header("Location: ./");
      }else{
        $msg = '<div class="alert alert-danger">
        <strong>Error!</strong><br>
          '.$rq[1].'
      </div>';
      }
    }
  }
?>
<body>
  <?php if(!include("userdata.php")){?>
    <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header"><h4>BaseCode <span style="color:red;">SETUP</span></h4></div>
              <div class="card-body">
                <?php 
                  if(is_null($msg)){?>
                    <div class="alert alert-danger">
                      <strong>No admin account found!</strong><br>
                        Fill the form to create one.
                    </div>
                  <?php }else{ 
                    echo $msg;
                  } ?>
                <form method="POST" action="" class="needs-validation" novalidate="">
                  <input type="hidden" name="case" value="signup" />
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="username" class="form-control" name="username" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in a username
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                    	<label for="password" class="control-label">Password</label>
                    </div>
                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                    <div class="invalid-feedback">
                      please fill in a password
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                    	<label for="repassword" class="control-label">Retype Password</label>
                    </div>
                    <input id="repassword" type="password" class="form-control" name="repassword" tabindex="3" required>
                    <div class="invalid-feedback">
                      please retype the password
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-warning btn-lg btn-block" tabindex="4">
                      Create Account
                    </button>
                  </div>
                </form>
                <div class="text-center mt-4 mb-3">
                  <div class="text-job text-muted">If forgot your credentials detelete the file "/pathToBasecode/gui/userdata.php".</div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<?php  }else{ ?>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header"><h4>BaseCode Login</h4></div>

              <div class="card-body">
              <?php 
                  if($_GET['signed'] == "show_message"){?>
                    <div class="alert alert-success">
                      <strong>SIGNED UP!</strong><br>
                        Please login to continue...
                    </div>
                  <?php }
                    if(!is_null($msg)){ 
                      echo $msg;
                    }
                  ?>
                <form method="POST" action="" class="needs-validation" novalidate="">
                  <input type="hidden" name="case" value="login" />
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="username" class="form-control" name="username" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in your username
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                    	<label for="password" class="control-label">Password</label>
                    </div>
                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                    <div class="invalid-feedback">
                      please fill in your password
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Login
                    </button>
                  </div>
                </form>
                <div class="text-center mt-4 mb-3">
                  <div class="text-job text-muted">If forgot your credentials detelete the file "/pathToBasecode/gui/userdata.php".</div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <?php } ?>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="./assets/js/stisla.js"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="./assets/js/scripts.js"></script>
  <script src="./assets/js/custom.js"></script>

  <!-- Page Specific JS File -->
</body>
</html>
