<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\migrations;

use craft\db\Migration;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

class m190720_150847_CreateTrackingTable extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp() : bool
    {
        if ($this->getDb()->tableExists('{{%craftstatictracking}}')) {
            return true;
        }

        $this->createTable('{{%craftstatictracking}}', [
            'id' => $this->primaryKey(),
            'elementId' => $this->bigInteger()->unsigned(),
            'type' => $this->tinyText()->notNull(),
            'cacheBustOnUtcDate' => $this->dateTime()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown() : bool
    {
        $this->dropTableIfExists('{{%craftstatictracking}}');

        return true;
    }
}
