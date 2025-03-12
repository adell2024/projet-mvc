<?php

class Article extends Model
{
    public function __construct()
    {
        $this->table = 'articles';  // Spécifier la table
        parent::__construct();
    }
}
