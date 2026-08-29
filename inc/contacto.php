<?php
/**
 * Formulario de contacto.
 *
 * Se implementa en el tema en lugar de instalar un plugin: son ciento
 * cincuenta líneas, queda versionado con el resto del sitio y no añade una
 * superficie de ataque que haya que mantener actualizada. Los plugins de
 * formularios son, año tras año, de los vectores más explotados de WordPress.
 *
 * Uso: [nl_contacto] en cualquier página.
 *
 * @package nestjslatam
 */

defined( 'ABSPATH' ) || exit;

const NESTJSLATAM_CONTACTO_NONCE  = 'nestjslatam_contacto';
const NESTJSLATAM_CONTACTO_ESPERA = 120; // segundos entre envíos por IP

/**
 * Identificador de quien envía, para limitar la frecuencia.
 *
 * Se guarda el hash y no la IP: sirve igual para contar y no deja datos
 * personales en la tabla de opciones.
 */
function nestjslatam_contacto_huella() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] )
		? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
		: 'desconocida';

	return 'nl_contacto_' . md5( $ip . wp_salt() );
}

/**
 * Procesa el envío. Devuelve array( tipo, mensaje ) o null si no hay envío.
 */
function nestjslatam_contacto_procesar() {
	if ( ! isset( $_POST['nl_contacto_enviar'] ) ) {
		return null;
	}

	if ( ! isset( $_POST['nl_contacto_nonce'] )
		|| ! wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['nl_contacto_nonce'] ) ),
			NESTJSLATAM_CONTACTO_NONCE
		) ) {
		return array( 'error', __( 'La sesión caducó. Vuelve a enviarlo, por favor.', 'nestjslatam' ) );
	}

	// Trampa para robots: un campo que un humano no ve y no rellena. Si viene
	// con algo, se descarta en silencio — decirle a un bot que lo detectaste
	// sólo le enseña a evitarlo la próxima vez.
	if ( ! empty( $_POST['nl_web'] ) ) {
		return array( 'ok', __( 'Gracias, hemos recibido tu mensaje.', 'nestjslatam' ) );
	}

	$huella = nestjslatam_contacto_huella();
	if ( get_transient( $huella ) ) {
		return array(
			'error',
			__( 'Acabas de enviar un mensaje. Espera un par de minutos antes del siguiente.', 'nestjslatam' ),
		);
	}

	$nombre  = sanitize_text_field( wp_unslash( $_POST['nl_nombre'] ?? '' ) );
	$correo  = sanitize_email( wp_unslash( $_POST['nl_correo'] ?? '' ) );
	$asunto  = sanitize_text_field( wp_unslash( $_POST['nl_asunto'] ?? '' ) );
	$mensaje = sanitize_textarea_field( wp_unslash( $_POST['nl_mensaje'] ?? '' ) );

	if ( '' === $nombre || '' === $mensaje ) {
		return array( 'error', __( 'Faltan el nombre o el mensaje.', 'nestjslatam' ) );
	}
	if ( ! is_email( $correo ) ) {
		return array( 'error', __( 'Ese correo no parece válido; sin él no podemos responderte.', 'nestjslatam' ) );
	}
	if ( strlen( $mensaje ) < 20 ) {
		return array( 'error', __( 'Cuéntanos un poco más: con menos de veinte caracteres no podemos ayudarte.', 'nestjslatam' ) );
	}

	$destino = apply_filters( 'nestjslatam_contacto_destino', get_option( 'admin_email' ) );

	$cuerpo = sprintf(
		"Nombre: %s\nCorreo: %s\nAsunto: %s\n\n%s\n\n---\nEnviado desde %s",
		$nombre,
		$correo,
		'' !== $asunto ? $asunto : '(sin asunto)',
		$mensaje,
		home_url( '/' )
	);

	// El remitente es SIEMPRE del propio dominio, nunca el correo de quien
	// escribe: poner ahí una dirección ajena hace que SPF y DMARC rechacen el
	// mensaje, y además es por donde se cuela la inyección de cabeceras.
	// Para responder está Reply-To, que sí lleva su dirección ya saneada.
	$dominio  = wp_parse_url( home_url(), PHP_URL_HOST );
	$cabeceras = array(
		'From: ' . get_bloginfo( 'name' ) . ' <no-responder@' . $dominio . '>',
		'Reply-To: ' . $nombre . ' <' . $correo . '>',
		'Content-Type: text/plain; charset=UTF-8',
	);

	$enviado = wp_mail(
		$destino,
		sprintf( '[%s] %s', get_bloginfo( 'name' ), '' !== $asunto ? $asunto : __( 'Mensaje de contacto', 'nestjslatam' ) ),
		$cuerpo,
		$cabeceras
	);

	if ( ! $enviado ) {
		return array(
			'error',
			__( 'No hemos podido enviarlo. Escríbenos directamente por GitHub mientras lo arreglamos.', 'nestjslatam' ),
		);
	}

	set_transient( $huella, 1, NESTJSLATAM_CONTACTO_ESPERA );

	return array( 'ok', __( 'Recibido. Te respondemos en cuanto podamos.', 'nestjslatam' ) );
}

