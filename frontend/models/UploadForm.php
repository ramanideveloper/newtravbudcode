<?php
namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\mongodb\ActiveRecord;

class UploadForm extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public static function collectionName()
    {
        return 'tbl_image';
    }
    public function attributes()
    {
        return ['_id', 'imageFile'];
    }
    
      public function getUser()
    {
        return $this->hasOne(UserForm::className(), ['_id' => 'post_user_id']);
    }
   
    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) 
        {
          $temp = $this->imageFile;
          echo '<pre>';
          print_r($temp);
          exit;
          $this->imageFile->saveAs('./uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
          return true;
          
        } else {
            return false;
        }
    }
    // site controller
    /*   public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                
            
            if ($model->upload()) {
                // file is uploaded successfully
                $temp = $model->imageFile;
                $date = time();
                $post = new PostForm();
                $post->post_text = $temp->name;
                $post->post_status = '1';
                $post->post_created_date = $date;
                $post->post_user_id = '1234';
              echo '<pre>';         
            print_r($post);
            exit;
                $post->insert();
                
         
                return;
            }
        }

        return $this->render('upload', ['model' => $model]);
    }*/
}