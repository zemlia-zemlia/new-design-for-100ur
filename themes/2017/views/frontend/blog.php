<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._header');
?>

    <div class="top-form-replace"></div>

    <div class="container">
        <div class="row">
            <div id="left-panel"></div>

            <div class="col-sm-8" id="center-panel">
                <?php echo $content; ?>
            </div>

            <div class="col-sm-4" id="right-panel">

                <div class="inside article-preview">
                    <h3>Самые обсуждаемые статьи</h3>
                    <?php
                    $this->widget('application.widgets.RecentPosts.RecentPosts', [
                        'number' => 5,
                        'order' => 'comments',
                        'intervalDays' => '10000',
                    ]);
                    ?>
                </div>

                <div class="inside article-preview">
                    <h3>Самые популярные статьи</h3>
                    <?php
                    $this->widget('application.widgets.RecentPosts.RecentPosts', [
                        'number' => 5,
                        'order' => 'views',
                        'intervalDays' => 800,
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._footer');
?>