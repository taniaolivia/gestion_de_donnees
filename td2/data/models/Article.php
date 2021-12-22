<?php

namespace data\models;

use hellokant\model\Model;
require_once __DIR__ . '/../../src/model/Model.php';

Class Article extends Model
{
    protected static $table = 'article';
    protected static $idColumn = 'id';

    public function categorie()
    {
        return $this->belongsTo('data\models\Categorie', 'id_categ');
    }
}