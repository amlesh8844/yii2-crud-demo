<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Supplier]].
 *
 * @see \app\models\Supplier
 */
class SupplierQuery extends \netis\crud\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Supplier[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Supplier|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}