<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $profession= $date_of_birth = $contact = "";
$name_err = $address_err = $profession_err = $date_of_birth_err = $contact_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate profession
    $input_profession = trim($_POST["profession"]);
    if(empty($input_profession)){
        $profession_err = "Please enter a profession.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $profession_err = "Please enter a valid profession.";
    } else{
        $profession = $input_profession;
    }

    $input_date_of_birth = trim($_POST["date_of_birth"]);
    if(empty($input_date_of_birth)){
        $date_of_birth_err = "Plase enter your date of birth.";     
    } elseif(!date($input_date_of_birth)){
        $date_of_birth_err = "Please enter a valid date of birth.";
    } else{
        $date_of_birth = $input_date_of_birth;
    }

    // Validate contact
    $input_contact = trim($_POST["contact"]);
    if(empty($input_contact)){
        $contact_err = "Plase enter the contact number.";     
    } elseif(!ctype_digit($input_contact)){
        $contact_err = "Please enter a valid contact number.";
    } else{
        $contact = $input_contact;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($profession_err) &&empty($date_of_birth_err) &&empty($contact_err)){
        // Prepare an update statement
        $sql = "UPDATE personal SET name=?, address=?, profession=?, date_of_birth=?, contact=? WHERE id=?";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssssi", $param_name, $param_address, $param_profession, $param_date_of_birth, $param_contact, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_profession = $profession;
            $param_date_of_birth = $date_of_birth;
            $param_contact = $contact;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: personalindex.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM personal WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
                    $address = $row["address"];
                    $profession = $row["profession"];
                    $date_of_birth = $row["date_of_birth"];
                    $contact = $row["contact"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();
        
        // Close connection
        $mysqli->close();
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
                         <div class="form-group <?php echo (!empty($profession_err)) ? 'has-error' : ''; ?>">
                            <label>Profession</label>
                            <input type="text" name="profession" class="form-control" value="<?php echo $profession; ?>">
                            <span class="help-block"><?php echo $profession_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($date_of_birth_err)) ? 'has-error' : ''; ?>">
                            <label>Date of birth</label>
                            <input type="Date" name="date_of_birth" class="form-control" value="<?php echo $date_of_birth; ?>">
                            <span class="help-block"><?php echo $date_of_birth_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($contact_err)) ? 'has-error' : ''; ?>">
                            <label>Contact</label>
                            <input type="text" name="contact" class="form-control" value="<?php echo $contact; ?>">
                            <span class="help-block"><?php echo $contact_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="personalindex.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>