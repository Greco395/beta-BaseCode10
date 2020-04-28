<?php
/*~ GR3Studios BaseCode
.---------------------------------------------------------------------------.
|  Software: BaseCode - PHP base classes                                    |
|   Version: 10.0                                                           |
|      Site: https://domenicogreco.com/basecode/                            |
| ------------------------------------------------------------------------- |
|     Admin: Greco Domenico (project admininistrator & Author)              |
|   Founder: domenicogreco.com                                              |
| Copyright (c) 2018-2020, GR3Studios. All Rights Reserved.                 |
| ------------------------------------------------------------------------- |
|   License: Attribution-NonCommercial 4.0 International (CC BY-NC 4.0)     |
|            http://creativecommons.org/licenses/by-nc/4.0/                 |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
*/
/**
 * BaseCode - PHP base classes
 * NOTE: Requires PHP version 5 or later (written in 7.4)
 * @see       https://domenicogreco.com/ 
 * @author Greco Domenico
 * @copyright 2018 - 2020+ GR3Studios
 * @version 10.0.0
 * @license http://creativecommons.org/licenses/by-nc/4.0/ (CC BY-NC 4.0)
 * @license https://creativecommons.org/licenses/by-nc/4.0/legalcode
 */

if (version_compare(PHP_VERSION, '5.0.0', '<') ) exit("Sorry, this version of BaseCode will only run on PHP version 5 or greater!\n");
$basecode_version_name = "PHP BaseCode 10";
$basecode_version = "10.0.0";
$basecode_edition = "NBV";

if(!include("settings.php")){ die("Settings file not found, go in admin panel to fix!"); }

// check if php debug mode is enabled ( if enabled display all php errors )
if($BS_SETTINGS_INTERNAL['GENERAL']['DEBUG_MODE_PHP']){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}else{
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}
$BS_DEBUG=array();
function add_log($add_log, $type = "GENERIC_ERROR", $forced = false){
    global $BS_SETTINGS_INTERNAL;
    $new_log = "{Date: [".date("m-d-Y H:i", time())."]} - {Log_Type: [".$type."]} - {Log_Content: ['".$add_log."']}";
    if($BS_SETTINGS_INTERNAL['GENERAL']['DEBUG_VAR']){
        global $BS_DEBUG;
        $BS_DEBUG .= $new_log;
    }
    if($BS_SETTINGS_INTERNAL['GENERAL']['DEBUG_MODE'] == true or $forced == true){
        $this_directory = dirname(__FILE__);
        $log_file_name  = "basecode_error.log";
        if(!file_exists($log_file_name)){
            $fp = fopen($this_directory . "/".$log_file_name, "w");
            fwrite($fp, $new_log); 
            fclose($fp);
        }else{
            $log_file = fopen($this_directory . "/".$log_file_name, "a");
            fwrite($log_file, "\n----\n". $new_log);
            fclose($log_file);
        }
        return true; 
    }else{
        return false;
    }
}
function bs_die($error, $die_text = null){
    global $BS_SETTINGS_INTERNAL;
    if(!$BS_SETTINGS_INTERNAL['ERRORS']['NEVER_DIE_PAGE']){
        if(is_null($text)){
            die($BS_SETTINGS_INTERNAL['ERRORS']['DEFAULT_DIE_ERROR']);
        }
    }
    if($BS_SETTINGS_INTERNAL['ERRORS']['SHOW_HTML_ERROR_MESSAGE']){
        echo "<hr>".$error."<br><hr>for hide this messages edit ERRORS->SHOW_HTML_ERROR_MESSAGE to false<br><hr>";
    }
}

