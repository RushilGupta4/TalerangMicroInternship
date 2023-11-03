<?php 

  $showAlert = false;
  $showError = false;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once('partials/_json.php');

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $captcha = $_POST['g-recaptcha-response'];
    $secretKey = getenv("recaptchaSecret");
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip";

    $fire = file_get_contents($url);
    $response = json_decode($fire);

    if ($response -> success == false) {
      $showError = 'Incorrect Captcha';
    } elseif ($password !== $confirmPassword) {
      $showError = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
      $showError = 'Password must be at least 6 characters.';
    } else {
      $user = User::findByUsername($username);
      if ($user) {
        $showError = 'Username already exists.';
      } else {
        User::createUser($username, $password);
        $showAlert = true;
      }
    }
  }

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up</title>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</head>

<body>
  <?php require 'partials/_nav.php' ?>

  <?php if ($showAlert) {
      echo '
       <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your account is now created. Please login.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      ';
    } ?>

  <?php if ($showError) {
      echo '
       <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> '. $showError .'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      ';
    } ?>

  <div class="py-5"></div>

  <div class="mx-auto" style='width: 80%'>
    <div class="mt-5 mx-auto rounded border p-4 container">
      <h2 class="text-center">Sign Up</h2>
      <form action="signup.php" method="post" id="main-form" class="d-flex flex-column">
        <input class="form-control my-1" type="text" name="username" placeholder="Username" required>
        <input class="form-control my-1" type="password" name="password" placeholder="Password" required>
        <input class="form-control my-1" type="password" name="confirmPassword" placeholder="Password" required>
        <div class="g-recaptcha my-2 mx-auto" data-sitekey="6LfKsfAoAAAAAIGJ5MLEIOouRlY0Dq3T98ZXTDw-" style="transform:scale(0.85);-webkit-transform:scale(0.85);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
        <input type="submit" class="btn btn-primary" value="Submit">

      </form>
    </div>
  </div>
</body>

</html>