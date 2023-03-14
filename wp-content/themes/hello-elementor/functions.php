<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '2.6.1' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_load_textdomain', [ true ], '2.0', 'hello_elementor_load_textdomain' );
		if ( apply_filters( 'hello_elementor_load_textdomain', $hook_result ) ) {
			load_theme_textdomain( 'hello-elementor', get_template_directory() . '/languages' );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_register_menus', [ true ], '2.0', 'hello_elementor_register_menus' );
		if ( apply_filters( 'hello_elementor_register_menus', $hook_result ) ) {
			register_nav_menus( [ 'menu-1' => __( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => __( 'Footer', 'hello-elementor' ) ] );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_add_theme_support', [ true ], '2.0', 'hello_elementor_add_theme_support' );
		if ( apply_filters( 'hello_elementor_add_theme_support', $hook_result ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			$hook_result = apply_filters_deprecated( 'elementor_hello_theme_add_woocommerce_support', [ true ], '2.0', 'hello_elementor_add_woocommerce_support' );
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', $hook_result ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$enqueue_basic_style = apply_filters_deprecated( 'elementor_hello_theme_enqueue_style', [ true ], '2.0', 'hello_elementor_enqueue_style' );
		$min_suffix          = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', $enqueue_basic_style ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_register_elementor_locations', [ true ], '2.0', 'hello_elementor_register_elementor_locations' );
		if ( apply_filters( 'hello_elementor_register_elementor_locations', $hook_result ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

/**
 * If Elementor is installed and active, we can load the Elementor-specific Settings & Features
*/

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';

/**
 * Include customizer registration functions
*/
function hello_register_customizer_functions() {
	if ( is_customize_preview() ) {
		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_register_customizer_functions' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * Wrapper function to deal with backwards compatibility.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	}
}



// Redirigir al formulario de inicio de sesión si el usuario no ha iniciado sesión antes de ir al proceso de pago
add_action( 'template_redirect', 'redireccionar_a_login_si_no_ha_iniciado_sesion' );
function redireccionar_a_login_si_no_ha_iniciado_sesion() {
  if ( is_checkout() && ! is_user_logged_in() ) {
    wp_redirect( home_url('/registro/') );
    exit;
  }
}



function desactivar_notificaciones_admin() {
    remove_all_actions('admin_notices');
}
add_action('admin_init', 'desactivar_notificaciones_admin');


add_action( 'template_redirect', 'custom_checkout_redirect', 1 );
function custom_checkout_redirect() {
	if ( is_checkout() && isset( $_REQUEST['add-to-cart'] ) ) {
		$product_id = absint( $_REQUEST['add-to-cart'] );
		
		if ( ! empty( $product_id ) ) {
			WC()->cart->empty_cart();
			WC()->cart->add_to_cart( $product_id, 1 );
		}
	}
}


function mostrar_avatar_yith_shortcode() {
  if ( is_user_logged_in() ) {
    global $wpdb;
    $user_id = get_current_user_id();
    $avatar_id = get_user_meta( $user_id, 'yith-wcmap-avatar', true );
    error_log('Avatar ID: ' . $avatar_id);

    if ( $avatar_id ) {
      $avatar_url = $wpdb->get_var( $wpdb->prepare( "SELECT guid FROM $wpdb->posts WHERE ID = %d", $avatar_id ) );
      error_log('Avatar URL: ' . $avatar_url);
      return '<img src="' . esc_url( $avatar_url ) . '" alt="Avatar" style="    border-radius: 50%;width: 32px;height: 32px;vertical-align: middle;margin-right: 10px;border: 2px solid white;" />';
    }
  }

  return '';
}

add_shortcode('avatar_yith', 'mostrar_avatar_yith_shortcode');



function redirect_after_logout() {
    wp_redirect( home_url() );
    exit();
}
add_action('wp_logout','redirect_after_logout');

add_action( 'template_redirect', 'redirect_to_login_if_not_logged_in' );

function redirect_to_login_if_not_logged_in() {
    if ( is_account_page() && ! is_user_logged_in() ) {
        wp_redirect( home_url( '/login/' ) );
        exit;
    }
}

add_action( 'template_redirect', 'bgwp_redirect_empty_cart' );

function bgwp_redirect_empty_cart() {
    if ( is_cart() && WC()->cart->is_empty() ) {
        wp_safe_redirect( home_url( '/planes/' ) );
        exit;
    } elseif ( is_cart() ) {
        wp_safe_redirect( wc_get_checkout_url() );
        exit;
    }
}



function ocultar_alertas_checkout() {
  if (is_checkout()) {
    wc_clear_notices();
  }
}
add_action('template_redirect', 'ocultar_alertas_checkout');


// Agregar estilos CSS para las tarjetas de producto
function agregar_estilos_css() {
  ?>
  <style>
    .tarjeta-producto {
      display: block;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-shadow: 0 0 5px rgba(0,0,0,0.3);
      padding: 10px;
      margin-bottom: 20px;
      text-decoration: none;
      color: #333;
    }
    .tarjeta-producto:hover {
      border-color: #7839ee;
    }
    .tarjeta-producto.seleccionado {
      border-color: #7839ee;
      background-color: #f5f5f5;
    }
    .tarjeta-producto h3 {
      font-size: 18px;
      margin-top: 0;
      margin-bottom: 10px;
    }
    .tarjeta-producto p {
      font-size: 16px;
      margin-top: 0;
      margin-bottom: 10px;
    }
    .tarjeta-producto a {
      color: black;
    }
  </style>
  <?php
}

add_action('wp_head', 'agregar_estilos_css');

function mostrar_tarjetas_productos() {
  $product_ids = array(17, 19, 20);
  $selected_product_id = isset($_REQUEST['add-to-cart']) ? (int) $_REQUEST['add-to-cart'] : 0;
  $cart_items = WC()->cart->get_cart();
  foreach ($product_ids as $product_id) {
    $product = wc_get_product($product_id);
    if (!$product) {
      continue;
    }
    $classes = array('tarjeta-producto');
    if ($selected_product_id === $product_id) {
      $classes[] = 'seleccionado';
    }
    foreach ($cart_items as $cart_item_key => $cart_item) {
      if ($cart_item['product_id'] === $product_id) {
        $classes[] = 'seleccionado';
        break;
      }
    }
    ?>
    <div class="<?php echo implode(' ', $classes); ?>">
      <?php if (in_array('seleccionado', $classes)): ?>
        <span>
          <h3><?php echo $product->get_name(); ?></h3>
          <p><?php echo $product->get_price_html(); ?></p>
        </span>
      <?php else: ?>
        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="tarjeta-link">
          <h3><?php echo $product->get_name(); ?></h3>
          <p><?php echo $product->get_price_html(); ?></p>
        </a>
      <?php endif; ?>
    </div>
    <?php
  }
}
add_shortcode('mostrar_tarjetas', 'mostrar_tarjetas_productos');

function agregar_script_seleccion() {
  ?>
  <style>
    .loader-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }
    
    .loader {
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid #3498db;
      border-radius: 50%;
      animation: spin 2s linear infinite;
    }
    
    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
    
    body.no-scroll {
      overflow: hidden;
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var tarjetaLinks = document.querySelectorAll('.tarjeta-link');
      tarjetaLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
          event.preventDefault();
          var loaderOverlay = document.createElement('div');
          loaderOverlay.classList.add('loader-overlay');
          var loader = document.createElement('div');
          loader.classList.add('loader');
          loaderOverlay.appendChild(loader);
          document.body.appendChild(loaderOverlay);
          document.body.classList.add('no-scroll');
          location.href = link.href;
        });
      });
    });
  </script>
  <?php
}
add_action('wp_footer', 'agregar_script_seleccion');





add_shortcode('convert_table_to_vertical_header', 'convert_table_to_vertical_header');
function convert_table_to_vertical_header() {
    $table_output = do_shortcode('[ywcmap_woocommerce_subscription]');
    $html = new DOMDocument();
    $html->loadHTML('<?xml encoding="UTF-8">' . $table_output);
    $rows = $html->getElementsByTagName('tr');
    $total_rows = $rows->length;
    foreach ($rows as $index => $row) {
        if ($index === 0) {
            $row->getElementsByTagName('th')[0]->textContent = 'Suscripción #' . $row->getElementsByTagName('td')[0]->textContent;
            continue;
        }
        $new_table = '<table class="tabla-suscripcion">';
        $cells = $row->getElementsByTagName('td');
        $is_last_row = $index === ($total_rows - 1);
        $row_class = $is_last_row ? ' class="ultima-fila"' : '';
        $fila_id = 1; // variable para el identificador de fila
        $new_table .= '<tr id="fila-' . $fila_id . '"><th>Suscripción ' . $cells[0]->textContent . '</th><td><a href="' . $cells[4]->getElementsByTagName('a')[0]->getAttribute('href') . '" class="woocommerce-button button view">Ver detalles</a></td></tr>';
        foreach ($cells as $cell) {
            if ($cell->getAttribute('data-title') === 'ID' || $cell->getAttribute('data-title') === 'Actions') {
                continue;
            }
            $fila_id++; // aumentar identificador de fila en uno
            $new_table .= '<tr id="fila-' . $fila_id . '"><th>' . $cell->getAttribute('data-title') . '</th><td>' . $cell->textContent . '</td></tr>';
        }
        $new_table .= '</table>';
        echo $new_table;
    }
}











add_action( 'user_register', 'bgwp_send_user_data', 10, 1 );

function bgwp_send_user_data( $user_id ) {

    //Obtener el usuario recién registrado
    $user = get_userdata( $user_id );

    //Información del usuario a enviar
    $user_data = array(
        'email' => $user->user_email,
        'password' => $user->user_pass,
        'id' => $user->ID,
        'user' => $user->user_login,
    );

    //Enviar datos a través de la API
    $response = wp_remote_post(
        'http://46.101.159.194:47300/bgwp/register_user',
        array(
            'headers' => array(
                'Authorization' => 'API-KEY bgwp_marcou8XoL0b47ImGhZsSuwzHCxX0qC6jQhljhhsGYHjisq57KY2rp37miOZsCNRLt0vtsnNIqn9eZdtyr1OFQGaa2toPTpZgDRqPG9PEWV9onuoS7duFz7DSOO4AWowA4qC4stSnlRZ2BsySBaoKohGhX6MQYKq1uW5WE7VaJ0l0go1omtHVumG4ey4TuLzutcrsujwqbBgmGnMxtMk6ZSKRNT3jKrdqEdzcgHzyz8YU5bPn9KcsJ2PY99v85hP4Gp8cB0qyaYzSIGgY9E4oRgTUJwcWv1Y3GtwmMItNdWWcrU0v8tShVGLbIYGarpVtCbAAAfhSvcnnIdnStO404uBmBKeatlCb5O4l9o7awScqCuRmsHllURQrfCRCFbcuu8KS5pZbV1CtYoKHgIVFxTsKSIIIzTKYcJhceaceROOsMpIKpNQXXBNmg3ovKxaBiA0QeLa1N1plhPCSTzCQaTObrZB1UtXqglwcZ5hPb0cRHgT3yXFRmuwzyrIgUBVthWcOzVDPMflZ0OiP9kVCy4vsfs5ufJANXBmUDQGGYLtJvljImZdtZG1KBnQQd4W1k14GJ4S3bBfoUOMxWVh93hHh96OiZ512r1csceu5yV6BrS4q67NX4dzpoy00XlfTmyADxbOHRljF9ERybNIyeBJjlxO8hyd2E8KLRyVsCyqEkTdgNYbCEtZrX4qI9g7SCCjGgmdtPyDMZsGNlP271kcJzfoouH8W4vSBJ01k9WZkNedVPLAh55YbdQBFyRYaujbenuNUgqXY3lF1N5B9j1TxJ1LiYEhX3BKZxXLic8yNNSOalymqpthaGaNNimC1TorUVdmcx7T7CdxmuGdAm8o6MhabQCKGuEfI6OhS8Am8n09lWfffkvJ23KTacL2UuEhqG2aLB2q3vq9flHAJG7z4zEnZ5oGZ7VwjAUXvqQqiTi3kfNF5LmedecUDFgy21NBXxRzVfS1Lh05ajslpngHkbYS6JKvwRcR2pEfOFwUrd4sOZ58toBsZRf',
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $user_data )
        )
    );

    //Verificar si hubo un error en la respuesta
    if ( is_wp_error( $response ) ) {
        //Manejar el error
        error_log( $response->get_error_message() );
    } else {
        //Obtener el token de la respuesta
        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        $token = $data['token'];

        //Establecer una cookie para el token válido por 24 horas
        setcookie( 'bgwp_token', $token, time() + 86400, '/' );
    }
}

// display api response in popup on home page
add_action( 'wp_footer', 'display_api_response_popup' );

function display_api_response_popup() {
    if ( is_front_page() && isset( $_COOKIE['bgwp_token'] ) ) {
        $token = $_COOKIE['bgwp_token'];
        echo "<script>alert('" . $token . "');</script>";
    }
}

// add action to perform before user login
add_action( 'wp_authenticate', 'send_login_data_to_api', 10, 2 );

function send_login_data_to_api( $username, $password ) {

    // get user data
    $user = get_user_by( 'login', $username );
    $user_email = $user->user_email;
    $user_password = $password;
    $user_id = $user->ID;
    
    // create json data
    $data = array(
        'email' => $user_email,
        'password' => $user->user_pass,
        'id' => $user_id
    );
    $json_data = json_encode( $data );
    
    // set header
    $headers = array(
        'Authorization' => 'API-KEY bgwp_marcou8XoL0b47ImGhZsSuwzHCxX0qC6jQhljhhsGYHjisq57KY2rp37miOZsCNRLt0vtsnNIqn9eZdtyr1OFQGaa2toPTpZgDRqPG9PEWV9onuoS7duFz7DSOO4AWowA4qC4stSnlRZ2BsySBaoKohGhX6MQYKq1uW5WE7VaJ0l0go1omtHVumG4ey4TuLzutcrsujwqbBgmGnMxtMk6ZSKRNT3jKrdqEdzcgHzyz8YU5bPn9KcsJ2PY99v85hP4Gp8cB0qyaYzSIGgY9E4oRgTUJwcWv1Y3GtwmMItNdWWcrU0v8tShVGLbIYGarpVtCbAAAfhSvcnnIdnStO404uBmBKeatlCb5O4l9o7awScqCuRmsHllURQrfCRCFbcuu8KS5pZbV1CtYoKHgIVFxTsKSIIIzTKYcJhceaceROOsMpIKpNQXXBNmg3ovKxaBiA0QeLa1N1plhPCSTzCQaTObrZB1UtXqglwcZ5hPb0cRHgT3yXFRmuwzyrIgUBVthWcOzVDPMflZ0OiP9kVCy4vsfs5ufJANXBmUDQGGYLtJvljImZdtZG1KBnQQd4W1k14GJ4S3bBfoUOMxWVh93hHh96OiZ512r1csceu5yV6BrS4q67NX4dzpoy00XlfTmyADxbOHRljF9ERybNIyeBJjlxO8hyd2E8KLRyVsCyqEkTdgNYbCEtZrX4qI9g7SCCjGgmdtPyDMZsGNlP271kcJzfoouH8W4vSBJ01k9WZkNedVPLAh55YbdQBFyRYaujbenuNUgqXY3lF1N5B9j1TxJ1LiYEhX3BKZxXLic8yNNSOalymqpthaGaNNimC1TorUVdmcx7T7CdxmuGdAm8o6MhabQCKGuEfI6OhS8Am8n09lWfffkvJ23KTacL2UuEhqG2aLB2q3vq9flHAJG7z4zEnZ5oGZ7VwjAUXvqQqiTi3kfNF5LmedecUDFgy21NBXxRzVfS1Lh05ajslpngHkbYS6JKvwRcR2pEfOFwUrd4sOZ58toBsZRf',
        'Content-Type' => 'application/json'
    );
    
    // send post request
    $args = array(
        'headers' => $headers,
        'body' => $json_data,
        'timeout' => '5'
    );
    $response = wp_remote_post( 'http://46.101.159.194:47300/bgwp/login_user', $args );
if ( is_wp_error( $response ) ) {
    // if there is an error with the API, allow user to login normally
    return;
}
    
    // get token from api response and set cookie
    $api_response = json_decode( $response['body'], true );
     if ( isset( $api_response['error'] ) && ! empty( $api_response['error'] ) ) {
         wp_die( 'Usuario no autorizado' );
    }
    if ( isset( $api_response['token'] ) ) {
        $token = $api_response['token'];
        setcookie( 'bgwp_token', $token, time() + 86400, '/' );
        echo "<script>alert('Token: " . $token . "');</script>";
    }
}





add_action( 'woocommerce_thankyou', 'bgwp_redirect_to_my_account' );

function bgwp_redirect_to_my_account( $order_id ) {
    $order = wc_get_order( $order_id );
    $user_id = $order->get_user_id();
    $redirect_url = wc_get_page_permalink( 'myaccount' );
    wp_safe_redirect( $redirect_url );
    exit;
}







add_filter( 'woocommerce_add_error', '__return_false' );
add_filter( 'woocommerce_add_success', '__return_false' );















add_shortcode( 'crear_usuario', 'render_user_registration_form' );
function render_user_registration_form() {
    $output = '';
    if ( isset( $_POST['submit'] ) ) {
        $username = sanitize_user( $_POST['email'] );
        $email    = sanitize_email( $_POST['email'] );
        $password = $_POST['password'];
        $first_name = sanitize_text_field( $_POST['first_name'] );
        $last_name = sanitize_text_field( $_POST['last_name'] );
        $errors = new WP_Error();
        if ( empty( $username ) || empty( $email ) || empty( $password ) || empty( $first_name ) || empty( $last_name ) ) {
            $errors->add( 'field', 'Por favor completa todos los campos.' );
        }
        if ( ! is_email( $email ) ) {
            $errors->add( 'email_invalid', 'El correo electrónico no es válido.' );
        }
        if ( username_exists( $username ) || email_exists( $email ) ) {
            $errors->add( 'user_exists', 'El usuario ya existe.' );
        }
        if ( ! $errors->has_errors() ) {
            $user_id = wp_create_user( $username, $password, $email );
            if ( ! is_wp_error( $user_id ) ) {
                update_user_meta( $user_id, 'first_name', $first_name );
                update_user_meta( $user_id, 'last_name', $last_name );
                $parent_id = get_current_user_id();
                update_user_meta( $user_id, 'usuario_padre', $parent_id );
                $output .= __( 'Usuario creado con éxito.', 'text-domain' );
    $usuario_padre = get_current_user_id();
    $nombre_completo = $first_name . ' ' . $last_name;
    $token = $_COOKIE['bgwp_token'];
    $data2 = array(
        'name' => $nombre_completo,
        'email' => $email,
        'password' => $password,
        'role' => 'cotizador',
        'id_wordpress' => $user_id,
        'id_wordpress_admin' => $usuario_padre
    );
        $headers2 = array(
        'Authorization' => 'API-KEY bgwp_marcou8XoL0b47ImGhZsSuwzHCxX0qC6jQhljhhsGYHjisq57KY2rp37miOZsCNRLt0vtsnNIqn9eZdtyr1OFQGaa2toPTpZgDRqPG9PEWV9onuoS7duFz7DSOO4AWowA4qC4stSnlRZ2BsySBaoKohGhX6MQYKq1uW5WE7VaJ0l0go1omtHVumG4ey4TuLzutcrsujwqbBgmGnMxtMk6ZSKRNT3jKrdqEdzcgHzyz8YU5bPn9KcsJ2PY99v85hP4Gp8cB0qyaYzSIGgY9E4oRgTUJwcWv1Y3GtwmMItNdWWcrU0v8tShVGLbIYGarpVtCbAAAfhSvcnnIdnStO404uBmBKeatlCb5O4l9o7awScqCuRmsHllURQrfCRCFbcuu8KS5pZbV1CtYoKHgIVFxTsKSIIIzTKYcJhceaceROOsMpIKpNQXXBNmg3ovKxaBiA0QeLa1N1plhPCSTzCQaTObrZB1UtXqglwcZ5hPb0cRHgT3yXFRmuwzyrIgUBVthWcOzVDPMflZ0OiP9kVCy4vsfs5ufJANXBmUDQGGYLtJvljImZdtZG1KBnQQd4W1k14GJ4S3bBfoUOMxWVh93hHh96OiZ512r1csceu5yV6BrS4q67NX4dzpoy00XlfTmyADxbOHRljF9ERybNIyeBJjlxO8hyd2E8KLRyVsCyqEkTdgNYbCEtZrX4qI9g7SCCjGgmdtPyDMZsGNlP271kcJzfoouH8W4vSBJ01k9WZkNedVPLAh55YbdQBFyRYaujbenuNUgqXY3lF1N5B9j1TxJ1LiYEhX3BKZxXLic8yNNSOalymqpthaGaNNimC1TorUVdmcx7T7CdxmuGdAm8o6MhabQCKGuEfI6OhS8Am8n09lWfffkvJ23KTacL2UuEhqG2aLB2q3vq9flHAJG7z4zEnZ5oGZ7VwjAUXvqQqiTi3kfNF5LmedecUDFgy21NBXxRzVfS1Lh05ajslpngHkbYS6JKvwRcR2pEfOFwUrd4sOZ58toBsZRf',
        'authorization-token' => 'Bearer ' . $token,
        'Content-Type' => 'application/json'
    );
    $json_data2 = json_encode( $data2 );
    $args2 = array(
        'headers' => $headers2,
        'body' => $json_data2,
        'timeout' => '5'
    );
    $response = wp_remote_post( 'http://46.101.159.194:47300/bgwp/admin/new_user', $args2 );
echo "<script>alert('" . $response[body] . "');</script>";
echo "<script>alert('" . $usuario_padre . "');</script>";
            } else {
                $output .= __( 'Hubo un problema al crear al usuario. Por favor intenta de nuevo.', 'text-domain' );
            }
        } else {
            $output .= '<div class="woocommerce-error">';
            foreach ( $errors->get_error_messages() as $error ) {
                $output .= '<p>' . $error . '</p>';
            }
            $output .= '</div>';
        }
    }
// Renderizar formulario de registro
$output .= '
    <form method="post" class="woocommerce-form woocommerce-form-register register" enctype="multipart/form-data">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="first_name">' . __( 'Nombre', 'text-domain' ) . '<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="first_name" id="first_name" value="' . ( isset( $_POST['first_name'] ) ? $_POST['first_name'] : '' ) . '" />
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="last_name">' . __( 'Apellido', 'text-domain' ) . '<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="last_name" id="last_name" value="' . ( isset( $_POST['last_name'] ) ? $_POST['last_name'] : '' ) . '" />
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="email">' . __( 'Correo electrónico', 'text-domain' ) . '<span class="required">*</span></label>
            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="email" value="' . ( isset( $_POST['email'] ) ? $_POST['email'] : '' ) . '" />
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="password">' . __( 'Contraseña', 'text-domain' ) . '<span class="required">*</span></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="password" />
        </p>
        <p class="woocommerce-form-row form-row">
            <input type="submit" class="woocommerce-Button button" name="submit" value="' . __( 'Crear usuario', 'text-domain' ) . '" id="crear_usuario_btn" />
        </p>
    </form>';
    return $output;
}


function mostrar_lista_usuarios() {
    global $wpdb;
    $usuario_id = get_current_user_id();
    $usuarios = $wpdb->get_results( "SELECT u.ID, u.user_email, m.meta_value AS 'nombre', m2.meta_value AS 'apellido', m3.meta_value AS 'rol' FROM $wpdb->users u JOIN $wpdb->usermeta m ON u.ID = m.user_id JOIN $wpdb->usermeta m2 ON u.ID = m2.user_id JOIN $wpdb->usermeta m3 ON u.ID = m3.user_id WHERE m.meta_key = 'first_name' AND m2.meta_key = 'last_name' AND m3.meta_key = 'wp_capabilities' AND m3.meta_value NOT LIKE '%administrator%' AND u.ID != '$usuario_id' AND EXISTS (SELECT 1 FROM $wpdb->usermeta WHERE user_id = u.ID AND meta_key = 'usuario_padre' AND meta_value = '$usuario_id')" );
    $output = '
<div style="display: flex; justify-content: space-between;gap:20px; margin-bottom:40px; align-items: center;">
    <input type="text" id="filtro_usuario" placeholder="Filtrar usuarios" style="border-radius:8px; max-width:60%; border-color:lightgray;">
    <a href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6Ijk1NyJ9" id="btnAgregarUsuario" class="button">Agregar usuario</a>
</div>

    <table id="tabla_usuarios">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>email</th>
                <th>Borrar usuario</th>
            </tr>
        </thead>
        <tbody>';
    foreach ( $usuarios as $usuario ) {
        $output .= '<tr>
            <td>' . $usuario->nombre . '</td>
            <td>' . $usuario->apellido . '</td>
            <td>' . $usuario->user_email . '</td>
            <td><button onclick="borrar_usuario('.$usuario->ID.')">Borrar</button></td>
        </tr>';
    }
    $output .= '</tbody>
    </table>
    <script>
        jQuery("#filtro_usuario").on("keyup", function() {
            var valor_filtro = jQuery(this).val().toLowerCase();
            jQuery("#tabla_usuarios tbody tr").filter(function() {
                jQuery(this).toggle(jQuery(this).text().toLowerCase().indexOf(valor_filtro) > -1)
            });
        });

        function borrar_usuario(id) {
            if(confirm("¿Está seguro de que desea borrar este usuario?")) {
                jQuery.ajax({
                    url: "'.admin_url('admin-ajax.php').'",
                    type: "POST",
                    data: { action: "borrar_usuario_ajax", usuario_id: id },
                    success: function(response) {
                        if(response === "success") {
                            alert("Usuario borrado correctamente.");
                            location.reload();
                        } else {
                            alert("Error al borrar el usuario.");
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Ha ocurrido un error. Inténtelo de nuevo más tarde.");
                    }
                });
            }
        }
    </script>';
    return $output;
}

function borrar_usuario_ajax() {
    if(isset($_POST['usuario_id'])) {
        $usuario_id = $_POST['usuario_id'];
        if(wp_delete_user($usuario_id)) {
            echo "success";
        } else {
            echo "error";
        }
    }
    die();
}
add_action('wp_ajax_borrar_usuario_ajax', 'borrar_usuario_ajax');
add_shortcode( 'mostrar_usuarios', 'mostrar_lista_usuarios' );





function add_image_to_legend() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            var img_src = '/wp-content/uploads/2023/03/icono-contrasena-ave.png';
            $('.woocommerce-EditAccountForm.edit-account fieldset legend').prepend('<img src="' + img_src + '" alt="Imagen">');
        });
    </script>
    <?php
}
add_action('wp_footer', 'add_image_to_legend');