class INIT_BASE{ // this class supports max 10 db
    public $dbh = null;
    public $dbh1 = null;
    public $dbh2 = null;
    public $dbh3 = null;
    public $dbh4 = null;
    public $dbh5 = null;
    public $dbh6 = null;
    public $dbh7 = null;
    public $dbh8 = null;
    public $dbh9 = null;
    public $dbh_db_name = null;
    public $dbh1_db_name = null;
    public $dbh2_db_name = null;
    public $dbh3_db_name = null;
    public $dbh4_db_name = null;
    public $dbh5_db_name = null;
    public $dbh6_db_name = null;
    public $dbh7_db_name = null;
    public $dbh8_db_name = null;
    public $dbh9_db_name = null;
    public function __construct(){
        global $BS_SETTINGS_INTERNAL;
        // DB
        if($BS_SETTINGS_INTERNAL['DB']['IS_ENABLED']){
            if(is_array($BS_SETTINGS_INTERNAL['DB']['NAME'])){
                $total_conn = count($BS_SETTINGS_INTERNAL['DB']['NAME']);
                if($total_conn > 10){
                    add_log("The MAX number of connection DB's are 10, your connections are ".$total_conn, "DB_CONN_ERROR");
                    bs_die("The MAX number of connection DB's are 10, your connections are ".$total_conn);
                }
                foreach(range(0, $total_conn-1) as $conn_num) {
                    if($conn_num == 0){
                        $conn_var = "";
                    }else{
                        $conn_var = $conn_num;
                    }
                    try {
                        $this->{"dbh".$conn_var} = new PDO('mysql:host='.$BS_SETTINGS_INTERNAL['DB']['HOST'][$conn_num].';dbname='.$BS_SETTINGS_INTERNAL['DB']['NAME'][$conn_num].'', $BS_SETTINGS_INTERNAL['DB']['USER'][$conn_num], $BS_SETTINGS_INTERNAL['DB']['PASS'][$conn_num]);
                        $this->{"dbh".$conn_var."_db_name"} = $BS_SETTINGS_INTERNAL['DB']['NAME'][$conn_num];
                    } catch (PDOException $e) {
                        add_log("DB_VAR: dbh{$conn_var}, DB_NAME: {$BS_SETTINGS_INTERNAL['DB']['NAME'][$conn_num]}, ERROR: ".$e->getMessage(), "DB_CONN_ERROR");
                        bs_die("DB_VAR: dbh{$conn_var}, DB_NAME: {$BS_SETTINGS_INTERNAL['DB']['NAME'][$conn_num]}, ERROR: ".$e->getMessage());
                    }
                }
            }else{
                try {
                    $this->dbh = new PDO('mysql:host='.$BS_SETTINGS_INTERNAL['DB']['HOST'].';dbname='.$BS_SETTINGS_INTERNAL['DB']['NAME'].'', $BS_SETTINGS_INTERNAL['DB']['USER'], $BS_SETTINGS_INTERNAL['DB']['PASS']);
                } catch (PDOException $e) {
                    add_log($e->getMessage(), "DB_CONN_ERROR");
                    bs_die($e->getMessage());
                }
            }
        } #end_DB
        // SESSION 
        if(!isset($_SESSION)){
            session_name("secure");
            session_start();
        }
    }
}
$init = new INIT_BASE();

