<?php

use yii\db\Migration;

class m180403_114045_articles extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        #Новости --------------------------------------------------------------------------------------------------
        $this->createTable('{{%articles}}', [
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

        $this->createIndex("idx-articles-status", "{{%articles}}", "status");
        $this->createIndex("idx-articles-publish_date", "{{%articles}}", "publish_date");
        $this->createIndex("idx-articles-slug", "{{%articles}}", "slug");
        $this->createIndex("idx-articles-index_page", "{{%articles}}", "index_page");
        $this->createIndex("idx-articles-page_id", "{{%articles}}", "page_id");
        $this->createIndex("idx-articles-lang", "{{%articles}}", "lang");
        $this->createIndex("idx-articles-created", "{{%articles}}", "created");
        $this->createIndex("idx-articles-updated", "{{%articles}}", "updated");
        $this->createIndex("idx-articles-create_user_id", "{{%articles}}", "create_user_id");
        $this->createIndex("idx-articles-update_user_id", "{{%articles}}", "update_user_id");

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable("{{%articles}}");
    }


}
