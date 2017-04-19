<?php

namespace API\Lib\Interfaces\Models;

interface IQuery {
    /**
     * Returns a collection with all data, that are stored
     *
     * @return ICollection
     */
    function find();

    /**
     *
     * @param int $id The Primary ID to retrieve
     * @return null|IModel
     */
    function findPk($id);
}