/**
 * El formulario.
 */
function nestjslatam_contacto_shortcode() {
	$resultado = nestjslatam_contacto_procesar();
	$usuario   = wp_get_current_user();

	ob_start();
	?>
	<div class="nl-contacto">
		<?php if ( $resultado ) : ?>
			<p class="nl-contacto__aviso nl-contacto__aviso--<?php echo esc_attr( $resultado[0] ); ?>">
				<?php echo esc_html( $resultado[1] ); ?>
			</p>
		<?php endif; ?>

		<?php if ( ! $resultado || 'ok' !== $resultado[0] ) : ?>
		<form class="nl-contacto__form" method="post" action="">
			<?php wp_nonce_field( NESTJSLATAM_CONTACTO_NONCE, 'nl_contacto_nonce' ); ?>

			<div class="nl-contacto__fila">
				<label>
					<span><?php esc_html_e( 'Tu nombre', 'nestjslatam' ); ?></span>
					<input type="text" name="nl_nombre" required maxlength="120"
					       value="<?php echo esc_attr( $usuario->display_name ?? '' ); ?>">
				</label>
				<label>
					<span><?php esc_html_e( 'Tu correo', 'nestjslatam' ); ?></span>
					<input type="email" name="nl_correo" required maxlength="180"
					       value="<?php echo esc_attr( $usuario->user_email ?? '' ); ?>">
				</label>
			</div>

			<label>
				<span><?php esc_html_e( 'Asunto', 'nestjslatam' ); ?></span>
				<input type="text" name="nl_asunto" maxlength="160"
				       placeholder="<?php esc_attr_e( 'Sobre qué nos escribes', 'nestjslatam' ); ?>">
			</label>

			<label>
				<span><?php esc_html_e( 'Mensaje', 'nestjslatam' ); ?></span>
				<textarea name="nl_mensaje" rows="7" required minlength="20" maxlength="4000"
				          placeholder="<?php esc_attr_e( 'Cuéntanos. Si es un fallo, di qué versión usas y cómo reproducirlo.', 'nestjslatam' ); ?>"></textarea>
			</label>

			<p class="nl-contacto__trampa" aria-hidden="true">
				<label>No rellenes esto
					<input type="text" name="nl_web" tabindex="-1" autocomplete="off">
				</label>
			</p>

			<div class="nl-contacto__pie">
				<button type="submit" name="nl_contacto_enviar" value="1" class="nl-btn">
					<?php esc_html_e( 'Enviar mensaje', 'nestjslatam' ); ?>
				</button>
				<span class="nl-contacto__nota">
					<?php esc_html_e( 'Sólo usamos tu correo para responderte.', 'nestjslatam' ); ?>
				</span>
			</div>
		</form>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nl_contacto', 'nestjslatam_contacto_shortcode' );