add_action( 'wp_head', 'check_active_site' );
function check_active_site() {
    $user_id = get_current_user_id();
    $active_site = get_user_meta( $user_id, 'active_site', true );
    $subscriptions = wcs_get_users_subscriptions( $user_id );
    if ( $subscriptions ) {
        foreach ( $subscriptions as $subscription ) {
            if ( $subscription->get_status() == 'active' ) {
                update_user_meta( $user_id, 'active_site', '1' );
            } else {
            update_user_meta( $user_id, 'active_site', '' );
            }
        }
    }
}


add_action( 'wp_footer', 'add_active_site_message_to_my_account_page' );
function add_active_site_message_to_my_account_page() {
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $active_site = get_user_meta( $user_id, 'active_site', true );

        if ( empty( $active_site ) ) {
            ?>
            <script>
                (function() {
                    var userProfileDiv = document.querySelector('.user-profile');
                    if (userProfileDiv) {
                        var activeSiteMessageDiv = document.createElement('div');
                        activeSiteMessageDiv.classList.add('active-site-message');
                        activeSiteMessageDiv.innerHTML = '<svg viewBox="0 0 24 24">' +
                                                         '<path fill="#fff" d="M12 22c5.5 0 10-4.5 10-10S17.5 2 12 2 2 6.5 2 12s4.5 10 10 10zm0-18c4.4 0 8 3.6 8 8s-3.6 8-8 8-8-3.6-8-8 3.6-8 8-8zM11 7h2v7h-2zm0 8h2v2h-2z"/>' +
                                                         '</svg>' +
                                                         '<p>Su cuenta está en proceso de activación.</p>';
                        userProfileDiv.parentNode.insertBefore(activeSiteMessageDiv, userProfileDiv.nextSibling);
                    }
                })();
            </script>
            <?php
        }
    }
}



