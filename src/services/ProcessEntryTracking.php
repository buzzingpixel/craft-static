<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\services;

use buzzingpixel\craftstatic\Craftstatic;
use buzzingpixel\craftstatic\factories\QueryFactory;
use craft\base\Component;
use craft\db\Connection as DbConnection;
use craft\elements\Entry;
use craft\helpers\StringHelper;
use DateTimeImmutable;
use DateTimeZone;
use Throwable;

class ProcessEntryTracking extends Component
{
    /** @var DbConnection */
    private $dbConnection;
    /** @var QueryFactory */
    private $queryFactory;

    /** @var DateTimeZone */
    private $utcTimezone;
    /** @var DateTimeImmutable */
    private $currentTime;

    /**
     * @param mixed[] $config
     *
     * @throws Throwable
     */
    public function __construct(array $config = [])
    {
        parent::__construct();

        foreach ($config as $key => $val) {
            $this->{$key} = $val;
        }

        $this->utcTimezone = new DateTimeZone('UTC');

        $this->currentTime = new DateTimeImmutable('now', $this->utcTimezone);
    }

    /**
     * @throws Throwable
     */
    public function process(Entry $entry) : void
    {
        $this->processPostDate($entry);
        $this->processExpiryDate($entry);
    }

    /**
     * @throws Throwable
     */
    private function processPostDate(Entry $entry) : void
    {
        if (! $entry->postDate) {
            $this->dbConnection->createCommand()->delete(
                '{{%craftstatictracking}}',
                [
                    'elementId' => $entry->id,
                    'type' => 'future_entry',
                ]
            )
            ->execute();

            return;
        }

        $postDate = DateTimeImmutable::createFromFormat(
            Craftstatic::DATE_TIME_PRECISION_FORMAT,
            $entry->postDate->format(Craftstatic::DATE_TIME_PRECISION_FORMAT)
        );

        if (! $postDate) {
            $this->dbConnection->createCommand()->delete(
                '{{%craftstatictracking}}',
                [
                    'elementId' => $entry->id,
                    'type' => 'future_entry',
                ]
            )
                ->execute();

            return;
        }

        $postDate = $postDate->setTimezone($this->utcTimezone);

        if ($this->currentTime->getTimestamp() >= $postDate->getTimestamp()) {
            $this->dbConnection->createCommand()->delete(
                '{{%craftstatictracking}}',
                [
                    'elementId' => $entry->id,
                    'type' => 'future_entry',
                ]
            )
            ->execute();

            return;
        }

        $existingId  = null;
        $dateCreated = $dateUpdated = $this->currentTime->format(Craftstatic::MYSQL_DATE_TIME_FORMAT);
        $uid         = StringHelper::UUID();

        $existingQuery = $this->queryFactory->createQuery()
            ->from('{{%craftstatictracking}}')
            ->where('`elementId` = ' . $entry->id)
            ->andWhere('`type` = "future_entry"')
            ->one();

        if ($existingQuery) {
            $existingId  = $existingQuery['id'];
            $dateCreated = $existingQuery['dateCreated'];
            $uid         = $existingQuery['uid'];
        }

        $this->dbConnection->createCommand()->upsert(
            '{{%craftstatictracking}}',
            [
                'id' => $existingId,
                'elementId' => $entry->id,
                'type' => 'future_entry',
                'cacheBustOnUtcDate' => $postDate->format(Craftstatic::MYSQL_DATE_TIME_FORMAT),
                'dateCreated' => $dateCreated,
                'dateUpdated' => $dateUpdated,
                'uid' => $uid,
            ],
            true,
            [],
            false
        )
        ->execute();
    }

    /**
     * @throws Throwable
     */
    private function processExpiryDate(Entry $entry) : void
    {
        if (! $entry->expiryDate) {
            $this->dbConnection->createCommand()->delete(
                '{{%craftstatictracking}}',
                [
                    'elementId' => $entry->id,
                    'type' => 'expiring_entry',
                ]
            )
            ->execute();

            return;
        }

        $expiryDate = DateTimeImmutable::createFromFormat(
            Craftstatic::DATE_TIME_PRECISION_FORMAT,
            $entry->expiryDate->format(Craftstatic::DATE_TIME_PRECISION_FORMAT)
        );

        if (! $expiryDate) {
            $this->dbConnection->createCommand()->delete(
                '{{%craftstatictracking}}',
                [
                    'elementId' => $entry->id,
                    'type' => 'expiring_entry',
                ]
            )
                ->execute();

            return;
        }

        $expiryDate = $expiryDate->setTimezone($this->utcTimezone);

        if ($expiryDate->getTimestamp() <= $this->currentTime->getTimestamp()) {
            $this->dbConnection->createCommand()->delete(
                '{{%craftstatictracking}}',
                [
                    'elementId' => $entry->id,
                    'type' => 'expiring_entry',
                ]
            )
            ->execute();

            return;
        }

        $existingId  = null;
        $dateCreated = $dateUpdated = $this->currentTime->format(Craftstatic::MYSQL_DATE_TIME_FORMAT);
        $uid         = StringHelper::UUID();

        $existingQuery = $this->queryFactory->createQuery()
            ->from('{{%craftstatictracking}}')
            ->where('`elementId` = ' . $entry->id)
            ->andWhere('`type` = "expiring_entry"')
            ->one();

        if ($existingQuery) {
            $existingId  = $existingQuery['id'];
            $dateCreated = $existingQuery['dateCreated'];
            $uid         = $existingQuery['uid'];
        }

        $this->dbConnection->createCommand()->upsert(
            '{{%craftstatictracking}}',
            [
                'id' => $existingId,
                'elementId' => $entry->id,
                'type' => 'expiring_entry',
                'cacheBustOnUtcDate' => $expiryDate->format(Craftstatic::MYSQL_DATE_TIME_FORMAT),
                'dateCreated' => $dateCreated,
                'dateUpdated' => $dateUpdated,
                'uid' => $uid,
            ],
            true,
            [],
            false
        )
            ->execute();
    }
}
