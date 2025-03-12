<?php

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'mvc');
define('DB_USER', 'root');
define('DB_PASS', 'azerty');


// Classe de base pour le modèle
abstract class Model
{
    protected static $_connexion;
    protected $table;

    public function __construct()
    {
        $this->connect();
    }

    // Connexion unique à la base de données
    protected function connect()
    {
        if (!self::$_connexion) {
            try {
                self::$_connexion = new PDO(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
    }

    // Récupère tous les éléments de la table
    public function getAll()
    {
        if (empty($this->table)) {
            throw new Exception("Aucune table définie pour le modèle.");
        }

        $sql = "SELECT * FROM " . $this->table;
        $stmt = self::$_connexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupère un élément en fonction de son slug
    public function findBySlug($slug)
    {
        if (empty($this->table)) {
            throw new Exception("Aucune table définie pour le modèle.");
        }

        $sql = "SELECT * FROM " . $this->table . " WHERE slug = :slug";
        $stmt = self::$_connexion->prepare($sql);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
}

// Classe de base pour le contrôleur
abstract class Controller
{
    // Affiche la vue
    public function render($view, $data = [])
    {
        extract($data);
        $controllerName = strtolower(str_replace('Controller', '', get_class($this)));
        $viewFile = ROOT . 'views/' . $controllerName . '/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("Vue introuvable : " . $viewFile);
        }

        ob_start();
        require_once($viewFile);
        $content = ob_get_clean();

        require_once(ROOT . 'views/layout/default.php');
    }

    // Charge un modèle
    public function loadModel($model)
    {
        $modelFile = ROOT . 'models/' . $model . '.php';

        if (!file_exists($modelFile)) {
            throw new Exception("Modèle introuvable : " . $modelFile);
        }

        require_once($modelFile);
        $this->$model = new $model();
    }
}
