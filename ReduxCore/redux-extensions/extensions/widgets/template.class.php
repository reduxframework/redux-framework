
    /**
     * Adds {{CLASS}} widget.
     */
    class {{CLASS}} extends WP_Widget {

        function __construct() {
            parent::__construct(
                'Redux_Widget_{{ID}}', // Base ID of your widget, diff than class!
                '{{TITLE}}', // Widget name will appear in UI
                array(
                    'description' => '{{DESCRIPTION}}' // Widget description
                )
            );
        }

        function after_init( $args, $instance ) {

        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget( $args, $instance ) {
            echo $args['before_widget'];
    if ( ! empty( $title ) )
    echo $args['before_title'] . $title . $args['after_title'];
    echo __( 'Hello, World!', 'text_domain' );


    print_r($args);
    echo "<hr>";
    print_r($instance);
            do_action('redux/widgets/{{OPT_NAME}}/{{ID}}/render', $args, $instance);
            echo $args['after_widget'];
            //add_action('init', array( $this, 'after_init' ), $args, $instance, 30, 3 );
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form( $instance ) {
    print_r($instance);
        ?>
            {{SUBTITLE}}
        <?php
            if (isset($GLOBALS['redux-widget-{{OPT_NAME}}-{{ID}}']) && !empty($GLOBALS['redux-widget-{{OPT_NAME}}-{{ID}}'])) {
                echo '<div class="redux-main" style="margin:0;border:0;">';

                foreach ($GLOBALS['redux-widget-{{OPT_NAME}}-{{ID}}'] as $field) {

                    $field->value = isset( $$field->field['id'] ) ? esc_attr( $$field->field['id'] ) : '';
                    if (empty($field->value) && isset($field->field['default'])) {
                        $field->value = $field->field['default'];
                    }

                    $field->field['id'] = $this->get_field_id( $field->field['id'] );
                    $field->field['name'] = $this->get_field_name( $field->field['name'] );

                ?>
                    <fieldset id="widget-{{OPT_NAME}}-<?php echo $field->field['id'] ?>"
                              class="redux-field-container redux-field redux-container-<?php echo $field->field['type'] ?> "
                              data-id="<?php echo $field->field['id'] ?>">

                    </fieldset>
                <?php
                }
                echo '</div>';
            }


        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update( $new_instance, $old_instance ) {
            print_r($old_instance);
            print_r($new_instance);
            exit();
            $instance = array();
            foreach($new_instance as $k => $v) {
                $new_instance[$k] = strip_tags( $v );
            }
            return $instance;
        }

    } // class {{CLASS}}

    add_action( 'widgets_init', create_function('', 'return register_widget("{{CLASS}}");') );
