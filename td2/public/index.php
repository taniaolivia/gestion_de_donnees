<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . '/../src/connection/ConnectionFactory.php';
require_once __DIR__ . '/../src/query/Query.php';
require_once __DIR__ . '/../data/models/Article.php';
require_once __DIR__ . '/../data/models/Categorie.php';

use hellokant\connection\ConnectionFactory;
use hellokant\query\Query;
use data\models\Article;
use data\models\Categorie;


$conf = parse_ini_file("../src/conf/conf.ini");
ConnectionFactory::makeConnection($conf);

/**
 * TEST MODEL
 *  tests : insert
 */

/*$a = new Article();
$a->nom = 'velorouge';
$a->descr = 'un beau vélo tout bleu et rouge';
$a->tarif = 200;
$a->id_categ = 1;
$a->insert();

$id = $a->id;

echo '<br>'.'Article inséré avec id : '. $id . '<br>';*/

/**
 *  TEST MODEL
 *  tests : insert
 */

/*$delete = Article::one(66);
$delete->delete();

$id = $delete->id;

echo '<br>'.'Article supprimé avec id : '. $id . '<br>';*/

/**
 * TEST MODEL
 *  tests : finder
 */

$liste = Article::all() ;
print '<b><u>Liste des articles</u></b><br><br>';
foreach ($liste as $article)
{
    print '<li>'.$article->nom.'</li>';
}

$l = Article::find(64);
$article = $l[0] ;
print '<p>'.'Nom : '.$article->nom.'<br>'.'Description : '.$article->descr.
      '<br>'.'Tarif : '.$article->tarif.'<br>'.'ID Categorie : '.$article->id_categ.'</p>';

$l = Article::find(['nom','tarif'], 64) ;
$article = $l[0] ;
print '<p>'.'Nom : '.$article->nom.'<br>'.'Tarif : '.$article->tarif.'</p>';


$articles = Article::find(['nom', 'tarif'], ['tarif', '<=', 100 ]) ;
print '<b><u>Liste des articles (tarif est inférieur ou égal à 100)</u></b><br><br>';
foreach ($articles as $article)
{
    print '<li>'.'Nom : '.$article->nom.' , '.'Tarif : '.$article->tarif.'</li>';
}


$article = Article::first(64);
print '<p>'.'Nom : '.$article->nom.'<br>'.'Description : '.$article->descr.
    '<br>'.'Tarif : '.$article->tarif.'<br>'.'ID Categorie : '.$article->id_categ.'</p>';


$article = Article::first(['tarif', '<=', 100 ]);
print '<p>'.'Nom : '.$article->nom.'<br>'.'Description : '.$article->descr.
    '<br>'.'Tarif : '.$article->tarif.'<br>'.'ID Categorie : '.$article->id_categ.'</p>';


/*echo "<br><br>";
  $articles = Article::find(['nom', 'tarif'], [['nom','like','%velo%'], ['tarif', '<=', 100]]);
  var_dump($articles);*/

/**
 * TEST MODEL
 *  tests : gestion des associations
 */

/*$a = Article::first(64);
$categorie = $a->belongs_to('data\models\Categorie', 'id_categ');
var_dump($categorie);
echo "<br><br>";

$m = Categorie::first(1) ;
$list_article = $m->hasMany('data\models\Article', 'id_categ');
var_dump($list_article);
echo "<br><br>";

$categorie = Article::first(64)->categorie();
var_dump($categorie);
echo "<br><br>";

$list = Categorie::first(1)->articles();
var_dump($list);
echo "<br><br>";

$a = Article::first(64);
$categorie2 = $a->categorie;
var_dump($categorie2);
echo "<br><br>";

$c = Categorie::first(1);
$list_articles = $c->articles;
var_dump($list_articles);
echo "<br><br>";

$c = Categorie::first(1) ;
$list_articles = $c->articles ;
var_dump($list_articles);
echo "<br><br>";

$a = Article::first(65) ;
$categorie = $a->categorie ;
var_dump($categorie);
echo "<br><br>";*/


/**
 * TEST MODEL
 *  tests : query
 */
/*$q = Query::table('article')
    ->select(['id', 'nom', 'descr', 'tarif'])
    ->where('tarif', '<', 1000);
$res = $q->get();

var_dump($res);
echo "<br><br>";


$id = Query::table('article')
    ->insert(['nom'=>'velo', 'tarif'=>200, 'id_categ'=>1]);

echo '<br>'.'article inséré id : '. $id . '</br>';

$q = Query::table('article')
    ->select(['id', 'nom', 'descr', 'tarif'])
    ->where('tarif', '<', 1000)
    ->get();

var_dump($q);*/