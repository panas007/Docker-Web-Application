<?php
session_start();

require_once 'db_handler.php';
require_once 'index_functions.php';

checkUser($conn);


if($_SESSION["role"] !== "ADMIN" )
{
    header("location: wrongpage.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="welcome_css.css" />
    <link rel="stylesheet" href="events.css" />
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
                <a href="administration.php">Administration</a>
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
        <!--<img src="sidepic.jpg" alt="Concert picture" style="" width:auto;"";>-
    </nav>-->
    <div class="concertsdiv">
        <h1 id="con_t" class="form_title">My Administrator Page</h1>
        <p class="descr">
            Here you can add new User.<br />
            You can also Update or Delete the availible Users and also confirm the registration process <br />
        </p>


        <div class="con_table" id="show_table">
            <?php
			require_once 'db_handler.php';
			require_once 'index_functions.php';

            showUsersTable($conn);

            ?>
        </div>
        <br />

         <button name="Addnuser"  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddNewUserid">Add User</button>



    </div>
    <div class="modal fade" id="AddNewUserid" tabindex="-1" role="dialog" aria-labelledby="AddNewUseridLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AddNewUserid">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input name="c_id" id="uid" type="text" class="form-control" autofocus placeholder="ID" required/>
                        </div>
                        <div class="form-group">
                            <input name="name" id="uname" type="text" class="form-control" placeholder="Name" required />
                        </div>
                        <div class="form-group">
                            <input name="surname" id="usurname" type="text" class="form-control" placeholder="Surname" required />
                        </div>
                        <div class="form-group">
                            <input name="username" id="uusername" type="text" class="form-control" placeholder="Username" required />
                        </div>
                        <div class="form-group">
                            <input name="passwo" id="upassword" type="password" class="form-control" placeholder="Password" required />
                        </div>
                        <div class="form-group">
                            <input name="email" id="uemail" type="text" class="form-control" placeholder="Email" required />
                        </div>
                        <div class="form-group">
                            <input name="role" type="text" id="urole" list="browsers" class="form-control" placeholder="Role" required />
                            <datalist id="browsers">
                                <option value="ADMIN" />
                                <option value="EVENTORGANIZERS" />
                                <option value="USER" />
                            </datalist>
                        </div>
                        <div class="form-group">
                            <input name="validate" id="uvalidate" type="text" class="form-control" placeholder="Confirm(1 or 0)" required />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary subnew" data-dismiss="modal">Submit</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="EditUserProp" tabindex="-1" role="dialog" aria-labelledby="EditUserProp" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditUserProp">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input name="c_id" id="eid" type="text" class="form-control" autofocus  />
                        </div>
                        <div class="form-group">
                            <input name="name" id="ename" type="text" class="form-control"   />
                        </div>
                        <div class="form-group">
                            <input name="surname" id="esurname" type="text" class="form-control"   />
                        </div>
                        <div class="form-group">
                            <input name="username" id="eusername" type="text" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="email" id="eemail" type="text" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="role" type="text" id="erole" list="browsers" class="form-control"  />
                            <datalist id="browsers">
                                <option value="ADMIN" />
                                <option value="EVENTORGANIZERS" />
                                <option value="USER" />
                            </datalist>
                        </div>
                        <div class="form-group">
                            <input name="validate" id="evalidate" type="text" class="form-control" placeholder="Confirm(1 or 0)" required />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary subnew2" data-dismiss="modal">Submit</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).on('click','.subnew',function(){

            var id = document.getElementById("uid").value;
            var name = document.getElementById("uname").value;
            var surname = document.getElementById("usurname").value;
            var username = document.getElementById("uusername").value;
            var pass = document.getElementById("upassword").value;
            var email = document.getElementById("uemail").value;
            var role = document.getElementById("urole").value;
            var val = document.getElementById("uvalidate").value;

            addNewUser(id,name,surname,username,pass,email,role,val);


        });
        $(document).on('click','.DelUser',function(){
		    var currentRow=$(this).closest("tr");
		    var col1 = currentRow.find("td:eq(2)").html();

            delUser(col1);

        });
        var id;
        var tmpn;
        var tmpsurn;
        var tmpuser;
        var tmpemail;
        var tmprole;
        var tmpval;



        $(document).on('click', '.EditUser', function () {
            //event.preventDefault();
            //alert($(this).parents("tr").find(".edid").text());

            id = $(this).parents("tr").find(".edid").text();
            tmpn = $(this).parents("tr").find(".edname").text();
            tmpsurn = $(this).parents("tr").find(".edsurname").text();
            tmpuser = $(this).parents("tr").find(".edusername").text();
            tmpemail = $(this).parents("tr").find(".edemail").text();
            tmprole = $(this).parents("tr").find(".edrole").text();
            tmpval = $(this).parents("tr").find(".edconfirmed").text();

            document.getElementById("eid").value = id;
            document.getElementById("ename").value = tmpn;
            document.getElementById("esurname").value = tmpsurn;
            document.getElementById("eusername").value = tmpuser;
            document.getElementById("eemail").value = tmpemail;
            document.getElementById("erole").value = tmprole;
            document.getElementById("evalidate").value = tmpval;

		    

        });
        $(document).on('click', '.subnew2', function () {
            //var id = $(this).parents("tr").find(".edid").text();
            var newid = document.getElementById("eid").value;
            var name = document.getElementById("ename").value;
            var surname = document.getElementById("esurname").value;
            var username = document.getElementById("eusername").value;
            var email = document.getElementById("eemail").value;
            var role = document.getElementById("erole").value;
            var val = document.getElementById("evalidate").value;

            if (newid == "") {
                newid = id;

            }
            if (name == "") {
                name = tmpn;
            
            }
            if (surname == "") {
                surname = tmpsurn;

            }
            if (username == "") {
                username = tmpuser;

            }
            if (email == "") {
                email = tmpemail;

            }
            if (role == "") {
                role = tmprole;

            }
            if (val === "") {
                val = tmpval;

            }
            //alert(email);

            editUser(id,newid,name,surname,username,email,role,val);

	    });
        /*$('#button').submit(function(e) {
            e.preventDefault();
            // Coding
            $('#IDModal').modal('toggle'); //or  $('#IDModal').modal('hide');
            return false;
        });*/
    </script>

    <script>

    $( ".nav-container" ).hover(function() {
    $( "#navbar" ).toggle("slide");
  });
    </script>

</body>
</html>