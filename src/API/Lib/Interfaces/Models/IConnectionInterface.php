<?php

/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace API\Lib\Interfaces\Models;

/**
 * Interface for Propel Connection object.
 * Based on the PDO interface.
 * @see http://php.net/manual/en/book.pdo.php
 *
 * @author Francois Zaninotto
 */
interface IConnectionInterface
{
    /**
     * Turns off autocommit mode.
     *
     * While autocommit mode is turned off, changes made to the database via
     * the Connection object instance are not committed until you end the
     * transaction by calling Connection::commit().
     * Calling Connection::rollBack() will roll back all changes to the database
     * and return the connection to autocommit mode.
     *
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function beginTransaction() : bool;

    /**
     * Commits a transaction.
     *
     * commit() returns the database connection to autocommit mode until the
     * next call to connection::beginTransaction() starts a new transaction.
     *
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function commit() : bool;

    /**
     * Rolls back a transaction.
     *
     * Rolls back the current transaction, as initiated by beginTransaction().
     * It is an error to call this method if no transaction is active.
     * If the database was set to autocommit mode, this function will restore
     * autocommit mode after it has rolled back the transaction.
     *
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function rollBack() : bool;

    /**
     * Checks if inside a transaction.
     *
     * @return bool TRUE if a transaction is currently active, and FALSE if not.
     */
    public function inTransaction() : bool;
}
