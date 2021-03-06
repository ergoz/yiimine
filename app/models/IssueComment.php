<?php

/**
 * This is the model class for table "issue_comment".
 *
 * The followings are the available columns in table 'issue_comment':
 * @property string $id
 * @property string $issue_id
 * @property integer $user_id
 * @property string $text
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property Issue $issue
 * @property Users $user
 */
class IssueComment extends CActiveRecord {
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'issue_comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('text', 'required'),
            array('user_id, issue_id', 'numerical', 'integerOnly' => true),
            array('issue_id', 'length', 'max' => 11),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, issue_id, user_id, text, created_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'issue' => array(self::BELONGS_TO, 'Issue', 'issue_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'issue_id' => 'Задача',
            'user_id' => 'Автор',
            'text' => 'Комментарий',
            'created_date' => 'Created Date',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('issue_id', $this->issue_id, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('created_date', $this->created_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return IssueComment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    protected function beforeSave() {
        if(parent::beforeSave()) {
            if($this->isNewRecord) {
                $this->user_id = user()->id;
            }
            return true;
        } else
            return false;
    }


}