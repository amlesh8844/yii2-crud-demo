<?php

use yii\base\InvalidConfigException;
use yii\db\Schema;
use yii\db\Migration;
use yii\rbac\DbManager;

class m150519_090919_insert_default_user_and_auth_items extends Migration
{
    protected $schemaName = 'public';

    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }

    public function safeUp()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($authManager->ruleTable, [
            'name' => Schema::TYPE_STRING . '(256) NOT NULL',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name' => Schema::TYPE_STRING . '(256) NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'rule_name' => Schema::TYPE_STRING . '(256)',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $authManager->ruleTable . ' (name) ON DELETE SET NULL ON UPDATE CASCADE',
        ], $tableOptions);
        $prefix = str_replace('}}', '', $authManager->itemTable);
        $this->createIndex($prefix.'_type_idx}}', $authManager->itemTable, 'type');
        $this->createIndex($prefix.'_rule_name_idx}}', $authManager->itemTable, 'rule_name');

        $this->createTable($authManager->itemChildTable, [
            'parent' => Schema::TYPE_STRING . '(256) NOT NULL',
            'child' => Schema::TYPE_STRING . '(256) NOT NULL',
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable($authManager->assignmentTable, [
            'item_name' => Schema::TYPE_STRING . '(256) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (user_id) REFERENCES {{%users}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
        //$this->createIndex($authManager->assignmentTable.'_item_name_idx', $authManager->assignmentTable, 'item_name');
        //$this->createIndex($authManager->assignmentTable.'_user_id_idx', $authManager->assignmentTable, 'user_id');

        $this->execute("SELECT setval('public.{{%users_id_seq}}', 1, false)");
        $this->insert('{{%users}}', [
            'username' => 'admin',
            'password' => Yii::$app->security->generatePasswordHash('admin'),
            'email' => 'invalid',
            'firstname' => 'Administrator',
            'lastname' => 'Administrator',
            'is_active' => true,
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
        ]);
    }

    public function safeDown()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $this->execute('TRUNCATE {{%users}}, '.$authManager->itemTable.','.$authManager->assignmentTable.' RESTART IDENTITY CASCADE');
        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }
}