class TOOLS{
    public function get_domaininfo($url) {
        preg_match("/^(https|http|ftp):\/\/(.*?)\//", "$url/" , $matches);
        $parts = explode(".", $matches[2]);
        $tld = array_pop($parts);
        $host = array_pop($parts);
        if ( strlen($tld) == 2 && strlen($host) <= 3 ) {
            $tld = "$host.$tld";
            $host = array_pop($parts);
        }
        return array(
            'protocol' => $matches[1],
            'subdomain' => implode(".", $parts),
            'domain' => "$host.$tld",
            'host'=>$host,'tld'=>$tld
        );
    }
    public function get_current_url($include_parameters = true){ 
        global $_SERVER, $_GET;
        $s = &$_SERVER;
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        $uri = $protocol . '://' . $host . $s['REQUEST_URI'];
        $segments = explode('?', $uri, 2);
        $url = $segments[0];
        if($include_parameters){
            $params = "";
            foreach($_GET as $key => $value){
                $params .= $key . "=" . $value. "&";
            }
            $url = rtrim($url."?".$params,"&");
        }
        return $url;
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
    public function is_dateExpired($date_in){ // date format: dd-mm-YY
        $today = date('d-m-Y');
        $expireDate = new DateTime($date_in);
        $date1=date_create($today);
        $date2=date_create($expireDate->format('Y-m-d'));
        $diff=date_diff($date1,$date2);
        $timeDiff = $diff->format("%R%a days");
        if($timeDiff < 0){
            return array(false, "Expired", $timeDiff, $diff);
        }else{
            return array(true, "Not Expired", $timeDiff, $diff);
        }
    }
    public function arrayAllKeysAreNumbers($array) {
        foreach($array as $key => $value){
            if(!is_numeric($key)){
                return false;
            }
        }
        return true;
    }
    public function is_3d_array($a) {
        $rv = array_filter($a,'is_array');
        if(count($rv)>0) return true;
        return false;
    }
    public function array_divider($array, $divider1 = " = ", $divider2 = ", "){
        $string="";
        $callback = 
        function ($value, $key) use (&$string) {
            $string .= $key ."||||||". $value . "%$%$%";
        };
        array_walk_recursive($array, $callback);
        $res = substr_replace($string, "", -5);
        $res = str_replace("||||||", $divider1, $res);
        $res = str_replace("%$%$%", $divider2, $res);
        return $res;
    }
    public function dateITA($date = "d M Y H:i"){
        $giorno = date("d", time());
        $mese = date("m", time());
        $anno = date("Y", time());
        $ora = date("H", time());
        $minuti = date("i", time());
        $arrayMesi = array("01" => "Gennaio", "02" => "Febbraio", "03" => "Marzo", "04" => "Aprile", "05" => "Maggio", "06" => "Giugno", "07" => "Luglio", "08" => "Agosto", "09" => "Settembre", "10" => "Ottobre", "11" => "Novembre", "12" => "Dicembre");
        $mese_ita = $arrayMesi[$mese];
        $date = str_replace("d", $giorno, $date);
        $date = str_replace("D", $giorno, $date);
        $date = str_replace("y", $anno, $date);
        $date = str_replace("Y", $anno, $date);
        $date = str_replace("h", $ora, $date);
        $date = str_replace("H", $ora, $date);
        $date = str_replace("i", $minuti, $date);
        $date = str_replace("I", $minuti, $date);
        $date = str_replace("m", $mese_ita, $date);
        $date = str_replace("M", $mese_ita, $date);
        return $date;
    }
}
if($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES']){
    if(isset($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['tools']) || $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES']['tools'] != false){
        if(!is_null($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['tools'])){
            ${$BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['tools']} = new TOOLS;
        } 
    }else{
        $class_tools = new TOOLS;
    }
}

class CAPTCHA{
    public $gc_public_key;
    private $gc_private_key;
    public $hc_public_key;
    private $hc_private_key;
    public function __construct(){    
        global $BS_SETTINGS_INTERNAL;
        $gc_public_key = isset($BS_SETTINGS_INTERNAL['CAPTCHA']['GOOGLE_CAPTCHA_PUBLIC-KEY']);
        $gc_private_key = isset($BS_SETTINGS_INTERNAL['CAPTCHA']['GOOGLE_CAPTCHA_PRIVATE-KEY']);
        $hc_public_key = isset($BS_SETTINGS_INTERNAL['CAPTCHA']['HCAPTCHA_PUBLIC-KEY']);
        $hc_private_key = isset($BS_SETTINGS_INTERNAL['CAPTCHA']['HCAPTCHA_PRIVATE-KEY']);
        if(isset($gc_public_key) and $gc_public_key != ""){
            if(isset($gc_private_key) and $gc_private_key != ""){
                $this->gc_public_key = $gc_public_key;
                $this->gc_private_key = $gc_private_key;
            }else{
                add_log("INVALID PUBLIC KEY", "GOOGLE_CAPTCHA_ERROR");
            }
        }else{
            add_log("INVALID PRIVATE KEY", "GOOGLE_CAPTCHA_ERROR");
        }
        if(isset($hc_public_key) and $hc_public_key != ""){
            if(isset($hc_private_key) and $hc_private_key != ""){
                $this->hc_public_key = $hc_public_key;
                $this->hc_private_key = $hc_private_key;
            }else{
                add_log("INVALID PUBLIC KEY", "HCAPTCHA_ERROR");
            }
        }else{
            add_log("INVALID PRIVATE KEY", "HCAPTCHA_ERROR");
        }
    }
    // GOOGLE CAPTCHA
    public function show_GCAPTCHA($theme = "dark"){
        return '<div class="g-recaptcha" data-sitekey="'.$this->gc_public_key.'" data-theme="'.$theme.'"></div>';
    }
    public function check_GCaptcha($post_or_get = "post"){
        global $_POST, $_GET;
        if(strtoupper($post_or_get) == "GET"){
            $g_recaptcha_response = $_GET['g-captcha-response'];
        }else{
            $g_recaptcha_response = $_POST['g-captcha-response'];
        }
        if(isset($g_recaptcha_response)){
            $private_key = $this->gc_private_key;
            $url       = 'https://www.google.com/recaptcha/api/siteverify';
            $data      =  array(
                'secret'   => $private_key,
                'response' => $g_recaptcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR']);
            $options = array(
                'http'     => array(
                'header'   => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'   => 'POST',
                'content'  => http_build_query($data)
                ));
            $context  = stream_context_create($options);
            $result = json_decode(file_get_contents($url, false, $context));
            if($result->success == 1){
                $result = true;
            }else{ 
                $result = false; 
            }
        }
        return $result;
    }
    // HCAPTCHA
    public function show_HCAPTCHA($theme = "dark"){
        return '<div class="h-captcha" data-sitekey="'.$this->gc_public_key.'" data-theme="'.$theme.'"></div>';
    }
    public function check_HCaptcha($post_or_get = "post"){
        global $_POST, $_GET;
        if(strtoupper($post_or_get) == "GET"){
            $captcha_response = $_GET['h-captcha-response'];
        }else{
            $captcha_response = $_POST['h-captcha-response'];
        }
        $secret = $this->hc_private_key;
        if(isset($captcha_response) && !empty($captcha_response)){
            // get verify response
            $verifyResponse = file_get_contents('https://hcaptcha.com/siteverify?secret='.$secret.'&response='.$captcha_response.'&remoteip='.$_SERVER['REMOTE_ADDR']);
            $responseData = json_decode($verifyResponse);
            // check response
            if($responseData->success){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
if($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES']){
    if(isset($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['captcha']) || $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES']['captcha'] != false){
        if(!is_null($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['captcha'])){
            ${$BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['captcha']} = new CAPTCHA;
        } 
    }else{
        $class_captcha = new CAPTCHA;
    }
}

class AGENT{
    public function get_ip(){
        global $_SERVER;
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }
        if(strlen($ip) < 4){
            $ip = "localhost";
        }
        return $ip;
    }
    public function get_os(){
        $user_agent = getenv("HTTP_USER_AGENT");
        if(strpos($user_agent, "Win") !== FALSE){
            $os = "windows";
        }elseif(strpos($user_agent, "Linux") !== FALSE){
            $os = "linux";
        }elseif(strpos($user_agent, "Mac") !== FALSE){
            $os = "mac";
        }elseif(strpos($user_agent, "Ios") !== FALSE){
            $os = "ios";
        }elseif(strpos($user_agent, "Android") !== FALSE){
            $os = "android";
        }else{
            $os = "Unknow";
        }
        return $os;
    }
    public function get_browser(){
        $browser = $_SERVER['HTTP_USER_AGENT'];
        return $browser;
    }
    public function get_refer(){
        $referrer = $_SERVER['HTTP_REFERER'];
        if ($referred == "") {
            $referrer = "direct";
        }
        return $referrer;
    }
}
if($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES']){
    if(isset($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['agent']) || $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES']['agent'] != false){
        if(!is_null($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['agent'])){
            ${$BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['agent']} = new AGENT;
        } 
    }else{
        $class_agent = new AGENT;
    }
}

class SQL extends INIT_BASE{
    private $dbs = null;
    private $dbs_name = null;
    public function set($db = "dbh"){
        $sel_db = $this->{$db};
        if(isset($sel_db)){
            $this->dbs = $sel_db;
            $this->dbs_name = $this->{$db."_db_name"};
        }else{
            add_log("SELECTED DB NOT FOUND! [ DB: \$".$db." ]","DB_NOT_FOUND-SQL_CLASS");
            bs_die("SELECTED DB NOT FOUND! [ DB: \$".$db." ]");
        }
    }
    public function check_IFselected_db(){
        global $BS_SETTINGS_INTERNAL;
        if(is_array($BS_SETTINGS_INTERNAL['DB']['NAME'])){
            if(count($BS_SETTINGS_INTERNAL['DB']['NAME']) > 1){
                if(is_null($this->dbs)){
                    add_log("Database not selected! before using sql function add \$class_sql->set(\$dbh)", "DB_ERROR_DATABASE-NOT-SELECTED", true);
                    bs_die("Database not selected! before using sql function add \$class_sql->set(\$dbh)");
                }
            }else{
                if(is_null($this->dbs)){
                    $this->dbs = $dbh;
                }
            }
        }else{
            if(is_null($this->dbs)){
                $this->dbs = $dbh;
            }
        }
    }
    public function tableExists($pdo, $table) {
        try {
            $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
        } catch (Exception $e) {
            return FALSE;
        }
        return $result !== FALSE;
    }
    public function create_table($table_name, $fields){
        global $BS_SETTINGS_INTERNAL;
        $this->check_IFselected_db();
        $pdo = $this->dbs;
        $sql_filds = "";
        $primary_key = "";
        foreach($fields as $field_name => $field_attr){
            $sql_add = "";
            if(isset($field_attr['type'])){
                $sql_add .= " ".$field_attr['type'];
            }else{
                $sql_add .= " varchar(256)";
            }
            if(in_array("null", $field_attr) or in_array("NULL", $field_attr)){
                $sql_add .= " NULL";
            }elseif(in_array("not_null", $field_attr) or in_array("NOT_NULL", $field_attr) or in_array("not null", $field_attr) or in_array("NOT NULL", $field_attr)){
                $sql_add .= " NOT NULL";
            }else{
                $sql_add .= " NULL";
            }
            if(isset($field_attr['default'])){
                $sql_add .= " DEFAULT '".$field_attr['default']."'";
            }
            if(in_array("AUTO_INCREMENT", $field_attr) or in_array("auto_increment", $field_attr)){
                $sql_add .= " AUTO_INCREMENT";
            }
            if(in_array("primary_key", $field_attr) or in_array("PRIMARY_KEY", $field_attr) or in_array("primary key", $field_attr) or in_array("PRIMARY KEY", $field_attr) or in_array("primary", $field_attr)){
                $primary_key = $field_name;
            }
            $sql_filds .= "`{$field_name}`".$sql_add.", ";
        }
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->dbs_name}`.`{$table_name}` (".$sql_filds." PRIMARY KEY (`{$primary_key}`)) ";
        $pdo->query($sql);
        return $this->tableExists($pdo, $table_name); 
    }
    public function columsCount($table_name){
        $pdo = $this->dbs;
        $stmt = $pdo->prepare("SELECT * FROM {$table_name}");
        $stmt->execute();
        return $stmt->columnCount();
    }
    public function tableToColums($table_name){
        $pdo = $this->dbs;
        $stmt = $pdo->prepare("SHOW COLUMNS FROM {$table_name}");
        $stmt->execute();
        $col = array();
        while ($row = $stmt->fetch(PDO::FETCH_NUM)){
            $col[] = (string)$row[0];
        }
        return $col;
    }
    public function run($sql){
        $pdo = $this->dbs;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function insert($table_name, $array){
        $pdo = $this->dbs;
        $table_colums = $this->tableToColums($table_name);
        $tools = new TOOLS;
        $to_add_cols = "";
        $to_add = "";
        $num = 0;
        if(!$tools->arrayAllKeysAreNumbers($array)){
            foreach($table_colums as $col){
                if(isset($array[$col])){
                    $to_add_cols .= " `".$col."`,";
                    if(is_null($array[$col]) or $array[$col] == "null" or $array[$col] == "NULL"){
                        $to_add .= " NULL,";
                    }else{
                        $to_add .= " '".$array[$col]."',";
                    }
                    
                }
            }
        }else{
            $num = 0;
            foreach($table_colums as $col){
                $to_add_cols .= " `".$col."`,";
                if(is_null($array[$num]) or $array[$num] == "null" or $array[$num] == "NULL"){
                    $to_add .= " NULL,";
                }else{
                    $to_add .= " '".$array[$num]."',";
                }
                $num++;
            }
        }
        $to_add_cols = rtrim($to_add_cols, ",");
        $to_add = rtrim($to_add, ",");
        $sql = "INSERT INTO `{$table_name}` ($to_add_cols) VALUES (".$to_add.")";
        $stmt = $pdo->prepare($sql);          
        if($stmt->execute()) { 
            return true;
        } else {
            add_log(json_encode($stmt->errorInfo()), "DB_INSERT-ERROT", true);
            return false;
        }
        $stmt->close();  
        
    }
    public function update($table_name, $array, $identifyer){
        $pdo = $this->dbs;
        $to_edit = "";
        $target_edit = "";
        $tools = new TOOLS;
        if(!$tools->arrayAllKeysAreNumbers($identifyer)){
            foreach($identifyer as $key => $value){
                $identifyer_db = array($key,$value);
            break;
            }
        }else{
            $identifyer_db = array($identifyer[0],$identifyer[1]);
        }
        foreach($array as $col => $new_value){
            if(is_null($new_value) or $new_value == "null" or $new_value == "NULL"){
                $edit_value = " NULL,";
            }else{
                $edit_value = "'".$new_value."',";
            }
            $target_edit .= " `".$col."` = ".$edit_value;
        }
        $target_edit = rtrim($target_edit,",");
        $sql = "UPDATE `{$table_name}` SET {$target_edit} WHERE `{$table_name}`.`{$identifyer_db[0]}` = {$identifyer_db[1]}";
        $stmt = $pdo->prepare($sql);          
        if($stmt->execute()) { 
            return true;
        } else {
            add_log(json_encode($stmt->errorInfo()), "DB_UPDATE-ERROT", true);
            return false;
        }
        $stmt->close(); 
    }
    public function select($table, $array){
        $pdo = $this->dbs;
        $tools = new TOOLS;
        if(!$tools->arrayAllKeysAreNumbers($array)){
            foreach($array as $key => $value){
                $identifyer_db = array($key,$value);
            break;
            }
        }else{
            $identifyer_db = array($array[0],$array[1]);
        }
        $stmt = $pdo->prepare("SELECT * FROM ".$table." WHERE ".$identifyer_db['0']." = ?");
        $stmt->execute(array($identifyer_db['1']));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function getRow($table, $array){ return $this->select($table, $array); }
}
if($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES']){
    if(isset($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['sql']) || $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES']['sql'] != false){
        if(!is_null($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['sql'])){
            ${$BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['sql']} = new SQL;
        } 
    }else{
        $class_sql = new SQL;
    }
}

class HTML{
    public function blockAdBlock($html_to_show_whe_blocked, $bg_color = "#fff", $opacity = "95"){
        // This script is inspired by the German site antiblock.org
        echo <<<HTML
        <style>#fbb8{position:fixed !important;position:absolute;top:-2px;top:expression((t=document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop)+"px");left:-1px;width:103%;height:98%;background-color:{$bg_color};opacity:.{$opacity};filter:alpha(opacity=95);display:block;padding:20% 0}#fbb8 *{text-align:center;margin:0 auto;display:block;filter:none;font:bold 14px Verdana,Arial,sans-serif;text-decoration:none}#fbb8 ~ *{display:none}</style><div id="fbb8"><span>Please enable / Bitte aktiviere JavaScript!<br>Veuillez activer / Por favor activa el Javascript!</span></div><script>window.document.getElementById("fbb8").parentNode.removeChild(window.document.getElementById("fbb8"));(function(f,k){function g(a){a&&fbb8.nextFunction()}var h=f.document,l=["i","u"];g.prototype={rand:function(a){return Math.floor(Math.random()*a)},getElementBy:function(a,c){return a?h.getElementById(a):h.getElementsByTagName(c)},getStyle:function(a){var c=h.defaultView;return c&&c.getComputedStyle?c.getComputedStyle(a,null):a.currentStyle},deferExecution:function(a){setTimeout(a,2E3)},insert:function(a,c){var e=h.createElement("span"),d=h.body,b=d.childNodes.length,m=d.style,f=0,g=0;if("fbb8"==c){e.setAttribute("id",c);m.margin=m.padding=0;m.height="100%";for(b=this.rand(b);f<b;f++)1==d.childNodes[f].nodeType&&(g=Math.max(g,parseFloat(this.getStyle(d.childNodes[f]).zIndex)||0));g&&(e.style.zIndex=g+1);b++}e.innerHTML=a;d.insertBefore(e,d.childNodes[b-1])},displayMessage:function(a){var c=this;a="abisuq".charAt(c.rand(5));c.insert("<"+a+'>{$html_to_show_whe_blocked}'+("</"+a+">"),"fbb8");h.addEventListener&&c.deferExecution(function(){c.getElementBy("fbb8").addEventListener("DOMNodeRemoved",function(){c.displayMessage()},!1)})},i:function(){for(var a="ad-tower,ad_728_90,premium_ad,rxgcontent,secondad,sideadscol,site-ads,ad,ads,adsense".split(","),c=a.length,e="",d=this,b=0,f="abisuq".charAt(d.rand(5));b<c;b++)d.getElementBy(a[b])||(e+="<"+f+' id="'+a[b]+'"></'+f+">");d.insert(e);d.deferExecution(function(){for(b=0;b<c;b++)if(null==d.getElementBy(a[b]).offsetParent||"none"==d.getStyle(d.getElementBy(a[b])).display)return d.displayMessage("#"+a[b]+"("+b+")");d.nextFunction()})},u:function(){var a="-popexit.,.utils.ads.,/ad2gather.,/ads-module.,/adsquareleft.,/adsword.,/googlempu.,/pagepeel/ad,/pc_ads.,/480x60_".split(","),c=this,e=c.getElementBy(0,"img"),d,b;e[0]!==k&&e[0].src!==k&&(d=new Image,d.onload=function(){b=this;b.onload=null;b.onerror=function(){l=null;c.displayMessage(b.src)};b.src=e[0].src+"#"+a.join("")},d.src=e[0].src);c.deferExecution(function(){c.nextFunction()})},nextFunction:function(){var a=l[0];a!==k&&(l.shift(),this[a]())}};f.fbb8=fbb8=new g;h.addEventListener?f.addEventListener("load",g,!1):f.attachEvent("onload",g)})(window);</script>
HTML;
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
    public function check_username($username, $public_message_success = true, $public_message_error = false){
        if(ctype_alnum(str_replace(array("-", "_"),"",$username))){
            return true;
        } else {
            return false;
        }
    }
    public function check_email($address_to_verify){
        list($user, $domain) = explode('@', $address_to_verify);
        if(checkdnsrr($domain, "MX")){
            if(strstr($address_to_verify, "@") == FALSE){
                return false;
            }else{
                if(strstr($domain, '.') == FALSE){
                    return false;
                }else{
                    return true;
                }
            }
        }else{
            return false;
        }
    }
}
if($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES']){
    if(isset($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['html']) || $BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES']['html'] != false){
        if(!is_null($BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['html'])){
            ${$BS_SETTINGS_INTERNAL['GENERAL']['PRE_DECLARED_CLASSES_NAMES']['html']} = new HTML;
        } 
    }else{
        $class_html = new HTML;
    }
}

/******************************************************************************/
/****************************** PLUGIN MANAGER ********************************/
/*************************************************************** Engine v3 ****/
/******************************************************************************/

class PLUGINS{
    public $plugin_folder;
    public $plugin_index_file;
    public $plugin_config_file;
    public $plugins;
    public $logs;
    function __construct(){
        global $BS_SETTINGS_INTERNAL;
        $this->plugin_folder = $BS_SETTINGS_INTERNAL['PLUGINS']['FOLDER_NAME'];
        $this->plugin_index_file = $BS_SETTINGS_INTERNAL['PLUGINS']['FILE-INCLUDED_FROM_BASECODE'];
        $this->plugin_config_file = $BS_SETTINGS_INTERNAL['PLUGINS']['FILE-GUI_PLUGIN_MANAGER'];
    }
    public function getPlugins(){
        $tools = new TOOLS;
        if (!file_exists(__DIR__."/".$this->plugin_folder)) {
            mkdir($this->plugin_folder, 0777);
        }
        $plugins = $tools->getSubDirectories(__DIR__."/".$this->plugin_folder);
        $this->plugins = $plugins;
        return $plugins;
    }
    public function load($plg, $force){
        if(file_exists(__DIR__."/".$this->plugin_folder."/".$plg."/.disabled")){
            if(isset($force)){
                $check_disabled = false;
            }else{
                $check_disabled = true;
            }
        }else{
            $check_disabled = false;
        }
        if($check_disabled == true){
            return "plugin_disabled";
        }else{
            if(file_exists(__DIR__."/".$this->plugin_folder."/".$plg."/".$this->plugin_index_file)){
                exec("php -l ".(__DIR__."/".$this->plugin_folder."/".$plg."/".$this->plugin_index_file)."", $output_check_plugin, $return_check_plugin);
                if ($return_check_plugin === 0) {
                    // Correct syntax
                    return include(__DIR__."/".$this->plugin_folder."/".$plg."/".$this->plugin_index_file);
                    $this->logs = "Date: ".date("m-d-Y H:i", time())." || Log_Type: SUCCESS_INCLUDED_PLUGIN || Log_Content: Plugin: ".$plg."<br>";
                }else{
                    // Syntax errors
                    $this->logs = "Date: ".date("m-d-Y H:i", time())." || Log_Type: PLUGIN_IS_DIE || Log_Content: The plugin have a critical error - plugin: ".$plg."<br>";
                    add_log("(plugin have syntax error/s) Plugin_is_die: (".$this->plugin_index_file.") in folder: ".$plg, "PLUGIN_NOT_WORK");
                }
            }
        }
    }
    public function multipleLoad($array, $force_all){
        $as="";
        foreach($array as $plugin){
            $as .= $this->load($plugin, $force_all);
        }
        return $as;
    }
}
$bs_class_plugins = new PLUGINS();
$gr_plugin_log="";
if($BS_SETTINGS_INTERNAL['PLUGINS']['IS_ENABLED'] == true){
    if(isset($bs_class_plugins->getPlugins()['0'])){
        foreach($bs_class_plugins->getPlugins() as $plugin){
            if(!file_exists(__DIR__."/".$bs_class_plugins->plugin_folder."/".$plugin."/.disabled")){
                if(file_exists(__DIR__."/".$bs_class_plugins->plugin_folder."/".$plugin."/".$bs_class_plugins->plugin_index_file)){
                    exec("php -l ".(__DIR__."/".$bs_class_plugins->plugin_folder."/".$plugin."/".$bs_class_plugins->plugin_index_file)."", $output_check_plugin, $return_check_plugin);
                    if ($return_check_plugin === 0) {
                        // Correct syntax
                        if((include(__DIR__."/".$bs_class_plugins->plugin_folder."/".$plugin."/".$bs_class_plugins->plugin_index_file)) == true){
                            $gr_plugin_log .= "Date: ".date("m-d-Y H:i", time())." || Log_Type: SUCCESS_INCLUDED_PLUGIN || Log_Content: Plugin: ".$plugin."<br>";
                        }else{
                            $gr_plugin_log .= "Date: ".date("m-d-Y H:i", time())." || Log_Type: PLUGIN_NOT_WORK || Log_Content: The plugin not work: ".$plugin."<br>";
                            add_log("Plugin_have_error: (".$bs_class_plugins->plugin_index_file.") in folder: ".$plugin, "PLUGIN_NOT_WORK");
                        }
                    } else {
                        // Syntax errors
                        $gr_plugin_log .= "Date: ".date("m-d-Y H:i", time())." || Log_Type: PLUGIN_IS_DIE || Log_Content: The plugin have a critical error - plugin: ".$plugin."<br>";
                        add_log("(plugin have syntax error/s) Plugin_is_die: (".$bs_class_plugins->plugin_index_file.") in folder: ".$plugin, "PLUGIN_NOT_WORK");
                    }
                }else{
                    $gr_plugin_log .= "Date: ".date("m-d-Y H:i", time())." || Log_Type: INESISTENT_PLUGIN_FILE || Log_Content: Not Found File: ".$plugin."<br>";
                    add_log("Inesistent_Plugin_index (".$bs_class_plugins->plugin_index_file.") in folder: ".$plugin, "PLUGIN_INDEX_FILE_NOT_FOUND");
                }
            }
        }
    }
}



/*
$class_html->blockAdBlock("ciao");
/*
$class_sql->set("dbh");
var_dump (
    $class_sql->select("test_tabella", array("id"=>16))
);
/*
$class_sql->set("dbh");
var_dump (
    $class_sql->update("test_tabella", array("nome"=>"KHGTY","password"=>"null"),array("id"=>18))
);
/*
$class_sql->set("dbh");
var_dump (
    $class_sql->insert("test_tabella", array("NULL", "ciaociao", "zoiziz"))
);
echo "<hr>";
var_dump (
    $class_sql->insert("test_tabella", array("id"=>"NULL","nome"=>"ciaociao"))
);

/* // crea tabella 
$class_sql->set("dbh");
var_dump (
    $class_sql->create_table("test_tabella",
        array(
            "id" => array("type"=>"int(11)", "not null", "auto_increment", "primary key"),
            "nome" => array("null"),
            "password" => array("null", "default"=>"non_impostata1"),
        )
    )
);
*/



echo "<br><br><hr><br>work!";