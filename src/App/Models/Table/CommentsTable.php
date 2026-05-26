<?php
namespace App\Models\Table;

use Core\Table;

class CommentsTable extends Table {
    /**
     * Initialize table and associations.
     *
     * @param array $config Configuration options.
     * @return void
     */
    public function initialize(array $config) {
        $this->setTable('comments');
        $this->setPrimaryKey('id');
        $this->setEntityClass(\App\Models\Entity\Comment::class);

        $this->belongsTo('Users');
        $this->belongsTo('Images');
    }
}