add_action( 'wp_head', 'check_active_site_2' );
function check_active_site_2() {
    $user_id = get_current_user_id();
    $active_site = get_user_meta( $user_id, 'active_site', true );
    $subscriptions = wcs_get_users_subscriptions( $user_id );
    if ( $subscriptions ) {
        foreach ( $subscriptions as $subscription ) {
            if ( $subscription->get_status() == 'active' ) {
                update_user_meta( $user_id, 'active_site', '1' );
            } else {
            update_user_meta( $user_id, 'active_site', '' );
            }
        }
    } 
    if ( empty( $active_site ) ) {
    echo "<style>#my-account-menu.layout-simple.position-vertical-left .myaccount-menu li a.yith-mi-equipo { display: none !important; }</style>";
    echo "<style>#my-account-menu.layout-simple.position-vertical-left .myaccount-menu li a.yith-personaliza-tu-web { display: none !important; }</style>";
    echo "<style>#my-account-menu.layout-simple.position-vertical-left .myaccount-menu li a.yith-proveedores { display: none !important; }</style>";
    }
}


add_action( 'template_redirect', 'restrict_access_to_pages' );
function restrict_access_to_pages() {
    // Chequea si el usuario está logueado
    if ( is_user_logged_in() ) {
        // Chequea si el usuario tiene la meta key "active_site" vacía
        $active_site = get_user_meta( get_current_user_id(), 'active_site', true );
        if ( empty( $active_site ) ) {
            // Chequea si el usuario intenta acceder a una de las páginas restringidas
            $restricted_pages = array( '/mi-cuenta/mi-equipo/', '/mi-cuenta/personaliza-tu-web/', '/mi-cuenta/proveedores/' );
            $current_page = $_SERVER['REQUEST_URI'];
            if ( in_array( $current_page, $restricted_pages ) ) {
                // Redirecciona al usuario a /mi-cuenta
                wp_redirect( '/mi-cuenta' );
                exit();
            }
        }
    }
}






