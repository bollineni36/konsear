<?php 
	require_once("includes/start.php");
	require_once("includes/config.php");
	require_once("includes/tablenames.php");
	require_once("includes/constants.php");
	require_once("includes/classes/VDatabase.php");
	require_once("includes/vutils.php");
	require_once("includes/vlib.php");
	require_once("includes/validations.php");
	try{
		
		$db = new VDatabase(true);
		$message = "";
		if(isset($_POST['submit']))
		{
			$email 		= ($_POST['email']);
			$password 	= ($_POST['password']);
			
			if(isEmpty($email)) 
			{

				$message = "Please Enter Email Id";
							
			}
			elseif(isEmpty($password)) 
			{
			
				$message = "Please Enter Password";
			
			}

			if($message == ""){
							
				
				$query = sprintf("SELECT PublisherId, PublisherName, PublisherDbaName, PublisherPassword, PublisherEmailId, PublisherActiveFlag, PublisherPlanId FROM %s%s WHERE PublisherEmailId = '%s' AND PublisherActiveFlag='Active'", DB_PREFIX, PUBLISHER_TABLE, $email);
				
				$row = $db->getRow($query);
										
				if(isset($row) && (decrypt($row['PublisherPassword']) == $password)) {
				
						$_SESSION['pubid'] = $row['PublisherId'];
						
						$_SESSION['pname'] = $row['PublisherName'];
						
						$_SESSION['bname'] = $row['PublisherDbaName'];
						
						$_SESSION['emailid'] = $row['PublisherEmailId'];
						
						$_SESSION['status'] = $row['PublisherActiveFlag'];
						
						$_SESSION['planid'] = $row['PublisherPlanId'];
						
						unset($_SESSION['subid']);
						
						redirectPage('manage_offers.php');
						
				}
				else
				{
					
					$message = "Invalid EmailId/Password. ";

				} 
				
			} 	
		}
	}
	catch(Exception  $e)
	{
		print_r($e);
	}
		$db->closeConnection();
		
	
	
?>
<!doctype html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" data-useragent="Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Konsear Local Offers</title>
    
    <meta name="description" content="Your City. Your Deals and Events. Welcome to Konsear Local Offers! Konsear Local Offers brings you the best local deals and events in your community." /> 
	<meta name="keywords" content="local offers, coupons, daily deals, groupon, living social, deals, local events, deal of the day, half off deals, half off specials, online coupons, happy hour, wine tasting" /> 
	
	
	<?php include('includes.php'); ?>
	<link rel="stylesheet" href="css/style1.css" type="text/css" media="all">
	<style>
		#change-image { font-size: 0.8em; }
	</style>

  </head>
  <body>
	
  
	<?php include('header.php'); ?>
	<?php include('menu1.php'); ?>
	
	<div class="row">
		<div class="hideinmobile"><?php include('leftbar.php'); ?></div>
	    <div class="medium-6 columns">
			 <form name="login" id="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="size">
				<div class="medium-12 columns bottomspace">
					
					<?php if($message != "") echo "<div class='alert-box success radius'>".$message."</div>"; ?>
					<h3 class="lightgrey">Publisher Login Form</h3>
					<br/>
					<div class="medium-5 columns">
						<label class="inline">Email</label>
					</div>
					<div class="medium-7 columns">
						<input type="text" name="email" id="email" value="<?php if(isset($email)) echo $email; ?>">
					</div>
					<div class="medium-5 columns">
						<label class="inline">Password</label>
					</div>
					<div class="medium-7 columns">
						<input type="password" name="password" id="password" value="">
					</div>
					<div class="medium-5 columns">&nbsp;</div>
					<div class="medium-7 columns">
						<div align="center">
							<input type="submit" name="submit" value="Login" class="tiny button radius">
						</div>
					</div>
					<div class="medium-5 columns">&nbsp;</div>
					<div class="medium-7 columne">
						<p class="textcenter"><a href="forgot_password.php">Forgot Password</a></p>
						<p class="textcenter vhighnormal"><a href="plans_pricing.php">Sign up</a></p>
					</div>
				</div>
			</form>
		</div> 
		<div class="showinmobile"><?php include('leftbar.php'); ?></div>
		<?php include('rightbar.php'); ?>
	</div>
 

  <!-- Footer -->
	<?php include('footer1.php'); ?>

    <script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/foundation.js"></script>
    <script>
      $(document).foundation();

      var doc = document.documentElement;
      doc.setAttribute('data-useragent', navigator.userAgent);
    </script>
  </body>
</html>