<?php
     session_start();

     try{
         require "include/dbh-inc.php";
     }catch(Exception $e){
         die("Database Handler Not Found : ". $e->getMessage());
     }
 
     $query ="SELECT first_name ,last_name FROM doctors WHERE username=?";
     $stmt = $conn->prepare($query);
     $stmt->bind_param("s", $_SESSION['username']);
     $stmt->execute() or die("Unable to execute query");
     $stmt->store_result();
     $stmt->bind_result($fname, $lname);
     $stmt->fetch();
     $stmt->free_result();
     $stmt->close();


    $fetch_sql= "SELECT middle_name, dob, age,
                    gender, specialization, email_id, contact_no, 
                    address, city, state, pincode 
                    FROM doctors WHERE username = ?";
    
    $stmt = $conn->prepare($fetch_sql);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute() or die("Unable to execute query");
    $stmt->store_result();
    // $dob = $stmt->num_rows;
    $stmt->bind_result($mname, $wrong_dob, $age, $gender, $special, $email, $contact, $addr, $city, $state, $pincode);
    $stmt->fetch();
    $stmt->free_result();
    $stmt->close();
    
    $wrong_dob = explode("-", $wrong_dob);

    $dob = $wrong_dob[2].'/'.$wrong_dob[1].'/'.$wrong_dob[0];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Doctor Profile</title>
        <link href="css/styles.css" rel="stylesheet" />
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>

        <style>
            .error-text{
                color:red;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <span class="h1 fas fa-hospital-symbol" style="color:rgb(4, 152, 238); margin: auto 2rem;" id="logo">
                
            </span>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" style="margin: auto 0.5rem;" href="#"><i class="fas fa-bars"></i></button>
            <div class="form-inline ml-auto" style="color:whitesmoke; margin-right:0.3rem;">
                <i class="fas fa-user fa-fw" style="margin-right: 0.5rem;"></i>
               <!-- User's first name and last name  -->
               <?= htmlentities($fname)." ". htmlentities($lname);?>
            </div>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="dashboard_dr.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            
                            <a class="nav-link" href="#">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                Profile
                            </a>
                            
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#change-pass-modal">
                                <div class="sb-nav-link-icon"><i class="fas fa-pen"></i></div>
                                Change the Password
                            </a>

                            <a class="nav-link" href="#" data-toggle="modal" data-target="#resign-modal">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-times"></i></div>
                                Resign
                            </a>

                            <a class="nav-link" href="include/logout-inc.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-power-off"></i></div>
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <!-- User's username -->
                        <?= htmlentities($_SESSION['username']);?>   
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid" style="margin-top: 0.75rem;">

                        <div class="breadcrumb mb-4 d-flex align-items-center" style="color: black;">
                            <i class="fas fa-user" style="margin-right: 0.75rem;"></i>
                             Profile   
                        </div>

                        <form method="POST" id="profile-form" action="include/profile-inc.php">
                            <!-- full name -->
                            <div class="form-row">
                                 <div class="col-md-4">
                                    <label class="h6" for="fname">First Name</label>
                                    <input type="text" class="form-control border-prop" name="first-name" id="fname" 
                                    value="<?= htmlentities($fname);?>"
                                    placeholder="NA" disabled>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="h6" for="mname">Midddle Name</label>
                                      <input type="text" class="form-control border-prop" name="middle-name" id="mname" 
                                      value="<?= htmlentities($mname);?>"
                                      placeholder="NA" disabled>
                                      
                                  </div>
                                  <div class="col">
                                      <label class="h6" for="lname">Surname Name</label>
                                      <input type="text" class="form-control border-prop" name="last-name" id="lname" 
                                      value="<?= htmlentities($lname);?>"
                                      placeholder="NA" disabled>
                                  </div>
                            </div>
            
                            <hr class="my-4">
            
                            <!-- age and dob -->
                            <div class="form-row">
                                <div class="col" id="dateInput">
                                    <label class="h6" for="dob">Date of Birth</label>
                                    <input type='text' class="form-control border-prop" name="dob" id="dob" 
                                    value="<?= htmlentities($dob);?>"
                                    placeholder="NA" disabled autocomplete="off"/>
                                    <span class="input-group-addon"></span>
                                    
                                </div>
                                <div class="col">
                                    <label class="h6" for="age">Age</label>
                                    <input type="text" class="form-control border-prop" name="age" id="age" 
                                    value="<?= htmlentities($age);?>"
                                    placeholder="NA" disabled>
                                    <input type="hidden" name="age-val" id="age-val">
                                </div>
                            </div>
                            
                            <hr class="my-4">
            
                            <!-- gender -->
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label class="h6" for="genderlabel" id="gender-id">Gender :</label>
                                    <div class="custom-control">
                                        <div class="custom-control custom-radio custom-control-inline" name="genderlabel">
                                            <input type="radio" id="M" value = "M" name="gender" class="custom-control-input"  
                                            <?= $gender=="M"?'checked':'';?>
                                            disabled>
                                            <label class="custom-control-label" for="M">Male</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="F" value = "F" name="gender" class="custom-control-input" 
                                            <?= $gender=="F"?'checked':'';?>
                                            disabled>
                                            <label class="custom-control-label" for="F">Female</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="T" value = "T" name="gender" class="custom-control-input" 
                                            <?= $gender=="T"?'checked':'';?>
                                            disabled>
                                            <label class="custom-control-label" for="T">Transgender</label>
                                        </div>
                                    </div> 
                                    <small class="error-text" id="gender-error" ></small>
                                </div>
            
                                <!-- specialization -->
                                <div class="col-md-6">
                                    <label class="h6" for="genderlabel" id="gender-id">Specialization :</label>
                                    <input type="text" class="form-control border-prop" name="special" id="special" 
                                    value="<?= htmlentities($special);?>"
                                    placeholder="NA" disabled>
                                </div>
                            </div>
            
                            <hr class="my-4">
            
                            <!-- email -->
                            <div class="form-row">
                                <div class="col-md-5 offset-md-1">
                                    <label class="h6" for="email">Email ID :</label>
                                    <input type="email" class="form-control border-prop" name="email" id="email" 
                                    value="<?= htmlentities($email);?>"
                                    placeholder="NA" required>
                                    <small class="error-text" id="email-error" ></small>
                                </div>
                                <!-- contact -->
                                <div class="form-group col-md-5">
                                    <label class="h6" for="contact">Contact No. :</label>
                                    <input type="text" class="form-control border-prop remove-arrow" name="contact" id="contact" 
                                    value="<?= htmlentities($contact);?>"
                                    placeholder="NA" required>
                                    <small class="error-text" id="contact-error" ></small>
                                </div> 
                            </div>
            
                            <hr class="my-4">
            
                            <div class="form-group">
                                <label class="h6" for="address">Address :</label>
                                <textarea name="address" id="address" class="form-control border-prop" rows="2" placeholder="NA" required><?= htmlentities($addr);?></textarea>
                                <small class="error-text" id="addr-error" ></small>
                            </div>
                            <!-- city state zipcode -->
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label class="h6" for="city">City :</label>
                                    <input type="text" class="form-control border-prop" name="city" id="city" 
                                    value="<?= htmlentities($city);?>"
                                    placeholder="NA" required>
                                    <small class="error-text" id="city-error" ></small>
                                </div>
                                <div class="col-md-4">
                                    <label class="h6" for="state">State :</label>
                                    <select id="state" class="custom-select border-prop" name="state" required>
                                        <option value="Maharashtra"
                                            <?= $state=="Maharashtra"?'selected':'';?>
                                        >Maharashtra</option>
                                        <option value="Karnataka"
                                            <?= $state=="Karnataka"?'selected':'';?>
                                        >Karnataka</option>
                                        <option value="Keral"
                                            <?= $state=="Keral"?'selected':'';?>
                                        >Keral</option>
                                        <option value="Madhya Pradesh"
                                            <?= $state=="Madhya Pradesh"?'selected':'';?>    
                                        >Madhya Pradesh</option>
                                    </select>
                                    <small class="error-text" id="state-error" ></small>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="h6" for="zipcode">Pincode :</label>
                                    <input type="text" class="form-control border-prop remove-arrow" name="zipcode" id="zipcode"
                                    value="<?= htmlentities($pincode);?>" 
                                    placeholder="NA" required>
                                    <small class="error-text" id="zip-error" ></small>
                                </div>
                            </div>
            
                            <hr class="my-4">
            
                            
                            <div class="d-flex justify-content-center">
                                <input type="submit" name="save" id="savebtn" class="btn btn-success btn-lg" value="Save" style="margin: 1rem;">
                                <input type="reset" name="reset" id="resetbtn" class="btn btn-danger btn-lg" value="Reset" style="margin: 1rem;"
                                 onclick="window.location.href='profile_dr.php';" >
                            </div>
                        
                        </form>

                        <!-- resign modal  -->
                        <div class='modal' id='resign-modal'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                
                                    <!-- Modal Header -->
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                    </div>
                                    
                                    <!-- Modal body -->
                                    <div class='modal-body h5'>Are you sure, do you want to resign?</div>
                    
                                    <!-- Modal footer -->
                                    <div class='modal-footer'>
                                        <form method="POST" action="include/resign-inc.php">
                                            <input type="submit" class='btn btn-success' value="Yes" name="resign" id="resign" >
                                            <button type='button' class='btn btn-danger' data-dismiss='modal'>No</button>
                                        </form> 
                                    </div>
                                    
                                </div>
                            </div> 
                        </div>


                        <!-- Change password modal -->
                        <div class="modal fade" id="change-pass-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 720px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Change the Password</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                
                                    <form method="POST" id="change-pass-form" action="include/change-pass-inc.php">
              
                                        <div class="form-group">
                                            <label class="h6" for="password">Current Password :</label>
                                            <input type="password" class="form-control" id="old-password" name="old-password" placeholder="Enter your Current Password" required>
                                            <small class="error-text" id="old-pass-error" ></small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="h6" for="new-password">New Password :</label>
                                            <input type="password" class="form-control" id="new-password" name="new-password" placeholder="Enter New Password" required>
                                            <small class="error-text" id="new-pass-error" ></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="h6" for="con-new-password">Confirm the New Password :</label>
                                            <input type="password" class="form-control" id="con-new-password" name="con-new-password" placeholder="Confirm your New Password" required>
                                            <small class="error-text" id="con-new-pass-error" ></small>
                                        </div>

                                        <div class="modal-footer">
                                            <input type="submit" value="Change" name="change" id="change" class="btn btn-primary"/>
                                            <button type="button" id="cancel" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>         
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/dr_homepage_script.js"></script>
        <script src="js/change-pass-valid.js"></script>
        
    </body>
</html>