add_shortcode( 'bgwp_upload_image', 'bgwp_upload_image_shortcode' );

function bgwp_upload_image_shortcode() {
    ob_start();
    ?>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="bgwp_image" accept="image/*">
        <?php wp_nonce_field( 'bgwp_save_image', 'bgwp_save_image_nonce' ); ?>
        <input type="submit" value="Subir imagen">
    </form>
    <?php
    return ob_get_clean();
}

add_action( 'init', 'bgwp_handle_image_upload' );

function bgwp_handle_image_upload() {
    if ( isset( $_POST['bgwp_save_image_nonce'] ) && wp_verify_nonce( $_POST['bgwp_save_image_nonce'], 'bgwp_save_image' ) ) {
        $user_id = get_current_user_id();
        if ( empty( $user_id ) ) {
            return false;
        }

        if ( ! isset( $_FILES['bgwp_image'] ) ) {
            return false;
        }

        $image_file = $_FILES['bgwp_image'];
        if ( $image_file['size'] > 5242880 || ! in_array( $image_file['type'], array( 'image/jpeg', 'image/png' ) ) ) {
            return false;
        }

        $upload_dir = wp_upload_dir();
        $user_dir = sanitize_user( wp_get_current_user()->user_login );
        $image_dir = trailingslashit( $upload_dir['basedir'] ) . 'logos-interno/' . $user_dir;
        if ( ! file_exists( $image_dir ) ) {
            wp_mkdir_p( $image_dir );
        }

        $image_name = wp_unique_filename( $image_dir, $image_file['name'] );
        $image_path = trailingslashit( $image_dir ) . $image_name;
        move_uploaded_file( $image_file['tmp_name'], $image_path );

        update_user_meta( $user_id, 'bgwp_image_url', $upload_dir['baseurl'] . '/logos-interno/' . $user_dir . '/' . $image_name );
    }
}

