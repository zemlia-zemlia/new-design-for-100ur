<?php
class Tree extends CWidget {
    public function run() {
        $model = Cat::model()->findByPK(1); // ����� ������ Categories ������ �� ���� ������
        $tree = $model->getTreeViewData(false);
        $this->render('tree',array('tree'=>$tree,));
    }
}