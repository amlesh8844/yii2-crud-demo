<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Customercustomerdemo]].
 *
 * @see \app\models\Customercustomerdemo
 */
class CustomercustomerdemoQuery extends \netis\crud\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Customercustomerdemo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Customercustomerdemo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}