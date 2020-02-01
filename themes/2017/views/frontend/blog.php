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
                    <h3>Обсуждаемые новости</h3>
                    <?php
                    $this->widget('application.widgets.RecentPosts.RecentPosts', [
                        'number' => 5,
                        'order' => 'comments',
                        'intervalDays' => '10000',
                    ]);
                    ?>
                </div>

                <div class="inside article-preview">
                    <h3>Популярные новости</h3>
                    <?php
                    $this->widget('application.widgets.RecentPosts.RecentPosts', [
                        'number' => 5,
                        'order' => 'views',
                        'intervalDays' => 800,
                    ]);
                    ?>
                </div>
                
                <div class="inside">
                    <!-- Yandex.RTB R-A-279595-3 -->
                    <div id="yandex_rtb_R-A-279595-3"></div>
                    <script type="text/javascript">
                        (function(w, d, n, s, t) {
                            w[n] = w[n] || [];
                            w[n].push(function() {
                                Ya.Context.AdvManager.render({
                                    blockId: "R-A-279595-3",
                                    renderTo: "yandex_rtb_R-A-279595-3",
                                    async: true
                                });
                            });
                            t = d.getElementsByTagName("script")[0];
                            s = d.createElement("script");
                            s.type = "text/javascript";
                            s.src = "//an.yandex.ru/system/context.js";
                            s.async = true;
                            t.parentNode.insertBefore(s, t);
                        })(this, this.document, "yandexContextAsyncCallbacks");
                    </script>
                </div>

            </div>
        </div>
    </div>

<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._footer');
?>