add_shortcode( 'bgwp_logo_checkmark', 'bgwp_logo_checkmark_shortcode' );
function bgwp_logo_checkmark_shortcode() {
    $user_id = get_current_user_id();
    $logo_url = get_user_meta( $user_id, 'bgwp_image_url', true );
    if ( ! empty( $logo_url ) ) {
        $html = '<span style="background-color: #D1FAE0; color: #079856; border-radius: 6px; padding: 5px 10px;">Completado<i class="fas fa-check-circle" style="margin-left: 10px;"></i></span>';
        return $html;
    }
    return '';
}







// Shortcode para mostrar el nombre del sitio web y el mensaje de error si el nombre no está disponible
function nombre_web_shortcode() {
  if (is_user_logged_in()) {
    $user_id = get_current_user_id();
    $site_name = get_user_meta($user_id, 'nombre_del_sitio_web', true);
    ob_start();
    ?>
    <label for="site-name">Nombre del sitio web:</label>
    <input type="text" id="site-name" name="site_name" value="<?php echo esc_attr($site_name); ?>">
    <button type="button" id="save-button">Guardar</button>
    <div class="error-message" style="display: none;">
      <i class="fa fa-exclamation-circle"></i>
      <span>Nombre no disponible. Por favor, intente con otro nombre.</span>
    </div>
    <div class="loading-message" style="display: none;">
      <i class="fa fa-spinner fa-spin"></i>
      <span>Cargando...</span>
    </div>
    <div class="success-message" style="display: none;">
      <i class="fa fa-check-circle"></i>
      <span>¡Nombre guardado correctamente!</span>
    </div>
    <script>
      var saveButton = document.getElementById("save-button");
      saveButton.addEventListener("click", function() {
        var siteName = document.getElementById("site-name").value;
        if (siteName.length > 0) {
          var loadingMessage = document.querySelector(".loading-message");
          var errorMessage = document.querySelector(".error-message");
          var successMessage = document.querySelector(".success-message");
          loadingMessage.style.display = "block";
          errorMessage.style.display = "none";
          successMessage.style.display = "none";
          var xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var response = JSON.parse(this.responseText);
              if (response.success) {
                loadingMessage.style.display = "none";
                successMessage.style.display = "block";
              } else {
                loadingMessage.style.display = "none";
                errorMessage.style.display = "block";
              }
            }
          };
          xhttp.open("POST", "<?php echo admin_url('admin-ajax.php'); ?>", true);
          xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhttp.send("action=guardar_nombre_del_sitio_web&site_name=" + siteName);
        }
      });
    </script>
    <?php
    return ob_get_clean();
  }
}
add_shortcode('nombre_web', 'nombre_web_shortcode');

