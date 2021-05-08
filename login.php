<?php

session_start();
 

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: home.html");
  exit;
}
 



define('DB_SERVER', 'localhost:3307');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'aayushi1304');
define('DB_NAME', 'ad_uni');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Check if username is empty
    if(empty(trim($_POST["username"])))
	{
        $username_err = "Please enter username.";
    } else
	{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"])))
	{
        $password_err = "Please enter your password.";
    } 
	else
	{
        $password = trim($_POST["password"]);
		$password = hash('sha256', $password);
    }
	
	$userExists = "select COUNT(*) from user_register where username = '".$username."'";
	
        if ($link->query($userExists) == TRUE) 
		{
			$data = $link->query($userExists);
			$value = mysqli_fetch_assoc($data);
			$value = $value['COUNT(*)'];
			if($value == 0)
			{
				$username_err="user doest not exists";
			}
			
		}
    
    // Validate credentials
    if(empty($username_err) && empty($password_err))
	{

		
        $sql = "SELECT  username, password FROM user_register WHERE username = ?";//select query for sql password verification
        
        if($stmt = mysqli_prepare($link, $sql))
		{
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            
            if(mysqli_stmt_execute($stmt))
			{
                
                mysqli_stmt_store_result($stmt);
                
                
                if(mysqli_stmt_num_rows($stmt) == 1)
				{       
                    mysqli_stmt_bind_result($stmt, $username, $saved_password);
                    
                    if(mysqli_stmt_fetch($stmt))
					{
                        if($password ==  $saved_password)
						{
                            
                            session_start();
                            
                            $_SESSION["loggedin"] = true;                           
                            $_SESSION["username"] = $username;                            
                            // Redirect user to welcome page
                            header("location: home.html");//add the next page here
                        } 
						else
						{
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } 
            } 
			else
			{
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>Login</title>
</head>
<body>
    <li><a type="button" class="button" href="home.html">Home</a></li>
<center>
    <div class="wrapper login-page">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account ? <a href="register.php">Sign up now</a>.</p>
            <p>Forgot Password ? <a href="reset-password.php">Reset Here</a>
        </form>
    </div>    
</center>
</body>
</html>