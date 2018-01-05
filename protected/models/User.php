<?php

class User extends CActiveRecord
{
	public function beforeSave() 
	{
        if ($this->isNewRecord)
        {
            if (isset($this->password) && $this->password)
            {
            	$this->password = self::HashPassword($this->password);
            }
        }
        return parent::beforeSave();
    }

    public static function HashPassword($password)
    {
        return sha1(sha1(sha1($password)));
    }

	public function tableName()
	{
		return 'user';
	}

	public function rules()
	{
		return array(
			array('name, email, password', 'required'),
			array('name, password', 'length', 'max'=>50),
			array('email', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, email, password, reg_date', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('reg_date',$this->reg_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
