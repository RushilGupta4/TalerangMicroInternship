<?php
  $cookie = 'user_id';

  if(isset($_COOKIE[$cookie])) {
      setcookie($cookie, null, -1, '/');
  }
  header('Location: /login.php');
?>