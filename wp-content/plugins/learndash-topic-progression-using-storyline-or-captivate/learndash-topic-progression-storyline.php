<?php
/*
Plugin Name: LearnDash Topic Progression Using Storyline/Captivate
Plugin URI: http://www.discoverelearninguk.com
Description: Allows a topic page in LearnDash to be completed through an action conducted in Storyline or Captivate embedded content.
Author: Discover eLearning
Version: 1.0
Author URI: http://www.discoverelearninguk.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

LearnDash Topic Progression Using Storyline/Captivate is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
LearnDash Topic Progression Using Storyline/Captivate is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with LearnDash Topic Progression Using Storyline/Captivate. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/


require_once  __DIR__ . '/CMB2/init.php';

class DLUK_LearnDash_Integration {

    protected $prefix = '_dluk_';

    public function __construct() {
        add_action( 'cmb2_admin_init', array( $this, 'register_metabox' ) );
        add_filter( 'learndash_mark_complete', array( $this, 'toggle_mark_complete_button' ), 10, 2 );
    }

    public function register_metabox() {
        $box = new_cmb2_box( array(
            'id'            => $this->prefix . 'learndash_metabox',
            'title'         => 'Topic Progress Option',
            'object_types'  => array( 'sfwd-topic' ), // Post type
            'context'    => 'side',
            'priority'   => 'default',
        ) );

        $box->add_field( array(
            // 'name' => 'Hide "Mark Complete" Button',
            'id'   => $this->prefix . 'hide_mark_complete_button',
            'desc' => 'Hide the "Mark Complete" button below the topic content. <br/><br/><a href="http://www.discoverelearninguk.com/products/learndash-topic-progression-using-storyline-captivate-plugin" target="_blank">Click here</a> for instructions on how to use this option with Articulate Storyline / Adobe Captivate.',
            'type' => 'checkbox',
        ) );
    }

    public function toggle_mark_complete_button( $return, $post ) {

        if ( $post->post_type === 'sfwd-topic' ) {
            $hide_button = get_post_meta( $post->ID, $this->prefix . 'hide_mark_complete_button', true );

            if ( $hide_button === "on" ) {
                ob_start();
                include 'partials/sfwd-mark-complete-form.php';
                $return = ob_get_clean();
                add_action( 'wp_footer', array( $this, 'trigger_form_complete' ), 100 );
            }
        }

        return $return;
    }

    // Normally we would enqueue JavaScript, but this is such a small script
    // that it's not worth the extra HTTP request.
    public function trigger_form_complete() {
        ob_start();
        include 'partials/quiz-mark-as-complete.php';
        $js = ob_get_clean();
        echo $js;
    }

}

$dluk_learndash_integration = new DLUK_LearnDash_Integration();
