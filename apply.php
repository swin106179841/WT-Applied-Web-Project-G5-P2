<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Aidan Grier">
    <meta name="keywords" content="job application, enviroment, organisation, EcoSphere">
    <meta name="description" content="apply for jobs to protect the enviroment">
    <title>Apply for Jobs | EcoSphere</title>
    <link rel="stylesheet" href="./styles/style.css">
    <style>
        .labelRequired::after {
            content: "*";
            color: red;
        }
    </style>
</head>

<body>
    <?php include './header.inc';?>
    <main style="display: flow-root;">
        <div id="pageInfo">
            <h1>Apply for a Position</h1>
            <p>
                Join our team and contribute to protecting our Earth. <br>
                Easily apply for a number of positions through our simple web form.
            </p>
            <div id="sectionsList">
                <h2>Form Sections</h2>
                <ol id="formSectionList">
                    <li><a href="#jobSection">Job</a></li>
                    <li><a href="#personalSection">Personal Details</a></li>
                    <li><a href="#addressSection">Address</a></li>
                    <li><a href="#contactSection">Contact Details</a></li>
                    <li><a href="#skillSection">Skills</a></li>
                </ol>
            </div>
        </div>
        <form method="post" action="./process_eoi.php">
            <fieldset class="formSection" id="jobSection">
                <legend class="formSectionTitle">Job</legend>
                <p>
                    <label for="reference">Job Reference Number</label>
                    <input type="text" name="reference" id="reference" maxlength="6" minlength="6" placeholder="XXXXXX"
                        pattern="[a-zA-Z0-9]{6}" required>
                </p>
            </fieldset>
            <fieldset class="formSection" id="personalSection">
                <legend class="formSectionTitle">Personal Details</legend>
                <p>
                    <label for="name" class="labelRequired">First Name</label>
                    <input type="text" name="name" id="name" maxlength="20" placeholder="John" pattern="[a-zA-Z]{1,20}" required>
                </p>
                <p>
                    <label for="surname" class="labelRequired">Last Name</label>
                    <input type="text" name="surname" id="surname" maxlength="20" placeholder="Smith" pattern="[a-zA-Z]{1,20}" required>
                </p>
                <p>
                    <label for="DoB" class="labelRequired">Date of Birth</label>
                    <input type="text" name="DoB" id="DoB" placeholder="dd/mm/yyyy"
                        pattern="[0-3][0-9]/[0-1][0-9]/[1-2][0-9][0-9][0-9]" required>
                </p>
                <fieldset class="formSection" id="genderFieldset">
                    <legend class="labelRequired">Gender</legend>
                    <input type="radio" name="gender" id="genderM" value="male" required>
                    <label for="genderM">Male</label>
                    <input type="radio" name="gender" id="genderF" value="female" required>
                    <label for="genderF">Female</label>
                </fieldset>
            </fieldset>
            <fieldset class="formSection" id="addressSection">
                <legend class="formSectionTitle">Address</legend>
                <p>
                    <label for="address" class="labelRequired">Street Address</label>
                    <input type="text" name="address" id="address" maxlength="40" placeholder="1 Flinders St" required>
                </p>
                <p>
                    <label for="suburb" class="labelRequired">Suburb/Town</label>
                    <input type="text" name="suburb" id="suburb" maxlength="40" placeholder="Melbourne" required>
                </p>
                <p>
                    <label for="state" class="labelRequired">State</label>
                    <select name="state" id="state" required>
                        <option value="">- please select -</option>
                        <option value="VIC">VIC</option>
                        <option value="NSW">NSW</option>
                        <option value="QLD">QLD</option>
                        <option value="NT">NT</option>
                        <option value="WA">WA</option>
                        <option value="SA">SA</option>
                        <option value="TAS">TAS</option>
                        <option value="ACT">ACT</option>
                    </select>
                </p>
                <p>
                    <label for="postcode" class="labelRequired">Postcode</label>
                    <input type="text" name="postcode" id="postcode" placeholder="XXXX" pattern="[0-9][0-9][0-9][0-9]"
                        required>
                </p>
            </fieldset>
            <fieldset class="formSection" id="contactSection">
                <legend class="formSectionTitle">Contact</legend>
                <p>
                    <label for="email" class="labelRequired">email</label>
                    <input type="email" name="email" id="email" placeholder="john_s@mail.com" required>
                </p>
                <p>
                    <label for="phone" class="labelRequired">Phone Number</label>
                    <input type="text" name="phone" id="phone" placeholder="04123456789" pattern="[0-9]{8,12}" required>
                </p>
            </fieldset>
            <fieldset class="formSection" id="skillSection">
                <legend class="formSectionTitle">Skills</legend>
                <fieldset id="form-skills-check">
                    <legend class="labelRequired">Select Skills that Apply</legend>
                    <input type="checkbox" name="skills[]" id="skillA" value="motivated">
                    <label for="skillA">Self motivated to complete work.</label>
                    <input type="checkbox" name="skills[]" id="skillB" value="time management">
                    <label for="skillB">Strong time management skills.</label>
                    <input type="checkbox" name="skills[]" id="skillC" value="communication">
                    <label for="skillC">Strong communicator and can respectfully share ideas and feedback. </label>
                    <input type="checkbox" name="skills[]" id="skillD" value="enviroment">
                    <label for="skillD">A passion for protecting the enviroment.</label>
                </fieldset>
                <p>
                    <label for="otherSkills">Other Skills</label> <br>
                    <textarea name="otherSkills" id="otherSkills"
                        placeholder="Describe any additional skills here."
                        style="background: #21769022; border: none; padding: 0.25em 1em;border-radius: 3px;"></textarea>
                </p>
            </fieldset>
            <div id="formControls">
                <input type="submit" value="Apply" class="btn primary">
                <input type="reset" value="Reset Form" class="btn">
            </div>
        </form>
    </main>
    <?php include './footer.inc';?>
</body>

</html>
