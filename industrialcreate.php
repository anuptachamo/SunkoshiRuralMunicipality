<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $dropdown =$produces = $no_of_staffs =$establish_date = $description = $address = $contact = "";
$name_err = $dropdown_err =$produces_err = $no_of_staffs_err =$establish_date_err = $description_err = $address_err = $contact_err ="";
 
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

    // Validate types
    $dropdown = $_POST['dropdown'];

    // Validate no_of_staffs
    $input_no_of_staffs = trim($_POST["no_of_staffs"]);
    if(empty($input_no_of_staffs)){
        $no_of_staffs_err = "Please enter the no of staffs.";     
    } elseif(!ctype_digit($input_no_of_staffs)){
        $no_of_staffs_err = "Please enter a valid number.";
    } else{
        $no_of_staffs= $input_no_of_staffs;
    }

    // Validate establish_date
    $input_establish_date = trim($_POST["establish_date"]);
    if(empty($input_establish_date)){
        $establish_date_err = "Please enter establish date.";     
    } elseif(!date($input_establish_date)){
        $establish_date_err = "Please enter a positive integer value.";
    } else{
        $establish_date= $input_establish_date;
    }

    // Validate description
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter.";
    } elseif(!filter_var($input_description, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $description_err = "Please enter.";
    } else{
        $description = $input_description;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
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

    // Validate produces
    $input_produces = trim($_POST["produces"]);
    if(empty($input_produces)){
        $produces_err = "Please enter produces.";     
    } elseif(!ctype_digit($input_produces)){
        $produces_err = "Please enter a valid number.";
    } else{
        $produces= $input_produces;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($dropdown_err) && empty($no_of_staffs_err) && empty($establish_date_err) && empty($description_err) && empty($address_err) && empty($contact_err) && empty($produces_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO industrial (name, dropdown, no_of_staffs, establish_date, description, address, contact, produces) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssss", $param_name, $param_dropdown, $param_no_of_staffs, $param_establish_date, $param_description, $param_address, $param_contact, $param_produces);
            
            // Set parameters
            $param_name = $name;
            $param_dropdown = $dropdown;
            $param_no_of_staffs = $no_of_staffs;
            $param_establish_date = $establish_date;
            $param_description = $description;
            $param_address = $address;
            $param_contact = $contact;
            $param_produces = $produces;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: industrialindex.php");
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
                    <p>Please fill this form and submit to add Industrial Data record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <!-- name start -->
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter the name" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <!-- name end -->

                        <!-- types start -->
                        <div class="form-group <?php echo (!empty($dropdown_err)) ? 'has-error' : ''; ?>">
                            <label>Types</label>
                            <select name="dropdown">
                                <option value="">--select--</option>
                                <option value="Mini Industry">Mini Industry</option>
                                <option value="Micro Industry">Micro Industry</option>
                            </select>
                        </div>
                        <!-- types end -->

                        <!-- no of staffs start -->
                        <div class="form-group <?php echo (!empty($no_of_staffs_err)) ? 'has-error' : ''; ?>">
                            <label>No. of Staffs</label>
                            <input type="text" name="no_of_staffs" class="form-control" value="<?php echo $no_of_staffs; ?>">
                            <span class="help-block"><?php echo $no_of_staffs_err;?></span>
                        </div>
                        <!-- no of staffs end -->

                        <!-- establish date start -->
                        <div class="form-group <?php echo (!empty($establish_date_err)) ? 'has-error' : ''; ?>">
                            <label>Establish Date</label>
                            <input type="Date" name="establish_date" class="form-control" value="<?php echo $establish_date; ?>">
                            <span class="help-block"><?php echo $establish_date_err;?></span>
                        </div>
                        <!-- establish date end -->

                        <!-- description start -->
                        <div class="form-group <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
                            <span class="help-block"><?php echo $description_err;?></span>
                        </div>
                        <!-- description end -->

                        <!-- address start -->
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
                        <!-- address end -->
                        
                        <!-- contact start -->
                        <div class="form-group <?php echo (!empty($contact_err)) ? 'has-error' : ''; ?>">
                            <label>Contact</label>
                            <input type="text" name="contact" class="form-control" value="<?php echo $contact; ?>">
                            <span class="help-block"><?php echo $contact_err;?></span>
                        </div>
                        <!-- contact end -->

                        <!-- produces start -->
                        <div class="form-group <?php echo (!empty($produces_err)) ? 'has-error' : ''; ?>">
                            <label>Produces</label>
                            <input type="text" name="produces" class="form-control" value="<?php echo $produces; ?>">
                            <span class="help-block"><?php echo $produces_err;?></span>
                        </div>
                        <!-- produces end -->

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="industrialindex.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>