<?php
/*
Plugin Name: QRPage
*/

function qrpage() {
    wp_enqueue_script('jquery-qrpage', plugin_dir_url(__FILE__) . '_inc/qrcodejs/jquery.min.js', false, '1.0', false);
    wp_enqueue_script('qrpage', plugin_dir_url(__FILE__) . '_inc/qrcodejs/qrcode.min.js', ['jquery-qrpage'], '1.0', false);

    $url = get_permalink();
    $html = <<<HTML
        <div id="qrcode" style="width:128px; height:128px"></div>

        <script type="text/javascript">
        document.addEventListeners('DOMContentLoaded', () => {
            new QRCode('qrcode', {
                text: "$url",
                width: 128,
                height: 128,
                colorDark : '#000000',
                colorLight : '#ffffff',
                correctLevel : QRCode.CorrectLevel.H
            })
        })
        </script>
    HTML;
    return $html;
}

add_shortcode('qrpage', 'qrpage');