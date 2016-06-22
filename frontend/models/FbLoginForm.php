<?php
namespace frontend\models;

//use common\models\User;
use yii\base\Model;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;
use yii\mongodb\ActiveQueryInterface;

//use app\models\TblUserPost;

/**
 * FbLogin form
 */
class FbLoginForm extends ActiveRecord
{
    public static function CollectionName()
    {
        return ['travel','tbl_user'];
    }

    // set up for model attributes
    public function attributes()
    {
        return [
            '_id' ,
            'fb_id',
            'username',
            'password',
            'email',
            'photo',
            'created_date',
            'updated_date',
        ];
    }
    
     public function behaviors()
     {
        return [
            TimestampBehavior::className(),
        ];
     }
     
     public static function find()
    {
       // return parent::find()->where(['fb_id' => 1234 ]);
        // return new ActiveQuery(get_called_class());
    }
   
//     public static function findIdentity($id)
//     {
//        return static::findOne($id);
//     }
//     public static function findByUsername($username)
//     {
//        return static::findOne(['username' => $username]);
//     }

}
