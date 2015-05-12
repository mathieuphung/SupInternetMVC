<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 23/04/2015
 * Time: 23:45
 */

namespace Website\Controller;

/**
 * Class UserController
 *
 * Controller of all User actions
 *
 * @package Website\Controller
 */
class UserController extends AbstractBaseController{
    public $conn;

    public function __construct() {
        $this->conn = AbstractBaseController::getConnection();
    }
    /**
     * Recup all users and print it
     *
     * @return array
     */
    public function listUserAction(){
        $statement = $this->conn->prepare('SELECT * FROM users');
        $statement->execute();
        $users = $statement->fetchAll();
        /******/
        //you can return a Response object
        return [
            'view' => 'WebSite/View/user/listUser.html.php', // should be Twig : 'WebSite/View/user/listUser.html.twig'
            'users' => $users
        ];
    }


    /**
     * swho one user thanks to his id : &id=...
     *
     * @return array
     */
    public function showUserAction($request) {
        $statement = $this->conn->prepare('SELECT * FROM users  WHERE login = ?');
        $statement->execute(array($request['request']));
        $user = $statement->fetch();
        //you can return a Response object
        return [
            'view' => 'WebSite/View/user/showUser.html.php', // should be Twig : 'WebSite/View/user/listUser.html.twig'
            'user' => $user
        ];
    }

    /**
     * Add User and redirect on listUser after
     */
    public function addUserAction($request) {
        if ($request['request']) {
            $statement = $this->conn->prepare('INSERT INTO users(firstname, lastname, number, adress, email, login, password) VALUES (?,?,?,?,?,?,?)');
            $statement->execute($request['request']);
            //Redirect to show
            //you should return a RedirectResponse object
            return [
                'redirect_to' => 'http://localhost/Projet/SupInternetMVC/web/',// => manage it in index.php !! URL should be generate by Routing functions thanks to routing config

            ];
        }
        //you should return a Response object
        return [
            'view' => 'WebSite/View/user/addUser.html.php',// => create the file
            'user' => $user
        ];
    }


    /**
     * Delete User and redirect on listUser after
     */
    public function deleteUserAction($request) {
        if($request['request']) {
            $statement = $this->conn->prepare('DROP USER \'?\'@\'localhost\'');
            $statement->execute($request['request']);
        }
        //you should return a RedirectResponse object , redirect to list
        return [
            'redirect_to' => 'http://localhost/Projet/SupInternetMVC/web/',// => manage it in index.php !! URL should be generate by Routing functions thanks to routing config

        ];
    }

    /**
     * Log User (Session) , add session in $request first (index.php)
     */
    public function logUserAction($request) {
        if ($request['request']) {
            $statement = $this->conn->prepare('SELECT * FROM users  WHERE login = ?');
            $statement->execute(array($request['request']));
            $user = $statement->fetch();

            if($user) {

                //take FlashBag system from
                // https://github.com/NicolasBadey/SupInternetTweeter/blob/master/model/functions.php
                /**
                 * ajoute un message en session
                 *
                 * @param $type
                 * @param $message
                 */
                function addMessageFlash($type, $message)
                {
                    // autorise que 4 types de messages flash
                    $types = ['success','error','alert','info'];
                    if (!in_array($type, $types)) {
                        return false;
                    }
                    // on vérifie que le type existe
                    if (!isset($_SESSION['flashBag'][$type])) {
                        //si non on le créé avec un Array vide
                        $_SESSION['flashBag'][$type] = [];
                    }
                    // on ajoute le message
                    $_SESSION['flashBag'][$type][] = $message;
                }
                // line 87 : https://github.com/NicolasBadey/SupInternetTweeter/blob/master/index.php
                // Affiche les flashBag : des messages informatif du genre "votre message a bien été envoyé"
                if (isset($_SESSION['flashBag'])) {
                    foreach ($_SESSION['flashBag'] as $type => $flash) {
                        foreach ($flash as $key => $message) {
                            echo '<div class="'.$type.'" role="'.$type.'" >'.$message.'</div>';
                            // un fois affiché le message doit être supprimé
                            unset($_SESSION['flashBag'][$type][$key]);
                        }
                    }
                }
                // and manage error and success

                return [
                    'redirect_to' => 'http://localhost/Projet/SupInternetMVC/web/',// => manage it in index.php !! URL should be generate by Routing functions thanks to routing config
                    //Redirect to list or home
                    //you should return a RedirectResponse object
                ];
            }
        }
    }
}