<?php

declare(strict_types = 1);

/**
 * Abstract raw repository.
 */

namespace HDNET\Focuspoint\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Abstract raw repository.
 */
abstract class AbstractRawRepository
{
    /**
     * Find by uid.
     */
    public function findByUid(int $uid): ?array
    {
        $queryBuilder = $this->getQueryBuilder();
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('uid', $uid)
            )
            ->execute()
            ->fetchAll()
        ;

        return $rows[0] ?? null;
    }

    /**
     * Find all.
     */
    public function findAll(): array
    {
        $queryBuilder = $this->getQueryBuilder();

        return (array)$queryBuilder->select('*')
            ->from($this->getTableName())
            ->execute()
            ->fetchAll()
        ;
    }

    /**
     * Update by uid.
     */
    public function update(int $uid, array $values): void
    {
        $this->getConnection()->update(
            $this->getTableName(),
            $values,
            ['uid' => (int)$uid]
        );
    }

    /**
     * Insert.
     */
    public function insert(array $values): void
    {
        $this->getConnection()->insert(
            $this->getTableName(),
            $values
        );
    }

    /**
     * Get connection.
     */
    protected function getConnection(): \TYPO3\CMS\Core\Database\Connection
    {
        return $this->getConnectionPool()->getConnectionForTable($this->getTableName());
    }

    /**
     * Get query builder.
     */
    protected function getQueryBuilder(): \TYPO3\CMS\Core\Database\Query\QueryBuilder
    {
        return $this->getConnectionPool()->getQueryBuilderForTable($this->getTableName());
    }

    /**
     * Get the tablename.
     */
    abstract protected function getTableName(): string;

    /**
     * Get connection pool.
     */
    private function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
}
