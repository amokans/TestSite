<?php
ob_start();
session_start();
include_once ('salt.php');
include_once ('connect.php');
//echo $_SESSION['LAST_ACTIVITY']."<br>";
//echo time()."<br>";
//unset($_SESSION['LAST_ACTIVITY']);
if (!isset($_SESSION['LAST_ACTIVITY'])){
	echo "You are not logged in. Redirecting you to the login page.<br>Click&nbsp<a href='login.php'>here</a> if you are not automatically redirected.";
	header("refresh: 5;url=login.php");
	break;
}
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
    // last request was more than 15 minutes ago
    $_SESSION = array();     // unset $_SESSION variable for the runtime 
    session_destroy();   // destroy session data in storage
    echo "Your Session has expired. Please Login again.<br> Redirecting...<p></p>Click&nbsp<a href='login.php'>here</a> if you are not automatically redirected.";
	//sleep(5);//seconds to wait..
	header("refresh: 5;url=login.php");
	break;
}
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] < 900)){
		$auth = $_SESSION['salt'];
		$pwd = $_SESSION['pwd'];
		if(isset($_SESSION['user']) and isset($_SESSION['loginTime']) and isset($_SESSION['hash'])) {
			$user = $_SESSION['user'];
			$time = $_SESSION['loginTime'];
			$hash = $_SESSION['hash'];
			//echo $hash."<br>";
			//echo $auth."<br>";
			$sql = "SELECT firstName,lastName FROM users WHERE email='$user' AND password='$pwd'";
			$result = mysql_query($sql, $link);
			$name = mysql_fetch_array($result);
			//echo $sql."<br>";
			$hashCalculated = sha1($user.$time.$auth);
			//echo $hashCalculated."<br>";
				if ($hash != $hashCalculated) {
				//header('location:login_form.php');
				echo "check the log files, the user was not authenticated!";
				}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Pearson AWS Portal</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reveal.css">
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css" rel="stylesheet" />
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.min.js"></script>
    <script type="text/javascript" src="Source/UI/Bootstrap.js"></script>
	<script type="text/javascript" src="js/jquery.reveal.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/mootools_and_boostrap.js"></script>
	
    <style type="text/css">
      body {
        padding-top: 60px;
      }
    </style>

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
  </head>
  <body>
    <div class="topbar">
      <div class="topbar-inner" data-behavior="BS.Dropdown">
        <div class="container-fluid">
          <a class="brand" href="home.php">Pearson AWS Portal</a>
          <ul class="nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
   			<li class="menu pull-right">
   				<a class="pull-right menu" href='#'>Welcome, <?php echo $name[firstName]."&nbsp".$name[lastName];?><b class="caret"</b></a>
			<ul class="menu-dropdown pull-right">
				<li><a href='#'>Settings</a></li>  
                <li><a href='#'>Profile</a></li>  
                <li class='divider'></li>  
                <li><a href='logout.php'>Logout</a></li>
			</ul>
			</li>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="sidebar">
        <div class="well">
          <h5>Instances</h5>
          <ul>
            <li><a href="aws-sdk.php">Create Instances</a></li>
            <li><a href="view_instances.php"><b>View Instances</b></a></li>
          </ul>
          <h5>Billing</h5>
          <ul>
            <li><a href="#">View MTD Billing</a></li>
            <li><a href="#">View YTD Billing</a></li>
            <li><a href="#">Send Billing</a></li>
          </ul>
          <h5>Account</h5>
          <ul>
            <li><a href="#">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
      <div class="content">
        <!-- Main hero unit for a primary marketing message or call to action -->
        <div class="hero-unit">
          <h1>Instance List</h1>
          <p>This shows all the instances that you have created within the US-East Region</p>

          <!--  <p><a class="btn btn-large" href="#" data-reveal-id="myModal" data-animation="fadeAndPop" data-dismissmodalclass="close-reveal-modal">Click for Video</a>
</p>  -->
         </div> 	
          	<div class="condensed-table">	
				<?php
				require_once 'AWSSDKforPHP/sdk.class.php';
				$ec2 = new AmazonEC2();
				$ec2->set_hostname('ec2.us-east-1.amazonaws.com');
				$response = $ec2->describe_instances(); 
				/*%******************************************************************************************%*/
				
				$instances = array();	
				echo "<table align='center'>";
				echo "<r> <td><b>InstanceId</b></td><td><b>InstanceState</b></td><td><b>InstanceType</b></td><td><b>InstanceTime</b></td><td><b>AvailabilityZone</b></td>";
				foreach ($response->body->reservationSet->item as $item)
				{
				$instanceId = (string) $item->instancesSet->item->instanceId;
				$instanceState = (string) $item->instancesSet->item->instanceState->name;
				$instanceType = (string) $item->instancesSet->item->instanceType;
				$instanceTime = (string) $item->instancesSet->item->launchTime;
				$instanceLoc = (string) $item->instancesSet->item->placement->availabilityZone;
				
				echo '<tr> <td> ' . $instanceId . ' </td> <td>' . $instanceState . '</td> <td>' . $instanceType . '</td> <td>' . $instanceTime . ' </td> <td>' . $instanceLoc . '</td> </tr>';
				}
				?>
			</div>
      </div>
    </div>
      <script>
    var behavior = new Behavior().apply(document.body);
    var delegator = new Delegator({
      getBehavior: function(){ return behavior; }
    }).attach(document.body);
  </script>
  </body>
</html>
