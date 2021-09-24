<?php
            
    //starting the session
    session_start();

    try{
        require "dbh-inc.php";
    }catch(Exception $e){
        die("Database Handler Not Found : ". $e->getMessage());
    }

    //checking register btn is set or not
    if(isset($_POST['login'])){

        $input_username = $_POST['username'];   //name of username input 
        $input_password = $_POST['password'];		// name of password input

        echo($input_username);
        echo($input_password);

         //query to select user from db
         $sql = "SELECT admin_id, login_username FROM admin WHERE login_username = ? AND login_password = ?";

         $stmt = $conn->prepare($sql);
         $stmt->bind_param("ss", $input_username, $input_password);
         $stmt->execute() or die("Unable to execute select query");

        //storing the result of executed query
        $stmt->store_result();

       if($stmt->num_rows != 1){

           //closing the prepared statement
           $stmt->close();

           //if user not found then return to index page
           header("Location: ../index.php?dberror=1");
       }else{
           // binding the parameters for result
           $stmt->bind_result($admin_id, $login_username);
           
           // fectching those results
           $stmt->fetch();

           $_SESSION['userid'] = $admin_id;
           $_SESSION['username'] = $login_username;

        
            //closing the prepared statement
            $stmt->close();

            //redirect to homepage
            header("Location: ../Admin_dashboard.php");
                
       }
        
    } else{
        //else returning to same file
        header("Location: ../demo.php");
    }
