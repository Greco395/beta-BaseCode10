<?php 
$enable_head_security = true; // this variable is essential!!!
$page_title = "Plugins - Basecode";
$page_name = "add_plugin";
include("head.php");
?>
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Add Plugins</h1>
          </div>
<?php 
if(isset($_POST) && isset($_POST['new_pl_case'])){
  $pre_enablePlugin = false;
  if($_POST['new_pl_case'] == "add_by_url"){
    if(isset($_POST['is_enabled']) && $_POST['is_enabled'] == "on"){
      $pre_enablePlugin = true;
    }
    $rq = $class_administrator_bs->installPluginBYurl($_POST['url'], $pre_enablePlugin);
    if($rq[0]){
      echo '<div class="alert alert-success">
              <div class="alert-title">Success</div>
              '.$rq[1].'
            </div>';
    }else{
      echo '<div class="alert alert-danger">
              <div class="alert-title">Error!</div>
              '.$rq[1].'
            </div>';
    }
  }elseif($_POST['new_pl_case'] == "add_by_upload"){
    if(isset($_POST['is_enabled_2']) && $_POST['is_enabled_2'] == "on"){
      $pre_enablePlugin = true;
    }
    $rq = $class_administrator_bs->installPluginBYupload($pre_enablePlugin);
    if($rq[0]){
      echo '<div class="alert alert-success">
              <div class="alert-title">Success</div>
              '.$rq[1].'
            </div>';
    }else{
      echo '<div class="alert alert-danger">
              <div class="alert-title">Error!</div>
              '.$rq[1].'
            </div>';
    }
  }
}


if(class_exists('ZipArchive')){
if(file_get_contents("https://greco395.com/API/basecode/is_store.php") == "enabled"){
?>
              <div class="col-12 mb-4">
                <div class="hero bg-primary text-white">
                  <div class="hero-inner">
                    <h2>PLUGINS STORE</h2>
                    <p class="lead">From the store you can download plugins that will simplify your work and help you in the arduous work of managing a website.</p>
                    <div class="mt-4">
                      <a href="https://greco395.com/basecode/store/" target="_blank" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-shopping-bag"></i> Setup Account</a>
                    </div>
                  </div>
                </div>
              </div>
<?php 
  }
?>
          <div class="card">
            <div class="card-header">
              <h4>Add zip by URL</h4>
            </div>
            <form action="" method="POST">
            <input type="hidden" name="new_pl_case" value="add_by_url" />
            <div class="card-body">
              <div class="form-group">
                <label>Url of your .zip file</label>
                <input name="url" type="text" class="form-control" placeholder="Example: https://domain.ext/path/myPlugin.zip">
              </div>
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="is_enabled" name="is_enabled">
                <label class="custom-control-label" for="is_enabled">Enable Plugin</label>
              </div>
            </div>
            <div class="card-footer text-right">
              <button class="btn btn-block btn-primary">DOWNLOAD AND INSTALL</button>
            </div>
            </form>
          </div>



          <div class="card">
            <div class="card-header">
              <h4>Upload zip file</h4>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="new_pl_case" value="add_by_upload" />
            <div class="card-body">
              <div class="form-group">
                <p>Select your zip file</p>
                   <input type="file" name="plugin_zip" class="form-control">
              </div>
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="is_enabled_2" name="is_enabled_2">
                <label class="custom-control-label" for="is_enabled_2">Enable Plugin</label>
              </div>
            </div>
            <div class="card-footer text-right">
              <button class="btn btn-block btn-primary">UPLOAD AND INSTALL</button>
            </div>
            </form>
          </div>

          

        </section>
      </div>
<?php 
  }else{
?>
  <div class="card">
    <div class="card-header">
      <h4>Function disabled!</h4>
    </div>
    <div class="card-body">
      <div class="empty-state" data-height="600" style="height: 600px;">
        <img class="img-fluid" src="./assets/img/drawkit/drawkit-nature-man-colour.svg" alt="image">
lass="lead">
        The ZipArchive class is essential for being able to install new plugins from here. 
        <br>        <h2 class="mt-0">PHP class ZipArchive not found</h2>
        <p>
        Try seeking help by asking "<code>How Install php<?php echo explode(".", PHP_VERSION)[0].".".explode(".", PHP_VERSION)[1]; ?>-zip on ubuntu</code>" (if ubuntu is your system)
        </p>
        <a href="?reload" class="btn btn-warning mt-4">Try Again</a>
      </div>
    </div>
  </div>
<?php
  } // ...
  include("footer.php");
?>