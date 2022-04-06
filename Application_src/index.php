<?php
session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>sign up and login form</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="Login_css.css" >
</head>
<body>
	<div class="container">
		<form name="myform" action="login_user_req.php" class="form" id="login" method="post">
			<h1 class="form_title">Login</h1>
			<p>Insert Email and Password to Login or click to the Register button to create a new account</p>
			<div class="form_input-group">
                <input name="email1" type="text" class="form_input" autofocus placeholder="Email" required />
			</div>
			<div class="form_input-group">
                <input name="passwo" type="password" class="form_input" placeholder="Password" required />
			</div>
            <button class="form_button" id="login" name="submit" type="submit">Login</button>

            <button onclick="hidef(event)" class="form_button" id="reg" name="reg" type="submit">Register</button>
		</form>
		<form name="myformr" action="register_helper_req.php" class="form form_hidden" id="register" method="post">
			<h1 class="form_title">Create new account</h1>
			<div class="form_input-group">
				<input name="username"  type="text" class="form_input" placeholder="Username" required/>
			</div>
			<div class="form_input-group">
				<input name="passwo" type="password" class="form_input"  placeholder="Password" required/>
			</div>
			<div class="form_input-group">
				<input name="email" type="text" class="form_input" placeholder="Email" required/>
			</div>
			<div class="form_input-group">
				<input name="role" type="text"  list="browsers" class="form_input" placeholder="Role" required/>
				<datalist id="browsers">
					<option value="EVENTORGANIZERS">
					<option value="USER">
				</datalist>
			</div>

			<button class="form_button" name="submit" type="submit" >Submit</button>
		</form>

        <?php
			if(isset($_GET["error"])){
                if(($_GET["error"]=="invalidusername")){
					echo "<p> Invalid Email or Password, please try again </p>";
                }
				else if(($_GET["error"]=="invalidemail")){
					echo "<p> Invalid Email, email must be \"something@something.com\" </p>";
                }
				else if(($_GET["error"]=="takenidoremail")){
					echo "<p> The email is taken, please login with another user or try again </p>";
                }

				else if(($_GET["error"]=="dontexist")){
					echo "<p> The username dont exist </p>";
                }
				else if(($_GET["error"]=="wrongpassword")){
					echo "<p> Wrong Password</p>";
                }
				else if(($_GET["error"]=="notconfirmed")){
					echo "<p>Wait for Confirmation</p>";
                }
				else if(($_GET["error"]=="none")){
					echo "<p></p>";
                }

            }
        ?>

	</div>
	<script src="login_js.js"></script>
</body>
</html>

