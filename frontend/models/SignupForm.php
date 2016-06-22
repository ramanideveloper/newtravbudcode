<?php

namespace frontend\models;
use yii\mongodb\ActiveRecord;

use Yii;

/**
 * ContactForm is the model behind the contact form.
 */
class SignupForm extends ActiveRecord
{
    public static function CollectionName()
    {
        return 'tbl_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                // name, email, subject and body are required
                [['username', 'lname', 'email', 'password', 'con_password', 'birth_date', 'gender'], 'required'],
                // email has to be a valid email address
                ['email', 'email'],
                [['con_password'], 'compare', 'compareAttribute' => 'password'],
                // verifyCode needs to be entered correctly
                //['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    /*
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }
    */

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    /*
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
    */

    public function attributes()
    {
        return ['_id', 'fb_id', 'username', 'lname', 'email', 'password', 'con_password', 'birth_date', 'gender','photo','created_date','updated_date'];
    }
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
     protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->email);
        }

        return $this->_user;
    }
}
