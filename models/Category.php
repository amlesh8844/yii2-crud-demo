<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property integer $CategoryID
 * @property string $CategoryName
 * @property string $Description
 * @property resource $Picture
 */
class Category extends \netis\crud\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function filteringRules()
    {
        return [
            [['CategoryID', 'CategoryName', 'Description', 'Picture'], 'trim'],
            [['CategoryID', 'CategoryName', 'Description', 'Picture'], 'default'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CategoryID', 'CategoryName'], 'required'],
            [['Description', 'Picture'], 'safe'],
            [['CategoryID'], 'integer', 'min' => -0x8000, 'max' => 0x7FFF],
            [['CategoryName'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CategoryID' => Yii::t('app', 'Category ID'),
            'CategoryName' => Yii::t('app', 'Category Name'),
            'Description' => Yii::t('app', 'Description'),
            'Picture' => Yii::t('app', 'Picture'),
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'labels' => [
                'class' => 'netis\crud\db\LabelsBehavior',
                'attributes' => ['CategoryName'],
                'crudLabels' => [
                    'default'  => Yii::t('app', 'Category'),
                    'relation' => Yii::t('app', 'Categories'),
                    'index'    => Yii::t('app', 'Browse Categories'),
                    'create'   => Yii::t('app', 'Create Category'),
                    'read'     => Yii::t('app', 'View Category'),
                    'update'   => Yii::t('app', 'Update Category'),
                    'delete'   => Yii::t('app', 'Delete Category'),
                ],
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function relations()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }
}
