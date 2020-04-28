<?php
if(!isset($enable_head_security)){ die(";("); }
$bs_version = "10.0.0"; 
$bs_edition = "BR update 2";
if(!include("brain.php")){
    die("brain.php not found! <a href='https://greco395.com/basecode/docs/?error=brain_not_found#brain'>more info</a>");
}
if(isset($_GET) && isset($_GET['logout'])){
  if($_GET['logout'] == "now"){
    unset($_SESSION);
    session_destroy();
  }
}
if(!$class_administrator_bs->check_logged()){ header("Location: access.php"); die("Not logged, click <a href='access.php'>here</a>."); }
if(!isset($page_title)){ $page_title="Basecode"; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?php echo $page_title; ?></title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="./node_modules/jqvmap/dist/jqvmap.min.css">
  <link rel="stylesheet" href="./node_modules/weathericons/css/weather-icons.min.css">
  <link rel="stylesheet" href="./node_modules/weathericons/css/weather-icons-wind.min.css">
  <link rel="stylesheet" href="./node_modules/summernote/dist/summernote-bs4.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/components.css">
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="./assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">Hi, Admin</div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="?logout=now" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="./">BaseCode</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="./">BS</a>
          </div>
          <ul class="sidebar-menu">
              <li class="menu-header">Dashboard</li>
              <li class="nav-item <?php if($page_name == "home"){ echo "active"; } ?> ">
                <a href="./" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
              </li>
              <li class="menu-header">Plugins</li>
              <li class="nav-item <?php if($page_name == "plugins"){ echo "active"; } ?>">
                <a href="./plugins.php" class="nav-link"><i class="fas fa-plug"></i><span>Manage Plugins</span></a>
              </li>
              <li class="nav-item <?php if($page_name == "add_plugin"){ echo "active"; } ?>">
                <a href="./add_plugin.php" class="nav-link"><i class="fas fa-plus"></i><span>Add Plugin</span></a>
              </li>
              <li class="menu-header">Configurations</li>
              <li class="nav-item <?php if($page_name == "settings"){ echo "active"; } ?>">
                <a href="./edit_configs.php" class="nav-link"><i class="fas fa-cog"></i><span>Settings</span></a>
              </li>
            </ul>

            <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
              <a href="https://greco395.com/basecode/docs" target="_blank" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-book"></i> Documentation
              </a>
            </div>
        </aside>
      </div>