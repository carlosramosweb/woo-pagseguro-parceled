<?php
/**
 * Calcular o valor dos produtos em 12x parcelas sem juros.
 */ 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Sair se for acessado diretamente.
}

	
/**
 * Criação do submenu
 */
function register_woo_pagseguro_parceled_submenu_page() {
	add_submenu_page( 'woocommerce', 'PagSeguro Parceled', 'PagSeguro Parceled', 'manage_woocommerce', 'woo-pagseguro-parceled-admin', 'woo_pagseguro_parceled_admin' ); 
}

add_action( 'admin_menu', 'register_woo_pagseguro_parceled_submenu_page', 70 );

function woo_pagseguro_parceled_admin() {
	
	 $message = "";
	 if( isset( $_POST['_update'] ) && isset( $_POST['_wpnonce'] ) ) {
		$_update = sanitize_text_field( $_POST['_update'] );
		$_wpnonce = sanitize_text_field( $_POST['_wpnonce'] );
	 }
	 
	 if( isset( $_wpnonce ) && isset( $_update )) {
		if ( ! wp_verify_nonce( $_wpnonce, "woo-pagseguro-parceled-update" ) ) {
			$message = "error";
			
		} else if ( empty( $_update ) ) {
			$message = "error";			
		}
		
		if( isset( $_POST['pagseguro_settings'] ) ) {
			$pagseguro_settings = array();
			$pagseguro_settings = $_POST['pagseguro_settings'];
			update_option( "woocommerce_pagseguro_settings", $pagseguro_settings );
		}
		
		$message = "updated";	
	 }
	
	$pagseguro_settings = array();
	$pagseguro_settings = get_option( 'woocommerce_pagseguro_settings' );	
	
	if( ! empty( $pagseguro_settings['pagseguro_parceled_enabled'] ) ) {
		$pagseguro_parceled_enabled = $pagseguro_settings['pagseguro_parceled_enabled'];
	} else {
		$pagseguro_parceled_enabled = "";
	}
	if( ! empty( $pagseguro_settings['installment_single_product'] ) ) {
		$installment_single_product = $pagseguro_settings['installment_single_product'];
	} else {
		$installment_single_product = "";
	}
	if( ! empty( $pagseguro_settings['installment'] ) ) {
		$installment = $pagseguro_settings['installment'];
	} else {
		$installment = "0";
	}
	 
	?>
<!----->
<div id="wpwrap">
<!--start-->
    <h1>Parcelamento PagSeguro</h1>
    <p>De acordo com a configuração do PagSeguro faça o mesmo aqui.<br/>
	Sistema para exibição do parcelamento sem e com juros.</p>
    
    <?php if( isset( $message ) ) { ?>
        <div class="wrap">
    	<?php if( $message == "updated" ) { ?>
            <div id="message" class="updated notice is-dismissible" style="margin-left: 0px;">
                <p><?php echo __( 'Atualizações feita com sucesso!', 'woo-pagseguro-parceled' ) ; ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        <?php echo __( 'Dispensar este aviso.', 'woo-pagseguro-parceled' ) ; ?>
                    </span>
                </button>
            </div>
            <?php } ?>
            <?php if( $message == "error" ) { ?>
            <div id="message" class="updated error is-dismissible" style="margin-left: 0px;">
                <p><?php echo __( 'Erro! Não conseguimos fazer as atualizações!', 'woo-pagseguro-parceled' ) ; ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        <?php echo __( 'Dispensar este aviso.', 'woo-pagseguro-parceled' ) ; ?>
                    </span>
                </button>
            </div>
        <?php } ?>
    	</div>
    <?php } ?>
    <!----->
    <!---->
    <div class="wrap woocommerce">
    	<!---->
            <nav class="nav-tab-wrapper wc-nav-tab-wrapper">
            <?php
			if( isset( $_GET['tab'] ) ) {
				$tab = esc_attr( $_GET['tab'] );
			}
			?>
           		<a href="<?php echo esc_url( admin_url( 'admin.php?page=woo-pagseguro-parceled-admin' ) ); ?>" class="nav-tab <?php if( $tab == "" ) { echo "nav-tab-active"; }; ?>">Configurações</a>
            	<a href="<?php echo esc_url( admin_url( 'admin.php?page=woo-pagseguro-parceled-admin&tab=wpn-doacao' ) ); ?>" class="nav-tab <?php if( $tab == "wpn-doacao") { echo "nav-tab-active"; }; ?>">Doação</a>
            </nav>
            <!---->
            <?php if(!isset($tab)) { ?>
        	<form action="<?php echo esc_url( admin_url( 'admin.php?page=woo-pagseguro-parceled-admin' ) ); ?>" method="post" enctype="application/x-www-form-urlencoded">
                <!---->
                <table class="form-table">
                    <tbody>
                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Habilita/Desabilita', 'woo-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
                                <label>
                                    <input type="checkbox" name="pagseguro_settings[pagseguro_parceled_enabled]" value="yes" <?php if( $pagseguro_parceled_enabled == "yes" ) { echo 'checked="checked"'; } ?>>
                                    <?php echo __( 'Ativar notificações', 'woo-pagseguro-parceled' ) ; ?>
                                </label>
                           </td>
                        </tr>  
                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Exibir parcelamento na página detalhe do produto? (Single)', 'woo-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
                                <label>
                                	<input type="checkbox" name="pagseguro_settings[installment_single_product]" value="yes" <?php if( $installment_single_product == "yes" ) { echo 'checked="checked"'; } ?>> <?php echo __( 'Sim!', 'woo-pagseguro-parceled' ); ?>

                                <a href="<?php echo esc_url( plugins_url( '/woo-pagseguro-parceled/images/show-product-single.jpg', dirname(__FILE__) ) ); ?>" style=" display: inline-block; margin-left:50px; " target="blank">
								<?php echo __( 'Ver Exemplo', 'woo-pagseguro-parceled' ); ?>
                                    
                                </a>
                                </label>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Número de parcela sem juros:', 'woo-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
							<select name="pagseguro_settings[installment]">
								<option value="0" <?php if( $installment == "0" ) { echo "selected"; } ?>>Todas com juros</option>
								<option value="1" <?php if( $installment == "1" ) { echo "selected"; } ?>>1x sem juros</option>
								<option value="2" <?php if( $installment == "2" ) { echo "selected"; } ?>>2x sem juros</option>
								<option value="3" <?php if( $installment == "3" ) { echo "selected"; } ?>>3x sem juros</option>
								<option value="4" <?php if( $installment == "4" ) { echo "selected"; } ?>>4x sem juros</option>
								<option value="5" <?php if( $installment == "5" ) { echo "selected"; } ?>>5x sem juros</option>
								<option value="6" <?php if( $installment == "6" ) { echo "selected"; } ?>>6x sem juros</option>
								<option value="7" <?php if( $installment == "7" ) { echo "selected"; } ?>>7x sem juros</option>
								<option value="8" <?php if( $installment == "8" ) { echo "selected"; } ?>>8x sem juros</option>
								<option value="9" <?php if( $installment == "9" ) { echo "selected"; } ?>>9x sem juros</option>
								<option value="10" <?php if( $installment == "10" ) { echo "selected"; } ?>>10x sem juros</option>
								<option value="11" <?php if( $installment == "11" ) { echo "selected"; } ?>>11x sem juros</option>
								<option value="12" <?php if( $installment == "12" ) { echo "selected"; } ?>>12x sem juros</option>
							</select>
                            </td>
                        </tr>
                        <!---->
                    </tbody>
                </table>
                <!---->
                <hr/>
                <div class="submit">
                    <button class="button-primary" type="submit"><?php echo __( 'Salvar Alterações', 'woo-pagseguro-parceled' ) ; ?></button>
                    <input type="hidden" name="_update" value="1">
                    <input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'woo-pagseguro-parceled-update' ) ); ?>">
                    <!---->
                    <span>
                    	<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: middle;"></span>
    					<?php echo __( 'Não esqueça de <strong>salvar suas alterações</strong>.', 'woo-pagseguro-parceled' ) ; ?>
                    </span>
                </div>
                <!---->
        	</form>
        <?php } else if($tab == "wpn-doacao") { ?>
            <h2><?php echo __( 'Oba! Fique a vontade.', 'woo-pagseguro-parceled' ) ; ?></h2>
        	<div class="">
            	<p><?php echo __( '<strong>É totalmente seguro!</strong> Ajude a manter esse plugin sempre atualizado com seu incentivo.', 'woo-pagseguro-parceled' ) ; ?></p>
            </div>
			<!---->
            <table class="form-table">
                <tbody>
                    <!---->
                    <tr valign="top">
                        <th scope="row">
                            <button class="button-primary" onClick="window.open('http://donate.criacaocriativa.com')">
                            <?php echo __( 'Quero doar agora', 'woo-pagseguro-parceled' ) ; ?>
                            </button>
                        </th>
                        <td>
                            <label>
							<span>
								<span class="dashicons dashicons-warning" style="vertical-align: middle;"></span>
								<?php echo __( 'Você será direcionado para um site seguro.', 'woo-pagseguro-parceled' ) ; ?> 
							</span> 
                            </label>
                        </td>
                    </tr>
                    <!---->
                </tbody>
            </table>
            <!---->
        <?php } ?>
        <!---->
    </div>
<!--enf wpwrap-->
</div>
<script>	
</script> 
<!---->
</div>
<?php	
}