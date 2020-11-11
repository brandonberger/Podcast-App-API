<?php
namespace Library;

trait Logging {

    public function logUpdate($oldValues = null, $newValues = null, $table = null)
    {
        switch ($table) {
            case 'Users':
                $propelTable = new \Models\Users\Users();
                break;
        }

        $mapClass = $propelTable::TABLE_MAP;
        $map = $mapClass::getTableMap();
        $map->getRelations();

        foreach ($newValues->getModifiedColumns() as $columnName) {
            $column = $map->getColumn($columnName);
            //display name
            $name = $column->normalizeName($columnName);
            $name = str_replace("_"," ", $name);

            $message = 'changed ' . $name . ' from ';

            $message .= $oldValues->getByName($column->getPhpName());
            $message .= ' to ';
            $message .= $propelTable->getByName($column->getPhpName());

            echo $message;
        }
    }
}