// Manejar la solicitud AJAX para validar el nombre del sitio web
function guardar_nombre_del_sitio_web() {
  if (is_user_logged_in() && isset($_POST['site_name'])) {
    $user_id = get_current_user_id();
    $site_name = sanitize_text_field($_POST['site_name']);
    $users = get_users(['meta_key' => 'nombre_del_sitio_web', 'meta_value' => $site_name]);
    if (empty($users)) {
      update_user_meta($user_id, 'nombre_del_sitio_web', $site_name);
      wp_send_json_success('Nombre guardado correctamente');
    } else {
      wp_send_json_error('Nombre no disponible. Por favor, intente con otro nombre.');
}
}
wp_die();
}
add_action('wp_ajax_guardar_nombre_del_sitio_web', 'guardar_nombre_del_sitio_web');
add_action('wp_ajax_nopriv_guardar_nombre_del_sitio_web', 'guardar_nombre_del_sitio_web');

function nombre_web_styles() {
  ?>
  <style>
    .error-message {
      display: none;
      padding: 10px;
      border: 1px solid #f00;
      border-radius: 5px;
      background-color: #f9e3e3;
      color: #f00;
      margin-bottom: 10px;
    }
    .error-message i {
      margin-right: 10px;
    }
    .loading-message {
      display: none;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #f5f5f5;
      color: #666;
      margin-bottom: 10px;
    }
    .loading-message i {
      margin-right: 10px;
      animation: fa-spin 2s infinite linear;
    }
    @keyframes fa-spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
  <?php
}
add_action('wp_head', 'nombre_web_styles');












