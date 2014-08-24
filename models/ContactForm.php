<?php

namespace albertborsos\yii2cms\models;

use Yii;
use yii\base\Model;
use yii\base\ModelEvent;
use yii\helpers\HtmlPurifier;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $nameFirst;
    public $nameLast;
    public $email;
    public $phone;
    public $subject;
    public $body;
    public $verifyCode;

    private $numbers = [];

    private $operand = '+';
    private $result = [16, 'tizenhat'];

    public function init()
    {
        parent::init();
        $this->numbers[] = [
            'int' => 10,
            'string' => 'tíz'
        ];
        $this->numbers[] = [
            'int' => 6,
            'string' => 'hat'
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['nameFirst', 'nameLast', 'email', 'phone', 'subject', 'body', 'verifyCode'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['verifyCode', 'validateVerifyCode'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nameFirst' => 'Keresztnév',
            'nameLast' => 'Vezetéknév',
            'phone' => 'Telefonszám',
            'subject' => 'Tárgy',
            'body' => 'Üzenet',
            'verifyCode' => 'Megerősítés',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        $sent = Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->nameLast.' '.$this->nameFirst])
            ->setHtmlBody($this->setMailContent())
            ->setSubject($this->setSubject())
            ->setTextBody(HtmlPurifier::process($this->setMailContent()))
            ->send();
        if ($sent){
            Yii::$app->session->setFlash('success', '<h4>Köszönöm '.$this->nameFirst.'! Az e-mailedet megkaptam!</h4><p>Hamarosan válaszolok Neked!</p>');
        }else{
            Yii::$app->session->setFlash('error', '<h4>Hiba történt az e-mail küldés során!</h4>');
        }
    }

    private function setMailContent(){
        return '<p><strong>Feladó: </strong>'.$this->nameLast.' '.$this->nameFirst.'<br />'
            .'<strong>Telefonszám: </strong>'.$this->phone.'<br />'
            .'<strong>E-mail cím: </strong>'.$this->email.'</p>'
            .$this->body;

    }

    private function setSubject(){
        return '['.Yii::$app->name.'] '.$this->subject;
    }

    public function getVerifyCodePlaceholder(){
        $placeholder = '';
        $count = count($this->numbers);
        $n = 1;
        foreach($this->numbers as $number){
            $placeholder .= $number['string'];
            if ($n !== $count){
                $placeholder .= ' '.$this->operand.' ';
            }else{
                $placeholder .= ' = ?';
            }
            $n++;
        }
        return $placeholder;
    }

    public function validateVerifyCode(){
        $isOK = false;
        foreach($this->result as $result){
            if($result == $this->verifyCode){
                $isOK = true;
            }
        }
        if (!$isOK){
            $this->addError('verifyCode', 'Nem megfelelő a megerősítő mező értéke!');
        }
    }
}
