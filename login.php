<?php 
  $showError = false;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once('partials/_json.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $captcha = $_POST['g-recaptcha-response'];
    $secretKey = "6LfKsfAoAAAAAGSh73SfVJzmIrLTlQUWcVyoM5c4";
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip";

    $fire = file_get_contents($url);
    $response = json_decode($fire);

    if ($response -> success == false) {
      $showError = 'Incorrect Captcha';
    } else {
      $authenticated = User::authenticate($username, $password);

      if ($authenticated) {
        $user = User::findByUsername($username);
        setcookie("user_id", $user -> id, time() + (86400 * 30), "/");
        header('Location: /home.php');
      } else {
        $showError = 'Invalid credentials.';
      }
    }
  }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</head>

<body>
    <?php require 'partials/_nav.php' ?>

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
            <h2 class="text-center">Login</h2>
            <form action="login.php" method="post" id="main-form" class="d-flex flex-column">
                <input class="form-control my-1" type="text" name="username" placeholder="Username">
                <input class="form-control my-1" type="password" name="password" placeholder="Password"
                    required>
               <div class="g-recaptcha my-2 mx-auto" data-sitekey="6LfKsfAoAAAAAIGJ5MLEIOouRlY0Dq3T98ZXTDw-" style="transform:scale(0.85);-webkit-transform:scale(0.85);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
              <input type="submit" class="btn btn-primary" value="Submit">
            </form>
        </div>
    </div>
</body>

</html>