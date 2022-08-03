<?php
	session_start();
	if(!isset($_SESSION['userid']))
	{
		header("location: index.php?error=nopermissions");
		die;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>welcome page</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="welcome_css.css" >
    <script src="event_functions.js"></script>
</head>
<body>
	<!--<div class="templ">
	<ul>
	  <li><a href="#home">Concerts</a></li>
	  <li><a href="#news">Favorites</a></li>
	  <li><a href="#contact">Organizer</a></li>
	  <li><a href="#about">Administration</a></li>
	</ul>
	</div>-->
    <div class="topnav nav-container" >
        <p class ="hovermenu" style="text-align: center;"></p>
        <nav id="navbar">
            <!-- Centered link -->
            <div>
                <a href="concerts.php">Concerts</a>
                <a href="favorites.php">Favorites</a>
                <a href="organizer.php">Organizer</a>
                <a href="welcome.php">MyFeed</a>
            </div>

            <!-- Right-aligned links -->
            <div class="topnav-right">
                <a href="logout.php">
                    <?php echo "Logout" ." " .$_SESSION["username"] ." (" .$_SESSION["role"] .")"; ?>
                </a>
            </div>
        </nav>
    </div>

	<section>
	<div class="main-text">
		<!--<img src="sidepic.jpg" alt="Concert picture" style="" width:auto;"";>-->
	</div>
	<div class="welcome_con">
			<h1 class="form_title">Welcome to Concert events</h1>
			<p class="descr">
				A webpage you can see all the concerts near you and and all over the country. <br>
				Choose your favorites and keep up for all the good events.<br><br>
			</p>

		<p class="descr">
			Notifications for your favorites Concerts :
		</p>

		<div class="table-wrapper-scroll-y my-custom-scrollbar">
		<?php
			$queryString = "type=show_feed";
			$queryString =$queryString."&userID=".$_SESSION['userid'];
			//echo "<p> New Not </p>";

			$ch = curl_init();
			$url = "http://restProxy:1030/showNotificationsReq.php";

			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_HTTPHEADER,array(
				"X-Auth-Token: Secret_key"
			));

			$response = curl_exec($ch);
			echo $response;
			curl_close($ch);


		?>
		</div>

	</div>
	</section>
	<script>

    $( ".nav-container" ).hover(function() {
    $( "#navbar" ).toggle("slide");
  });
	</script>

	<script>
    $(document).ready(function(){
		setInterval(function(){ 
		var ajaxRequest = new XMLHttpRequest();
		ajaxRequest.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var ajaxDisplay = document.getElementById("nottab");
				ajaxDisplay.innerHTML = this.responseText;
			}
		};
		var ip = "php_logic/events_helper.php";
    	ajaxRequest.open("POST", ip, true);
		ajaxRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		var queryString = "type=show_news_feed";
		//alert("hi");
		ajaxRequest.send(queryString);


    },7000);
});
	</script>

</body>
</html>
