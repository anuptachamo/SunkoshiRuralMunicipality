<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $profession = $date_of_birth = $contact = "";
$name_err = $address_err = $profession_err = $date_of_birth_err = $contact_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate profession
    $input_profession = trim($_POST["profession"]);
    if(empty($input_profession)){
        $profession_err = "Please enter an profession.";     
    } else{
        $profession = $input_profession;
    }


    // Validate date_of_birth
    $input_date_of_birth = trim($_POST["date_of_birth"]);
    if(empty($input_date_of_birth)){
        $date_of_birth_err = "Please enter your date of birth.";     
    } elseif(!date($input_date_of_birth)){
        $date_of_birth_err = "Please enter a positive integer value.";
    } else{
        $date_of_birth= $input_date_of_birth;
    }
    
    // Validate contact
    $input_contact = trim($_POST["contact"]);
    if(empty($input_contact)){
        $contact_err = "Please enter the contact number.";     
    } elseif(!ctype_digit($input_contact)){
        $contact_err = "Please enter a valid number.";
    } else{
        $contact= $input_contact;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($profession_err) && empty($date_of_birth_err) && empty($contact_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO personal (name, address, profession, date_of_birth, contact) VALUES (?, ?, ?, ?, ?)";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_name, $param_address, $param_profession, $param_date_of_birth, $param_contact);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_profession = $profession;
            $param_date_of_birth = $date_of_birth;
            $param_contact = $contact;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add Personal Data record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="personalindex.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>