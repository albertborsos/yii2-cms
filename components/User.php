<?php

namespace albertborsos\yii2cms\components;


class User extends \yii\web\User{

    public $defaultRole = 'guest';

    public $destroySession = true;

} 