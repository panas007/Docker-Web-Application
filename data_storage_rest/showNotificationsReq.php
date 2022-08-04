<?php
require_once 'mongo_con.php';

if($_POST['type'] === "show_feed"){

    $userID=$_POST['userID'];

    //file_put_contents('testfile1.txt', print_r($userID, TRUE));

    $filter = [
        "userid" => $userID
    ];
    $options = [];
    $query = new \MongoDB\Driver\Query($filter, $options);
    
    $feeds = $mongodb->executeQuery("condata_db.feeds", $query);
    
    foreach($feeds as $fee) {
        $feedarr[] = array( 
        "userid" => $fee->userid,
        "subid" =>$fee->subscription,
        "startdate" => $fee->startdate, 
        "enddate" => $fee->enddate,
        "startsale" => $fee->startsale,
        "endsale" => $fee->endsale,
        "title" => $fee->title,
        'created' =>  $fee->created,
        'readed' =>  $fee->readed,
        'isSeen' => $fee->isSeen,
        "sold_tick" => $fee->sold_tick,
        "tickets" => $fee->tickets
        );
    }
    //file_put_contents('testfile2.txt', print_r($feedarr, TRUE));
    $display_string = "<table id=\"nottab\" class=\"table table-bordered table-striped mb-0\">";
    if($feedarr != null){
        $flag=1;
        foreach($feedarr as $row) {
            $min=25000;
            if($row["readed"] !="none"){
                $nowtime = new DateTime("now", new DateTimeZone("Europe/Athens"));
                //$nowtime= $nowtime->format("Y-m-d h:i:s");
                $seentime =  new DateTime($row["readed"]);

                $sel = $nowtime->getTimestamp() - $seentime->getTimestamp() + 7200;

                /*$diff = $nowtime->diff($seentime);
                $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
                $hoursInSecs = $diff->h * 60 * 60;
                $minsInSecs = $diff->i * 60;
                $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;*/
                //file_put_contents('sel.txt', print_r($sel, TRUE));
                //file_put_contents('minutes.txt', print_r($seconds, TRUE));
                //file_put_contents('nowtime.txt', print_r($nowtime, TRUE));
                //file_put_contents('seentime.txt', print_r($seentime, TRUE));

            }
                if ($userID === $row["userid"] && ($row["readed"] === "none" || $sel<86400) ){
                    $flag=0;
        
                    if ($row["startsale"] ==="1"){
                        $display_string .= "<tr> <td class=\"descr\" style=\"font-size:20px\"> Concert : <b>";
                        $display_string .= $row["title"]. "</b> </br> <b>IS NOW SELLING TICKETS </b> </br>";
                        $display_string .= "Starting sale Date :".$row["startdate"]." ";
                        $display_string .= "Ending  sale Date  :".$row["enddate"]." ";
                        $display_string .= "</tr></td>";
                    }
                    if ($row["endsale"] === "1"){
                        $display_string .= "<tr> <td class=\"descr\" style=\"font-size:20px\"> Concert : <b>";
                        $display_string .= $row["title"]." </b></br> <b>IS NOW STOP SELLING TICKETS </b> </br>";
                        $display_string .= "Starting sale Date :".$row["startdate"]." ";
                        $display_string .= "Ending  sale Date  :".$row["enddate"]." ";
                        $display_string .= "</tr></td>";
                    }
                    if ($row["sold_tick"] === "1"){
                        $display_string .= "<tr> <td class=\"descr\" style=\"font-size:20px\">Concert : <b>";
                        $display_string .= $row["title"]." </b>";
                        $display_string .= "with Starting sale Date :".$row["startdate"]." ";
                        $display_string .= "and Ending  sale Date  :".$row["enddate"]." ";
                        $display_string .= " is now <b>SOLD OUT</b>";
                        $display_string .= "</tr></td>";
                    }
                    $filter = [
                        'subscription' =>$row["subid"],
                        'created' => $row["created"]
                    ];

                    if ($row["readed"]==="none"){
                        $date = new DateTime("now",new DateTimeZone("Europe/Athens"));
                        $seentime= $date->format("Y-m-d h:i:s");
                        $newvals = [
                            '$set' => [
                                'isSeen' => 1,
                                'readed' => $seentime
                                ]

                        ];
                    }
                    else{
                        $newvals = [
                            '$set' => [
                                'isSeen' => 1
                                ]

                        ];
                    }
                    //file_put_contents('testfile3.txt', print_r($seentime, TRUE));
                    //file_put_contents('seentimefirst.txt', print_r($readed, TRUE));
                    $writer = new MongoDB\Driver\BulkWrite();
                    $conc = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
                    $writer->update($filter,$newvals);
                    $res = $mongodb->executeBulkWrite('condata_db.feeds', $writer,$conc);
                    //file_put_contents('testfile1.txt', print_r($res, TRUE));
                    
                }
            }
    }
    else
    {
        $display_string = "<tr> <td class=\"descr\" style=\"font-size:20px\"> No new Notification &#128522 </tr>";
    }

    if($flag===1)
        $display_string = "<tr> <td class=\"descr\" style=\"font-size:20px\"> No new Notification &#128522 </tr>";


    $display_string .= "</table";
    echo $display_string;
    exit();

}





?>