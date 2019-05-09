<?php

namespace app\controllers;

use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\web\Controller;
use app\models\LoginForm;

class AuthController extends Controller
{
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('/auth/login', [
            'model' => $model,
        ]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->signup()) {
                return $this->redirect(['auth/login']);
            }
        }
        return $this->render('signup', ['model' => $model]);
    }

    public function actionTest()
    {
        $user = User::findOne(1);
        Yii::$app->user->logout($user);

        if (Yii::$app->user->isGuest) {
            echo 'you are guest';
        } else {
            echo 'you are auth';
        }
    }
}