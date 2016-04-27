<?php
/** UAP Install Step 1 **/
/** System Check **/

/** PHP Version Check 5.5 or Greater **/
if (version_compare(phpversion(), '5.5.0', '<')) {
	/** PHP Version too old **/
	$php_version = phpversion();
	$php_display = "<font color=red><b>Out of Date!</b></font>";
	$php_status = false;
	$step1_errors[] = true;
} else {
	/** PHP Version Good **/
	$php_version = phpversion();
	$php_display = "<font color=green><b>Good To Go!</b></font>";
	$php_status = true;
}

/** mySQL Server Check **/
/** Get mySQL version **/
function getMySQLVersion() {
	$output = shell_exec('mysql -V');
	preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
	return $version[0];
}
if (!function_exists('mysqli_connect')) {
	/** mySQL Bad **/
	$mysql_version = getMySQLVersion();
	$mysql_display = "<font color=red><b>No Server Found!</b></font>";
	$mysql_status = false;
	$step1_errors[] = true;
} else {
	/** mySQL Good **/
	$mysql_version = getMySQLVersion();
	$mysql_display = "<font color=green><b>Good To Go!</b></font>";
	$mysql_status = true;
}

/** URL Rewrite Module Check **/
$mrw_isEnabled = in_array('mod_rewrite', apache_get_modules());
if (!$mrw_isEnabled) {
	/** URL Rewrite Module Bad **/
	$mrw_version = "N/A";
	$mrw_display = "<font color=red><b>Not Enabled!</b></font>";
	$mrw_status = false;
	$step1_errors[] = true;
} else {
	/** URL Rewrite Module Good **/
	$mrw_version = "N/A";
	$mrw_display = "<font color=green><b>Good To Go!</b></font>";
	$mrw_status = true;
}

/** Fileinfo Extension Check **/
$fi_isEnabled = !extension_loaded('php_fileinfo');
if (!$fi_isEnabled) {
	/** Fileinfo Extension Bad **/
	$fi_version = "N/A";
	$fi_display = "<font color=red><b>Not Enabled!</b></font>";
	$fi_status = false;
	$step1_errors[] = true;
} else {
	/** Fileinfo Extension Good **/
	$fi_version = "N/A";
	$fi_display = "<font color=green><b>Good To Go!</b></font>";
	$fi_status = true;
}

/** Get Current Install Directory **/
$my_folder = dirname( realpath( __FILE__ ) ) . DIRECTORY_SEPARATOR;
$my_folder = preg_replace( '~[/\\\\][^/\\\\]*[/\\\\]$~' , DIRECTORY_SEPARATOR , $my_folder );
$my_folder = str_replace("app/", "", $my_folder);

/** Folder Writeable Check **/
function folder_writable($folder){
	$folder = ROOTDIR.''.$folder.'';
	if (is_writable($folder)) {
		return true;
	} else {
		return false;
	}
}

/** Config File Writeable Check **/
if(folder_writable('app/Config.example.php')){
	$write_config = "<font color=green><strong>Writeable!</strong></font>";
}else{
	$write_config = "<font color=red><strong>Not Writeable!</strong></font>";
	$step1_errors[] = true;
}

/** app dir Writeable Check **/
if(folder_writable('app')){
	$write_app = "<font color=green><strong>Writeable!</strong></font>";
}else{
	$write_app = "<font color=red><strong>Not Writeable!</strong></font>";
	$step1_errors[] = true;
}

/** Error log File Writeable Check **/
if(folder_writable('app/Logs/error.log')){
	$write_errorlog = "<font color=green><strong>Writeable!</strong></font>";
}else{
	$write_errorlog = "<font color=red><strong>Not Writeable!</strong></font>";
	$step1_errors[] = true;
}

/** Images Writeable Check **/
if(folder_writable('assets/images/')){
	$write_images = "<font color=green><strong>Writeable!</strong></font>";
}else{
	$write_images = "<font color=red><strong>Not Writeable!</strong></font>";
	$step1_errors[] = true;
}

/** profile-pics Writeable Check **/
if(folder_writable('assets/images/profile-pics/')){
	$write_profile_images = "<font color=green><strong>Writeable!</strong></font>";
}else{
	$write_profile_images = "<font color=red><strong>Not Writeable!</strong></font>";
	$step1_errors[] = true;
}

?>

<div class='panel panel-info'>
	<div class='panel-heading'>
		<h3>UAP 3 Installation</h3>
	</div>
	<div class='panel-body'>
		Welcome to the UAP 3 Installation Process.  <br>
		First we are going to check and make sure you have everything needed to install. <br>
		<br>

		<div class="panel panel-default">
		  <!-- Default panel contents -->
		  <div class="panel-heading">UserApplePie requires the following to be installed and working before installation can continue.</div>
		  <!-- Table -->
		  <table class="table">
		    <th> Requirement </th><th> Version </th><th> Status </th>
				<tr><td> PHP 5.5 or Greater </td><td> <?=$php_version?> </td><td> <?=$php_display?> </td></tr>
				<tr><td> mySQL Database Server </td><td> <?=$mysql_version?> </td><td> <?=$mysql_display?> </td></tr>
				<tr><td> URL Rewrite Module </td><td> <?=$mrw_version?> </td><td> <?=$mrw_display?> </td></tr>
				<tr><td> PHP Fileinfo Enabled </td><td> <?=$fi_version?> </td><td> <?=$fi_display?> </td></tr>
		  </table>
		</div>

		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">UserApplePie requires the following files/folders to have write permission by server.</div>
			<!-- Table -->
			<table class="table">
				<th> Folder </th><th> Status </th>
				<tr><td> ../app/ </td><td> <?=$write_app?> </td></tr>
				<tr><td> ../app/Config.example.php </td><td> <?=$write_config?> </td></tr>
				<tr><td> ../app/Logs/error.log </td><td> <?=$write_errorlog?> </td></tr>
				<tr><td> ../assets/images/ </td><td> <?=$write_images?> </td></tr>
				<tr><td> ../assets/images/profile-images/ </td><td> <?=$write_profile_images?> </td></tr>
			</table>
		</div>

		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">Other Server Data</div>
			<!-- Table -->
			<table class="table">
				<th> Information </th><th> More Information </th>
				<tr><td> Install Location </td><td> <?=$my_folder?> </td></tr>
			</table>
		</div>

		<?php
		
			/** Check if all requirements are good **/
			$error_count = count($step1_errors);
			
		
			if($error_count == "0"){
				echo "<div class='alert alert-info'>Everything above looks good. Lets go to the next step. </div>";
				echo "<a href='/?install_step=2' class='btn btn-primary btn-lg'>Move on to Step 2</a>";
			}else{
				echo "<div class='alert alert-danger'>There are <font color=red><b>$error_count errors</b></font> above. ";
				echo "<a href='/' class='btn btn-warning btn-sm pull-right'>Refresh Page</a><hr>";
				echo "Once errors are fixed, refresh this page to retest settings.<br>";
				echo "Make sure there is no <font color=red><b>RED</b></font> text before you can move on to Step 2!";
				
				echo "</div>";
			}
		?>

	</div>
</div>
