<?php
//session_start();
require_once 'mongo_con.php';

if(($_POST['type'] === "get_con" ) || ($_POST['type'] === "rst_tbl" )){

     //Show table and RESET table if a search happened

    $filter = [];
    $options = ['sort' => ['id' => 1]];
    $query = new \MongoDB\Driver\Query($filter, $options);
    
    $concertlist = $mongodb->executeQuery("condata_db.concerts", $query);
    
    foreach($concertlist as $con) {

        $conarr[] = array( 
        "id" => $con->id,
        "title" =>$con->title,
        "date" => $con->date, 
        "artistname" => $con->artistname,
        "category" => $con->category
        );
    }
    $bb = json_encode($conarr);
    $display_string = "<table>";
    $display_string .= "<tr>";
    $display_string .= "<th>Add to <br> Favorites</th>";
    $display_string .= "<th>ID</th>";
    $display_string .= "<th>Name</th>";
    $display_string .= "<th>Date</th>";
    $display_string .= "<th>Artist Name</th>";
    $display_string .= "<th>Category</th>";
    $display_string .= "</tr>";
    $checkBox = "<input class =\"addButton\"value =\"Add\" type=\"button\"/>";
    // Insert a new row in the table for each person returned
    if($conarr != null){
    foreach($conarr as $row) {
            $display_string .= "<tr>";
            $display_string .= "<td>".$checkBox."</td>";
            $display_string .= "<td>".$row["id"]."</td>";
            $display_string .= "<td>".$row["title"]."</td>";
            $display_string .= "<td>".$row["date"]."</td>";
            $display_string .= "<td>".$row["artistname"]."</td>";
            $display_string .= "<td>".$row["category"]."</td>";
            $display_string .= "</tr>";
        }
    }

    $display_string .= "</table>";
    echo $display_string;
    exit();
}
if($_POST['type'] === "search_con"){

    //Search Concert by attributes 

    $filter  = [
            '$or' =>[
        ['id' => intval($_POST['term'])],
        ['title' => $_POST['term']],
        ['date' => $_POST['term']],
        ['artistname' => $_POST['term']],
        ['category' => $_POST['term']]
        ]
    ];
    $query = new \MongoDB\Driver\Query($filter,[]);
    
    $concertlist = $mongodb->executeQuery("condata_db.concerts", $query);
    
    foreach($concertlist as $con) {
        $conarr[] = array( 
        "id" => $con->id,
        "title" =>$con->title,
        "date" => $con->date, 
        "artistname" => $con->artistname,
        "category" => $con->category
        );
    }

    
    $display_string = "<table>";
    $display_string .= "<tr>";
    $display_string .= "<th>Add to <br> Favorites</th>";
    $display_string .= "<th>ID</th>";
    $display_string .= "<th>Name</th>";
    $display_string .= "<th>Date</th>";
    $display_string .= "<th>Artist Name</th>";
    $display_string .= "<th>Category</th>";
    $display_string .= "</tr>";
    $checkBox = "<input class =\"addButton\"value =\"Add\" type=\"button\"/>";
    // Insert a new row in the table for each person returned
    if($conarr != null){
        foreach($conarr as $row) {
            $display_string .= "<tr>";
            $display_string .= "<td>".$checkBox."</td>";
            $display_string .= "<td>".$row["id"]."</td>";
            $display_string .= "<td>".$row["title"]."</td>";
            $display_string .= "<td>".$row["date"]."</td>";
            $display_string .= "<td>".$row["artistname"]."</td>";
            $display_string .= "<td>".$row["category"]."</td>";
            $display_string .= "</tr>";
        }
    }

    $display_string .= "</table>";
    echo $display_string;
    exit();
}
if($_POST['type'] === "Add_to_fav"){

    //Adding Concert to favorites

    $filter = [];
    $options = [
        'sort' => ['id' => -1],
        'limit' => 1
    ];
    //get next id
    $query = new \MongoDB\Driver\Query($filter,$options);
    $exist = $mongodb->executeQuery("condata_db.favorites", $query);
    $exist = $exist->toArray();
    if ($exist === null) {
        $idfav = 1;
    }
    else{
        $row = $exist[0]->id;
        $temp = $row+1;
        $idfav = $temp;
    }
    
    //Check if the concert is already in favorites
    $concertid = intval($_POST['conid']);
    $userid = $_POST['userid'];

    $filter = [
                'userid' => $userid ,
                'concertid' =>  $concertid
    ];

    $query = new \MongoDB\Driver\Query($filter,[]);
    $exist = $mongodb->executeQuery("condata_db.favorites", $query);
    $exist = $exist->toArray();

    //Insert the concert to favorites if it is not already exist
    if($exist[0]->title === null ){
            $filter = [
                'id' => $idfav,
                'userid' => $userid,
                'concertid' => $concertid,
                'title' => $_POST['title'],
                'date' => $_POST['date']           
            ];
        
        $writer = new MongoDB\Driver\BulkWrite();
        $conc = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
        $writer->insert($filter);
        $res = $mongodb->executeBulkWrite('condata_db.favorites', $writer,$conc);
        echo "Added to Favorites!!!";
    }else{
        echo "The Concerts is already in favorites"; // ." ".$temp." " .$idfav ;
    }
}
else if ($_POST['type'] === "show_fav_table"){

    //Show users favorites concert table

    $filter = [
        "userid" => $_POST['userid']
    ];
    $options = [];
    $query = new \MongoDB\Driver\Query($filter, $options);
    
    $concertlist = $mongodb->executeQuery("condata_db.favorites", $query);
    
    foreach($concertlist as $con) {
        $conarr[] = array( 
        "id" => $con->id,
        "userid" =>$con->userid,
        "concertid" => $con->concertid, 
        "title" => $con->title,
        "date" => $con->date
        );
    }

    $checkBox = "<input class =\"addButton\" value =\"X\" type=\"button\"/>";
    if($conarr != null){
        echo "<table id=\"contable\"><tr><th>Delete From <br/> Favorites</th><th>ID</th><th>User ID</th><th>Concert ID</th><th>Title</th><th>Date</th>";
        foreach($conarr as $row){
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
else if ($_POST['type'] === "del_fav"){

    //Delete Concert from favorites

    $filter = [
        'id' => intval($_POST['conid'])
    ];
    $options = [];
    

    $del = new MongoDB\Driver\BulkWrite();
    $con = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
    $del->delete($filter);
    $result = $mongodb->executeBulkWrite('condata_db.favorites', $del,$con);
    
    //var_dump($result);

    $filter = [
        "userid" => $_POST['userid']
    ];
    $options = [];
    $query = new \MongoDB\Driver\Query($filter, $options);
    
    $concertlist = $mongodb->executeQuery("condata_db.favorites", $query);
    
    foreach($concertlist as $con) {
        $conarr[] = array( 
        "id" => $con->id,
        "userid" =>$con->userid,
        "concertid" => $con->concertid, 
        "title" => $con->title,
        "date" => $con->date
        );
    }

    $checkBox = "<input class =\"addButton\" value =\"X\" type=\"button\"/>";
    if($conarr != null){
        echo "<table id=\"contable\"><tr><th>Delete From <br/> Favorites</th><th>ID</th><th>User ID</th><th>Concert ID</th><th>Title</th><th>Date</th>";
        foreach($conarr as $row){
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
//Add subscription to mongodb
else if ($_POST['type'] === "Add_Sub"){
    $filter = [
        'subid' => $_POST["subid"],
        'userid' =>  $_POST["userid"],
        'concertid' =>  $_POST["conid"]
    ];

    $writer = new MongoDB\Driver\BulkWrite();
    $conc = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
    $writer->insert($filter);
    $res = $mongodb->executeBulkWrite('condata_db.subs', $writer,$conc);
    echo $res;
}
//Delete subscription from monfodb
else if ($_POST['type'] === "Del_Sub"){
    $filter = [
        'userid' =>  $_POST["userid"],
        'concertid' =>  $_POST["concertid"]
    ];

    $writer = new MongoDB\Driver\BulkWrite();
    $conc = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
    $writer->delete($filter);
    $res = $mongodb->executeBulkWrite('condata_db.subs', $writer,$conc);


    $filter = [
        'subscription' =>  $_POST["subid"]
    ];

    $writer = new MongoDB\Driver\BulkWrite();
    $conc = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
    $writer->delete($filter);
    $ress = $mongodb->executeBulkWrite('condata_db.feeds', $writer,$conc);

    echo $res;
}
else if ($_POST['type'] === "Get_sub"){
    $filter = [
        'userid' =>  $_POST["userid"],
        'concertid' =>  $_POST["concertid"]
    ];
    $options = [];
    $query = new \MongoDB\Driver\Query($filter, $options);
    
    $subs = $mongodb->executeQuery("condata_db.subs", $query);
    foreach($subs as $sub) {
        $conarr[] = array( 
        "subid" => $sub->subid
        );
    }
    foreach($conarr as $row){
        $subid=$row['subid'];
    }
    


    echo $subid;
}
?>