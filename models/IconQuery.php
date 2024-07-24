<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Icon]].
 *
 * @see Icon
 */
class IconQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Icon[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Icon|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
