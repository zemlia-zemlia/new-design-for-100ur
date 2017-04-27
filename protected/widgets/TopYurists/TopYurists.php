<?php

// виджет для вывода топа юристов

class TopYurists extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию
    public $limit = 6; // лимит выводимых юристов

    public function run()
    {
//        $criteria = new CDbCriteria;
//                
//        $criteria->addInCondition('role', array(User::ROLE_JURIST, User::ROLE_OPERATOR, User::ROLE_CALL_MANAGER));
//        $criteria->addColumnCondition(array('active100'=>1));
//        $criteria->order = "RAND()";
//        $criteria->limit = $this->limit;
//        $criteria->with = array('categories', 'settings', 'settings.town');
//        
//        $users = User::model()->cache($this->cacheTime)->findAll($criteria);
        
        $users=Yii::app()->cache->get('top_yurists');
        
        if($users === false) {
            
            $users = array();
            
            // найдем 6 произвольных пользователей с ролями Юрист, оператор, менеджер операторов
            $yurisIds = Yii::app()->db->createCommand()
                    ->select('u.id, COUNT(*) counter')
                    ->from('{{user}} u')
                    ->leftJoin('{{answer}} a', 'a.authorId=u.id')
                    ->where('role = ' . User::ROLE_JURIST . ' AND active100=1')
                    ->group("u.id")
                    ->limit($this->limit)
                    ->order('counter DESC')
                    ->queryAll();

            

            // найдем город, категории, число ответов для каждого юриста из полученного списка
            
            foreach($yurisIds as $yurisId) {

                $yuristInfo = Yii::app()->db->createCommand()
                        ->select("u.id, u.name, u.lastName, u_s.alias, u.avatar, t.name townName, q_c.name catName, COUNT(*) answersCount")
                        ->from('{{user}} u')
                        ->leftJoin('{{answer}} a', 'a.authorId=u.id')
                        ->leftJoin('{{yuristSettings}} u_s', 'u.id=u_s.yuristId')
                        ->leftJoin('{{town}} t', 't.id=u.townId')
                        ->leftJoin('{{user2category}} u2c', 'u2c.uId=u.id')
                        ->leftJoin('{{questionCategory}} q_c', 'q_c.id = u2c.cId')
                        ->where('a.status IN(0,1) AND u.id=:uid AND q_c.id IS NOT NULL', array(':uid'=>$yurisId['id']))
                        ->group('q_c.id')
                        ->limit(3)
                        ->queryAll();
                
                
                foreach($yuristInfo as $yInfo){
                    $users[$yInfo['id']]['id'] =  $yInfo['id'];
                    $users[$yInfo['id']]['name'] =  $yInfo['name'];
                    $users[$yInfo['id']]['lastName'] =  $yInfo['lastName'];
                    $users[$yInfo['id']]['alias'] =  $yInfo['alias'];
                    $users[$yInfo['id']]['avatar'] =  $yInfo['avatar'];
                    $users[$yInfo['id']]['town'] =  $yInfo['townName'];
                    $users[$yInfo['id']]['answersCount'] =  $yInfo['answersCount'];                    
                    
                    $users[$yInfo['id']]['categories'][] = $yInfo['catName'];
                }
                
                
                
                       
            }

        
            Yii::app()->cache->set('top_yurists', $users, $this->cacheTime);
        }
        $this->render($this->template, array(
            'users' =>  $users,
        ));
    }
}
?>