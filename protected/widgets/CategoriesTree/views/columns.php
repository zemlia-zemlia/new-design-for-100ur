
<!-- Categories -->
<section class="categories">
    <div class="container">
        <h2 class="categories__title section-title">Категории справочных материалов</h2>
        <div class="row">

            <?php $index = 0;

            foreach ($topCategories as $key=> $category): ?>

                <?php
                if (0 == $index % 5): ?>
                    <div class="col-sm-3">
                <?php endif; ?>
                        <div class="categories__list-wrap">
                            <div class="categories__list-title"><?= $key ?></div>
                            <ul class="categories__list">
                    <?php foreach ($category as $cat) : ?>
                                <li class="categories__list-item">
                                    <a href="<?= Yii::app()->createUrl('questionCategory/alias', ['name' => CHtml::encode($cat['alias'])]) ?>" class="categories__list-link">
                    <?php if (isset($cat['icon'])) : ?>
                                <span class="categories__list-link-img img">
                                    <img src="/upload/category_icons/<?= $cat['icon']; ?>" width="30" alt="<?= CHtml::encode($cat['name']); ?>">
                                </span>
                    <?php endif; ?>
                            <span class="categories__list-link-title"><?= CHtml::encode($cat['name'])?></span>
                                    </a>
                                </li>
                 <?php endforeach; ?>
                </ul>
                </div>
                <?php if (4 == $index % 5 ): ?>

                    </div>
                <?php endif; ?>
            <?php $index++;
            endforeach; ?>
        </div>
    </div>

</section>




