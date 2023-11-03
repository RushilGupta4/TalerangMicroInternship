<?php
class User {
    public $id;
    public $username;
    public $password;

    public function save() {
        $users = json_decode(file_get_contents('json/users.json'), true);
        $users[] = [
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password,
        ];
        file_put_contents('json/users.json', json_encode($users));
    }

    public static function authenticate($username, $password) {
        $users = json_decode(file_get_contents('json/users.json'), true);
        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                $authenticatedUser = new User();
                $authenticatedUser->id = $user['id'];
                $authenticatedUser->username = $user['username'];
                $authenticatedUser->password = $user['password'];
                return $authenticatedUser;
            }
        }
        return false;
    }

    public static function findByUsername($username) {
        $users = json_decode(file_get_contents('json/users.json'), true);
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $foundUser = new User();
                $foundUser->id = $user['id'];
                $foundUser->username = $user['username'];
                $foundUser->password = $user['password'];
                return $foundUser;
            }
        }
        return false;
    }

    public static function findById($id) {
        $users = json_decode(file_get_contents('json/users.json'), true);
        foreach ($users as $user) {
            if ($user['id'] == $id) {
                $foundUser = new User();
                $foundUser->id = $user['id'];
                $foundUser->username = $user['username'];
                $foundUser->password = $user['password'];
                return $foundUser;
            }
        }
        return false;
    }


    public static function createUser($username, $password){
        $users = json_decode(file_get_contents('json/users.json'), true);

        // Create a new user object
        $newUser = new User();
        $newUser->id = count($users); // Set the user ID
        $newUser->username = $username; // Set the username
        $newUser->password = password_hash($password, PASSWORD_DEFAULT); // Set the password

        // Save the user to the database
        $newUser->save();
    }

}
?>