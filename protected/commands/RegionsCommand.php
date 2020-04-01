<?php

use App\helpers\StringHelper;

class RegionsCommand extends CConsoleCommand
{
    public function actionCountries()
    {
        $countries = Yii::app()->db->createCommand()
                ->select('name, id')
                ->from('{{country}}')
                ->queryAll();

        foreach ($countries as $country) {
            Yii::app()->db->createCommand()
                    ->update(
                        '{{town}}',
                        [
                            'countryId' => $country['id'],
                        ],
                        'country=:countryName',
                        [
                            ':countryName' => $country['name'],
                        ]
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
                        [
                            'regionId' => $region['id'],
                        ],
                        'ocrug=:regionName',
                        [
                            ':regionName' => $region['name'],
                        ]
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
                        [
                            'alias' => $regionAlias,
                            'countryId' => $town['countryId'],
                        ],
                        'id=:id',
                        [
                            ':id' => $town['regionId'],
                        ]
                    );
        }
    }
}
