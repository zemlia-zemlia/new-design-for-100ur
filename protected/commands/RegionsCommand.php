<?php

class RegionsCommand extends CConsoleCommand
{
    public function actionCountries()
    {
        $countries = Yii::app()->db->createCommand()
                ->select('name, id')
                ->from('{{country}}')
                ->queryAll();
        
        foreach ($countries as $country) {
            
//            $alias = CustomFuncs::translit($country['name']);
//            echo $country['id'] . " - " . $country['name'] . ' - ' . $alias;
//            Yii::app()->db->createCommand()
//                    ->update('{{country}}',
//                        array(
//                            'alias'=>$alias
//                        ),
//                        'id=:id',
//                        array(
//                            ':id'       =>  $country['id']
//                        ));
            
            Yii::app()->db->createCommand()
                    ->update(
                        '{{town}}',
                        array(
                            'countryId'=>$country['id']
                        ),
                        'country=:countryName',
                        array(
                            ':countryName'       =>  $country['name']
                        )
                    );
        }
    }


    public function actionRegions()
    {
        $regions = Yii::app()->db->createCommand()
                ->select('name, id')
                ->from('{{region}}')
                ->queryAll();
        

        
        foreach ($regions as $region) {
            Yii::app()->db->createCommand()
                    ->update(
                        '{{town}}',
                        array(
                            'regionId'=>$region['id']
                        ),
                        'ocrug=:regionName',
                        array(
                            ':regionName'       =>  $region['name']
                        )
                    );
        }
    }
    
    
    public function actionCountriesRegions()
    {
        $towns = Yii::app()->db->createCommand()
                ->select('regionId, countryId, ocrug, country')
                ->from('{{town}}')
                ->queryAll();
        
        foreach ($towns as $town) {
            $regionAlias = StringHelper::translit($town['ocrug']);
            Yii::app()->db->createCommand()
                    ->update(
                        '{{region}}',
                        array(
                            'alias'     =>  $regionAlias,
                            'countryId' =>  $town['countryId'],
                        ),
                        'id=:id',
                        array(
                            ':id'       =>  $town['regionId']
                        )
                    );
        }
    }
}
