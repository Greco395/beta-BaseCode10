<?php 
$enable_head_security = true; // this variable is essential!!!
$page_title = "Settings - Basecode";
$page_name = "settings";
include("head.php");
if(!$class_administrator_bs->check_logged()){ header("Location: access.php"); die("Not logged, click <a href='access.php'>here</a>."); }
?>
<?php
if(!isset($_GET['edit'])){
?>
<!-- Main Content -->
<div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Settings</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="./">Dashboard</a></div>
              <div class="breadcrumb-item">Settings</div>
            </div>
          </div>

          <div class="section-body">
            <h2 class="section-title">Overview</h2>
            <p class="section-lead">
              Organize and adjust all settings about this BaseCode version.
            </p>

            <div class="row">
              <div class="col-lg-6">
                <div class="card card-large-icons">
                  <div class="card-icon bg-primary text-white">
                    <i class="fas fa-cog"></i>
                  </div>
                  <div class="card-body">
                    <h4>General</h4>
                    <p>General settings such as, debug mode, pre declared class names and so on.</p>
                    <a href="?edit=general" class="card-cta">Change Setting <i class="fas fa-chevron-right"></i></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="card card-large-icons">
                  <div class="card-icon bg-primary text-white">
                    <i class="fas fa-briefcase"></i>
                  </div>
                  <div class="card-body">
                    <h4>Plugins</h4>
                    <p>Enable or disable plugins, set the folder and so on..</p>
                    <a href="?edit=plugins" class="card-cta">Change Setting <i class="fas fa-chevron-right"></i></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="card card-large-icons">
                  <div class="card-icon bg-primary text-white">
                    <i class="fas fa-database"></i>
                  </div>
                  <div class="card-body">
                    <h4>Database</h4>
                    <p>Add, remove or manage one or multiple databases.</p>
                    <a href="?edit=database" class="card-cta">Change Setting <i class="fas fa-chevron-right"></i></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="card card-large-icons">
                  <div class="card-icon bg-primary text-white">
                    <i class="fas fa-robot"></i>
                  </div>
                  <div class="card-body">
                    <h4>Captcha</h4>
                    <p>Set your tookens for use Google captcha or HCaptcha.</p>
                    <a href="?edit=captcha" class="card-cta">Change Setting <i class="fas fa-chevron-right"></i></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="card card-large-icons">
                  <div class="card-icon bg-primary text-white">
                    <i class="fas fa-bug"></i>
                  </div>
                  <div class="card-body">
                    <h4>Errors</h4>
                    <p>Choose whether to show errors of both php and BaseCode or choose the messages to show in these cases and more.</p>
                    <a href="?edit=errors" class="card-cta text-primary">Change Setting <i class="fas fa-chevron-right"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
<?php
}else{
  if($_GET['edit'] == "general"){
    $title = "General Settings";
    $active="1"; 
  }elseif($_GET['edit'] == "plugins"){
    $title = "Plugins Settings";
    $active="2";
  }elseif($_GET['edit'] == "database"){
    $title = "Database Settings";
    $active="3";
  }elseif($_GET['edit'] == "captcha"){
    $title = "Captcha Settings";
    $active="4";
  }elseif($_GET['edit'] == "errors"){
    $title = "Error Settings";
    $active="5";
  }else{
    header("Location: ?all");
  }
?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <div class="section-header-back">
              <a href="?all" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1><?php echo $title; ?></h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="?all">Settings</a></div>
              <div class="breadcrumb-item"><?php echo $title; ?></div>
            </div>
          </div>

<?php 
include("../settings.php");
  if(isset($_POST)){
// GENERAL
    if(isset($_POST['case']) && $_POST['case'] == "update_general"){
      if(!$_POST['DEBUG_MODE_PHP'] or $_POST['DEBUG_MODE_PHP'] == "false"){
        $BS_SETTINGS_INTERNAL['GENERAL']['DEBUG_MODE_PHP'] = false;
      }else{
        $BS_SETTINGS_INTERNAL['GENERAL']['DEBUG_MODE_PHP'] = true;
      }
      if(!$_POST['DEBUG_MODE'] or $_POST['DEBUG_MODE'] == "false"){
        $BS_SETTINGS_INTERNAL['GENERAL']['DEBUG_MODE'] = false;
      }else{
        $BS_SETTINGS_INTERNAL['GENERAL']['DEBUG_MODE'] = true;
      }
      if(!$_POST['DEBUG_VAR'] or $_POST['DEBUG_VAR'] == "false"){
        $BS_SETTINGS_INTERNAL['GENERAL']['DEBUG_VAR'] = false;
      }else{
        $BS_SETTINGS_INTERNAL['GENERAL']['DEBUG_VAR'] = true;
      }
      if(!$_POST['PRE_DECLARED_CLASSES'] or $_POST['PRE_DECLARED_CLASSES'] == "false"){
        $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES'] = false;
      }else{
        $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES'] = true;
      }
      if(isset($_POST['tools']) && !empty($_POST['tools']) and isset($_POST['captcha']) && !empty($_POST['captcha']) and 
      isset($_POST['agent']) && !empty($_POST['agent']) and isset($_POST['sql']) && !empty($_POST['sql']) and 
      isset($_POST['html']) && !empty($_POST['html'])){
        $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['tools'] = $_POST['tools'];
        $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['captcha'] = $_POST['captcha'];
        $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['agent'] = $_POST['agent'];
        $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['sql'] = $_POST['sql'];
        $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['html'] = $_POST['html'];
      }

      $class_administrator_bs->saveSettings($BS_SETTINGS_INTERNAL);
      
    }elseif(isset($_POST['case']) && $_POST['case'] == "update_plugins"){
// PLUGINS 
      if(!$_POST['IS_ENABLED'] or $_POST['IS_ENABLED'] == "false"){
        $BS_SETTINGS_INTERNAL['PLUGINS']['IS_ENABLED'] = false;
      }else{
        $BS_SETTINGS_INTERNAL['PLUGINS']['IS_ENABLED'] = true;
      }
      $BS_SETTINGS_INTERNAL['PLUGINS']['FOLDER_NAME'] = $_POST['FOLDER_NAME'];
      $BS_SETTINGS_INTERNAL['PLUGINS']['FILE-INCLUDED_FROM_BASECODE'] = $_POST['FILE-INCLUDED_FROM_BASECODE'];
      $BS_SETTINGS_INTERNAL['PLUGINS']['FILE-GUI_PLUGIN_MANAGER'] = $_POST['FILE-GUI_PLUGIN_MANAGER'];
      $class_administrator_bs->saveSettings($BS_SETTINGS_INTERNAL);
    }elseif(isset($_POST['case']) && $_POST['case'] == "update_database"){
// DATABASE 
      if($_POST['IS_ENABLED'] or $_POST['IS_ENABLED'] == "true"){
        $BS_SETTINGS_INTERNAL['PLUGINS']['IS_ENABLED'] = true;
      }else{
        $BS_SETTINGS_INTERNAL['PLUGINS']['IS_ENABLED'] = false;
      }
      $BS_SETTINGS_INTERNAL['DB']['HOST'] = $class_administrator_bs->stringToArrayFake($_POST['HOST']);
      $BS_SETTINGS_INTERNAL['DB']['NAME'] = $class_administrator_bs->stringToArrayFake($_POST['NAME']);
      $BS_SETTINGS_INTERNAL['DB']['USER'] = $class_administrator_bs->stringToArrayFake($_POST['USER']);
      $BS_SETTINGS_INTERNAL['DB']['PASS'] = $class_administrator_bs->stringToArrayFake($_POST['PASS']);
      $class_administrator_bs->saveSettings($BS_SETTINGS_INTERNAL);
      $saved = true;
    }elseif(isset($_POST['case']) && $_POST['case'] == "update_captcha"){
// CAPTCHA 
      $BS_SETTINGS_INTERNAL['CAPTCHA']['GOOGLE_CAPTCHA_PUBLIC-KEY'] = $_POST['GOOGLE_CAPTCHA_PUBLIC-KEY'];
      $BS_SETTINGS_INTERNAL['CAPTCHA']['GOOGLE_CAPTCHA_PRIVATE-KEY'] = $_POST['GOOGLE_CAPTCHA_PRIVATE-KEY'];
      $BS_SETTINGS_INTERNAL['CAPTCHA']['HCAPTCHA_PUBLIC-KEY'] = $_POST['HCAPTCHA_PUBLIC-KEY'];
      $BS_SETTINGS_INTERNAL['CAPTCHA']['HCAPTCHA_PRIVATE-KEY'] = $_POST['HCAPTCHA_PRIVATE-KEY'];
      $class_administrator_bs->saveSettings($BS_SETTINGS_INTERNAL);
    }elseif(isset($_POST['case']) && $_POST['case'] == "update_errors"){
// ERRORS 
      if(!$_POST['NEVER_DIE_PAGE'] or $_POST['NEVER_DIE_PAGE'] == "false"){
        $BS_SETTINGS_INTERNAL['ERRORS']['NEVER_DIE_PAGE'] = false;
      }else{
        $BS_SETTINGS_INTERNAL['ERRORS']['NEVER_DIE_PAGE'] = true;
      }
      if(!$_POST['SHOW_HTML_ERROR_MESSAGE'] or $_POST['SHOW_HTML_ERROR_MESSAGE'] == "false"){
        $BS_SETTINGS_INTERNAL['ERRORS']['SHOW_HTML_ERROR_MESSAGE'] = false;
      }else{
        $BS_SETTINGS_INTERNAL['ERRORS']['SHOW_HTML_ERROR_MESSAGE'] = true;
      }
      $BS_SETTINGS_INTERNAL['ERRORS']['DEFAULT_PUBLIC_ERROR'] = $_POST['DEFAULT_PUBLIC_ERROR'];
      $BS_SETTINGS_INTERNAL['ERRORS']['DEFAULT_ERROR'] = $_POST['DEFAULT_ERROR'];
      $BS_SETTINGS_INTERNAL['ERRORS']['DEFAULT_DIE_ERROR'] = $_POST['DEFAULT_DIE_ERROR'];
      $class_administrator_bs->saveSettings($BS_SETTINGS_INTERNAL);
    }
  }
?>

          <div class="section-body">
            <h2 class="section-title">All About <?php echo $title; ?></h2>
            <p class="section-lead">
              You can adjust all <?php echo $title; ?> here
            </p>

            <div id="output-status"></div>
            <div class="row">
              <div class="col-md-4">
                <div class="card">
                  <div class="card-header">
                    <h4>Jump To</h4>
                  </div>
                  <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                      <li class="nav-item"><a href="?edit=general" class="nav-link <?php if($active=="1"){ echo "active"; } ?>">General</a></li>
                      <li class="nav-item"><a href="?edit=plugins" class="nav-link <?php if($active=="2"){ echo "active"; } ?>">Plugins</a></li>
                      <li class="nav-item"><a href="?edit=database" class="nav-link <?php if($active=="3"){ echo "active"; } ?>">Database</a></li>
                      <li class="nav-item"><a href="?edit=captcha" class="nav-link <?php if($active=="4"){ echo "active"; } ?>">Captcha</a></li>
                      <li class="nav-item"><a href="?edit=errors" class="nav-link <?php if($active=="5"){ echo "active"; } ?>">Errors</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                  <div class="card" id="settings-card">
                    <div class="card-header">
                      <h4><?php echo $title; ?></h4>
                    </div>
                    <div class="card-body">
                        <?php 
                          if($_GET['edit'] == "general"){
                            $set = $BS_SETTINGS_INTERNAL['GENERAL'];
                          ?>
                              <form action="?edit=general" method="POST">
                                <input type="hidden" name="case" value="update_general" />
                                <div class="form-group">
                                  <label>DEBUG MODE PHP</label>
                                  <select class="custom-select" name="DEBUG_MODE_PHP">
                                    <option disabled>Select an option</option>
                                    <?php 
                                      if($set['DEBUG_MODE_PHP']){
                                        echo '<option value="true" selected="">True</option>
                                        <option value="false">False (Default)</option>';
                                      }else{
                                        echo '<option value="true">True</option>
                                        <option value="false" selected="">False (Default)</option>';
                                      }
                                    ?>
                                  </select>
                                  <small class="form-text text-muted">
                                    If is true all PHP errors in your website (included plugins) are shown.
                                  </small>
                                </div>
                                <div class="form-group">
                                  <label>DEBUG MODE</label>
                                  <select class="custom-select" name="DEBUG_MODE">
                                    <option disabled>Select an option</option>
                                    <?php 
                                      if($set['DEBUG_MODE']){
                                        echo '<option value="true" selected="">True (Default)</option>
                                        <option value="false">False</option>';
                                      }else{
                                        echo '<option value="true">True (Default)</option>
                                        <option value="false" selected="">False</option>';
                                      }
                                    ?>
                                  </select>
                                  <small class="form-text text-muted">
                                    If true, a log file is created every time there is an error in using basecode functions to help solve the problem.
                                  </small>
                                </div>
                                <div class="form-group">
                                  <label>DEBUG VAR</label>
                                  <select class="custom-select" name="DEBUG_VAR">
                                    <option disabled>Select an option</option>
                                    <?php 
                                      if($set['DEBUG_VAR']){
                                        echo '<option value="true" selected="">True (Default)</option>
                                        <option value="false">False</option>';
                                      }else{
                                        echo '<option value="true">True (Default)</option>
                                        <option value="false" selected="">False</option>';
                                      }
                                    ?>
                                  </select>
                                  <small class="form-text text-muted">
                                    If true, every time there is an error in using basecode functions it is added to the global variable $BS_DEBUG.
                                  </small>
                                </div>
                                <div class="form-group">
                                  <label>PRE DECLARED CLASSES</label>
                                  <select class="custom-select" name="PRE_DECLARED_CLASSES">
                                    <option disabled>Select an option</option>
                                    <?php 
                                      if($set['PRE_DECLARED_CLASSES']){
                                        echo '<option value="true" selected="">True (Default)</option>
                                        <option value="false">False</option>';
                                      }else{
                                        echo '<option value="true">True (Default)</option>
                                        <option value="false" selected="">False</option>';
                                      }
                                    ?>
                                  </select>
                                  <small class="form-text text-muted">
                                    If true, all BaseCode classes are pre-declared when main.php is run (can customize below). For more info see the documentation (click <a href='https://greco395.com/basecode/docs/#pre_declared_classes' target="_blank">here</a>).
                                  </small>
                                  <br>
                                  <?php 
                                    if($set['PRE_DECLARED_CLASSES']){
                                  ?>
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Class <code>TOOLS</code></label>
                                    <div class="col-sm-9">
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            $
                                          </div>
                                        </div>
                                        <input type="text" class="form-control" name="tools" value="<?php echo $set['PRE_DECLARED_CLASSES_NAMES']['tools']; ?>">
                                      </div>
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Class <code>CAPTCHA</code></label>
                                    <div class="col-sm-9">
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            $
                                          </div>
                                        </div>
                                        <input type="text" class="form-control" name="captcha" value="<?php echo $set['PRE_DECLARED_CLASSES_NAMES']['captcha']; ?>">
                                      </div>
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Class <code>AGENT</code></label>
                                    <div class="col-sm-9">
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            $
                                          </div>
                                        </div>
                                        <input type="text" class="form-control" name="agent" value="<?php echo $set['PRE_DECLARED_CLASSES_NAMES']['agent']; ?>">
                                      </div>
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Class <code>SQL</code></label>
                                    <div class="col-sm-9">
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            $
                                          </div>
                                        </div>
                                        <input type="text" class="form-control" name="sql" value="<?php echo $set['PRE_DECLARED_CLASSES_NAMES']['sql']; ?>">
                                      </div>
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Class <code>HTML</code></label>
                                    <div class="col-sm-9">
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            $
                                          </div>
                                        </div>
                                        <input type="text" class="form-control" name="html" value="<?php echo $set['PRE_DECLARED_CLASSES_NAMES']['html']; ?>">
                                      </div>
                                    </div>
                                  </div>

                                  <?php    
                                    }
                                  ?>
                                </div>
                        <?php
// PLUGINS
                            }elseif($_GET['edit'] == "plugins"){ 
                              $set = $BS_SETTINGS_INTERNAL['PLUGINS'];
                            ?>
                                <form action="?edit=plugins" method="POST">
                                <input type="hidden" name="case" value="update_plugins" />
                                <div class="form-group">
                                  <label>ENABLE PLUGINS</label>
                                  <select class="custom-select" name="IS_ENABLED">
                                    <option disabled>Select an option</option>
                                    <?php 
                                      if($set['IS_ENABLED']){
                                        echo '<option value="true" selected="">True (Default)</option>
                                        <option value="false">False</option>';
                                      }else{
                                        echo '<option value="true">True (Default)</option>
                                        <option value="false" selected="">False</option>';
                                      }
                                    ?>
                                  </select>
                                  <small class="form-text text-muted">
                                   For more info abount plugins visit <a htef="https://greco395.com/basecode/docs/#plugins" target="_blank">https://greco395.com/basecode/docs/#plugins</a>
                                  </small>
                                </div>

                                <div class="form-group">
                                  <label>Plugins folder name</label>
                                  <input type="text" class="form-control" name="FOLDER_NAME" value="<?php echo $set['FOLDER_NAME']; ?>">
                                  <small class="form-text text-muted">
                                    Only the name of the folder! Don't add slashes. (the folder must be in the same as the main.php file)
                                  </small>
                                </div>

                                <div class="form-group">
                                  <label>Main plugin file</label>
                                  <input type="text" class="form-control" name="FILE-INCLUDED_FROM_BASECODE" value="<?php echo $set['FILE-INCLUDED_FROM_BASECODE']; ?>">
                                  <small class="form-text text-muted">
                                    The name of the file that is included by BaseCode when the plugins are loaded.
                                  </small>
                                </div>

                                <div class="form-group">
                                  <label>Plugins custom page</label>
                                  <input type="text" class="form-control" name="FILE-GUI_PLUGIN_MANAGER" value="<?php echo $set['FILE-GUI_PLUGIN_MANAGER']; ?>">
                                </div>
                            <?php 
// DATABASE
                            }elseif($_GET['edit'] == "database"){
                              if(!isset($saved) or !$saved){
                              $set = $BS_SETTINGS_INTERNAL['DB'];
                            ?>
                                <form action="?edit=database" method="POST">
                                <input type="hidden" name="case" value="update_database" />
                                <div class="form-group">
                                  <label>ENABLE DABASES  (pdo)</label>
                                  <select class="custom-select" name="IS_ENABLED">
                                    <option disabled>Select an option</option>
                                    <?php 
                                      if($set['IS_ENABLED']){
                                        echo '<option value="true" selected="">True</option>
                                        <option value="false">False (Default)</option>';
                                      }else{
                                        echo '<option value="true">True</option>
                                        <option value="false" selected="">False (Default)</option>';
                                      }
                                    ?>
                                  </select>
                                  <small class="form-text text-muted">
                                   For more info abount databases visit <a htef="https://greco395.com/basecode/docs/#database" target="_blank">https://greco395.com/basecode/docs/#databases</a>
                                  </small>
                                </div>

                                <code>Click <b><a href="https://greco395.com/basecode/docs/#multiple_databases">here</a></b> to see how to compile this section with multiple databases</code>
                                <br><br>

                                <div class="form-group">
                                  <label>Databases HOSTS</label>
                                  <textarea style="min-height:60px;" class="form-control" name="HOST" rows="3" width="100%" placeholder='Example: "localhost", "localhost", "123.456.789"'><?php echo $class_administrator_bs->arrayToStringArray($set['HOST'], true); ?></textarea>
                                </div>

                                <div class="form-group">
                                  <label>Databases NAMES</label>
                                  <textarea style="min-height:60px;" class="form-control" name="NAME" rows="3" width="100%" placeholder='Example: "DB_NAME1", "DB_NAME2", "DBname3"'><?php echo $class_administrator_bs->arrayToStringArray($set['NAME'], true); ?></textarea>
                                </div>

                                <div class="form-group">
                                  <label>Databases USERS</label>
                                  <textarea style="min-height:60px;" class="form-control" name="USER" rows="3" width="100%" placeholder='Example: "DB_USER_1", "DB_USER_2", "DB_USER_3"'><?php echo $class_administrator_bs->arrayToStringArray($set['USER'], true); ?></textarea>
                                </div>

                                <div class="form-group">
                                  <label>Databases PASSWORDS</label>
                                  <textarea style="min-height:60px;" class="form-control" name="PASS" rows="3" width="100%" placeholder='Example: "DB_PASS_1", "DB_PASSWORD_2", "DBpass3"'><?php echo $class_administrator_bs->arrayToStringArray($set['PASS'], true); ?></textarea>
                                </div>

 
                        <?php
                            }else{
                              echo '<div class="alert alert-warning alert-has-icon">
                              <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                              <div class="alert-body">
                                <div class="alert-title">Saved</div>
                                Your db informations are saved.
                                <br><br><a class="btn btn-primary" href="?edit=database">Reload Page</a>
                              </div>
                            </div>';
                            }
// CAPTCHA
                            }elseif($_GET['edit'] == "captcha"){
                              $set = $BS_SETTINGS_INTERNAL['CAPTCHA'];
                        ?>
                              <form action="?edit=captcha" method="POST">
                                <input type="hidden" name="case" value="update_captcha" />

                                <div class="section-title" style="margin-top:0px;">Google Captcha</div>

                                <div class="form-group">
                                  <label>Public Key</label>
                                  <input type="text" class="form-control" name="GOOGLE_CAPTCHA_PUBLIC-KEY" value="<?php echo $set['GOOGLE_CAPTCHA_PUBLIC-KEY']; ?>">
                                </div>
                                <div class="form-group">
                                  <label>Private Key</label>
                                  <input type="text" class="form-control" name="GOOGLE_CAPTCHA_PRIVATE-KEY" value="<?php echo $set['GOOGLE_CAPTCHA_PRIVATE-KEY']; ?>">
                                </div>

                                <div class="section-title">HCAPTCHA</div>

                                <div class="form-group">
                                  <label>Public Key</label>
                                  <input type="text" class="form-control" name="HCAPTCHA_PUBLIC-KEY" value="<?php echo $set['HCAPTCHA_PUBLIC-KEY']; ?>">
                                </div>
                                <div class="form-group">
                                  <label>Private Key</label>
                                  <input type="text" class="form-control" name="HCAPTCHA_PRIVATE-KEY" value="<?php echo $set['HCAPTCHA_PRIVATE-KEY']; ?>">
                                </div>
                        <?php
// ERRORS
                            }elseif($_GET['edit'] == "errors"){
                              $set = $BS_SETTINGS_INTERNAL['ERRORS'];
                              ?>
                                  <form action="?edit=errors" method="POST">
                                  <input type="hidden" name="case" value="update_errors" />
                                  <div class="form-group">
                                    <label>(on error) Evit public pages die</label>
                                    <select class="custom-select" name="NEVER_DIE_PAGE">
                                      <option disabled>Select an option</option>
                                      <?php 
                                        if($set['NEVER_DIE_PAGE']){
                                          echo '<option value="true" selected="">True </option>
                                          <option value="false">False (Default)</option>';
                                        }else{
                                          echo '<option value="true">True </option>
                                          <option value="false" selected="">False (Default)</option>';
                                        }
                                      ?>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label>Show HTML errors on public pages</label>
                                    <select class="custom-select" name="SHOW_HTML_ERROR_MESSAGE">
                                      <option disabled>Select an option</option>
                                      <?php 
                                        if($set['SHOW_HTML_ERROR_MESSAGE']){
                                          echo '<option value="true" selected="">True </option>
                                          <option value="false">False (Default)</option>';
                                        }else{
                                          echo '<option value="true">True </option>
                                          <option value="false" selected="">False (Default)</option>';
                                        }
                                      ?>
                                    </select>
                                  </div>

                                  <div class="form-group">
                                    <label>Default Public Error Message</label>
                                    <textarea style="min-height:80px;" class="form-control" name="DEFAULT_PUBLIC_ERROR" rows="3" width="100%" placeholder=""><?php echo $set['DEFAULT_PUBLIC_ERROR']; ?></textarea>
                                  </div>

                                  <div class="form-group">
                                    <label>Default Error Message</label>
                                    <textarea style="min-height:80px;" class="form-control" name="DEFAULT_ERROR" rows="3" width="100%" placeholder=""><?php echo $set['DEFAULT_ERROR']; ?></textarea>
                                  </div>

                                  <div class="form-group">
                                    <label>Default Public Error Message</label>
                                    <textarea style="min-height:80px;" class="form-control" name="DEFAULT_DIE_ERROR" rows="3" width="100%" placeholder=""><?php echo $set['DEFAULT_DIE_ERROR']; ?></textarea>
                                  </div>
  
                                  
                        <?php
                            }else{
                              header("Location: ?all");
                            }
                        ?>


                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary" type="submit">Save Changes</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
  		    </div>
      	</section>
      </div>
<?php
}
?>

<?php
    include("footer.php");
?>