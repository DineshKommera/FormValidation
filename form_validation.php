<!DOCTYPE html>
<html>
  <head>
    <title>Form Validation Example</title>
  </head>
  <body>
    <?php

    class UserData {
      public $email;
      public $username;
      public $password;
      public $gender;

      function __construct($email, $username, $password, $gender) {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->gender = $gender;
      }
    }

    // Initialize variables to hold user data and validation errors
    $email = $username = $password = $gender = '';
    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Get the form data and sanitize it
      $email = trim($_POST["email"]);
      $username = trim($_POST["username"]);
      $password = trim($_POST["password"]);
      $gender = trim($_POST["gender"]);

      // Validate the email
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
      }

      // Validate the username
      if (!preg_match('/^[A-Za-z0-9]{6,10}$/', $username)) {
        $errors['username'] = 'Username must be 6-10 characters and alphanumeric';
      }

      // Validate the password
      if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/', $password)) {
        $errors['password'] = 'Password must contain at least one lowercase letter, one uppercase letter, and one number';
      }

      // Validate the gender
      if (empty($gender)) {
        $errors['gender'] = 'Please select a gender';
      }

      // If there are validation errors, redisplay the form with error messages and user input
      if (count($errors) > 0) {
        // Start output buffering to capture HTML output
        ob_start(); ?>
        <!DOCTYPE html>
        <html>
          <head>
            <title>Form Validation Example</title>
          </head>
          <body>
            <h1>Form Validation Example</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
              <?php if (isset($errors['email'])) { echo '<span style="color:red">'.$errors['email'].'</span>'; } ?><br>

              <label for="username">Username:</label>
              <input type="text" id="username" name="username" pattern="[A-Za-z0-9]{6,10}" value="<?php echo htmlspecialchars($username); ?>" required>
              <?php if (isset($errors['username'])) { echo '<span style="color:red">'.$errors['username'].'</span>'; } ?><br>

              <label for="password">Password:</label>
              <input type="password" id="password" name="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$" required>
              <?php if (isset($errors['password'])) { echo '<span style="color:red">'.$errors['password'].'</span>'; } ?><br>

              <label for="gender">Gender:</label>
              <select id="gender" name="gender" required>
              <option value="">Please select a gender</option>
              <option value="male" <?php if ($gender == 'male') { echo 'selected'; } ?>>Male</option>
              <option value="female" <?php if ($gender == 'female') { echo 'selected'; } ?>>Female</option>
              <option value="non-binary" <?php if ($gender == 'non-binary') { echo 'selected'; } ?>>Non-binary</option>
              <option value="other" <?php if ($gender == 'other') { echo 'selected'; } ?>>Other</option>
              <?php if (isset($errors['gender'])) { echo '<span style="color:red">'.$errors['gender'].'</span>'; } ?><br>

              <input type="submit" value="Submit">
            </form>
          </body>
        </html>
        <?php
        // Get the buffered output and output it
        $output = ob_get_clean();
        echo $output;
      } else {
        // If there are no validation errors, write the data to a log file and display a success message
        $userData = new UserData($email, $username, $password, $gender);
        $logFile = fopen('user_data.log', 'a');
        fwrite($logFile, json_encode($userData) . "\n");
        fclose($logFile);
        echo '<h1>Thank you for submitting the form!</h1>';
      }
    } else {
      // If the form has not been submitted, display it
      ?>
      <h1>Form Validation Example</h1>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <?php if (isset($errors['email'])) { echo '<span style="color:red">'.$errors['email'].'</span>'; } ?><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" pattern="[A-Za-z0-9]{6,10}" value="<?php echo htmlspecialchars($username); ?>" required>
        <?php if (isset($errors['username'])) { echo '<span style="color:red">'.$errors['username'].'</span>'; } ?><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$" required>
        <?php if (isset($errors['password'])) { echo '<span style="color:red">'.$errors['password'].'</span>'; } ?><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
          <option value="">Please select a gender</option>
          <option value="male" <?php if ($gender == 'male') { echo 'selected'; } ?>>Male</option>
          <option value="female" <?php if ($gender == 'female') { echo 'selected'; } ?>>Female</option>
          <option value="non-binary" <?php if ($gender == 'non-binary') { echo 'selected'; } ?>>Non-binary</option>
          <option value="other" <?php if ($gender == 'other') { echo 'selected'; } ?>>Other</option>
        </select>
        <?php if (isset($errors['gender'])) { echo '<span style="color:red">'.$errors['gender'].'</span>'; } ?><br>
        <input type="submit" value="Submit">
  </form>
</body>
</html>
<?php
}
// Define a UserData class to store user data
class UserData {
  public $email;
  public $username;
  public $password;
  public $gender;
public function __construct($email, $username, $password, $gender) {
$this->email = $email;
$this->username = $username;
$this->password = $password;
$this->gender = $gender;
}
}

// If the form has been submitted and there are no validation errors, write the data to a log file
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errors)) {
$userData = new UserData($email, $username, $password, $gender);
$logFile = fopen('user_data.log', 'a');
fwrite($logFile, json_encode($userData) . "\n");
fclose($logFile);
echo '<h1>Thank you for submitting the form!</h1>';
}
?>
