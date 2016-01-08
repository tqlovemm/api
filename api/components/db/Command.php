<?php
namespace app\components\db;

class Command extends \yii\db\Command
{

    public function batchReplace($table, $columns, $rows)
    {
        $sql = $this->db->getQueryBuilder()->batchInsert($table, $columns, $rows);
        return $this->setSql('REPLACE' . substr($sql, strpos($sql, ' ')));
    }

}

?>