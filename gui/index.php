<?php 
$enable_head_security = true; // this variable is essential!!!
$page_title = "Dashboard - Basecode";
$page_name = "home";
include("head.php");
if(!$class_administrator_bs->check_logged()){ header("Location: access.php"); die("Not logged, click <a href='access.php'>here</a>."); }
?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard</h1>
          </div>
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="fas fa-feather-alt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Installed Plugins</h4>
                  </div>
                  <div class="card-body">
                    <?php 
                      echo $class_administrator_bs->numPlugins();
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Active Plugins</h4>
                  </div>
                  <div class="card-body">
                    <?php 
                      echo $class_administrator_bs->numActivePlugins();
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="fas fa-lightbulb"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>BS Status</h4>
                  </div>
                  <div class="card-body">
                    <?php 
                      if($class_administrator_bs->check_main_status()){
                        echo "running";
                      }else{
                        echo "<a href='https://greco395.com/basecode/docs/?error=main_die#main' style='color:red;'>DIE <i class=\"fas fa-external-link-alt\"></i></a>";
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-sync-alt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Version</h4>
                  </div>
                  <div class="card-body">
                    <?php echo $bs_version; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>






          <div class="section-body">
            <div class="invoice">
              <div class="invoice-print">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="invoice-title">
                      <h2>BaseCode</h2>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-12">
                    <div class="table-responsive">
                      <table class="table table-striped table-hover table-md">
                        <tbody>
                        <tr>
                          <td>Version</td>
                          <td><?php echo $bs_version; ?></td>
                        </tr>
                        <tr>
                          <td>Edition</td>
                          <td><?php echo $bs_edition; ?></td>
                        </tr>
                        <tr>
                          <td>PHP version</td>
                          <td><?php echo PHP_VERSION; ?></td>
                        </tr>
                      </tbody></table>
                    </div>
                  </div>
                </div>

              </div>
              <hr>
              <div class="row">
                  <div class="col-lg-12">
                    <div class="invoice-title">
                      <h2>Updates</h2>
                    </div>
                  </div>
                </div>
                <br>

<?php
// CHECK FOR UPDATES FROM BASECODE SERVER
if(in_array  ('curl', get_loaded_extensions())) {
  $curlSES=curl_init(); 
  curl_setopt($curlSES,CURLOPT_URL,"https://greco395.com/API/basecode/?v=".$class_administrator_bs->internal_bs_version);
  curl_setopt($curlSES,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($curlSES,CURLOPT_HEADER, false); 
  $result=curl_exec($curlSES);
  curl_close($curlSES);
}else{
  $result = file_get_contents("https://greco395.com/API/basecode/?v=".$class_administrator_bs->internal_bs_version);
}
$up_rq = json_decode($result, true); 
if($up_rq['update'] != false){
  echo '<span class="badge badge-danger">An update is avaliable</span><br><br>

  <div class="card card-warning">
                  <div class="card-header">
                    <h4>Changelog</h4>
                  </div>
                  <div class="card-body">
                    '.$up_rq['changelog'].'
                  </div>
                </div>

  <a href="#" data-toggle="modal" data-target="#updateModal" class="btn btn-icon btn-block icon-left btn-info"><i class="fas fa-upload"></i> UPDATE</a><br>';
}else{
  echo '<span class="badge badge-success">You have the last versione on basecode</span>';
}
?>
<br>
            </div>
          </div>


          <div class="section-body">
            <h2 class="section-title">RESET</h2>
            <p class="section-lead">
              Use this section to reset basecode components.
            </p>

            <div class="row">
              <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Reset <code>ALL</code></h4>
                  </div>
                  <div class="card-body">
                    <p class="text-muted">This action is <code>IRREVERSIBLE</code> and will reset <code>ALL files</code>.</p>
                    <div class="buttons">
                      <button class="btn btn-block btn-outline-dark" data-toggle="modal" data-target="#resetALLmodal">RESET BASECODE</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Reset <code>Main File</code></h4>
                  </div>
                  <div class="card-body">
                  <p class="text-muted">This action is <code>IRREVERSIBLE</code> and will reset the "<code>main.php</code>".</p>
                    <div class="buttons">
                      <button class="btn btn-block btn-outline-danger" data-toggle="modal" data-target="#resetMAINmodal">RESET MAIN FILE</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </div>
          
          <div class="row">
          <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Reset <code>Settings</code></h4>
                  </div>
                  <div class="card-body">
                    <p class="text-muted">This action is <code>IRREVERSIBLE</code> and will reset the "<code>settings.php</code>".</p>
                    <div class="buttons">
                    <button class="btn btn-block btn-outline-secondary" data-toggle="modal" data-target="#resetSETTINGSmodal">RESET ALL SETTINGS</button>
                    </div>
                  </div>
                </div>
              </div>
            
            
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Reset <code>Brain</code> File</h4>
                  </div>
                  <div class="card-body">
                    <p class="text-muted">This action is <code>IRREVERSIBLE</code> and will reset the "<code>brain.php</code>".</p>
                    <div class="buttons">
                    <button class="btn btn-block btn-outline-warning" data-toggle="modal" data-target="#resetBRAINmodal">RESET ALL SETTINGS</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </div>
          </div>
          </div>
          </div>
          </div>

        </section>
      </div>

<!-- MODALS -->
      <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">BASECODE UPDATE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Clicking "Update" will download a new version of BaseCode from the basecode server.<br>
                  To perform the update it is essential to use the php <code>eval()</code> function, before updating make sure it is enabled and <code>do not forget to make a backup</code>.<br>
                  The automatic update or any associated risk such as data loss is completely borne by the user.</p>
                <form action="brain.php?bs_show_brainHTML=yes" method="POST">

                <br>
                <div class="form-group">
                  <label>Confirm your password to continue</label>
                  <input type="password" class="form-control" name="password" placeholder="Insert your password">
                </div>
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <a class="btn btn-secondary" data-dismiss="modal">NO! GO BACK</a>
                  <input type="hidden" name="case" value="update_now" />
                  <button type="submit" class="btn btn-danger">UPDATE NOW</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="resetMAINmodal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">RESET Main File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Are you sure to reset the main file ("main.php") and replace it with the backup?</p>
                <form action="brain.php?bs_show_brainHTML=yes" method="POST">
                <br>
                <div class="form-group">
                  <label>Confirm your password to continue</label>
                  <input type="password" class="form-control" name="password" placeholder="Insert your password">
                </div>
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <a class="btn btn-secondary" data-dismiss="modal">NO! GO BACK</a>
                  <input type="hidden" name="case" value="reset_MAIN" />
                  <button type="button" class="btn btn-danger">YES! RESET MAIN FILE</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="resetSETTINGSmodal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">RESET Settings File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Are you sure to reset the settings file ("settings.php") and replace it with the backup?</p>
                <form action="brain.php?bs_show_brainHTML=yes" method="POST">
                <br>
                <div class="form-group">
                  <label>Confirm your password to continue</label>
                  <input type="password" class="form-control" name="password" placeholder="Insert your password">
                </div>
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <a class="btn btn-secondary" data-dismiss="modal">NO! GO BACK</a>
                  <input type="hidden" name="case" value="reset_SETTINGS" />
                  <button type="button" class="btn btn-danger">YES! RESET SETTINGS</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="resetBRAINmodal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">RESET Brain File (gui)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Are you sure to reset the brain file ("brain.php") and replace it with the backup?</p>
                <form action="brain.php?bs_show_brainHTML=yes" method="POST">
                <br>
                <div class="form-group">
                  <label>Confirm your password to continue</label>
                  <input type="password" class="form-control" name="password" placeholder="Insert your password">
                </div>
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <a class="btn btn-secondary" data-dismiss="modal">NO! GO BACK</a>
                  <input type="hidden" name="case" value="reset_BRAIN" />
                  <button type="button" class="btn btn-danger">YES! RESET SETTINGS</button>
                </form>
              </div>
            </div>
          </div>
        </div>
<!-- END MODALS -->

<?php 
  include("footer.php");
?>