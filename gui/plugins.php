<?php 
$enable_head_security = true; // this variable is essential!!!
$page_title = "Plugins - Basecode";
$page_name = "plugins";
include("head.php");
if(!$class_administrator_bs->check_logged()){ header("Location: access.php"); die("Not logged, click <a href='access.php'>here</a>."); }
?>
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Manage Plugins</h1>
          </div>
<?php 
  if(isset($_POST) && isset($_POST['pl_case'])){
    if($_POST['pl_case'] == "DELETE_PL"){
      if($class_administrator_bs->delete_plugin(base64_decode($_POST['name']))){
        echo '<div class="alert alert-dark">
                <div class="alert-title">Success</div>
                Plugin Deleted.
              </div>';
      }else{
        echo '<div class="alert alert-danger">
                <div class="alert-title">Error!</div>
                Invalid plugin!
              </div>';
      }
    }elseif($_POST['pl_case'] == "enable_pl"){
      if($class_administrator_bs->enable_plugin(base64_decode($_POST['name']))){
        echo '<div class="alert alert-success">
                <div class="alert-title">Success</div>
                Plugin enabled.
              </div>';
      }else{
        echo '<div class="alert alert-danger">
                <div class="alert-title">Error!</div>
                Invalid plugin!
              </div>';
      }
    }elseif($_POST['pl_case'] == "disable_pl"){
      if($class_administrator_bs->disable_plugin(base64_decode($_POST['name']))){
        echo '<div class="alert alert-success">
                <div class="alert-title">Success</div>
                Plugin disabled.
              </div>';
      }else{
        echo '<div class="alert alert-danger">
                <div class="alert-title">Error!</div>
                Invalid plugin!
              </div>';
      }
    }
  }
?>
          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header">
                  <h4>All Plugins</h4>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive table-invoice">
                    <table class="table table-striped">
                      <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      <?php 
                        $plugins = $class_administrator_bs->getPlugins();
                        if($class_administrator_bs->numPlugins() < 1){
                          echo "<tr><td>No Plugins...</td></tr>";
                        }
                        $mod_num = 0;
                        $plugins_modal = '';
                        foreach($plugins as $plugin){
                            $PLstatus="";
                            $enable_btn = '<input type="hidden" name="pl_case" value="disable_pl" /> <button class="btn btn-warning">DISABLE NOW</button>';
                            $sts = $class_administrator_bs->plugin_status($plugin);
                            if($sts[0]){
                                $PLstatus = '<div class="badge badge-success">enabled</div>';
                            }else{
                              if($sts[1] == 3){
                                $PLstatus .= '<div class="badge badge-secondary" style="color:black;">DISABLED</div>';
                                $enable_btn = '<input type="hidden" name="pl_case" value="enable_pl" /> <button class="btn btn-success">ENABLE NOW</button>';
                              }else{
                                $PLstatus .= '<div class="badge badge-danger">DIE</div>&ensp;';
                              }
                              if($sts[1] == 1){
                                $PLstatus .= '<div class="badge badge-warning">syntax error</div>';
                              }elseif($sts[1] == 2){
                                $PLstatus .= '<div class="badge badge-warning">no index file</div>';
                              }
                            }
                            $plugins_modal .= '<!-- MODAL START -->
                            <div class="modal fade" tabindex="-1" role="dialog" id="manage_'.$mod_num.'">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title">'.$plugin.'</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <p>EDIT PLUGIN SETTING</p>
                                        NOTE: Some plugins do not have this page, in these cases you will be redirected to an empty page.
                                        <br><br>
                                        <a href="'.$class_administrator_bs->plugins_folder."/".$plugin."/".$class_administrator_bs->plugin_confFile.'" class="btn btn-primary">GO TO SETTINGS PLUGIN PAGE</a>
                                        <hr>
                                        <p>ENABLE/DISABLE PLUGIN</p>
                                        <form action="" method="POST">
                                        <input type="hidden" name="name" value="'.base64_encode($plugin).'" />
                                          '.$enable_btn.'
                                        </form>
                                        <hr>
                                        <p>DELETE PLUGIN</p>
                                        <form action="" method="POST">
                                          <input type="hidden" name="pl_case" value="DELETE_PL" />
                                          <input type="hidden" name="name" value="'.base64_encode($plugin).'" />
                                          <button class="btn btn-danger">YES, DELETE THIS PLUGIN</button>
                                        </form>
                                      </div>
                                      <div class="modal-footer bg-whitesmoke br">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <!-- MODAL END -->
                                ';
                      ?>
                      <tr>
                        <td><a href="#"><?php echo $plugin; ?></a></td>
                        <td>
                          <?php 
                              echo $PLstatus;
                          ?>
                        </td>
                        <td>
                          <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#manage_<?php echo $mod_num; ?>">Manage</a>
                        </td>
                      </tr>
                      <?php 
                            $mod_num++;
                        }
                      ?>
                      

                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-hero">
                <div class="card-header">
                  <div class="card-icon">
                    <i class="far fa-question-circle"></i>
                  </div>
                  <h4><?php echo $all_pl_num = $class_administrator_bs->numPlugins(); ?></h4>
                  <div class="card-description">Total plugins</div>
                </div>
                <div class="card-body p-0">
                  <div class="tickets-list">
                    <a href="#" class="ticket-item">
                      <div class="ticket-title">
                        <h4><?php echo $actv_pl_num = $class_administrator_bs->numActivePlugins(); ?></h4>
                      </div>
                      <div class="ticket-info">
                        <div>Actived plugins</div>
                      </div>
                    </a>
                    <a href="#" class="ticket-item">
                      <div class="ticket-title">
                        <h4><?php echo $all_pl_num = $all_pl_num - $actv_pl_num; ?></h4>
                      </div>
                      <div class="ticket-info">
                        <div>Disabled Plugins</div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          </section>
          </div>

<?php
  if(isset($plugins_modal)){
      echo $plugins_modal;
  }
  include("footer.php");
?>