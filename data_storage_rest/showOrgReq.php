<?php
require_once 'mongo_con.php';

if( $_POST['type'] === "show_con_org" ) {

    //var_dump("error");

    $filter = [
        "createby" => $_POST["id"]
    ];
    //file_put_contents('testfile1.txt', print_r($_POST['id'], TRUE));
    $options = ['sort' => ['id' => 1]];
    $query = new \MongoDB\Driver\Query($filter, $options);
    
    $concertlist = $mongodb->executeQuery("condata_db.concerts", $query);
    
    foreach($concertlist as $con) {
        $conarr[] = array( 
        "id" => $con->id,
        "title" =>$con->title,
        "date" => $con->date, 
        "artistname" => $con->artistname,
        "category" => $con->category,
        "start_date"=> $con->start_date,
        "end_date"=> $con->end_date,
        "total_tickets"=> $con->total_tickets
        );
    }

    $bb = json_encode($conarr);
    
    $checkBox = "<input class =\"DelCon btn btn-primary\" value =\"Del\" type=\"button\"/>";
    $checkBox2 = "<input name=\"EdiUCon\" class =\"EdiConcerts btn btn-primary\" value =\"Edit\" type=\"submit\" data-toggle=\"modal\" data-target=\"#EdiConcert\"/>";
    if ($conarr!=null) {
        echo "<table id=\"contable\"><tr><th>Delete</th><th>Edit</th><th>ID</th><th>Title</th><th>Date</th><th>Artist Name</th><th>Category</th><th>Starting</th><th>Ending</th><th>Tickets</th></tr>";
        foreach($conarr as $row){
            echo "<tr>
                            <td>".$checkBox."</td>
                            <td>".$checkBox2."</td>
							<td class=\"csid\">".$row["id"]."</td>
							<td class=\"cstitle\">".$row["title"]."</td>
							<td class=\"csdate\">".$row["date"]."</td>
							<td class=\"csartist\">".$row["artistname"]."</td>
							<td class=\"cscategory\">".$row["category"]."</td>
                            <td class=\"csstart\">".$row["start_date"]."</td>
                            <td class=\"csend\">".$row["end_date"]."</td>
                            <td class=\"cstickets\">".$row["total_tickets"]."</td>
						</tr>";
        }
        echo "</table>";
    } else {
        echo "<table id=\"contable\"><tr><th>Delete</th><th>Edit</th><th>ID</th><th>Name</th><th>Date</th><th>Artist Name</th><th>Category</th></tr></table>";
    }

    

    exit();
}
if( $_POST['type'] === "Add_con" ) {

    $title = $_POST["title"];
    $date = $_POST["date"];
    $artistname = $_POST["artistname"];
    $category = $_POST["category"];
    $organizerid =  $_POST["organizerid"];
    $startdate = $_POST["startdate"];
    $enddate = $_POST["enddate"];
    $tickets =  $_POST["tickets"];
    
    //$startdate = strtotime($startdate);
    //$enddate = strtotime($enddate);

    $filter = [];
    $options = [
        'sort' => ['id' => -1],
        'limit' => 1
    ];
    //get next id
    $query = new \MongoDB\Driver\Query($filter,$options);
    $exist = $mongodb->executeQuery("condata_db.concerts", $query);
    $exist = $exist->toArray();
    //var_dump($exist);
    if ($exist === null) {
        $idfav = 1;
    }
    else{
        $row = $exist[0]->id;
        $temp = $row+1;
        $idfav = $temp;
    }
    //file_put_contents('seentimefirst.txt', print_r($idfav, TRUE));
    $filter = [
        'id' => $idfav,
        'title' => $title,
        'date' => $date,
        'artistname' => $artistname,
        'category' => $category,
        'createby' => $organizerid,
        'start_date' => $startdate,
        'end_date' =>  $enddate ,   
        'total_tickets' => $tickets
    ];
   
    $bwrite = new MongoDB\Driver\BulkWrite();
    $wrt = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
    $bwrite->insert($filter);
    $res = $mongodb->executeBulkWrite('condata_db.concerts', $bwrite,$wrt);
    var_dump($res->getModifiedCount());

    if($res->getInsertedCount()!=0 ){
        $id_to_orion= array('id' => $idfav);
        echo json_encode($id_to_orion);
    }
    else{
        $id_to_orion=array('id' => -1);
        echo $id_to_orion;
    }

    exit();
}

if( $_POST['type'] === "Edit_con" ) {

    $title = $_POST["title"];
    $date = $_POST["date"];
    $artistname = $_POST["artistname"];
    $category = $_POST["category"];
    $organizerid =  $_POST["organizerid"];
    $startdate = $_POST["startdate"];
    $enddate = $_POST["enddate"];
    $tickets =  $_POST["tickets"];

    $filter = [
        'id' => intval($_POST['id'])  
    ];
    $start_date = strtotime($startdate);
    $end_date = strtotime($enddate);
    $toupdate = [
        '$set' => [
            'title' => $_POST['title'],
            'date' => $_POST['date'],
            'artistname' => $_POST['artistname'],
            'category' => $_POST['category'],
            'start_date' => $startdate,
            'end_date' => $enddate,
            'total_tickets' => $_POST['tickets']

        ]
    ];
    $write = new MongoDB\Driver\BulkWrite();
    $wrt = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
    $write->update($filter,$toupdate);
    $res = $mongodb->executeBulkWrite('condata_db.concerts', $write,$wrt);
    //var_dump($res->getModifiedCount());

    //Remove it also from favorites
    $filter = [
        'concertid' => intval($_POST['id'])
    ];
    $toupdate = [
        '$set' => [
            'title' => $_POST['title'],
            'date' => $_POST['date'],
        ]
    ];

    $write = new MongoDB\Driver\BulkWrite();
    $wrt = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
    $write->update($filter,$toupdate);
    $responce = $mongodb->executeBulkWrite('condata_db.favorites', $write,$wrt);


    //if($res->getModifiedCount()!=null){
    $id_to_orion=array('id' => $idfav);
    echo json_encode($id_to_orion);
    //}
    /*else{
        $id_to_orion=array('id' => "ERROR");
        echo json_encode($id_to_orion);
    }*/

}

if( $_POST['type'] === "Del_con" ){

    $filter = [
        'id' => intval($_POST['id'])
    ];
    $options = [];
    

    $del = new MongoDB\Driver\BulkWrite();
    $con = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
    $del->delete($filter);
    $result = $mongodb->executeBulkWrite('condata_db.concerts', $del,$con);

    //Remove it also from favorites
    $filter = [
        'concertid' => intval($_POST['id'])
    ];
    $options = [];
    

    $del = new MongoDB\Driver\BulkWrite();
    $con = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
    $del->delete($filter);
    $result = $mongodb->executeBulkWrite('condata_db.favorites', $del,$con);


    $id_to_orion=array('id' => $result);
    echo json_encode($id_to_orion);
}
?>