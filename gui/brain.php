<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class ADMINISTRATOR{
    public $plugins_folder;
    public $plugin_indexFile;
    public $plugin_confFile;
    public $plugin_enabled;
    public $internal_bs_version = "10-0-0";
    public function __construct(){
        session_name("secure");
        session_start();
        if(include("../settings.php")){
            $this->plugins_folder = $BS_SETTINGS_INTERNAL['PLUGINS']['FOLDER_NAME'];
            $this->plugin_indexFile = $BS_SETTINGS_INTERNAL['PLUGINS']['FILE-INCLUDED_FROM_BASECODE'];
            $this->plugin_confFile = $BS_SETTINGS_INTERNAL['PLUGINS']['FILE-GUI_PLUGIN_MANAGER'];
            $this->plugin_enabled = $BS_SETTINGS_INTERNAL['PLUGINS']['IS_ENABLED'];
        }else{
            $this->plugins_folder = "plugins";
            $this->plugin_indexFile = "index.php";
            $this->plugin_confFile = "config.php";
            $this->plugin_enabled = true;
        }
    }
    public function check_main_status(){
        exec("php -l ../main.php", $output_check_main, $return_check_main);
        if ($return_check_main === 0) {
            return true;
        }else{
            return false;
        }
    }
    public function login($username,$password){
        include("userdata.php");
        if(urlencode(htmlspecialchars($username)) == $real_username){
          if(password_verify($password,$real_password)){
            return true;
          }else{
            return false;
          }
        }else{
          return false;
        }
      
      }
    public function check_logged(){
        global $_SESSION;
        if(isset($_SESSION['logged']) and $_SESSION['logged']){
            if(isset($_SESSION['username'])){
                return true;
            }else{
                return false;
                die();
            }
        }else{
            return false;
            die();
        }
        return false;
        die();
    }
    public function getSubDirectories($path){
        $dirs = array();
        $dir = dir($path);
        while (false !== ($entry = $dir->read())) {
            if ($entry != '.' && $entry != '..') {
                if (is_dir($path . '/' .$entry)) {
                        $dirs[] = $entry; 
                }
            }
        }
        return $dirs;
    }
    public function numPlugins(){
        $num = $this->getSubDirectories("../".$this->plugins_folder."/");
        return count($num);
    }
    public function numActivePlugins(){
        $nums = 0;
        foreach($this->getSubDirectories("../".$this->plugins_folder."/") as $plugin){
            if(!file_exists("../".$this->plugins_folder."/".$plugin."/.disabled")){
                $nums++;
            }
        }
        return $nums;
    }
    public function restore_file($file_to_restore, $backup_file){
        if(!$this->check_logged()){ return die("NOT LOGGED"); }
        $bakup = file_get_contents(__DIR__."/".$backup_file);
        $f=fopen($file_to_restore,'w');
        fwrite($f,$bakup);
        fclose($f);
    }
    public function getPlugins(){
        if (!file_exists(__DIR__."/".$this->plugins_folder)) {
            mkdir($this->plugins_folder, 0777);
        }
        $plugins = $this->getSubDirectories("../".$this->plugins_folder);
        return $plugins;
    }
    public function delete_files($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
    
            foreach( $files as $file ){
                $this->delete_files( $file );      
            }
    
            rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );  
        }
    }
    public function delete_ALL($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
              if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") 
                    delete_ALL($dir."/".$object); 
                else unlink   ($dir."/".$object);
              }
            }
            reset($objects);
            rmdir($dir);
          }
    }
    public function plugin_status($name){
        if(file_exists("../".$this->plugins_folder."/".$name."/.disabled")){
            return array(false, 3);
        }
        if(file_exists("../".$this->plugins_folder."/".$name."/".$this->plugin_indexFile)){
            exec("php -l ../".$this->plugins_folder."/".$name."/".$this->plugin_indexFile, $output_check_pl, $return_check_pl);
            if ($return_check_pl === 0) {
                return array(true, 0);
            }else{
                return array(false, 1);
            }
        }else{
            return array(false, 2);
        }
    }
    public function delete_plugin($name){
        if(!is_dir("../".$this->plugins_folder."/".$name."/")){
            return false;
        }
        $this->delete_ALL("../".$this->plugins_folder."/".$name."/");
        return true;
    }
    public function enable_plugin($name){
        if(!is_dir("../".$this->plugins_folder."/".$name."/")){
            return false;
        }
        $this->delete_files("../".$this->plugins_folder."/".$name."/.disabled");
        return true;
    }
    public function disable_plugin($name){
        if(!is_dir("../".$this->plugins_folder."/".$name."/")){
            return false;
        }
        $disabletor = fopen("../".$this->plugins_folder."/".$name."/.disabled", "w") or die("Unable to open file!");
        $txt = "What are you doing here?";
        fwrite($disabletor, $txt);
        fclose($disabletor);
        return true;
    }
    public function extractPlugin($filename, $enable=false){
        $file_path = "../".$this->plugins_folder;
        $target_file = $file_path."/".$filename;
        if($this->plugin_enabled) {
            $source = $file_path;
            $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
            if(!in_array(mime_content_type($target_file), $accepted_types)){
                unlink($target_file);
                return array(false, "The file you are trying to upload is not a ZIP file or is corrupted. Please try again.");
            }
            $expl_filename = explode(".", $filename);
            if(strtolower(end($expl_filename)) != 'zip'){
                unlink($target_file);
                return array(false, "The file you are trying to upload is not a .zip file. Please try again.");
                die();
            }
            // extract zip
                $zip = new ZipArchive();
                $x = $zip->open($target_file);
                $plugin_name = explode("/", $zip->getNameIndex(0))['0'];
                for($i = 0; $i < $zip->numFiles; $i++){  
                    if(explode("/", $zip->getNameIndex($i))[0] != $plugin_name){
                        unlink($target_file);
                        return array(false, "The zipped file must contain ONLY the plugin folder");
                        die();
                    }
                }
                if(!is_dir($file_path."/".$plugin_name)){
                    if ($x) {
                        $zip->extractTo($file_path."/");
                        $zip->close();
                        unlink($target_file);
                    }else{
                        unlink($target_file);
                        return array(false, "PHP have not extracted the zip file... try again.");
                    }
                    if(is_dir($file_path."/".$plugin_name)){
                        $ds_file = fopen($file_path."/".$plugin_name."/.disabled", "w") or die("Unable to create/delete file, check permissions.");
                        fwrite($ds_file, "");
                        fclose($ds_file);
                        // if you find an easter egg is no here ;)
                        chmod($file_path."/".$plugin_name, 0777);
                        if($enable){
                            $this->enable_plugin($plugin_name);
                            $add_msg = " and Enabled!";
                        }else{
                            $add_msg = ", go in <a href='./plugins.php'>plugins sections</a> to enable!";
                        }
                        return array(true, "Plugin \"<b>".$plugin_name."</b>\" installed correctly".$add_msg."<br><br><br><a class='btn btn-primary' href='./plugins.php'>MANAGE PLUGINS</a>");
                    }else{
                        unlink($file_path."/".$plugin_name);
                        return array(false, "There is no folder in this zipped file!");
                    }
                }else{
                    unlink($target_file);
                    return array(false, "Another plugin with the same name already exist in the plugins folder!");
                }
                
        }
        return array(false, "Plugins are disabled from settings!");
    }
    public function installPluginBYurl($source, $enable=false){
        $new_name = uniqid(time(), true) .".zip";
        $destination = "../{$this->plugins_folder}/".$new_name;
        if(in_array  ('curl', get_loaded_extensions())) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $source);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec ($ch);
            curl_close ($ch);
            $file = fopen($destination, "w+");
            fputs($file, $data);
            fclose($file);
        }else{
            file_put_contents($destination, fopen($source, 'r'));
        }
        if(!file_exists($destination)){
            return array(false, "Impossible to download zip file");
        }
        return $this->extractPlugin($new_name,$enable);
    }
    public function installPluginBYupload($enable=false){
        if(isset($_FILES['plugin_zip'])){
            $new_name = uniqid(time(), true) .".zip";
            $destination = "../{$this->plugins_folder}/".$new_name;
            $file_tmp = $_FILES['plugin_zip']['tmp_name'];
            $name_expl = explode('.',$_FILES['plugin_zip']['name']);
            $file_ext=strtolower(end($name_expl));
            if($file_ext != "zip"){
                return array(false, "The file you are trying to upload is not a .zip file. Please try again.");
                die();
            }
            if(move_uploaded_file($file_tmp, $destination)){
                return $this->extractPlugin($new_name,$enable);
            }else{
                return array(false, "Error with PHP move_uploaded_file()");
                die();
            }
        }else{
            return array(false, "Where is the zip?");
        }
    }
    public function boolToString($value){
        if($value){
            return "true";
        }else{
            return "false";
        }
    }
    public function redirect($url){
        if (!headers_sent()){
            header('Location: '.$url);
            exit;
          }else{
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.$url.'";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
            echo '</noscript>'; exit;
        }
    }
    public function arrayToStringArray($array, $pure=false){
        $pre = "array(";
        $el = "";
        if(is_array($array)){
            foreach($array as $element){
                if(is_array($element)){
                    $this->arrayToStringArray($element);
                }else{
                    $el .= "\"".str_replace('"','\"',$element)."\", ";
                }
            }
            $el = rtrim($el,", ");
            if($pure){
                return $el;
            }else{
                return $pre.$el.")";
            }
        }else{
            return $array;
        }
    }

    public function stringToArrayFake($strings){
        return "array(".$strings.")";
    }
    
    public function saveSettings($new_settings){ 
        $new_settingsFile = "<?php \n \$BS_SETTINGS_INTERNAL = array( \n \x20\x20\"GENERAL\" => array( \n \x20\x20\x20\x20\x20\"DEBUG_MODE_PHP\" => {$this->boolToString($new_settings['GENERAL']['DEBUG_MODE_PHP'])}, // show all php errors \n \x20\x20\x20\x20\x20\"DEBUG_MODE\" => {$this->boolToString($new_settings['GENERAL']['DEBUG_MODE'])}, // if enabled on error/warnig create a log file \n \x20\x20\x20\x20\x20\"DEBUG_VAR\" => {$this->boolToString($new_settings['GENERAL']['DEBUG_VAR'])}, // If true all actions are save in var: \$DEBUG \n \n \x20\x20\x20\x20\x20\"PRE_DECLARED_CLASSES\" => {$this->boolToString($new_settings['GENERAL']['DEBUG_MODE_PHP'])}, \n \x20\x20\x20\x20\x20\"PRE_DECLARED_CLASSES_NAMES\" => array(\"tools\"   => \"{$new_settings['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['tools']}\", \n \x20\x20\x20\x20\x20                              \"captcha\" => \"{$new_settings['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['captcha']}\", \n \x20\x20\x20\x20\x20                              \"agent\"   => \"{$new_settings['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['agent']}\", \n \x20\x20\x20\x20\x20                              \"sql\"     => \"{$new_settings['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['sql']}\", \n \x20\x20\x20\x20\x20                              \"html\"    => \"{$new_settings['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['html']}\" \n \x20\x20\x20\x20                            ) \n \x20\x20), \n \x20\x20\"PLUGINS\" => array( \n \x20\x20\x20\x20\x20\"IS_ENABLED\" => {$this->boolToString($new_settings['PLUGINS']['IS_ENABLED'])}, \n \x20\x20\x20\x20\x20\"FOLDER_NAME\" => \"{$new_settings['PLUGINS']['FOLDER_NAME']}\", \n \x20\x20\x20\x20\x20\"FILE-INCLUDED_FROM_BASECODE\" => \"{$new_settings['PLUGINS']['FILE-INCLUDED_FROM_BASECODE']}\", \n \x20\x20\x20\x20\x20\"FILE-GUI_PLUGIN_MANAGER\" => \"{$new_settings['PLUGINS']['FILE-GUI_PLUGIN_MANAGER']}\", \n \x20\x20), \n \x20\x20\"DB\" => array( \n \x20\x20\x20\x20\x20\"IS_ENABLED\" => {$this->boolToString($new_settings['DB']['IS_ENABLED'])}, // enable or disabled db \n \x20\x20\x20\x20\x20\"HOST\" => {$this->arrayToStringArray($new_settings['DB']['HOST'])}, \n \x20\x20\x20\x20\x20\"NAME\" => {$this->arrayToStringArray($new_settings['DB']['NAME'])}, \n \x20\x20\x20\x20\x20\"USER\" => {$this->arrayToStringArray($new_settings['DB']['USER'])}, \n \x20\x20\x20\x20\x20\"PASS\" => {$this->arrayToStringArray($new_settings['DB']['PASS'])}, \n \x20\x20), \n \x20\x20\"CAPTCHA\" => array( \n \x20\x20\x20\x20\x20\"GOOGLE_CAPTCHA_PUBLIC-KEY\" => \"{$new_settings['CAPTCHA']['GOOGLE_CAPTCHA_PUBLIC-KEY']}\", \n \x20\x20\x20\x20\x20\"GOOGLE_CAPTCHA_PRIVATE-KEY\" => \"{$new_settings['CAPTCHA']['GOOGLE_CAPTCHA_PRIVATE-KEY']}\", \n \x20\x20\x20\x20\x20\"HCAPTCHA_PUBLIC-KEY\" => \"{$new_settings['CAPTCHA']['HCAPTCHA_PUBLIC-KEY']}\", \n \x20\x20\x20\x20\x20\"HCAPTCHA_PRIVATE-KEY\" => \"{$new_settings['CAPTCHA']['HCAPTCHA_PRIVATE-KEY']}\", \n \x20\x20), \n \x20\x20// Errors \n \x20\x20\"ERRORS\" => array( \n \x20\x20\x20\x20\x20\x20\"NEVER_DIE_PAGE\" => {$this->boolToString($new_settings['ERRORS']['NEVER_DIE_PAGE'])}, \n \x20\x20\x20\x20\x20\x20\"SHOW_HTML_ERROR_MESSAGE\" => {$this->boolToString($new_settings['ERRORS']['SHOW_HTML_ERROR_MESSAGE'])}, \n \n \x20\x20\x20\x20\x20\"DEFAULT_PUBLIC_ERROR\" => \"{$new_settings['ERRORS']['DEFAULT_PUBLIC_ERROR']}\", \n \x20\x20\x20\x20\x20\"DEFAULT_ERROR\" => \"{$new_settings['ERRORS']['DEFAULT_ERROR']}\", \n \x20\x20\x20\x20\x20\"DEFAULT_DIE_ERROR\" => \"{$new_settings['ERRORS']['DEFAULT_DIE_ERROR']}\", \n \x20\x20) \n);";
        $f=fopen('../settings.php','w');
        fwrite($f,$new_settingsFile);
        fclose($f);
    }
}
$class_administrator_bs = new ADMINISTRATOR;

