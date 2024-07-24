<?php

namespace app\controllers;

use app\models\Icon;
use app\models\Parameter;
use Yii;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class ParameterController extends \yii\rest\Controller
{
    public function actionError()
    {
        Yii::$app->response->statusCode = 501;

        return Yii::$app->response;
    }

    /**
     * @param int $type
     * @return Parameter[]|array
     */
    public function actionIndex(int $type): array
    {
        $params = Parameter::find()->with('icon');

        if ($type) {
            $params = $params->where(['type' => $type]);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $params->all();
    }

    /**
     * @param int $searchId
     * @param string $title
     * @return Parameter[]|array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionSearch(int $searchId, string $title): array
    {
        $params = Parameter::find();

        if ($searchId) {
            $params = $params->where(['id' => $searchId]);

            if ($title) {
                $params->andWhere(['LIKE', 'title', $title]);
            }
        } else if ($title) {
            $params = $params->where(['LIKE', 'title', $title]);
        } else {
            throw new BadRequestHttpException('Нет данных для поиска');
        }

        if (empty($params->all())) {
            throw new NotFoundHttpException('Нет результатов');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $params->all();
    }

    /**
     * @param int $paramId
     * @return array|\yii\console\Response|Response
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionSaveIcons(int $paramId)
    {
        $param = Parameter::findOne($paramId);

        if (!$param) {
            throw new NotFoundHttpException('Такого параметра не существует');
        }

        if ($param->type !== 2) {
            throw new BadRequestHttpException('К этому параметру нельзя сохранить иконки');
        }

        $files = [];
        $files['icon'] = UploadedFile::getInstanceByName('icon');
        $files['icon_gray'] = UploadedFile::getInstanceByName('icon_gray');

        foreach ($files as $key => $file) {
            if ($file) {

                // checks if icon exists
                if ($key === 'icon' && $param->icon) {
                    $old_image = Icon::findOne($param->icon['id']);
                } else if ($key === 'icon_gray' && $param->grayIcon) {
                    $old_image = Icon::findOne($param->grayIcon['id']);

                }

                // removes old icon
                if (isset($old_image)) {
                    $old_image->unlinkIcon();
                    $old_image->delete();
                }

                $icon = new Icon();
                $icon->icon = $file;
                $icon->type = $key;

                if ($icon->validate()) {
                    $icon->handlePhoto();
                    $icon->link('param', $param);
                } else {
                    Yii::$app->response->statusCode = 400;
                    return $icon->errors;
                }
            }
        }

        Yii::$app->response->statusCode = 200;

        return Yii::$app->response;
    }

    /**
     * @param int $iconId
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteIcon(int $iconId): Response
    {
        $image = Icon::findOne($iconId);

        if ($image) {
            $image->unlinkIcon();
            $image->delete();

            Yii::$app->response->statusCode = 204;

            return Yii::$app->response;
        }

        throw new NotFoundHttpException('Изображение с таким ID не найдено');
    }

}
