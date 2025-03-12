<?php

// Définition de la racine du projet
define('ROOT', rtrim(str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']), '/') . DIRECTORY_SEPARATOR);

// Inclusion de la logique centrale
require_once(ROOT . 'app' . DIRECTORY_SEPARATOR . 'Core.php');

// Récupération des paramètres dans l'URL
$params = isset($_GET['p']) ? explode('/', trim($_GET['p'], '/')) : [];

// Vérification de la présence du contrôleur
$controllerName = !empty($params[0]) ? ucfirst($params[0]) . 'Controller' : 'MainController';
$controllerFile = ROOT . 'controllers' . DIRECTORY_SEPARATOR . $controllerName . '.php';
$action = $params[1] ?? 'index';

// Chargement du contrôleur si disponible
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    // Vérification que la classe du contrôleur existe
    if (class_exists($controllerName)) {
        $controllerInstance = new $controllerName();

        // Vérification que la méthode existe et est accessible
        if (method_exists($controllerInstance, $action) && is_callable([$controllerInstance, $action])) {
            $arguments = array_slice($params, 2); // Récupération des arguments après le contrôleur et l'action
            call_user_func_array([$controllerInstance, $action], $arguments);
        } else {
            // Méthode non trouvée
            http_response_code(404);
            echo "Erreur 404 : L'action <strong>{$action}</strong> est introuvable.";
        }
    } else {
        // Classe du contrôleur inexistante
        http_response_code(500);
        echo "Erreur 500 : La classe <strong>{$controllerName}</strong> n'existe pas.";
    }
} else {
    // Fichier du contrôleur inexistant
    http_response_code(404);
    echo "Erreur 404 : Le contrôleur <strong>{$controllerName}</strong> est introuvable.";
}
