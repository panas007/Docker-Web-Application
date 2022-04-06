<?php
	session_start();

    //Check if user is logged in 
    if(!isset($_SESSION['userid']))
	{
		header("location: index.php?error=nopermissions");
		die;
	}
    //Check if the role is the correct one for the current page
    if($_SESSION["role"] !== "User" )
    {
        header("location: wrongpage.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Concert page</title>
	<meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="welcome_css.css">
	<link rel="stylesheet" href="events.css" >
    <script src="event_functions.js"></script>
</head>
<body>

    <div class="topnav nav-container">
        <p class="hovermenu" style="text-align: center;"></p>
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

     <!--<nav>
       <img src="sidepic.jpg" alt="Concert picture" style="" width:auto;"";>-
    </nav>-->
    <div class="concertsdiv">
        <h1 id="con_t" class="form_title">My Concert Page</h1>
        <p class="descr">
            Here you can see all the availible concerts.<br />
            You can also search for concerts by: <br />
            <span style="font-weight:bold;">Artist name,Organazer username,Category of Music,Date and Title.</span>
        </p>


        <div class="con_table" id="show_table">
            <?php
            $queryString = "type=get_con";

            $ch = curl_init();
            $url = "http://restProxy:1030/showConReq.php";

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
            //$response = json_decode($response);

            ?>
        </div>
        <br />

        <div class="search">
            <form name="form_search_con" class="form_one" id="form_con" method="post">

                <input name="userSearch" type="text" class="searchTerm" id="cs" placeholder="Search Concerts.." />
                <button name="submit" onclick="ajaxSearch()" type="submit" class="searchButton"><i class="fa fa-search"></i></button>
                <button name="submit" onclick="resetTable()" type="submit" class="Rbutton">Reset Table</button>
            </form>
        </div>
        <input type="hidden" id="hdnSession" data-value="@Request.RequestContext.HttpContext.Session['someKey']" />

        

    </div>

	<script>
	n = 1;	
	$(document).on('click','.addButton',function(){
		var currentRow=$(this).closest("tr");
		var col1 = currentRow.find("td:eq(1)").html();
        var col2 = currentRow.find("td:eq(2)").html();
        var col3 = currentRow.find("td:eq(3)").html();
        var userid = "<?php echo $_SESSION['userid'] ?>";
        //alert(userid);

		addFavorites(col1,col2,col3, userid);
        		//alert(n);
		n++;
	});

    </script>

    <script>

        $( ".nav-container" ).hover(function() {
            $( "#navbar" ).toggle("slide");
        });
    </script>
</body>
</html>
