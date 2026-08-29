<?php
/**
 * Acceso y registro.
 *
 * Da a la pantalla de acceso la identidad del sitio y añade un atajo para
 * poner acceso y registro dentro de una página normal.
 *
 * NO abre el registro por su cuenta: eso es un ajuste del sitio
 * (Ajustes -> Generales -> «Cualquiera puede registrarse») y activarlo sin
 * protección antispam llena la base de usuarios en cuestión de días. La
 * decisión es del administrador, no del tema.
 *
 * @package nestjslatam
 */

defined( 'ABSPATH' ) || exit;

/**
 * La pantalla de acceso, con la paleta del sitio.
 *
 * Se encolan las mismas hojas que el front, así que un cambio de tokens se
 * propaga aquí sin duplicar ni un color.
 */
function nestjslatam_login_styles() {
	foreach ( array( 'tokens', 'base' ) as $hoja ) {
		wp_enqueue_style(
			"nestjslatam-login-{$hoja}",
			get_stylesheet_directory_uri() . "/assets/css/{$hoja}.css",
			array(),
			NESTJSLATAM_VERSION
		);
	}

	wp_enqueue_style(
		'nestjslatam-login',
		get_stylesheet_directory_uri() . '/assets/css/login.css',
		array( 'nestjslatam-login-base' ),
		NESTJSLATAM_VERSION
	);

	wp_enqueue_style(
		'nestjslatam-login-fonts',
		'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap',
		array(),
		null
	);
}
add_action( 'login_enqueue_scripts', 'nestjslatam_login_styles' );

/** El logo de la pantalla de acceso lleva al sitio, no a wordpress.org. */
function nestjslatam_login_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'nestjslatam_login_url' );

function nestjslatam_login_title() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'nestjslatam_login_title' );

/**
 * Quien no administra vuelve al sitio, no al escritorio.
 *
 * Un lector que se registra para comentar no tiene nada que hacer en
 * wp-admin, y aterrizar ahí es desconcertante.
 */
function nestjslatam_login_redirect( $destino, $solicitado, $usuario ) {
	if ( ! ( $usuario instanceof WP_User ) ) {
		return $destino;
	}

	return user_can( $usuario, 'edit_posts' ) ? $destino : home_url( '/' );
}
add_filter( 'login_redirect', 'nestjslatam_login_redirect', 10, 3 );

/** Y si llega a wp-admin por su cuenta, se le devuelve — salvo a admin-ajax. */
function nestjslatam_block_admin() {
	if ( wp_doing_ajax() || ! is_user_logged_in() || current_user_can( 'edit_posts' ) ) {
		return;
	}

	wp_safe_redirect( home_url( '/' ) );
	exit;
}
add_action( 'admin_init', 'nestjslatam_block_admin' );

/** Sin barra de administración para quien no publica. */
function nestjslatam_hide_admin_bar( $mostrar ) {
	return current_user_can( 'edit_posts' ) ? $mostrar : false;
}
add_filter( 'show_admin_bar', 'nestjslatam_hide_admin_bar' );

/**
 * Atajo [nl_acceso] — acceso y registro dentro de una página normal.
 *
 * Usa `wp_login_form()` en lugar de un formulario propio: así el nonce, el
 * limitador de intentos y cualquier plugin de seguridad siguen aplicándose.
 * Un formulario escrito a mano que hiciera POST a wp-login.php se los salta.
 */
