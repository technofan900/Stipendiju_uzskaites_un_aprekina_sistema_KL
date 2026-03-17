<?php

namespace Core;
use Core\App;
use Core\Database;

class Authenticator {

    public function attempt($email, $password) {
        $user = App::resolve(Database::class)->query('SELECT * FROM users WHERE email = :email', [
            'email' => $email
        ])->find();

    if ($user && password_verify($password, $user['password'])){

        $this->login([
            'id' => $user['id'],
            'email' => $user['email']
            ]);

            return true;
        }

        return false;
    }

    public function login ($user) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email']
        ];
        session_regenerate_id(true);
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']); 
    }
    

}