<h2>Liste des articles</h2>
<ul>
    <?php foreach ($articles as $article): ?>
        <li><a href="/articles/read/<?php echo $article['slug']; ?>"><?php echo $article['title']; ?></a></li>
    <?php endforeach; ?>
</ul>