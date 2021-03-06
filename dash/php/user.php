<?php
include 'password.php';

class User extends Password
{

    private $_db;

    public function __construct($db)
    {
        parent::__construct();

        $this->_db = $db;
    }

    private function get_user_hash($username)
    {

        try {
            $stmt = $this->_db->prepare('SELECT * FROM members WHERE username = :username AND active="Yes" ');
            $stmt->execute(array('username' => $username));

            return $stmt->fetch();

        } catch (PDOException $e) {
            echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
        }
    }

    public function login($username, $password)
    {

        $row = $this->get_user_hash($username);

        if ($this->password_verify($password, $row['password']) == 1) {

            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['memberID'] = $row['memberID'];
            $_SESSION['type'] = $row['type'];
            return true;

        }
    }

    public function logout()
    {
        session_destroy();
        session_abort();
        session_status();
    }



    public function is_logged_in()
    {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            return true;
        }
    }

}
