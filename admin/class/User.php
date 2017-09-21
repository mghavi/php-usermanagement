<?php

class User {

    private $id, $email, $password, $cookie;

    function setCookie($cookie) {
        $this->cookie = $cookie;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function getId() {
        return $this->id;
    }

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    /**
     * Check if session is started or not
     */
    function __construct() {

        if (session_status() != 2) {
            throw new Exception("Session is not started yet");
        }
    }

    function checklogin() {
        $stmt = DB::getPdo()->prepare("SELECT * FROM users WHERE email=:email AND password =:password");
        $stmt->execute([":email" => $this->email, ":password" => $this->password]);
        if ($stmt->rowCount() == 1) {
            $_SESSION['user'] = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($this->cookie == 1) {
                setcookie("id", $_SESSION['user']['user_id'], ( time() + (3600 * 24 * 365)), "/");
                setcookie("token", $this->generateCookieToken($this->password), ( time() + (3600 * 24 * 365)), "/");
            }
            return true;
        } else {
            return FALSE;
        }
    }

    function isLogin() {
        if ($this->isLoginBySession()) {
            return true;
        }
        if ($this->isLoginByCookie()) {
            return TRUE;
        }
        return false;
    }

    /**
     * check user session
     * @return bool
     */
    function isLoginBySession() {
        return (isset($_SESSION['user']));
    }

    /**
     * check if user has cookie and if he has check if it 's valid or not
     * @return boolean
     */
    function isLoginByCookie() {
        if (isset($_COOKIE['id']) && isset($_COOKIE['token'])) {
            $id = filter_input(INPUT_COOKIE, "id");
            $token = filter_input(INPUT_COOKIE, "token");
            $user = $this->getUser($id);
            if ($user && $this->checkToken($token, $user['password'])) {
                $_SESSION['user'] = $user;
                return true;
            }
            return false;
        }
        return false;
    }

    function checkToken($token, $password) {
        $validToken = $this->generateCookieToken($password);
        if ($validToken == $token) {
            return True;
        }
        return false;
    }

    /**
     * hash password for encrypting user passwords
     * @return string
     */
    function hashPassword() {
        return sha1(md5(sha1("$this->password")));
    }

    function generateCookieToken($password) {
        return sha1(sha1(md5("$password")));
    }

    function getUser($id) {
        $stmt = Db::getPdo()->prepare("SELECT * FROM users WHERE user_id =:id");
        $stmt->execute([":id" => $id]);
        if ($stmt->rowCount()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return FALSE;
    }

}
