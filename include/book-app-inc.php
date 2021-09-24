<?php
    session_start();

    try{
        require "dbh-inc.php";
    }catch(Exception $e){
        die("Database Handler Not Found : ". $e->getMessage());
    }

    if(isset($_POST['book'])){

        if(empty($_POST['app-date-input']) || empty($_POST['app-time-input'])){
            header("Location: ../book_app.php?emptyFields=1");
        }else{
            $wrong_date = explode("/", $_POST['app-date-input']);
            $app_time = $_POST['app-time-input'];

            $app_date = $wrong_date[2].'-'.$wrong_date[1].'-'.$wrong_date[0];
            
            $doc_id= $_POST['doc-id'] ;
            $pat_id= $_SESSION['userid'];   
        
            // checking wether pat already appointment is already booked
            $queryAlreadyPat = "SELECT app_id FROM appointment WHERE patient_id = ? AND doctor_id = ? AND app_date = ? AND app_time = ?";
            $sqlqueryAlreadyPat=$conn->prepare($queryAlreadyPat);
            $sqlqueryAlreadyPat->bind_param("iiss", $pat_id, $doc_id, $app_date, $app_time);
            
            $sqlqueryAlreadyPat->execute() or die("Unable to execute query");
            $sqlqueryAlreadyPat->store_result();
            echo("2 : ". $sqlqueryAlreadyPat->num_rows);


            if($sqlqueryAlreadyPat->num_rows >= 1){
                header("Location: ../pat-dashboard.php?alreadyPatBooked=1");
                $sqlqueryAlreadyPat->close();
            }else{
                $sqlqueryAlreadyPat->close();

                 // checking wether doctor appointment is already booked
                $queryAnotherPat = "SELECT app_id FROM appointment WHERE doctor_id = ? AND app_date = ? AND app_time = ?";
                $sqlqueryAnotherPat=$conn->prepare($queryAnotherPat);
                $sqlqueryAnotherPat->bind_param("iss", $doc_id, $app_date, $app_time);
                
                $sqlqueryAnotherPat->execute() or die("Unable to execute query");
                $sqlqueryAnotherPat->store_result();

                echo("1 : ". $sqlqueryAnotherPat->num_rows);
                
                if($sqlqueryAnotherPat->num_rows >= 1){
                    header("Location: ../pat-dashboard.php?anotherPatBooked=1");
                    $sqlqueryAnotherPat->close();
                }else{
                    $sqlqueryAnotherPat->close();

                    // checking wether pat has another appointment is already booked
                    $queryPatAnohterBooking = "SELECT app_id FROM appointment WHERE patient_id = ? AND app_date = ? AND app_time = ?";
                    $sqlqueryPatAnohterBooking=$conn->prepare($queryPatAnohterBooking);
                    $sqlqueryPatAnohterBooking->bind_param("iss", $pat_id, $app_date, $app_time);
                    
                    $sqlqueryPatAnohterBooking->execute() or die("Unable to execute query");
                    $sqlqueryPatAnohterBooking->store_result();
                    echo("3 : ". $sqlqueryPatAnohterBooking->num_rows);
                    if($sqlqueryPatAnohterBooking->num_rows >= 1){
                        header("Location: ../pat-dashboard.php?PatAnotherBooked=1");
                        $sqlqueryPatAnohterBooking->close();
                    }else{
                        $sqlqueryPatAnohterBooking->close();
                
                        $sql="INSERT INTO appointment(doctor_id,patient_id,app_date,app_time) 
                                VALUES(?,?,?,?)";
                        
                        $sql=$conn->prepare($sql);
                        $sql->bind_param("iiss", $doc_id, $pat_id, $app_date, $app_time);
                        
                        $sql->execute() or die("Unable to execute query");
                        $sql->close();
            
                        echo($app_date);
                        echo($app_time);
                        header("Location: ../pat-dashboard.php?booked=1");
                    }
        
                    
                }
             
            }

        }
    }