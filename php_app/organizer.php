<?php
session_start();

if(!isset($_SESSION['userid']))
{
    header("location: index.php?error=nopermissions");
    die;
}

if($_SESSION["role"] !== "Organizer" )
{
    header("location: wrongpage.php");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Concert page</title>
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


        <!-- Centered link -->
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
    </nav>-->
    <div class="concertsdiv">
        <h1 id="con_t" class="form_title">My Organizer Page</h1>
        <p class="descr">
            Here you can add new Concerts.<br />
            You can also Update or Delete your availible Concerts <br />
        </p>


        <div class="con_table" id="show_table">
            <?php
			$queryString = "type=show_con_org";
            $queryString =$queryString. "&id=". $_SESSION['userid'];;

            $ch = curl_init();
            $url = "http://restProxy:1030/showOrgReq.php";

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
        <br />

        <button name="Addnuser" type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddNewConcert">Add Concert</button>



    </div>
    <div class="modal fade" id="AddNewConcert" tabindex="-1" role="dialog" aria-labelledby="AddNewConcertLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AddNewConcert">Add New Concert</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input name="tit" id="ctitle" type="text" class="form-control" placeholder="Title" required />
                        </div>
                        <div class="form-group">
                            <input name="dat" id="cdate" type="date" class="form-control" placeholder="Date" required />
                        </div>
                        <div class="form-group">
                            <input name="art" id="cartist" type="text" class="form-control" placeholder="Artist" required />
                        </div>
                        <div class="form-group">
                            <input name="cat" id="ccategory" type="text" class="form-control" placeholder="Category" required />
                        </div>
                        <div class="form-group">
                            <input name="sta" id="cstart" type="date" class="form-control" placeholder="Start Date" required />
                        </div>
                        <div class="form-group">
                            <input name="end" id="cend" type="date" class="form-control" placeholder="End Date" required />
                        </div>
                        <div class="form-group">
                            <input name="tic" id="ctickets" type="text" class="form-control" placeholder="Tickets" required />
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

    <div class="modal fade" id="EdiConcert" tabindex="-1" role="dialog" aria-labelledby="EdiConcertLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EdiConcert">Edit Concert</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input name="tit" id="cctitle" type="text" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="dat" id="ccdate" type="date" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="art" id="ccartist" type="text" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="cat" id="cccategory" type="text" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="sta" id="ccstart" type="date" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="end" id="ccend" type="date" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <input name="tic" id="cctickets" type="text" class="form-control"/>
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
            //event.preventDefault();
            //var id = document.getElementById("cid").value;
            var title = document.getElementById("ctitle").value;
            var date = document.getElementById("cdate").value;
            var artistname = document.getElementById("cartist").value;
            var category = document.getElementById("ccategory").value;
            var startdate = document.getElementById("cstart").value;
            var enddate = document.getElementById("cend").value;
            var tickets = document.getElementById("ctickets").value;
            var userid = "<?php echo $_SESSION['userid']; ?>";

            addnewConcert(id,title,date,artistname,category,startdate,enddate,tickets,userid);


        });
        $(document).on('click','.DelCon',function(){
		    var currentRow=$(this).closest("tr");
		    var col1 = currentRow.find("td:eq(2)").html();
            var userid = "<?php echo $_SESSION['userid']; ?>";

            delConcert(col1,userid);

        });
        var id;
        var tmptitle;
        var tmpdate;
        var tmpartistr;
        var tmpcategory;
        var tmpstartdate;
        var tmpenddate;
        var tmptickets;



        $(document).on('click', '.EdiConcerts', function () {
            //event.preventDefault();
            //alert($(this).parents("tr").find(".edid").text());

            id = $(this).parents("tr").find(".csid").text();
            tmptitle = $(this).parents("tr").find(".cstitle").text();
            tmpdate = $(this).parents("tr").find(".csdate").text();
            tmpartistr = $(this).parents("tr").find(".csartist").text();
            tmpcategory = $(this).parents("tr").find(".cscategory").text();
            tmpstartdate = $(this).parents("tr").find(".csstart").text();
            tmpenddate = $(this).parents("tr").find(".csend").text();
            tmptickets = $(this).parents("tr").find(".cstickets").text();


            //document.getElementById("ccid").value = id;
            document.getElementById("cctitle").value = tmptitle;
            document.getElementById("ccdate").value = tmpdate;
            document.getElementById("ccartist").value = tmpartistr;
            document.getElementById("cccategory").value = tmpcategory;
            document.getElementById("ccstart").value = tmpstartdate;
            document.getElementById("ccend").value = tmpenddate;
            document.getElementById("cctickets").value = tmptickets;




        });
        $(document).on('click', '.subnew2', function () {
            //var newid = document.getElementById("ccid").value;
            var title = document.getElementById("cctitle").value;
            var date = document.getElementById("ccdate").value;
            var artist = document.getElementById("ccartist").value;
            var category = document.getElementById("cccategory").value;
            var startdate = document.getElementById("ccstart").value;
            var enddate = document.getElementById("ccend").value;
            var tickets = document.getElementById("cctickets").value;
            var userid = "<?php echo $_SESSION['userid']; ?>";

            if (title == "") {
                title = tmptitle;

            }
            if (date == "") {
                date = tmpdate;

            }
            if (artist == "") {
                artist = tmpartist;

            }
            if (category == "") {
                category = tmpcategory;

            }
            if (startdate == "") {
                startdate = tmpstartdate;

            }
            if (enddate == "") {
                enddate = tmpenddate;

            }
            if (tickets == "") {
                tickets = tmptickets;

            }

            editCon(id,title,date,artist,category,startdate,enddate,tickets,userid);

	    });

    </script>

    <script>

        $( ".nav-container" ).hover(function() {
            $( "#navbar" ).toggle("slide");
        });
    </script>

</body>
</html>