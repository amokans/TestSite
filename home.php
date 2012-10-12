<?php
ob_start();
session_start();
include_once ('salt.php');
include_once ('connect.php');
//echo $_SESSION['LAST_ACTIVITY']."<br>";
//echo time()."<br>";
//unset($_SESSION['LAST_ACTIVITY']);
if (!isset($_SESSION['LAST_ACTIVITY'])){
	echo "You are not logged in. Please <a href='login.php'>login</a>";
	break;
}
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
    // last request was more than 15 minutes ago
    $_SESSION = array();     // unset $_SESSION variable for the runtime 
    session_destroy();   // destroy session data in storage
    echo "Your Session has expired. Please Login again.<br>";
    echo "<a href='login.php'>Login</a>";
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
ob_flush();
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
          <a class="brand" href="#">Pearson AWS Portal</a>
          <ul class="nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          <?php
          include_once('connect.php');
          ob_start();
          session_start();
		  $user=$_SESSION['user'];
		  $sql="Select firstName, lastName FROM users WHERE email='$user'";
		  $result=mysql_query($sql,$link);
		  $name=mysql_fetch_array($result);
		  /*echo "<li class='menu'>  
                        <a class='menu' href='#'>Account<b class='caret'></b></a>  
                        <ul class='menu-dropdown'>  
                            <li><a href='#'>Settings</a></li>  
                            <li><a href='#'>Profile</a></li>  
                            <li class='divider'></li>  
                            <li><a href='logout.php'>Logout</a></li> 
                        </ul>  
             	</li>";*/
           ?>
		  <!--  <li class="pull-right menu">  -->
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
            <li><a href="#">Create Instances</a></li>
            <li><a href="#">View Instances</a></li>
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
          <h1>Pearson AWS Portal</h1>
          <p>In this portal you will be able to create new AWS instances for QA and Development purposes with the help of the AWS APIs. Your cost center will be billed according to the purchases.</p>
          <p><a class="btn btn-large" href="#" data-reveal-id="myModal" data-animation="fadeAndPop" data-dismissmodalclass="close-reveal-modal">Click for Video</a>
</p>
          	<div id="myModal" class="reveal-modal">
     		<h2>AWS Intro Video</h2>
          		<p><iframe width="420" height="315" src="http://www.youtube.com/embed/CaJCmoGIW24" frameborder="10" allowfullscreen></iframe></p>
     			<a class="close-reveal-modal">&#215;</a>
          	</div>
        </div>
        <!-- Tabs -->
                  <h3>Instances</h3>
          <ul data-bs-tabs-options="{}" class="tabs" data-behavior="BS.Tabs">
            <li class="active"><a href="#home">AWS Service Updates</a></li>
            <li><a>Profile</a></li>
            <li><a>Messages</a></li>
            <li><a>Settings</a></li>
          </ul>
          <div id="my-tab-content" class="tab-content">
            <div style="display: block; overflow: visible;" class="active" id="home">
              <p><script language="JavaScript" src="http://itde.vccs.edu/rss2js/feed2js.php?src=http%3A%2F%2Fstatus.aws.amazon.com%2Frss%2Fec2-us-east-1.rss&chan=n&num=5&desc=1&date=y&targ=y" type="text/javascript"></script>

<noscript>
<a href="http://itde.vccs.edu/rss2js/feed2js.php?src=http%3A%2F%2Fstatus.aws.amazon.com%2Frss%2Fec2-us-east-1.rss&chan=n&num=5&desc=1&date=y&targ=y&html=y">View RSS feed</a>
</noscript></p>
            </div>
            <div id="profile">
              <p>Food truck fixie locavore, accusamus mcsweeney's marfa
nulla single-origin coffee squid. Exercitation +1 labore velit, blog
sartorial PBR leggings next level wes anderson artisan four loko
farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim
 craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo
 nostrud organic, assumenda labore aesthetic magna delectus mollit.
Keytar helvetica VHS salvia yr, vero magna velit sapiente labore
stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable
jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art
party scenester stumptown, tumblr butcher vero sint qui sapiente
accusamus tattooed echo park.</p>
            </div>
            <div id="messages">
              <p>Banksy do proident, brooklyn photo booth delectus sunt
artisan sed organic exercitation eiusmod four loko. Quis tattooed iphone
 esse aliqua. Master cleanse vero fixie mcsweeney's. Ethical portland
aute, irony food truck pitchfork lomo eu anim. Aesthetic blog DIY,
ethical beard leggings tofu consequat whatever cardigan nostrud.
Helvetica you probably haven't heard of them carles, marfa veniam
occaecat lomo before they sold out in shoreditch scenester sustainable
thundercats. Consectetur tofu craft beer, mollit brunch fap echo park
pitchfork mustache dolor.</p>
            </div>
            <div id="settings">
              <p>Sunt qui biodiesel mollit officia, fanny pack put a
bird on it thundercats seitan squid ad wolf bicycle rights blog. Et aute
 readymade farm-to-table carles 8-bit, nesciunt nulla etsy adipisicing
organic ea. Master cleanse mollit high life, next level Austin nesciunt
american apparel twee mustache adipisicing reprehenderit hoodie portland
 irony. Aliqua tofu quinoa +1 commodo eiusmod. High life williamsburg
cupidatat twee homo leggings. Four loko vinyl DIY consectetur nisi,
marfa retro keffiyeh vegan. Fanny pack viral retro consectetur gentrify
fap.</p>
            </div>
          </div>
        <!-- Example row of columns
        <div class="tab-pane" data-behavior="BS.Tabs">
          <div class="tabs">
          	<div class="tab-content">
            <h2>Heading</h2>
            <p>Etiam porta sem malesuada magna mollis euismod. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
            <p><a class="btn" href="#">View details &raquo;</a></p>
          	</div>
          </div>
          <div class="tabs">
            <h2>Heading</h2>
             <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn" href="#">View details &raquo;</a></p>
         </div>
          <div class="span5">
            <h2>Heading</h2>
            <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
            <p><a class="btn" href="#">View details &raquo;</a></p>
          </div>
        </div>
        <hr>
        <!-- Example row of columns
        <div class="row">
          <div class="span6">
            <h2>Heading</h2>
            <p>Etiam porta sem malesuada magna mollis euismod. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
            <p><a class="btn" href="#">View details &raquo;</a></p>
          </div>
          <div class="span5">
            <h2>Heading</h2>
             <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn" href="#">View details &raquo;</a></p>
         </div>
          <div class="span5">
            <h2>Heading</h2>
            <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
            <p><a class="btn" href="#">View details &raquo;</a></p>
          </div>
        </div>  -->
        <footer>
          <p>&copy; Pearson 2012
        </footer>
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