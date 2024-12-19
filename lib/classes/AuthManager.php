<?php

class AuthManager {
    private $databaseManager;

    public function __construct($databaseManager) {
        $this->databaseManager = $databaseManager;
    }

    public function requiresRegistration() {
        if(!$this->databaseManager->tableExists('users')){return true;}
        $stmt = $this->databaseManager->getPDO()->query("SELECT COUNT(*) FROM users");
        $userCount = $stmt->fetchColumn();
        return $userCount === 0;
    }

    public function isUserLoggedIn(){
        if(!$this->databaseManager->tableExists('users')){return false;}
        $timeout = 3600; //60 minutes
        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
            if ($_SESSION['IP'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['UserAgent'] !== $_SERVER['HTTP_USER_AGENT']) {
                session_unset();
                session_destroy();
                return false;
            }
            if (time() - $_SESSION['lastPing'] > $timeout) {
                session_unset();
                session_destroy();
                return false;
            }
            $_SESSION['lastPing'] = time();
            return true;
        }
        return false;
    }

    public function register($username, $password){
        try{
            $this->databaseManager->initialize();
            $this->databaseManager->createUsersTable();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $stmt = $this->databaseManager->getPDO()->prepare($query);
            $stmt->execute([
                ':username' => $username,
                ':password' => $hashedPassword,
            ]);
            $userId = $this->databaseManager->getPDO()->lastInsertId();
            $this->startSession($userId);
            return true;
        }catch(PDOException $e){
            return false;
        }
    }

    public function login($username, $password){
        try{
            $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $stmt = $this->databaseManager->getPDO()->prepare($query);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {return false;}
            if (password_verify($password, $user['password'])) {
                $this->startSession($user['id']);
                return true;
            }
            return false;
        }catch(PDOException $e){
            return false;
        }
    }

    public function logout(){
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            if (!session_destroy()) {
                return false;
            }
            return true;
        }
        return false;
    }

    private function startSession($userId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['loggedIn'] = true;
        $_SESSION['userId'] = $userId;
        $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['UserAgent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['lastPing'] = time();
    }
}

?>
