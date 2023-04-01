<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php wp_head(); ?>
</head>

<body id="top">

<!-- preloader
================================================== -->
<div id="preloader">
    <div id="loader" class="dots-fade">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>


<!-- header
================================================== -->
<header class="s-header header">

    <h5 class="header__logo">
        <a class="logo" href="<?php bloginfo('url') ?>">
            <?php bloginfo('name'); ?>
        </a>
    </h5> <!-- end header__logo -->

    <a class="header__toggle-menu" href="#0" title="Menu"><span>Menu</span></a>
    <nav class="header__nav-wrap">

        <h2 class="header__nav-heading h6">Navigate to</h2>

        <?php wp_nav_menu([
            'menu'       => 'ul',
            'container'  => null,
            'menu_class' => 'header__nav',
            'depth'      => 2
        ]) ?>
        <!-- <ul class="header__nav">
            <li class="current"><a href="index.html" title="">Home</a></li>
            <li class="has-children">
                <a href="#0" title="">Categories</a>
                <ul class="sub-menu">
                    <li><a href="category.html">Lifestyle</a></li>
                    <li><a href="category.html">Health</a></li>
                    <li><a href="category.html">Family</a></li>
                    <li><a href="category.html">Management</a></li>
                    <li><a href="category.html">Travel</a></li>
                    <li><a href="category.html">Work</a></li>
                </ul>
            </li>
            <li class="has-children">
                <a href="#0" title="">Blog</a>
                <ul class="sub-menu">
                    <li><a href="single-video.html">Video Post</a></li>
                    <li><a href="single-audio.html">Audio Post</a></li>
                    <li><a href="single-standard.html">Standard Post</a></li>
                </ul>
            </li>
            <li><a href="style-guide.html" title="">Styles</a></li>
            <li><a href="page-about.html" title="">About</a></li>
            <li><a href="page-contact.html" title="">Contact</a></li>
        </ul> -->

        <a href="#0" title="Close Menu" class="header__overlay-close close-mobile-menu">Close</a>

    </nav> <!-- end header__nav-wrap -->

</header> <!-- s-header -->
