<?php
function photo_sphere_viewer_callback($atts) {
    $default = [
        'id'                     => '',
        'class'                  => '',
        'image'                  => PSV_ASSETS_URL . 'img/bryce-canyon-national-park.jpg',
        'caption'                => esc_html__('From shortcodes', 'photo-sphere-viewer'),
        'navbar'                 => 'autorotate,download,caption,fullscreen',
        'width'                  => 100, //vw
        'height'                 => 80, //vh
        'min_fov'                => 30,
        'max_fov'                => 90,
        'default_zoom_lavel'     => 50,
        'default_longitude'      => 0,
        'default_latitude'       => 0,
        'longitude_range'        => 0,
        'latitude_range'         => 0,
        'autorotate_delay'       => '',
        'autorotate_speed'       => 10,
        'fish_eye'               => 'no',
        'mouse_wheel'            => 'yes',
        'mousewheel_ctrl_key'    => 'no',
        'mouse_move'             => 'yes',
        'touch_move_two_fingers' => 'yes',
        'capture_cursor'         => 'no',
        'move_speed'             => 1,
        'mouse_wheel_speed'      => 1,
        'zoom_button_increment'  => 2,
        'canvas_background'      => '#333',
    ];
    $atts             = shortcode_atts($default, $atts, 'photo_sphere_viewer');
    $id               = wp_unique_id('xero-psv-container-');
    $navbar_options   = explode(',', $atts['navbar']);
    $autorotate       = ($atts['autorotate_delay'] !== '') ? $atts['autorotate_delay'] : false;
    $autorotate_speed = ($atts['autorotate_speed'] !== '') ? (float) ($atts['autorotate_speed'] / 100) : 0.2;
    $data_settings    = [
        wp_json_encode(array_filter([
            'container'           => $id,
            'panorama'            => $atts['image'],
            'caption'             => [$atts['caption']],
            'navbar'              => $navbar_options,
            'size'                => [
                'width'  => $atts['width'] . '%',
                'height' => $atts['height'] . 'vh',
            ],
            'minFov'              => $atts['min_fov'],
            'maxFov'              => $atts['max_fov'],
            'defaultZoomLvl'      => $atts['default_zoom_lavel'],

            'defaultLong'         => $atts['default_longitude'],
            'defaultLat'          => $atts['default_latitude'],
            'longitudeRange'      => $atts['longitude_range'],
            'latitudeRange'       => $atts['latitude_range'],
            'autorotateDelay'     => $autorotate,
            'autorotateSpeed'     => $autorotate_speed,
            'fisheye'             => ('yes' === $atts['fish_eye']) ? true : false,
            'mousewheel'          => ('yes' === $atts['mouse_wheel']) ? true : false,
            'mousewheelCtrlKey'   => ('yes' === $atts['mousewheel_ctrl_key']) ? true : false,
            'mousemove'           => ('yes' === $atts['mouse_move']) ? true : false,
            'touchmoveTwoFingers' => ('yes' === $atts['touch_move_two_fingers']) ? true : false,
            'captureCursor'       => ('yes' === $atts['capture_cursor']) ? true : false,
            'moveSpeed'           => $atts['move_speed'],
            'mousewheelSpeed'     => $atts['mouse_wheel_speed'],
            'zoomButtonIncrement' => $atts['zoom_button_increment'],
            'canvasBackground'    => $atts['canvas_background'],
        ])),
    ];
    return "<div class='xero-psv-container'><div id='" . esc_attr($id) . "' data-settings='" . esc_attr($data_settings[0]) . "'></div></div>";
}
add_shortcode('photo_sphere_viewer', 'photo_sphere_viewer_callback');