function nestjslatam_acceso_shortcode( $atts ) {
	$atts = shortcode_atts(
		array( 'redirect' => home_url( '/' ) ),
		$atts,
		'nl_acceso'
	);

	if ( is_user_logged_in() ) {
		$usuario = wp_get_current_user();

		return sprintf(
			'<div class="nl-auth nl-auth--in"><p class="nl-auth__hi">Hola, <strong>%1$s</strong>.</p>'
				. '<p class="nl-auth__note">Ya tienes la sesión iniciada.</p>'
				. '<a class="nl-btn nl-btn--ghost" href="%2$s">Cerrar sesión</a></div>',
			esc_html( $usuario->display_name ),
			esc_url( wp_logout_url( home_url( '/' ) ) )
		);
	}

	ob_start();
	?>
	<div class="nl-auth">
		<div class="nl-auth__panel">
			<h2 class="nl-auth__title">Entrar</h2>
			<?php
			wp_login_form(
				array(
					'redirect'       => esc_url_raw( $atts['redirect'] ),
					'label_username' => 'Usuario o correo',
					'label_password' => 'Contraseña',
					'label_remember' => 'Recuérdame',
					'label_log_in'   => 'Entrar',
				)
			);
			?>
			<p class="nl-auth__aside">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">¿Olvidaste la contraseña?</a>
			</p>
		</div>

		<?php if ( get_option( 'users_can_register' ) ) : ?>
			<div class="nl-auth__panel">
				<h2 class="nl-auth__title">Crear cuenta</h2>
				<p class="nl-auth__note">
					Te llegará un correo para elegir contraseña. Con la cuenta puedes
					comentar en los artículos y participar en las discusiones.
				</p>
				<a class="nl-btn" href="<?php echo esc_url( wp_registration_url() ); ?>">Registrarme</a>
			</div>
		<?php else : ?>
			<div class="nl-auth__panel">
				<h2 class="nl-auth__title">Colaborar</h2>
				<p class="nl-auth__note">
					El registro está cerrado por ahora. Para participar no hace falta
					cuenta aquí: los repositorios están abiertos en GitHub.
				</p>
				<a class="nl-btn nl-btn--ghost" href="https://github.com/nestjslatam">Ir a GitHub</a>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nl_acceso', 'nestjslatam_acceso_shortcode' );

/**
 * Acceso y perfil en la cabecera.
 *
 * `generate_menu_bar_items` es el hook que GeneratePress reserva para esto,
 * así que el elemento vive dentro de la barra de navegación y hereda su
 * comportamiento responsive en lugar de flotar por encima.
 *
 * No hay un segundo mecanismo de acceso: los enlaces apuntan a wp-login.php,
 * que es el que ya existe. Lo que cambia es el diseño y desde dónde se llega.
 */
/**
 * GitHub en la barra de navegación.
 *
 * Antes había además enlaces a Guías y Documentación, pero desde que el menú
 * se completa solo esos destinos ya están a la izquierda: repetirlos a la
 * derecha era ruido, no atajo.
 */
function nestjslatam_header_actions() {
	?>
	<span class="nl-actions">
		<a class="nl-actions__gh" href="https://github.com/nestjslatam"
		   aria-label="<?php esc_attr_e( 'NestJS Latam en GitHub', 'nestjslatam' ); ?>">
			<svg width="19" height="19" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
				<path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38
				0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01
				1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95
				0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27
				2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82
				2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0
				.21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
			</svg>
		</a>
	</span>
	<?php
}
add_action( 'generate_menu_bar_items', 'nestjslatam_header_actions', 5 );

function nestjslatam_header_account() {
	if ( ! is_user_logged_in() ) {
		printf(
			'<span class="nl-account nl-account--out">'
				. '<a class="nl-account__in" href="%s">%s</a></span>',
			esc_url( wp_login_url( home_url( add_query_arg( array() ) ) ) ),
			esc_html__( 'Entrar', 'nestjslatam' )
		);
		return;
	}

	$u        = wp_get_current_user();
	$perfil   = get_edit_profile_url( $u->ID );
	$es_autor = user_can( $u, 'edit_posts' );
	?>
	<span class="nl-account" data-nl-account>
		<button type="button" class="nl-account__btn" aria-expanded="false" aria-haspopup="true">
			<?php echo get_avatar( $u->ID, 52, '', '', array( 'class' => 'nl-account__avatar' ) ); ?>
			<span class="nl-account__name"><?php echo esc_html( $u->display_name ); ?></span>
			<svg class="nl-account__caret" width="10" height="6" viewBox="0 0 10 6" aria-hidden="true">
				<path d="M1 1l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
			</svg>
		</button>

		<div class="nl-account__menu" hidden>
			<div class="nl-account__head">
				<?php echo get_avatar( $u->ID, 80, '', '', array( 'class' => 'nl-account__avatar-lg' ) ); ?>
				<div>
					<p class="nl-account__display"><?php echo esc_html( $u->display_name ); ?></p>
					<p class="nl-account__mail"><?php echo esc_html( $u->user_email ); ?></p>
					<p class="nl-account__role"><?php echo esc_html( $es_autor ? __( 'Autor', 'nestjslatam' ) : __( 'Miembro', 'nestjslatam' ) ); ?></p>
				</div>
			</div>

			<ul class="nl-account__links">
				<li><a href="<?php echo esc_url( $perfil ); ?>"><?php esc_html_e( 'Mi perfil', 'nestjslatam' ); ?></a></li>
				<?php if ( $es_autor ) : ?>
					<li><a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>"><?php esc_html_e( 'Mis artículos', 'nestjslatam' ); ?></a></li>
					<li><a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php esc_html_e( 'Escribir', 'nestjslatam' ); ?></a></li>
				<?php else : ?>
					<li><a href="<?php echo esc_url( home_url( '/comunidad/' ) ); ?>"><?php esc_html_e( 'Cómo participar', 'nestjslatam' ); ?></a></li>
				<?php endif; ?>
				<li><a href="<?php echo esc_url( get_author_posts_url( $u->ID ) ); ?>"><?php esc_html_e( 'Mi actividad', 'nestjslatam' ); ?></a></li>
			</ul>

			<a class="nl-account__out" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">
				<?php esc_html_e( 'Cerrar sesión', 'nestjslatam' ); ?>
			</a>
		</div>
	</span>
	<?php
}
add_action( 'generate_menu_bar_items', 'nestjslatam_header_account' );
