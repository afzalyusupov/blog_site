<?php

namespace app\controllers;

use app\models\Article;
use app\models\Category;
use app\models\CommentForm;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $data = Article::getAll();
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();

        return $this->render('index', [
            'articles'=>$data['articles'],
            'pagination'=>$data['pagination'],
            'popular'=>$popular,
            'recent'=>$recent,
            'category'=>$categories
        ]);
    }

    public function actionView($id)
    {
        $article = Article::findOne($id);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        $comments = $article->getArticleComments();
        $commentForm = new CommentForm();

        return $this->render('view', [
            'article'=>$article,
            'popular'=>$popular,
            'recent'=>$recent,
            'category'=>$categories,
            'comments'=>$comments,
            'commentForm'=>$commentForm
         ]);
    }

    public function actionCategory($id)
    {
        // build a DB query to get all articles with status = 1
        $query = Article::find()->where(['category_id'=>$id]);

        // get the total number of articles (but do not fetch the article data yet)
        $count = $query->count();

        // create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $count, 'pageSize'=>6]);

        // limit the query using the pagination and retrieve the articles
        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $data['articles']=$articles;
        $data['pagination']=$pagination;
        $popular=Article::getPopular();
        $recent=Article::getRecent();
        $categories=Article::getAll();

        return $this->render('category', [
            'articles'=>$data['articles'],
            'pagination'=>$data['pagination'],
            'popular'=>$popular,
            'recent'=>$recent,
            'category'=>$categories
            ]);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionComment($id)
    {
        $model = new CommentForm();

        if (Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if ($model->saveComment($id))
            {
                Yii::$app->getSession()->setFlash('comment', 'Your comment will be added soon');
                return $this->redirect(['site/view', 'id'=>$id]);
            }
        }
    }
}