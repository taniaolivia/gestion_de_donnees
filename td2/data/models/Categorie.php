<?php

namespace data\models;

use hellokant\model\Model;

Class Categorie extends Model
{
    protected static $table = 'categorie';
    protected static $idColumn = 'id';

    public function articles()
    {
        return $this->hasMany('data\models\Article', 'id_categ');
    }
}