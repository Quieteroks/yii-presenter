<?php

namespace quieteroks\presenter\examples;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\User;

class ProfileController extends Controller
{
    /**
     * Rendering profile view page
     *
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        try {
            $presenter = new UserPresenter(User::findOne($id));
            return $this->render('view', compact('presenter'));
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
