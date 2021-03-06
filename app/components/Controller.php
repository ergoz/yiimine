<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {
    public $pageDescription;
    public $projectMenu = array();
    public $issueMenu = array();
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public function init() {
        parent::init();
        $this->_setProjectMenu();
        $this->_setIssueMenu();
    }

    private function _setProjectMenu() {
        $this->projectMenu[] = array('label' => 'Все', 'url' => array('/project/index'));
        $this->projectMenu[] = array('label' => 'Создать', 'url' => array('/project/create'));
        $this->projectMenu[] = '---';
        $this->projectMenu[] = array('label' => 'Последние');

        $sql = 'SELECT * FROM project ORDER BY id DESC LIMIT 3';
        $projects = db()->createCommand($sql)->queryAll();
        foreach($projects as $k=>$proj)
            $this->projectMenu[] = array('label' => $proj['title'], 'url' => array('/project/view', 'url' => $proj['identifier']));
    }

    private function _setIssueMenu() {
        $this->issueMenu[] = array('label' => 'Создать', 'url' => array('/issue/create'));
        $this->issueMenu[] = '---';
        $this->issueMenu[] = array('label' => 'Назначенные мне');

        $sql = 'SELECT * FROM issue WHERE assigned_to_id = '.user()->id.' ORDER BY priority_id ASC LIMIT 5';
        $issues = db()->createCommand($sql)->queryAll();
        foreach($issues as $k=>$issue)
            $this->issueMenu[] = array('label' => $issue['subject'], 'url' => array('/issue/view', 'id' => $issue['id']));
    }


    /**
     * Loads the requested data model.
     * @param string the model class name
     * @param integer the model ID
     * @param array additional search criteria
     * @param boolean whether to throw exception if the model is not found. Defaults to true.
     * @return CActiveRecord the model instance.
     * @throws CHttpException if the model cannot be found
     */
    protected function loadModel($class, $id, $criteria = array(), $exceptionOnNull = true) {
        if (empty($criteria))
            $model = CActiveRecord::model($class)->findByPk($id);
        else {
            $finder = CActiveRecord::model($class);
            $c = new CDbCriteria($criteria);
            $c->mergeWith(array(
                'condition' => $finder->tableSchema->primaryKey . '=:id',
                'params' => array(':id' => $id),
            ));
            $model = $finder->find($c);
        }
        if (isset($model))
            return $model;
        else if ($exceptionOnNull)
            throw new CHttpException(404, 'Unable to find the requested object.');
    }

    /**
     * Loads the requested data model by the url.
     * @param string the model class name
     * @param string the model URL
     * @return CActiveRecord the model instance.
     * @throws CHttpException if the model cannot be found
     */
    protected function loadModelByUrl($class, $url) {
        $model = CActiveRecord::model($class)->findByAttributes(array('url' => $url));
        if (isset($model))
            return $model;
        else
            throw new CHttpException(404, 'Unable to find the requested object.');
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $model->formId) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}