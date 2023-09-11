<?php 
// database connection 


$conn = mysqli_connect('localhost','root','','showroom');                                                    // for database connection 
if ($conn) {                                                                                                   // check connection is ok
    # code...
    // echo "connected successfully";
} else {
    # code...
    echo mysqli_connect_error();                                                                         // show error if connection not ok
}



// data insertion and data validation

if (isset($_POST['submit'])) {                                                              // check user click on submit button or not
    $fname = $_POST["fname"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];
    $userIP = $_SERVER['REMOTE_ADDR'];

    $errors = array();                                                                   // to store errors in an array 


    // Validate fullname 
    if (empty($fname)) {
        $errors['fname'] = "Name is required.";
    } elseif (strlen($fname) < 4) {
        $errors['fname'] = "Name is too small";
    } //elseif (!ctype_alpha($fname)) {
    //     $errors['fname'] = " Name should only contain alphabetic Character Not Number & Special characters";
    // }

    // Validate phone 
    if (empty($phone)) {
        $errors['phone'] = "Phone is required.";
    } elseif (!is_numeric($phone)) {
        $errors['phone'] = "Phone number should only contain digits Not alphabetic & Special characters";
    } elseif (strlen($phone) > 10) {
        $errors['phone'] = "Phone number shouldn't be greater than 10 digits";
    } elseif (strlen($phone) < 10) {
        $errors['phone'] = "Phone number shouldn't be less than 10 digits";
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ' Inavlid email format';
    } elseif (strlen($email) > 40) {
        $errors['email'] = "Email  shouldn't be greater than 40 characters";
    }

    // Validate subject
    if (empty($subject)) {
        $errors['subject'] = "Subject is required.";
    } elseif (strlen($subject) > 200) {
        $errors['subject'] = "Subject  shouldn't exceed  200 characters";
    }
    // Validate message 
    if (empty($message)) {
        $errors['message'] = "Message is required.";
    } elseif (strlen($message) > 1000) {
        $errors['message'] = "Message  shouldn't exceed  1000 characters";
    }

    if (empty($errors)) {
        // Insert data into the database
        $query = "INSERT INTO contact_form SET fname = '$_POST[fname]',                             
       phone = '$_POST[phone]',
       email = '$_POST[email]',
       subject = '$_POST[subject]',
       message = '$_POST[message]',
       userIPaddress = '$userIP'                                                                        
       ";                                                                                              // user IP address

        // execution of insert query using mysqli_query(connection name, insert query)
        if ($results = mysqli_query($conn, $query)) {                                   //check query is correct or not

            // for sending confirmation mail after form submission using mail function
            $to = $email;
            $headers = "From: pard2356890@gmail.com";

            if (mail($to, $subject, $message, $headers)) {
                // echo "Test email sent successfully.";
                header('Location: index.php?success=true');                                            // for rediredct to index page
            } else {
                echo "Test email could not be sent.";                                                 // show error if Email not sent
            }
        } else {
            echo mysqli_error($conn);                                                                   // showing  error msg if error in syntax of sql query
        }
    }
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>Index</title>
    <!-- Add  CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Nabar Start -->

    <nav class="navbar">
        <div class="logo"><img src="showroom.png" style="height: 60px; width: 100px; " alt=""></div>
        <ul class="nav-links">
            <li><a style="font-size: 25px;" href="index.php">Home</a></li>

        </ul>
        <div class="hamburger">&#9776;</div>
    </nav>
    <!-- Navbar End -->
    <!--Contactus form Start-->
    <div class="contact-form">

        <!-- this is for showing success msg -->
        <?php
        if (isset($_GET['success']) && $_GET['success'] == true) {
            echo '<p style="color:green" > Form Submitted Successfully! And Confirmation Email Send </p>';
        }
        ?>

        <h2 style="text-align:center ; margin:10px;">Contact Us</h2>

        <form  method="post" action="index.php">
            <div >
                <label >Full Name:</label>
                <input type="text"   value="<?php echo isset($fname) ? $fname : ''; ?>" name="fname">
                <span ><?php echo isset($errors["fname"]) ? $errors["fname"] : ''; ?></span>
            </div>
            <div >
                <label >Phone Number:</label>
                <input type="text"   value="<?php echo isset($phone) ? $phone : ''; ?>" name="phone">
                <span ><?php echo isset($errors["phone"]) ? $errors["phone"] : ''; ?></span>
            </div>
            <div >
                <label >Email:</label>
                <input type="text"   value="<?php echo isset($email) ? $email : ''; ?>" name="email">
                <span ><?php echo isset($errors["email"]) ? $errors["email"] : ''; ?></span>
            </div>
            <div >
                <label >Subject:</label>
                <input type="text"   value="<?php echo isset($subject) ? $subject : ''; ?>" name="subject">
                <span ><?php echo isset($errors["subject"]) ? $errors["subject"] : ''; ?></span>
            </div>
            <div >
                <label >Message:</label>
                <textarea type="text"   name="message"><?php echo isset($message) ? $message : ''; ?></textarea>
                <span ><?php echo isset($errors["message"]) ? $errors["message"] : ''; ?></span>
            </div>

            <button type="submit" class="submitbtn" value="Submit" name="submit">Submit</button>
            <button type="reset"  class="resetbtn" value="Reset" name="reset">Reset</button>
        </form>
    </div>
    
    <!-- ContactUs Form end -->


<!-- javacsript code -->
    <script>
        const hamburger = document.querySelector(".hamburger");
        const navLinks = document.querySelector(".nav-links");

        hamburger.addEventListener("click", () => {
            navLinks.classList.toggle("active");
        });
    </script>
</body>

</html>