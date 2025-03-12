<?php

class ArticlesController extends Controller
{
    // Afficher la liste des articles
    public function index()
    {
        $this->loadModel('Article');
        $articles = $this->Article->getAll();
        $this->render('index', compact('articles'));
    }

    // Afficher un article spécifique à partir de son slug
    public function read($slug)
    {
        $this->loadModel('Article');
        $article = $this->Article->findBySlug($slug);
        if ($article) {
            $this->render('read', compact('article'));
        } else {
            header('HTTP/1.0 404 Not Found');
            echo 'L\'article n\'existe pas.';
        }
    }
}
