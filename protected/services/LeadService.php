<?php

namespace App\services;

use App\helpers\PhoneHelper;
use App\models\Lead;
use App\models\Lead2Category;
use App\models\QuestionCategory;
use App\models\User;
use CHtml;

/**
 * Бизнес логика работы с лидами
 * Class LeadService.
 */
class LeadService
{
    /**
     * @param Lead $lead
     * @param array $categories Массив QuestionCategory или id
     * @param array $allDirectionsHierarchy
     * @param bool $mapToParentCategory
     */
    public function mapLeadToCategories(
        Lead $lead,
        array $categories,
        array $allDirectionsHierarchy,
        bool $mapToParentCategory = false
    )
    {
        foreach ($categories as $cat) {
            $lead2category = new Lead2Category();
            $lead2category->leadId = $lead->id;
            $lead2category->cId = ($cat instanceof QuestionCategory) ? $cat->id : $cat;

            if ($lead2category->save() && $mapToParentCategory == true) {
                // проверим, не является ли указанная категория дочерней
                // если является, найдем ее родителя и запишем в категории лида
                foreach ($allDirectionsHierarchy as $parentId => $parentCategory) {
                    if (!$parentCategory['children']) {
                        continue;
                    }

                    foreach ($parentCategory['children'] as $childId => $childCategory) {
                        if ($childId == $cat->id) {
                            $lead2category = new Lead2Category();
                            $lead2category->leadId = $lead->id;
                            $lead2category->cId = $parentId;
                            $lead2category->save();
                            break;
                        }
                    }
                }
            }
        }
    }

    /**
     * Наполняет лид атрибутами из массива, проводит проверки, сохраняет
     * @param Lead $lead
     * @param array $postData
     * @param array $allDirectionsHierarchy
     * @return Lead
     */
    public function fillAndSaveLead(Lead $lead, array $postData, array $allDirectionsHierarchy): Lead
    {
        $lead->attributes = $postData;
        $lead->phone = PhoneHelper::normalizePhone($lead->phone);

        $duplicates = $lead->findDublicates(86400);
        if ($duplicates) {
            $lead->leadStatus = Lead::LEAD_STATUS_DUPLICATE;
        }

        if ($lead->validate()) {
            $lead->question = CHtml::encode('Нужна консультация юриста. Перезвоните мне. ' . $lead->question);

            if ($lead->save()) {
                // сохраним категории, к которым относится вопрос, если категория указана
                if (isset($postData['categories']) && 0 != $postData['categories']) {
                    $this->mapLeadToCategories($lead, [(int)$postData['categories']], $allDirectionsHierarchy, true);
                }
            }
        }

        return $lead;
    }

    /**
     * Присваивает лиду атрибуты из объекта пользователя
     * @param Lead $lead
     * @param User|null $user
     * @return Lead
     */
    public function fillLeadAttributesFromUserParams(Lead $lead, ?User $user): Lead
    {
        if (is_null($user)) {
            return $lead;
        }

        if (!$lead->name && $user->name) {
            $lead->name = $user->name;
        }

        if (!$lead->phone && $user->phone) {
            $lead->phone = $user->phone;
        }

        if (!$lead->townId && $user->townId) {
            $lead->townId = $user->townId;
        }

        return $lead;
    }
}
