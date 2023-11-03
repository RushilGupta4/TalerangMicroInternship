<?php 
    include_once('partials/_json.php');
    $cookie = 'user_id';
    $loggedIn = 0;

    if(isset($_COOKIE[$cookie])) {
        $id = $_COOKIE[$cookie];
        $user = User::findById($id);
        if ($user) {
            $loggedIn = 1;
        }
    }

    if (!in_array($_SERVER['REQUEST_URI'], ["/home.php", "/logout.php"]) && $loggedIn) {
        header('Location: /home.php');
    }

    if (in_array($_SERVER['REQUEST_URI'], ["/home.php", "/logout.php"]) && !$loggedIn) {
        header('Location: /login.php');
    }

?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid px-4 py-3">
        <a style="max-width: 50%; height: auto;" aria-current="page" href="/home.php">
            <img class="img-fluid" src="public/logo.png"></img>
        </a>

        <div class="d-flex gap-5">
            <?php 
            if (!$loggedIn) {
                echo '<a class="nav-link" href="/login.php">Login</a><a class="nav-link" href="/signup.php">Sign Up</a>';
            } else {
                echo '<a class="nav-link" href="/logout.php">Logout</a>';
            }  
            ?>
        </div>
    </div>
</nav>