add_shortcode( 'bgwp_nombre_checkmark', 'bgwp_nombre_checkmark_shortcode' );
function bgwp_nombre_checkmark_shortcode() {
    $user_id = get_current_user_id();
    $nombre_sitio = get_user_meta( $user_id, 'nombre_del_sitio_web', true );
    if ( ! empty( $nombre_sitio ) ) {
        $html = '<span style="background-color: #D1FAE0; color: #079856; border-radius: 6px; padding: 5px 10px;">Completado<i class="fas fa-check-circle" style="margin-left: 10px;"></i></span>';
        return $html;
    }
    return '';
}



add_shortcode( 'bgwp_submit_for_review', 'bgwp_submit_for_review_shortcode' );

function bgwp_submit_for_review_shortcode() {
    $user_id = get_current_user_id();
    $sitename = get_user_meta( $user_id, 'nombre_del_sitio_web', true );
    $logo_url = get_user_meta( $user_id, 'bgwp_image_url', true );
    $sitio_en_revision = get_user_meta( $user_id, 'sitio_en_revision', true );
    if ( ! empty( $sitename ) && ! empty( $logo_url ) ) {
        if ( ! empty( $sitio_en_revision ) ) {
            $html = '<div style="background-color: #c4e3cb; border-radius: 8px; padding: 20px; display: flex; align-items: center;">
                        <div style="background-color: #76b947; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; justify-content: center; align-items: center; margin-right: 20px;"><i class="fas fa-check"></i></div>
                        <p style="font-size: 18px; font-weight: bold;">Su sitio ya fue enviado a revisión, pronto nos comunicaremos para activar su nuevo sitio web.</p>
                    </div>';
        } else {
            $html = '<form method="post">
                        <input type="hidden" name="bgwp_submit_for_review" value="1">
                        <input type="submit" value="Enviar a revisión">
                    </form>';
        }
        return $html;
    }
    return '';
}

