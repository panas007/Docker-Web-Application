<?php

function invalidUsername($username){
    $res;
    $pattern = "/^[a-zA-Z0-9]*$/";
    if ( !preg_match($pattern , $username) ) {
        $res=true;
    }
    else{
        $res=false;
    }

    return $res;
}

function invalidEmail($email){
    $res;
    if ( !filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $res=true;
    }
    else{
        $res=false;
    }

    return $res;
}


function existedUsername($conn , $username ,$email) {
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("location: index.php?error=prepareerror");
        exit();
    }

    mysqli_stmt_bind_param( $stmt,"ss",$username ,$email);
    mysqli_stmt_execute($stmt);

    $resData = mysqli_stmt_get_result($stmt);


    if( $row = mysqli_fetch_assoc( $resData)){
        mysqli_stmt_close($stmt);
        return $row;
    }
    else{
        mysqli_stmt_close($stmt);
        $res=false;
        return $res;
    }


}

function createUser($conn,$id,$name,$surname,$username,$pass, $email ,$role)
{
    $sql = "INSERT INTO users (id, name, surname, username, password, email, role) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("location: index.php?error=stmtfailed");
        exit();
    }

    $hashpww = password_hash($pass,PASSWORD_DEFAULT);
    mysqli_stmt_bind_param( $stmt,"sssssss",$id,$name,$surname,$username,$hashpww, $email ,$role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: index.php?error=none");
    exit();

}


function loginUser($conn, $username ,$pass){
    $idexist=existedUsername($conn , $username ,$username);

    if($idexist === false){
        $array = array("error" => "idexist");
        echo json_encode($array);
        exit();
    }

    $hashPass =  $idexist["password"];
    //$checkPass = password_verify($pass,$hashPass);
    //Change later
    /*if($checkPass === false){
        $array = array("error" => "wrongpassword");
        echo json_encode($array);
        exit();
    }*/
    //else if($checkPass === true){
    if($idexist["password"] === "1234"){
        if($idexist["confirmed"] === 1){
            session_start();
            $_SESSION["userid"]=$idexist["id"];
            $_SESSION["username"]=$idexist["username"];
            $_SESSION["role"]=$idexist["role"];
            $array = array("error" => "loginsuccess");
            echo json_encode($array);
            exit();
        }
        else{
            $array = array("error" => "wrongpassword");
            echo json_encode($array);
            exit();
        }
    }
}

function checkUser($conn){
    if(isset($_SESSION['userid']))
	{

		$id = $_SESSION['userid'];
		$query = "select * from users where id = '$id'";

		$result = mysqli_query($conn,$query);
		if($result && mysqli_num_rows($result) > 0)
		{

			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}

	//redirect to login
	header("Location: index.php");
	die;
}

function showTable($conn){
    $sql = "SELECT * FROM concerts";
    $result = mysqli_query($conn,$sql);
    $checkBox = "<input class =\"addButton\" value =\"Add\" type=\"button\"/>";
    if (mysqli_num_rows($result)> 0) {
        echo "<table id=\"contable\"><tr><th>Add to <br> Favorites</th><th>ID</th><th>Name</th><th>Date</th><th>Artist Name</th><th>Category</th></tr>";
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                            <td>".$checkBox."</td>
							<td >".$row["id"]."</td>
							<td>".$row["title"]."</td>
							<td>".$row["date"]."</td>
							<td>".$row["artistname"]."</td>
							<td>".$row["category"]."</td>
						</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
}

function showFavTable($conn){

    $idd=$_SESSION["userid"];

    $sql = "SELECT favorites.id,favorites.userid,favorites.concertid,title,date
            FROM concerts JOIN favorites on concerts.id=favorites.concertid
            where userid='$idd';";
    $result = mysqli_query($conn,$sql);
    $checkBox = "<input class =\"addButton\" value =\"Del\" type=\"button\"/>";
    if (mysqli_num_rows($result)> 0) {
        echo "<table id=\"contable\"><tr><th>Delete</th><th>ID</th><th>User ID</th><th>Concert ID</th><th>Name</th><th>Date</th>";
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                            <td>".$checkBox."</td>
							<td >".$row["id"]."</td>
							<td>".$row["userid"]."</td>
							<td>".$row["concertid"]."</td>
                            <td>".$row["title"]."</td>
                            <td>".$row["date"]."</td>
						</tr>";
        }
        echo "</table>";
    } else {
        echo "<table id=\"contable\"><tr><th>Delete</th><th>ID</th><th>User ID</th><th>Concert ID</th><th>Name</th><th>Date</th>";
    }

}

function showUsersTable($conn){
    $sql = "SELECT *
            FROM users ;";
    $result = mysqli_query($conn,$sql);
    $checkBox = "<input class =\"DelUser btn btn-primary\" value =\"Del\" type=\"button\"/>";
    $checkBox2 = "<input name=\"Addnuser\" class =\"EditUser btn btn-primary\" value =\"Edit\" type=\"submit\" data-toggle=\"modal\" data-target=\"#EditUserProp\"/>";

    if (mysqli_num_rows($result)> 0) {
        echo "<table id=\"contable\"><tr><th>Delete</th><th>Edit</th>
        <th>ID</th><th>Name</th><th>Surname</th><th>Username</th><th>Password</th><th>Email</th><th>Role</th><th>Confirmed</th>";
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $pass=substr($row["password"],0,10);
            $pass.="...";
            echo "<tr>
                            <td>".$checkBox."</td>
                            <td>".$checkBox2."</td>
							<td class=\"edid\">".$row["id"]."</td>
							<td class=\"edname\">".$row["name"]."</td>
							<td class=\"edsurname\">".$row["surname"]."</td>
                            <td class=\"edusername\">".$row["username"]."</td>
                            <td class=\"edpass\">".$pass."</td>
                            <td class=\"edemail\">".$row["email"]."</td>
                            <td class=\"edrole\">".$row["role"]."</td>
                            <td class=\"edconfirmed\">".$row["confirmed"]."</td>
						</tr>";
        }
        echo "</table>";
    } else {
        echo "<table id=\"contable\"><tr><th>Delete</th><th>ID</th><th>User ID</th><th>Concert ID</th><th>Name</th><th>Date</th>";
    }
}

function showConcertsTable($conn){
    $id = $_SESSION['userid'];
    $sql = "SELECT * FROM concerts where organizerid='$id'";
    $result = mysqli_query($conn,$sql);
    $checkBox = "<input class =\"DelCon btn btn-primary\" value =\"Del\" type=\"button\"/>";
    $checkBox2 = "<input name=\"EdiUCon\" class =\"EdiConcerts btn btn-primary\" value =\"Edit\" type=\"submit\" data-toggle=\"modal\" data-target=\"#EdiConcert\"/>";
    if (mysqli_num_rows($result)> 0) {
        echo "<table id=\"contable\"><tr><th>Delete</th><th>Edit</th><th>ID</th><th>Title</th><th>Date</th><th>Artist Name</th><th>Category</th></tr>";
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                            <td>".$checkBox."</td>
                            <td>".$checkBox2."</td>
							<td class=\"csid\">".$row["id"]."</td>
							<td class=\"cstitle\">".$row["title"]."</td>
							<td class=\"csdate\">".$row["date"]."</td>
							<td class=\"csartist\">".$row["artistname"]."</td>
							<td class=\"cscategory\">".$row["category"]."</td>
						</tr>";
        }
        echo "</table>";
    } else {
        echo "<table id=\"contable\"><tr><th>Delete</th><th>Edit</th><th>ID</th><th>Name</th><th>Date</th><th>Artist Name</th><th>Category</th></tr>";
    }
}

?>