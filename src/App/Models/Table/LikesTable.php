<?php
namespace App\Models\Table;

use Core\Table;

class LikesTable extends Table {
    /**
     * Initialize table and associations.
     *
     * @param array $config Configuration options.
     * @return void
     */
    public function initialize(array $config) {
        $this->setTable('likes');
        $this->setPrimaryKey('id');
        $this->setEntityClass(\App\Models\Entity\Like::class);

        $this->belongsTo('Users');
        $this->belongsTo('Images');
    }
}