add_action( 'init', 'bgwp_handle_review_submission' );



function bgwp_handle_review_submission() {
    if ( isset( $_POST['bgwp_submit_for_review'] ) ) {
        $user_id = get_current_user_id();
        $sitename = get_user_meta( $user_id, 'nombre_del_sitio_web', true );
        $logo_url = get_user_meta( $user_id, 'bgwp_image_url', true );
        $message = "El usuario {$user_id} ha enviado su sitio '{$sitename}' para revisión. El logo se encuentra en {$logo_url}.";
        $to = 'contacto@empresamatriz.co';
        $subject = 'Nuevo sitio para revisión';
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );
        $mail_sent = wp_mail( $to, $subject, $message, $headers );
        $meta_updated = update_user_meta( $user_id, 'sitio_en_revision', 1 );
        if ( $meta_updated ) {
            echo '<script>alert("Sitio enviado a revisión correctamente.");</script>';
        }
    }
}



add_action( 'wp_head', 'bgwp_block_account_page' );

function bgwp_block_account_page() {
    $user_id = get_current_user_id();
    $sitio_en_revision = get_user_meta( $user_id, 'sitio_en_revision', true );
    if ( ! empty( $sitio_en_revision ) ) {
        echo '<style>.mi-cuenta-bloqueo { pointer-events: none; opacity: 0.5; }</style>';
    }
}











add_filter('manage_users_columns', 'add_user_site_column');
add_action('manage_users_custom_column', 'display_user_site_column', 10, 3);
add_action('admin_head', 'user_site_button_css');
add_action('admin_footer', 'user_site_button_js');
add_action('admin_action_create_user_site', 'create_user_site');

function add_user_site_column($columns) {
  $columns['user_site'] = 'Site';
  return $columns;
}

function display_user_site_column($value, $column_name, $user_id) {
  if ('user_site' == $column_name && get_user_meta($user_id, 'active_site', true) == '1') {
    $value = '<a href="' . wp_nonce_url('admin.php?action=create_user_site&user_id=' . $user_id, 'create_user_site') . '" class="button button-primary">Create Site</a>';
  }
  return $value;
}

function user_site_button_css() {
  global $pagenow;
  if ($pagenow == 'users.php') {
    echo '<style>.column-user_site { width: 10%; }</style>';
  }
}

function user_site_button_js() {
  global $pagenow;
  if ($pagenow == 'users.php') {
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
    ?>
    <script>
      jQuery(document).ready(function($) {
        $('a[href*="action=create_user_site"]').click(function(event) {
          event.preventDefault();
          var siteNameField = $('#create-site-form').find('input[name="site_name"]');
          var siteName = siteNameField.val();
          var siteUrl = normalize_url(siteName);
          var createSiteForm = $('<form>', {
            'action': '<?php echo esc_url(admin_url('network/site-new.php')); ?>',
            'method': 'post',
            'target': '_blank'
          }).append($('<input>', {
            'type': 'hidden',
            'name': 'action',
            'value': 'create-site'
          })).append($('<input>', {
            'type': 'hidden',
            'name': 'site_name',
            'value': '<?php echo esc_attr($site_name); ?>'
          })).append($('<input>', {
            'type': 'hidden',
            'name': 'site_url',
            'value': siteUrl
          })).append($('<input>', {
            'type': 'hidden',
            'name': 'site_email',
            'value': '<?php echo esc_attr(get_userdata($user_id)->user_email); ?>'
          })).append($('<input>', {
            'type': 'submit'
          })).appendTo('body');
          createSiteForm.submit();
        });
      });

      function normalize_url(str) {
        str = str.replace(' ', '-');
        str = str.toLowerCase();
        str = str.replace(/[^a-z0-9\-]/g, '');
        return str;
      }
    </script>
    <?php
  }
}
function create_user_site() {
  if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'create_user_site')) {
    wp_die('Security check');
  }

  $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
  if (!$user_id) {
    wp_die('User not found');
  }

  if (get_user_meta($user_id, 'active_site', true) != '1') {
    wp_die('Site creation not allowed');
  }

  $site_name = get_user_meta($user_id, 'nombre_del_sitio_web', true);
  if (!$site_name) {
    wp_die('Site name not found');
  }

  // Normalize site name for use as URL
  $site_url = normalize_url($site_name);

  // Redirect to site creation form with user email as admin email and site name as title
  $user_email = get_userdata($user_id)->user_email;
  $args = array(
    'action' => 'create-site',
    'site_title' => $site_name,
    'user_email' => $user_email,
    'site_url' => $site_url
  );
  $url = add_query_arg($args, network_admin_url('site-new.php'));
  wp_redirect($url);
  exit();
}
