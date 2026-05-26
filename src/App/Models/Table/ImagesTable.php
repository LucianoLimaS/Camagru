<?php
namespace App\Models\Table;

use Core\Table;

class ImagesTable extends Table {
    /**
     * Initialize table and associations.
     *
     * @param array $config Configuration options.
     * @return void
     */
    public function initialize(array $config) {
        $this->setTable('images');
        $this->setPrimaryKey('id');
        $this->setEntityClass(\App\Models\Entity\Image::class);

        $this->belongsTo('Users');
        $this->hasMany('Likes');
        $this->hasMany('Comments');
    }
}
