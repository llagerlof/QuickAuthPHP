<?php
/**
 * Quick Auth PHP 1.1
 *
 * Quick Auth PHP is a script to quickly add web authentication for multiple users. No database required.
 *
 * @package    QuickAuthPHP
 * @author     Lawrence Lagerlof <llagerlof@gmail.com>
 * @copyright  2018 Lawrence Lagerlof
 * @link       http://github.com/llagerlof/QuickAuthPHP
 * @license    https://tldrlegal.com/license/mit-license MIT
 */

// If this file wasn't included by another script, exit.
$included_files = get_included_files();
if (($included_files[0] == __FILE__) || empty($included_files)){
    die();
}

// If a session wasn't started, start one.
if (!isset($_SESSION) || (session_id() == '')) {
    session_start();
}

// If the variable "logout" is passed with value 1, logout.
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    unset($_SESSION['AUTH_user_authenticated']);
    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);
    exit;
}

// If username and password was POSTed, teste it against the password file.
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Find the password file in path defined in auth.conf.
    if (file_exists(dirname(__FILE__). '/auth.conf')) {
        $config = parse_ini_file(dirname(__FILE__) . '/auth.conf');
        $passwordfile = isset($config['passwordfile']) ? $config['passwordfile'] : dirname(__FILE__) . '/auth.pass';
    } else {
        // If there isn't no configuration file, look for the password file auth.pass in the same directory of this lib.
        $passwordfile = dirname(__FILE__). '/auth.pass';
    }
    // Password file don't exist. Alert user.
    if (!file_exists($passwordfile)) {
        echo '<p>missing password file [E2000]</p>';
        die();
    }

    // Parse the password file, testing for the valid credentials.
    $users = file($passwordfile, FILE_IGNORE_NEW_LINES);
    foreach ($users as $line) {
        $user = explode(',', $line);
        // Check the consistency of the current line from password file.
        if (count($user) != 2) {
            echo '<p>bad password file format [E2005]</p>';
            die();
        }
        if ((isset($user[0]) && (trim($user[0]) == '') ) || (isset($user[1]) && (trim($user[1]) == ''))) {
            echo '<p>bad password file format [E2010]</p>';
            die();
        }
        $username = $user[0];
        $password = $user[1];
        if ($_POST['username'] == $username) {
            if (password_verify($_POST['password'], $password)) {
                $_SESSION['AUTH_user_authenticated'] = $username;
                header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);
                exit;
            }
        }
    }
}

// If the user isn't authenticated, show login form and exit.
if (!isset($_SESSION['AUTH_user_authenticated'])) {
    echo '<form action="index.php" method="POST"><input type="text" name="username" placeholder="username"><input type="password" name="password" placeholder="password"><input type="submit" value="login"></form>';
    die();
}
