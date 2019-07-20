<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\services;

use buzzingpixel\craftstatic\Craftstatic;
use buzzingpixel\craftstatic\factories\QueryFactory;
use craft\base\Component;
use craft\db\Connection as DbConnection;
use DateTimeImmutable;
use DateTimeZone;
use Throwable;

class CheckEntryTracking extends Component
{
    /** @var QueryFactory */
    private $queryFactory;
    /** @var StaticHandlerService */
    private $staticHandler;
    /** @var DbConnection */
    private $dbConnection;

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

        $this->currentTime = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    /**
     * @throws Throwable
     */
    public function run() : void
    {
        $query = $this->queryFactory->createQuery()
            ->from('{{%craftstatictracking}}')
            ->where('`cacheBustOnUtcDate` <= "' . $this->currentTime->format(Craftstatic::MYSQL_DATE_TIME_FORMAT) . '"')
            ->one();

        if (! $query) {
            return;
        }

        $this->staticHandler->clearCache();

        $this->dbConnection->createCommand()->delete(
            '{{%craftstatictracking}}',
            '`cacheBustOnUtcDate` <= "' . $this->currentTime->format(Craftstatic::MYSQL_DATE_TIME_FORMAT) . '"'
        )
        ->execute();
    }
}
