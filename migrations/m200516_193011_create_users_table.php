<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m200516_193011_create_users_table extends Migration
{
    private const tableName = 'users';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::tableName, [
            'id' => $this->char(36)->notNull(),
            'username' => $this->string(32)->notNull(),
            'email' => $this->string(255)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'auth_key' => $this->string(255)->notNull(),
            'access_token' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);
        
        $this->createIndex('idx_users_username', self::tableName, ['username'], true);
        $this->createIndex('idx_users_email', self::tableName, ['email'], true);
        $this->createIndex('idx_users_auth_key', self::tableName, ['auth_key'], true);
        $this->createIndex('idx_users_access_token', self::tableName, ['access_token'], true);
        
        $this->addPrimaryKey('pk_users', self::tableName, ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_users_username', self::tableName);
        $this->dropIndex('idx_users_email', self::tableName);
        $this->dropIndex('idx_users_auth_key', self::tableName);
        $this->dropIndex('idx_users_access_token', self::tableName);
        
        $this->dropPrimaryKey('pk_users', self::tableName);
        
        $this->dropTable(self::tableName);
    }
}
