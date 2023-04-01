<!-- s-footer
================================================== -->
<footer class="s-footer">

    <div class="s-footer__main">
        <div class="row">

            <div class="col-six tab-full s-footer__about">

                <h4><?php bloginfo('name'); ?></h4>

                <p><?= get_theme_mod('museum-address', 'Улица Пушкина, Дом Колотушкина') ?></p>

            </div> <!-- end s-footer__about -->

            <div class="col-six tab-full s-footer__about mob-full sitelinks">
                <h4>Навигация</h4>

                <?php wp_nav_menu([
                    'menu_class' => 'linklist',
                    'depth'      => 1
                ]) ?>
                <!-- <ul class="linklist">
                    <li><a href="#0">Home</a></li>
                    <li><a href="#0">Blog</a></li>
                    <li><a href="#0">Styles</a></li>
                    <li><a href="#0">About</a></li>
                    <li><a href="#0">Contact</a></li>
                    <li><a href="#0">Privacy Policy</a></li>
                </ul> -->
            </div>

        </div>
    </div> <!-- end s-footer__main -->

    <div class="go-top">
        <a class="smoothscroll" title="Back to Top" href="#top"></a>
    </div>

</footer> <!-- end s-footer -->

<?php wp_footer(); ?>

</body>

</html>