<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\migrations;

use buzzingpixel\craftstatic\Craftstatic;
use craft\db\Migration;
use craft\elements\Entry;
use DateTimeImmutable;
use DateTimeZone;
use Throwable;
use function array_merge;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

class m190720_180630_CheckExistingEntriesForTracking extends Migration
{
    /**
     * @throws Throwable
     *
     * @inheritdoc
     */
    public function safeUp() : bool
    {
        $currentTime = new DateTimeImmutable('now', new DateTimeZone('UTC'));

        $expiringEntries = Entry::find()
            ->anyStatus()
            ->expiryDate('> ' . $currentTime->format('Y-m-d'))
            ->all();

        $futureEntries = Entry::find()
            ->anyStatus()
            ->postDate('> ' . $currentTime->format('Y-m-d'))
            ->all();

        $entries = array_merge($expiringEntries, $futureEntries);

        $processedIds = [];

        foreach ($entries as $entry) {
            if (isset($processedIds[$entry->id])) {
                continue;
            }

            Craftstatic::$plugin->getProcessEntryTracking()->process($entry);

            $processedIds[$entry->id] = $entry->id;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown() : bool
    {
        return true;
    }
}
