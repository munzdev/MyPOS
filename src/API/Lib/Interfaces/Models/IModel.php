<?php

namespace API\Lib\Interfaces\Models;

interface IModel {
    /**
     * Saves the model into the repository. If its not new, it will update the record. Otherways it will create a new record
     */
    function save();

    /**
     * Check if this record is new ( allready saved in repository )
     *
     * @return bool
     */
    function isNew() : bool;

    /**
     * Removes the record from the repository
     */
    function delete();

    /**
     * Clears data in the Model. If its an existing row, the Primary ID will be keeped
     */
    function clear();

    /**
     * Converts the Model datas into an Array where the Key is the CamlCase of the Repository Fieldname.
     */
    function toArray();
}