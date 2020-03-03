<?php

use yii\db\Migration;

class m200223_120000_articles extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        #Новости --------------------------------------------------------------------------------------------------
        $this->createTable('{{%article}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Hide'),
            'created' => $this->integer()->notNull()->comment('Creation timestamp'),
            'updated' => $this->integer()->notNull()->comment('Update timestamp'),
            'create_user_id' => $this->integer()->null()->comment('Creator'),
            'update_user_id' => $this->integer()->null()->comment('Updated'),
            'slug' => $this->string(400)->notNull()->comment('Url slug'),
            'title' => $this->string(255)->notNull()->comment('Title'),
            'title_seo' => $this->string(255)->notNull()->comment('Page title'),
            'description_seo' => $this->string(400)->null()->comment('Meta Description'),
            'announce' => $this->text()->null()->comment('Announce'),
            'body' => $this->text()->null()->comment('Main text'),
            'publish_date' => $this->integer()->notNull()->comment('Publish date'),
            'page_id' => $this->integer()->null()->comment('Page'),
            'index_page' => $this->integer(1)->null()->defaultValue(1)->comment('Show on index page'),
            'tsvector' => $this->getDb()->getSchema()->createColumnSchemaBuilder('tsvector')->notNull(),
            'lang' => $this->string(2)->notNull()->defaultValue('en')
        ]);

        $this->createIndex("idx-article-status", "{{%article}}", "status");
        $this->createIndex("idx-article-publish_date", "{{%article}}", "publish_date");
        $this->createIndex("idx-article-slug", "{{%article}}", "slug");
        $this->createIndex("idx-article-index_page", "{{%article}}", "index_page");
        $this->createIndex("idx-article-page_id", "{{%article}}", "page_id");
        $this->createIndex("idx-article-lang", "{{%article}}", "lang");
        $this->createIndex("idx-article-created", "{{%article}}", "created");
        $this->createIndex("idx-article-updated", "{{%article}}", "updated");
        $this->createIndex("idx-article-create_user_id", "{{%article}}", "create_user_id");
        $this->createIndex("idx-article-update_user_id", "{{%article}}", "update_user_id");

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable("{{%article}}");
    }


}
