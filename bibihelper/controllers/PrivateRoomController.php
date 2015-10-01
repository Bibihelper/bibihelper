<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Company;
use app\models\Shedule;
use app\models\User;
use app\models\Category;
use app\models\Country;
use app\models\CompanyServices;
use app\models\CompanyBrands;
use app\models\SpecialOffer;
use app\models\forms\CompanyInfoForm;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class PrivateRoomController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function actionIndex($id)
    {
        if (Yii::$app->user->isGuest || $id != Yii::$app->user->id) {
            return $this->redirect(Url::home());
        }
        
        $company = Company::findOne($id);
        $shedule = Shedule::getSheduleString($company->id);
        $categories = Category::find()->all();
        $countries = Country::find()->all();
        
        $cInfFrm = new CompanyInfoForm();
        $cInfFrm->loadInfo($id);
        
        return $this->render('/private-room/private-room', [
            'company' => $company, 
            'shedule' => $shedule,
            'categories' => $categories,
            'countries' => $countries,
            'cInfFrm' => $cInfFrm,
        ]);
    }
    
    public function actionLoadImage($id)
    {
        foreach ($_FILES as $file) {
            $fileTmpName = $file['tmp_name'];
            $fileName    = $file['name'];
            $fileRPath   = '/data/' . $id . '/';
            $fileFPath   = Yii::$app->basePath . '/web' . $fileRPath;
        }
        
        if (!is_writable($fileFPath)) {
            
            chmod($fileFPath, 0777);
            
        }
        
        $mv = move_uploaded_file($fileTmpName, $fileFPath . $fileName);
        
        if ($mv) {
            $imagine = new Imagine();
            $image = $imagine->open($fileFPath . $fileName);
            $box = new Box(700, 420);
            $image->resize($box)->save();
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $status = ($mv) ? 'OK' : 'ERROR';
        $responce = ['status' => $status, 'filename' => $fileRPath . $fileName];
        return $responce;
    }
    
    public function actionSetCompanyService()
    {
        $status = 'OK';
        $data = Yii::$app->request->post();      
        
        $cmid  = $data['cmid'];
        $sbid  = $data['sbid'];
        $state = $data['state'];
        
        $cs = new CompanyServices();
        $err = $cs->setCompanyService($cmid, $sbid, $state);
        
        if (!$err) {
            $status = 'ERROR';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $responce = ['status' => $status];
        return $responce;
    }
    
    public function actionSetCompanyBrand()
    {
        $status = 'OK';
        $data = Yii::$app->request->post();      
        
        $cmid  = $data['cmid'];
        $sbid  = $data['sbid'];
        $state = $data['state'];
        
        $cb = new CompanyBrands();
        $ok = $cb->setCompanyBrand($cmid, $sbid, $state);
        
        if (!$ok) {
            $status = 'ERROR';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $responce = ['status' => $status];
        return $responce;
    }
    
    public function actionSetSpecialOffer()
    {
        $status = "OK";
        $data = Yii::$app->request->post();
        $sp = new SpecialOffer();
        $ok = $sp->setSpecialOffer($data);
        
        if (!$ok) {
            $status = "ERROR";
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $responce = ['status' => $status];
        return $responce;
    }
    
    public function actionRemoveSpecialOffer()
    {
        $status = "OK";
        $data = Yii::$app->request->post();
        $sp = new SpecialOffer();
        $ok = $sp->removeSpecialOffer($data['cid']);
        
        if (!$ok) {
            $status = "ERROR";
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $responce = ['status' => $status];
        return $responce;
    }
    
    public function actionChangePassword()
    {
        $status = "OK";
        $err = 0;
        $error = "";
        $company = $user = null;
        $data = Yii::$app->request->post();
        $company = Company::findOne($data['cid']);
        
        if ($company) {
            $user = $company->user;
        } else {
            $err = 4;
        }
        
        if ($user) {
            $err = $user->changePassword($data);
        } else {
            $err = 4;
        }
        
        if ($err) {
            $status = "ERROR";
            
            switch ($err) {
                case 1: $error = "Неверный старый пароль"; break;
                case 2: $error = "Минимальная длина пароля - 6 символов"; break;
                case 3: $error = "Пароли не совпадают"; break;
                case 4: $error = "Unknown error"; break;
            }
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $responce = ['status' => $status, 'code' => $err, 'error' => $error];
        return $responce;
    }
    
    public function actionChangeEmail()
    {
        $status = "OK";
        $err = 0;
        $error = "";
        $company = $user = null;
        $data = Yii::$app->request->post();
        $company = Company::findOne($data['cid']);
        
        if ($company) {
            $user = $company->user;
        } else {
            $err = 4;
        }
        
        if ($user) {
            $err = $user->changeEmail($data);
        } else {
            $err = 4;
        }
        
        if ($err) {
            $status = "ERROR";
            
            switch ($err) {
                case 1: $error = "Неверный email"; break;
                case 4: $error = "Unknown error"; break;
            }
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $responce = ['status' => $status, 'code' => $err, 'error' => $error];
        return $responce;
    }
    
    public function actionOptionsSave()
    {
        $data = Yii::$app->request->post();
        $user = new User();
        $user = $user->findOne(Yii::$app->user->id);
        $this->optionsSave($data, $user);
        $this->redirect(Url::to('/private-room/index/?id=' . $user->company->id));
    }
    
    public function optionsSave($data, $user)
    {
        $db = Yii::$app->db;
        
        $transaction = $db->beginTransaction();
        
        try {
            
            $company = $user->company;
            $company->name  = $data['company_name_2'];
            $company->phone = $data['company_phone_2'];
            $company->twenty_four_hours = $data['shedule_twfh_2'];
            $company->save();
            
            $address = $user->company->address;
            $address->region   = $data['address_region_2'];
            $address->city     = $data['address_city_2'];
            $address->district = $data['address_district_2'];
            $address->street   = $data['address_street_2'];
            $address->home     = $data['address_home_2'];
            $address->housing  = $data['address_housing_2'];
            $address->building = $data['address_building_2'];
            $address->metro    = $data['address_metro_2'];
            $address->save();
            
            Shedule::deleteAll(['company_id' => $company->id]);
            
            $days = array(1 => 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
            
            for ($i = 1; $i <= 7; $i++) {
                $dataDay = 'shedule_' . $days[$i] . '_2';
                $dayValue = $data[$dataDay];
            
                if ($dayValue == 1) {
                    $shedule = new Shedule();
                    $shedule->company_id = $company->id;
                    $shedule->day        = $i;
                    $shedule->begin      = $data['b_hour_2'] . ':' . $data['b_minute_2'] . ':00';
                    $shedule->end        = $data['e_hour_2'] . ':' . $data['e_minute_2'] . ':00';
                    $shedule->save();
                }
            }
            
            $transaction->commit();
            
        } catch (Exception $e) {
        
            $transaction->rollBack();
            return false;
            
        }
        
        return true;
    }
}
