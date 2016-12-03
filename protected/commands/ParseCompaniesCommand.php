<?php

class ParseCompaniesCommand extends CConsoleCommand
{
    const COMPANY_PHOTO_THUMB_FOLDER = "/thumbs";
    const COMPANY_PHOTO_PATH = "../../../upload/company";

    
    // перенос данных из временной таблицы в постоянную
    public function actionIndex()
    {
        $companies = Yii::app()->db->createCommand()
                ->select("*")
                ->from("yurCompany_tmp")
                ->queryAll();
        
        foreach($companies as $company) {
            $yurCompany = new YurCompany;
            
            $yurCompany->setScenario("parsing");
            
            $yurCompany->name = $company['name'];
            $yurCompany->townId = $company['townId'];
            $yurCompany->metro = $company['metro'];
            $yurCompany->phone1 = $company['phone'];
            $yurCompany->address = $company['address'];
            $yurCompany->description = $company['description'];
            $yurCompany->website = $company['website'];
            $yurCompany->logo = $company['logo'];
            
            if($yurCompany->save()) {
                echo $company['id'] . "\n\r";
            } else {
                print_r($yurCompany->errors);
            }
        }
        
    }
    
    // загрузка и конвертация фотографий
    public function actionPhotos()
    {
        $companies = Yii::app()->db->createCommand()
                ->select("id, name, logoUrl")
                ->from("yurCompany_tmp")
                ->queryAll();
        
        foreach($companies as $company) {
            if($company['logoUrl']!='' && $company['logoUrl']!='javascript:void') {
                echo '\n\r' . $company['name'] . ': ';
                // определяем имя файла для хранения на сервере
                $newFileName = md5($company['logoUrl'].$company['name'].mt_rand(10000,100000)).".jpg";
                Yii::app()->ih
                ->load($company['logoUrl'])
                ->resize(1000, 300, true)
                ->save(__DIR__ . self::COMPANY_PHOTO_PATH . '/' . $newFileName)
                ->reload()
                ->resizeCanvas(210,210, array(255,255,255))
                ->save(__DIR__ . self::COMPANY_PHOTO_PATH . self::COMPANY_PHOTO_THUMB_FOLDER . '/' . $newFileName);

                $updateResult = Yii::app()->db->createCommand()
                        ->update("yurCompany_tmp", array('logo'=>$newFileName), 'id='.$company['id']);
                
                if($updateResult) {
                    echo "updated";
                } else {
                    echo "Error - not updated";
                }
                
            }
        }
        
    }
}