<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Recieved</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php require "header.inc";?>
<?php

    function cleanPost(string $v): string 
    {
        return htmlspecialchars(trim($_POST[$v]));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = [];

        // load values
        $reference = cleanPost("reference");
        $name = cleanPost("name");
        $surname = cleanPost("surname");
        $DoB = cleanPost("DoB");
        $gender = cleanPost("gender");
        $address = cleanPost("address");
        $suburb = cleanPost("suburb");
        $state = cleanPost("state");
        $postcode = cleanPost("postcode");
        $email = cleanPost("email");
        $phone = cleanPost("phone");
        $skills = "";
        $otherSkills = cleanPost("otherSkills");

        $allowedStates = ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
        $allowedSkills = ['motivated', 'time management', 'communication', 'enviroment'];
        $allowedGenders = ['male', 'female'];


        // validation with regex
        // ======================================
        // reference
        if (!preg_match('/^[A-Z]{3}[0-9]{3}$/', $reference)) {
            $errors['reference'] = "Reference must be 3 capital letters followed by 3 numbers.";
        }
        // name
        if (!preg_match('/^[a-zA-Z]{1,20}$/', $name)) {
            $errors['name'] = "Name must be 1-20 letters.";
        }
        // surname
        if (!preg_match('/^[a-zA-Z]{1,20}$/', $surname)) {
            $errors['surname'] = "Surname must be 1-20 letters.";
        }
        // DoB
        if (!preg_match('/^[0-3][0-9]\/[0-1][0-9]\/[1-2][0-9][0-9][0-9]$/', $DoB)) {
            $errors['DoB'] = "Date of Birth must be a valid date.";
        }
        // gender
        if (!in_array($gender, $allowedGenders, true)) {
            $errors['gender'] = "Select a valid gender option.";
        }
        // address
        if (!preg_match('/^[a-zA-Z0-9 \-\/]{1,40}$/', $address)) {
            $errors['address'] = "Address must be valid length and only contain symbols '-' and '/'.";
        }
        // suburb
        if (!preg_match('/^[a-zA-Z0-9 \-\/]{1,40}$/', $suburb)) {
            $errors['suburb'] = "Suburb must be valid length and only contain symbols '-' and '/'.";
        }
        // state
        if (!in_array($state, $allowedStates, true)) {
            $errors['state'] = "Select a valid state option.";
        }
        // postcode
        if (!preg_match('/^[0-9][0-9][0-9][0-9]$/', $postcode)) {
            $errors['postcode'] = "Postcode must be 4 numbers only.";
        }
        // email
        // email regex from: https://regex101.com/r/lHs2R3/1
        if (!preg_match('/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,}$/', $email)) {
            $errors['email'] = "Email must be valid email address.";
        }
        // phone 
        if (!preg_match('/^[0-9]{8,12}$/', $phone)) {
            $errors['phone'] = "Phone must be a valid Australian phone number.";
        }
        // skills - stored as string to better fit in with database
        $selected_skills = $_POST['skills'] ?? [];
        if (is_array($selected_skills)) {
            if (array_diff($selected_skills, $allowedSkills)) {
                $errors["skills"] = "Invalid skills selection.";
            } 
            $skills = implode(",", array_intersect($selected_skills, $allowedSkills));
        } else {
            $errors["skills-count"] = "Must select at least 1 skill.";
        }

        // other skills
        if(!isset($otherSkills)) {
            $otherSkills = "";
        }

        // display errors
        if ($errors) {
            // status code 400 because: https://stackoverflow.com/questions/3290182/which-status-code-should-i-use-for-failed-validations-or-invalid-duplicates
            http_response_code(400);
            echo "<main><h1>Form submission contains the following errors:</h1><ul>";
            foreach ($errors as $error) {
                echo "<li>" . $error . "</li>";
            }
            echo "</ul><p><a href='apply.php'>Return to form</a></p></main>";
            require "footer.inc";
            exit;
        }

        // database connection and create new eoi 
        require_once("settings.php");
        $conn = mysqli_connect($host, $user, $pwd, $sql_db);
        $stmt = $conn->prepare("INSERT INTO eoi (job_ref, NAME, surname, dob, gender, street, suburb, state, postcode, email, phone, skills, other_skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("sssssssssssss", $reference, $name, $surname, $DoB, $gender, $address, $suburb, $state, $postcode, $email, $phone, $skills, $otherSkills);
        $stmt->execute();
        // $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        // $stmt->bind_param("ss", $input_username, $input_password);
        // $stmt->execute();
        // $result = $stmt->get_result();
        echo "<main><h1>Application Received</h1>";
        // TODO query database for id number here
        echo "<p>Your Expression-of-Interest ID is: ";
        echo $conn->insert_id ."</p></main>";

    } else {
        header("Location: ./apply.php");
    }
?>
<?php require "footer.inc";?>

</body>
</html>