$msg="Hello ;)";
if(isset($_POST) && !empty($_POST['case']) && !empty($_GET['bs_show_brainHTML'])){
    if($_POST['case'] and $_POST['case'] == "reset_ALL"){
        if(!$class_administrator_bs->check_logged()){ return die("NOT LOGGED"); }
        $rq = $class_administrator_bs->login($_SESSION['username'], $_POST['password']);
        if($rq){
            $class_administrator_bs->restore_file("../main.php", "backup/backup_main.php");
            $class_administrator_bs->restore_file("../settings.php", "backup/backup_settings.php");
            $class_administrator_bs->restore_file("./brain.php", "backup/backup_brain.php");
            $type = "success";
            $msg = "ALL FILE ARE RESTORED!";
        }else{
            $type="danger";
            $msg = "INCORRECT PASSWORD";
        }
        
    }elseif($_POST['case'] and $_POST['case'] == "reset_MAIN"){
        if(!$class_administrator_bs->check_logged()){ return die("NOT LOGGED"); }
        $rq = $class_administrator_bs->login($_SESSION['username'], $_POST['password']);
        if($rq){
            $class_administrator_bs->restore_file("../main.php", "backup/backup_main.php");
            $type = "success";
            $msg = "MAIN FILE RESTORED!";
        }else{
            $type="danger";
            $msg = "INCORRECT PASSWORD";
        }
        
    }elseif($_POST['case'] and $_POST['case'] == "reset_SETTINGS"){
        if(!$class_administrator_bs->check_logged()){ return die("NOT LOGGED"); }
        $rq = $class_administrator_bs->login($_SESSION['username'], $_POST['password']);
        if($rq){
            $class_administrator_bs->restore_file("../settings.php", "backup/backup_settings.php");
            $type = "success";
            $msg = "SETTINGS FILE RESTORED!";
        }else{
            $type="danger";
            $msg = "INCORRECT PASSWORD";
        }
        
    }elseif($_POST['case'] and $_POST['case'] == "reset_BRAIN"){
        if(!$class_administrator_bs->check_logged()){ return die("NOT LOGGED"); }
        $rq = $class_administrator_bs->login($_SESSION['username'], $_POST['password']);
        if($rq){
            $class_administrator_bs->restore_file("./brain.php", "backup/backup_brain.php");
            $type = "success";
            $msg = "BRAIN FILE RESTORED!";
        }else{
            $type="danger";
            $msg = "INCORRECT PASSWORD";
        }
        
    }?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>BaseCode</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/components.css">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-<?php echo $type; ?>">
                    <div class="card-header">
                        <h4>ALERT</h4>
                    </div>
                    <div class="card-body">
                    <br>
                        <code><h3><?php echo $msg; ?></h3></code>
                    </div>
                    <div class="card-footer text-right">
                        <a href="index.php" class="btn btn-block btn-primary">RETURN TO DASHBOARD</a>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="./assets/js/stisla.js"></script>

  <!-- JS Libraies -->
  <script src="http://maps.google.com/maps/api/js?key=AIzaSyB55Np3_WsZwUQ9NS7DP-HnneleZLYZDNw&amp;sensor=true"></script>
  <script src="./node_modules/gmaps/gmaps.min.js"></script>

  <!-- Template JS File -->
  <script src="./assets/js/scripts.js"></script>
  <script src="./assets/js/custom.js"></script>

  <!-- Page Specific JS File -->
  <script src="./assets/js/page/utilities-contact.js"></script>
</body>
</html>
<?php 
}
?>