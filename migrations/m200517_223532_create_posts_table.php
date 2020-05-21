<?php

use yii\db\Migration;

/**
 * Handles the creation of table `posts`.
 */
class m200517_223532_create_posts_table extends Migration
{
    private const tableName = 'posts';
    
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->createTable(self::tableName, [
            'id' => $this->char(36)->notNull(),
            'title' => $this->string(64)->notNull(),
            'slug' => $this->string(64)->notNull(),
            'body' => $this->text()->notNull(),
            'created_by' => $this->char(36)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
        
        $this->addPrimaryKey('pk_posts', self::tableName, ['id']);
        
        $this->addForeignKey('fk_posts_created_by_users', self::tableName, ['created_by'], 'users', ['id'],
            'RESTRICT', 'CASCADE');
        
        $this->createIndex('idx_posts_title', self::tableName, ['title'], true);
        $this->createIndex('idx_posts_slug', self::tableName, ['slug'], true);
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_posts_title', self::tableName);
        $this->dropIndex('idx_posts_slug', self::tableName);
        $this->dropForeignKey('fk_posts_created_by_users', self::tableName);
        $this->dropPrimaryKey('pk_posts', self::tableName);
        $this->dropTable(self::tableName);
    }
}
