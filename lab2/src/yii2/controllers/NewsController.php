<?php

namespace app\controllers;

use app\models\News;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class NewsController extends Controller
{
    public $layout = 'news';

    public function behaviors(): array
    {
        return [
            // Разрешённые HTTP-методы для действий (дублирует ограничения в правилах URL)
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => ['like' => ['POST'], 'unlike' => ['DELETE'], 'likes' => ['GET']],
            ],
        ];
    }

    public function actions(): array
    {
        return ['error' => ['class' => 'yii\web\ErrorAction']];
    }

    private function visitor(): string
    {
        $session = Yii::$app->session;
        if (!$session->has('visitor_id')) {
            $session->set('visitor_id', Yii::$app->security->generateRandomString(32));
        }
        return $session->get('visitor_id');
    }

    private function likedIds(): array
    {
        return (new \yii\db\Query())
            ->select('news_id')->from('news_likes')
            ->where(['visitor_id' => $this->visitor()])
            ->column();
    }

    private function findNews(int $id): News
    {
        $news = News::findOne($id);
        if ($news === null) {
            throw new NotFoundHttpException('Новость не найдена');
        }
        return $news;
    }

    /** GET / и GET /news */
    public function actionIndex(): string
    {
        return $this->render('index', [
            'news'  => News::find()->orderBy(['created_at' => SORT_DESC])->all(),
            'liked' => $this->likedIds(),
        ]);
    }

    /** GET /news/<id> */
    public function actionView(int $id): string
    {
        $news = $this->findNews($id);
        return $this->render('view', ['item' => $news, 'liked' => in_array($news->id, $this->likedIds())]);
    }

    /** POST /news/<id>/like */
    public function actionLike(int $id): array
    {
        $news = $this->findNews($id);
        $db = Yii::$app->db;
        $db->transaction(function () use ($db, $news) {
            $sql = 'INSERT IGNORE INTO news_likes (news_id, visitor_id) VALUES (:n, :v)';
            if ($db->createCommand($sql, [':n' => $news->id, ':v' => $this->visitor()])->execute()) {
                $news->updateCounters(['likes_count' => 1]);
            }
        });
        return $this->state($news, true);
    }

    /** DELETE /news/<id>/like */
    public function actionUnlike(int $id): array
    {
        $news = $this->findNews($id);
        $db = Yii::$app->db;
        $db->transaction(function () use ($db, $news) {
            $deleted = $db->createCommand()
                ->delete('news_likes', ['news_id' => $news->id, 'visitor_id' => $this->visitor()])
                ->execute();
            if ($deleted && $news->likes_count > 0) {
                $news->updateCounters(['likes_count' => -1]);
            }
        });
        return $this->state($news, false);
    }

    /** GET /news/<id>/likes */
    public function actionLikes(int $id): array
    {
        $news = $this->findNews($id);
        return $this->state($news, in_array($news->id, $this->likedIds()));
    }

    /** GET /about */
    public function actionAbout(): string
    {
        return $this->render('about');
    }

    /** GET /routes — таблица правил urlManager */
    public function actionRoutes(): string
    {
        return $this->render('routes', ['rules' => Yii::$app->urlManager->rules]);
    }

    private function state(News $news, bool $liked): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $news->refresh();
        return ['ok' => true, 'news_id' => $news->id, 'likes' => (int) $news->likes_count, 'liked' => $liked];
    }
}
