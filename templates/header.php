<?php 
	//session_start();

	//$uname = $_SESSION["username"] ?? 'Guest'; //Null  coalescing
	//$redirect_msg = $_SESSION["message"] ?? 'Good day, '. $_SESSION["username"] . ', you have been successfully signed out.';

	if (isset($_SESSION["username"])) {
		$uname = $_SESSION["firstname"] . ' ' . $_SESSION["lastname"];
	} else {
		$uname = 'Guest';
	}

 ?>

<head>
	<?php 
		echo '<title>Citizens, Not Spectators - ' . $page_title . '</title>'
	 ?>
	
   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    

  	<link rel = "stylesheet"  
         href = "https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">  
      <script type = "text/javascript"  
         src = "https://code.jquery.com/jquery-2.1.1.min.js"></script>             
      <script src = "https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"> 

    <script type="text/javascript">

	    $('.datepicker').pickadate({
	        selectMonths: true,
	        selectYears: 15
	    });
    </script>


    <style type="text/css">
    	.brand {
    		background: #cbb09c !important;
    	}

    	.brand-text {
    		color: #cbb09c !important;
    	}

    	form {
    		max-width: 600px;
    		margin: 20px auto;
    		padding: 20px;
    	}

    	.sub-image {
    		width: 100px;
    		margin: 40px auto -30px;
    		display: block;
    		position: relative;
    		top: -30px
    	}

    	footer {
    		background-color: black;
    		margin-top: 50px;
    	}

		.separator { border-left: 1px solid grey; padding-left: 5px; padding-right: 5px}

    	.separator:first-child { border-left: 0px solid grey; padding-left: 5px; padding-right: 5px}

    	.error {font-size: 0.85em;}

    	.dropdown-content{
		   width: max-content !important;
		   height:auto !important;
		}

		.sign-in-up-nav {
			margin-top: 10px;
		}

    </style>
</head>
<body class="grey lighten-2">
	<div class="navbar-fixed">
		<nav class="yellow z-depth-0">
			<div class="container-fluid">
				<a href="index.php" class="brand-logo brand-text">Be Citizens, Not Spectators</a>
			  	<ul class="right hide-on-med-and-down">
					<li><em>(User : <?php echo htmlspecialchars($uname); ?>)</em></li>
				</ul>

			</div>
		</nav>
	</div>

	<?php 

	if (isset($_SESSION["username"])) { 
		// user is logged in 
		?>
		<div class="navbar-fixed">
			<nav class="nav">
			  	<a class="nav-link active" href="index.php">Home</a>
			  	<a class="nav-link" href="contact.php">Contact Us!</a>
			  	<a class="nav-link" href="about.php">About</a>
			  	<a class="nav-link" href="sign-out.php" tabindex="-1" aria-disabled="true">Sign Out</a>

			  	<?php 

				if ($_SESSION["active_status"] == 1) { 
					// user is logged in 
					?>
					
				  	<ul id="nav-mobile" class="right hide-on-small-and-down">
						<li><a href="submit-issue.php" class="nav-link">Post an issue</a></li>
						
						<a class="btn brand dropdown-button z-depth-0" href="#" data-activates="dropdown">Profile<i class="mdi-navigation-arrow-drop-down right"></i></a>
						<ul id = "dropdown" class = "dropdown-content">  
						 	<li><a href = "view-profile.php">View</a></li>
						 	<li class = "divider"></li> 
						 	<li><a href = "update-profile.php">Update</a></li>
						 	<li class = "divider"></li> 
						 	<li><a href = "view-my-posts.php">My Posts</a></li>
						</ul>

					</ul>
		
				<?php 
				} else {
						// not activated
					?>
					<ul id="nav-mobile" class="right hide-on-small-and-down">
						<li><a href="update-profile.php" class="btn brand z-depth-0">Update profile</a></li>
					</ul>

				<?php
				}  ?>

			</nav>
		</div>
		<?php 
	} else {
			// not logged in or guest
		?>
		
		<div class="nav-wrapper navbar-fixed">
			<nav class="nav">
			  	<a class="nav-link active" href="index.php">Home</a>
			  	<a class="nav-link" href="contact.php">Contact Us!</a>
			  	<a class="nav-link" href="about.php">About</a>
			  	<a class="nav-link" href="sign-in.php" tabindex="-1" aria-disabled="true">Sign In</a>			 	
			</nav>
		</div>
		

		<?php
		}

	 ?>
	
	