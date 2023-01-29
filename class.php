<?php


/*
 * Check if WooCommerce is active
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    function hsn_shipping_method()
    {
        if (!class_exists('HSN_Shipping_Method')) {
            class HSN_Shipping_Method extends WC_Shipping_Method
            {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct()
                {
                    $this->id                 = 'hsn';
                    $this->method_title       = __('HSN Shipping', 'hsn');
                    $this->method_description = __('Custom Shipping Method for HSN', 'hsn');

                    // Availability & Countries
                    /* $this->availability = 'including';
                            $this->countries = array(
                            'US', // Unites States of America
                            'CA', // Canada
                            'DE', // Germany
                            'GB', // United Kingdom
                            'IT',   // Italy
                            'ES', // Spain
                            'HR'  // Croatia
                            );
                        */
                    $countries_obj = new WC_Countries();
                    $countries = $countries_obj->__get('countries');
                    $default_country = $countries_obj->get_base_country();
                    $default_county_states = $countries_obj->get_states('IT');


                    $this->state = $default_county_states;

                    $this->country = $countries;

                    $this->init();

                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->state = isset($this->settings['state']) ? $this->settings['state'] : '';
                    $this->country = isset($this->settings['country']) ? $this->settings['country'] : '';
                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('HSN Shipping', 'hsn');
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void
                 */
                function init_form_fields()
                {

                    $this->form_fields = array(

                        'enabled' => array(
                            'title' => __('Enable', 'hsn'),
                            'type' => 'checkbox',
                            'description' => __('Enable this shipping.', 'hsn'),
                            'default' => 'yes'
                        ),

                        'corriere' => array(
                            'title' => __('corriere', 'hsn'),
                            'type' => 'select',

                            'options' =>  array('HSN'),
                        ),

                        'country' => array(
                            'title' => __('Country', 'hsn'),
                            'type' => 'multiselect',
                            'options' =>   $this->country,
                        ),

                        'state' => array(
                            'title' => __('Provincia', 'hsn'),
                            'type' => 'multiselect',
                            'options' =>   $this->state,
                        ),
                        'HSNactive' => array(
                            'title' => __('Stato', 'hsn'),
                            'type' => 'select',
                            'options' =>   array('non attivo', 'attivo'),
                        ),
                        'weight' => array(
                            'title' => __('Peso volume', 'hsn'),
                            'type' => 'number',
                            'description' => __('Maximum allowed weight', 'hsn'),
                            'default' => 100
                        ),


                    );
                }
            }
        }
    }

    add_action('woocommerce_shipping_init', 'hsn_shipping_method');

    function add_hsn_shipping_method($methods)
    {
        $methods[] = 'HSN_Shipping_Method';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_hsn_shipping_method');
}
