<?php

define('DB_SERVER', 'localhost:3307');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'aayushi1304');
define('DB_NAME', 'ad_uni');
 

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST")
{
 

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } 
	else
	{
		$username = trim($_POST["username"]);
        
    }
    
    
    if(empty(trim($_POST["password"])))
	{
        $password_err = "Please enter a password.";     
    }
	
	elseif(strlen(trim($_POST["password"])) < 6)
	{
        $password_err = "Password must have atleast 6 characters.";
    } 
	
	else
	{
        $password = trim($_POST["password"]);
    }
    
    
    if(empty(trim($_POST["confirm_password"])))
	{
        $confirm_password_err = "Please confirm password.";     
    } 
	else
	{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password))
		{
            $confirm_password_err = "Password did not match.";
        }
    }

    //Validate email
    if(empty(trim($_POST["email"])))
	{
        $email_err = "Please enter a email.";
    } 
	else
	{
		$email = trim($_POST["email"]);
		$email = trim($_POST["email"]);
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err))
	{
        
        // Prepare an insert statement
        $sql = "INSERT INTO user_register VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql))
		{
            // Bind variables to the prepared statement as parameters
            
            
            // Set parameters
            $param_username = $username;
            $param_password = hash('sha256', $password);// Creates a password hash
            $param_email = $email;
			$param_balance = "1000";
            
            
			mysqli_stmt_bind_param($stmt, "ssss",$param_username,$param_email,$param_password,$param_balance);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
			{
                echo "user added";
			}
			else
			{
				echo "error";
			}
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
</head>
<body>
    <li><a type="button" class="button" href="home.html">Home</a></li>
<center>
    <div class="wrapper login-page">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
			
			
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
	</center>    
</body>
</html>