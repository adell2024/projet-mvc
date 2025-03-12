<?php

// On définit la racine du projet
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

// Inclusion de la logique centrale
require_once(ROOT . 'app/Core.php');

// Récupération des paramètres dans l'URL
$params = isset($_GET['p']) ? explode('/', $_GET['p']) : [];


// Si la première partie du paramètre est non vide
if (!empty($params[0])) {
    $controller = ucfirst($params[0]) . 'Controller';  // Nom du contrôleur
    $action = isset($params[1]) ? $params[1] : 'index';  // Action par défaut (index)
    
    
    // Inclusion du contrôleur
    if (file_exists(ROOT . 'controllers/' . $controller . '.php')) {
        require_once(ROOT . 'controllers/' . $controller . '.php');
        $controllerInstance = new $controller();
        
        // Vérification que l'action existe
        if (method_exists($controllerInstance, $action)) {
            unset($params[0], $params[1]);

            call_user_func_array([$controllerInstance, $action], $params);  // Appel de l'action


        } else {
            // Action inexistante
            header('HTTP/1.0 404 Not Found');
            echo 'La méthode ' . $action . ' n\'existe pas.';
        }
    } else {
        // Contrôleur inexistant
        header('HTTP/1.0 404 Not Found');
        echo 'Le contrôleur ' . $controller . ' n\'existe pas.';
    }
} else {
    // Si aucun paramètre n'est fourni, on charge la page d'accueil
    require_once(ROOT . 'controllers/MainController.php');
    $controllerInstance = new MainController();
    $controllerInstance->index();
}
