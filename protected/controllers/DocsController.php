<?php

class DocsController extends Controller
{



    public function actionDownload($id)
    {
        $model = $this->loadModel($id);

        return $this->redirect($model->getDownloadLink());
    }



    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return Docs the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Docs::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

}
