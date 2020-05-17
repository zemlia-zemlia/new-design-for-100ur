<?php


namespace App\services;

use App\models\Lead;
use App\models\Lead2Category;
use App\models\QuestionCategory;

/**
 * Бизнес логика работы с лидами
 * Class LeadService
 * @package App\services
 */
class LeadService
{
    /**
     * @param Lead $lead
     * @param QuestionCategory[] $categories
     */
    public function mapLeadToCategories(Lead $lead, array $categories)
    {
        foreach ($categories as $cat) {
            $lead2category = new Lead2Category();
            $lead2category->leadId = $lead->id;
            $lead2category->cId = $cat->id;
            $lead2category->save();
        }
    }
}