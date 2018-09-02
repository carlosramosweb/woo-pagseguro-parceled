<?php
/*---------------------------------------------------------
Plugin Name: Woo PagSeguro Parceled
Plugin URI: https://wordpress.org/plugins/woo-pagseguro-parceled/
Author: carlosramosweb
Author URI: http://plugins.criacaocriativa.com.br/
Description: Ativa a exibição dos resultados de parcelamento sem juros e com, sistema do pagseguro.
Text Domain: woo-pagseguro-parceled
Domain Path: /languages/
Version: 1.6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 
------------------------------------------------------------*/

/**
 * Calcular o valor dos produtos em 12x parcelas sem juros.
 */ 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Sair se for acessado diretamente.
}

/**
 * Class WC_Woo_Product_Parceled.
 */
class WC_Woo_Product_Parceled {
	
	private static $new_version = '1.6.2';	
	/*
	 * Inicia tudo
	 * Aqui são adicionado os actions e filter para o sistema WooCommerce
	 * Pega os dados do Plugin WooCommerce PagSeguro
	 * Copia ou não o arquivo automaticamente
	 */
	public function __construct() {	
			
		
		$active_plugins = get_option( 'active_plugins' );
		$version_plugin = get_option( 'woo_pagseguro_parceled' );
		// Verifica com WooCommerce PagSeguro está instalado.
		if ( in_array( 'woocommerce-pagseguro/woocommerce-pagseguro.php', $active_plugins) ) {
		
			$pagseguro_settings = get_option( 'woocommerce_pagseguro_settings' );
			add_action( 'woocommerce_after_shop_loop_item',  array( $this, 'woo_product_parceled_loop'), 10 );
			add_action( 'woocommerce_single_product_summary',  array( $this, 'woo_product_parceled_single_product'), 11 );
			add_action( 'woocommerce_cart_totals_after_order_total',  array( $this, 'woo_product_parceled_cart'), 20 );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links_settings' ) );
			add_action( 'init', array( $this, 'localise' ) );	

			// Somente se estiver no painel administrativo
			if (is_admin()) {
				if (self::$new_version > $version_plugin or empty($version_plugin)) {
					add_action( 'admin_notices',  array( $this, 'woo_product_parceled_file_replace') );
					if ( empty($version_plugin) ) {
						add_option('woo_pagseguro_parceled', self::$new_version, 'yes');
					} else {
						update_option('woo_pagseguro_parceled', self::$new_version);
					}
				} else if(empty($pagseguro_settings[installment_single_product]) or empty($pagseguro_settings[installment])) {
					add_action( 'admin_notices',  array( $this, 'woo_product_parceled_file_replace') );
				} else {
					remove_action( 'admin_notices',  array( $this, 'woocommerce_install_notice') );
				}
				
			}
		} else if ( ! in_array( 'woocommerce/woocommerce.php', $active_plugins) ) {
			add_action( 'admin_notices', array( $this, 'woocommerce_plugin_failure_notice' ) );
			add_action( 'admin_notices', array( $this, 'desative_plugin_now' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'woocommerce_pagseguro_failure_notice' ) );
			add_action( 'admin_notices', array( $this, 'desative_plugin_now' ) );
		}
		
	}
	
	/**
	 * Função para tradução
	 */
	public function localise() {
		load_plugin_textdomain( 'woo-pagseguro-parceled', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	
	/**
	 * Desativar Plugin
	 */
	public function desative_plugin_now() {	
		$active_plugins = get_option( 'active_plugins' );
		$pagseguro_settings = get_option( 'woocommerce_pagseguro_settings' );
		$search_plugin = array_search('woo-pagseguro-parceled/woo-pagseguro-parceled.php', $active_plugins);
		if($search_plugin > 0) {
			unset($active_plugins[$search_plugin]);	
			update_option( 'active_plugins', $active_plugins);			
			if (! empty($pagseguro_settings)) {
				unset($pagseguro_settings[installment]);
				unset($pagseguro_settings[installment_single_product]);
				update_option( 'woocommerce_pagseguro_settings', $pagseguro_settings);
			}
		}
	}
	
	/**
	 * Aviso de falha woocommerce pagseguro
	 */
	public function woocommerce_pagseguro_failure_notice() {
		?>
		<div class="clear"></div><div class="error"><p><strong>Erro Importante!</strong> Você ainda não tem o <a href="https://wordpress.org/plugins/woocommerce-pagseguro/" target="_blank"><strong>Plugin WooCommerce PagSeguro</strong></a> instalado ou ativo!<hr/><em> É requerimento obrigatório para uso do plugin (Woo PagSeguro Parceled).</em></p></div><style>#message{ display:none; }</style>
        <?php
	}
	
	/**
	 * Aviso de falha woocommerce
	 */
	public function woocommerce_plugin_failure_notice() {
		?>
		<div class="clear"></div><div class="error"><p><strong>Erro Importante!</strong> Você ainda não tem o <a href="https://wordpress.org/plugins/woocommerce/" target="_blank"><strong>Plugin WooCommerce</strong></a> instalado ou ativo!<hr/><em> É requerimento obrigatório para uso do plugin (Woo PagSeguro Parceled).</em></p></div><style>#message{ display:none; }</style>
        <?php
	}
	
	/*
	 * Copia o arquivo com os novos dados de configurações
	 */
	public function woo_product_parceled_file_replace() {
		// @version plugin 2.11.3 @version file 2.11.0
		$plugin_dir = plugin_dir_path( __FILE__ ) . 'class-wc-pagseguro-gateway.php';
		$plugin_dir_woo = ABSPATH . 'wp-content/plugins/woocommerce-pagseguro/includes/class-wc-pagseguro-gateway.php';
	
		if (!copy($plugin_dir, $plugin_dir_woo)) {
			add_action( 'admin_notices', array( $this, 'woocommerce_install_notice_erro' ) );
		}else{
			add_action( 'admin_notices', array( $this, 'woocommerce_install_notice' ) );
		}
	}	
	
	/*
	 * Mensagem de erro caso o sistema não consiga copiar o arquivo
	 */
	public function woocommerce_install_notice_erro() {
		echo '<div class="clear"></div>
			<div class="error">
				<p>Houve um erro ao tentar finalizar a configuração do plugin! Faça a copia manualmente do arquivo.
				<a href="https://wordpress.org/plugins/woo-pagseguro-parceled/faq/" target="_blank">Ajuda aqui.</a></p>
				<button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button>
			</div>';
	}
	
	/*
	 * Mensagem que confirma a copia do arquivo pelo sistema automáticamente.
	 */
	public function woocommerce_install_notice() {
		echo '<div class="clear"></div>
			<div class="updated notice is-dismissible">
				<p>Conseguimos copiar o arquivo com sucesso! Você já pode aproveitar o máximo de seu sistema.
				<a href="'.admin_url('admin.php?page=wc-settings&tab=checkout&section=wc_pagseguro_gateway').'">Configure Já!</a></p>				
				<button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button>
			</div>';
	}	

	/*
	 * Link para configurar o plugin após sua instalação com sucesso.
	 */
	public static function plugin_action_links_settings( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_pagseguro_gateway' ) . '" title="Configurar Plugin" class="error">Configurar</a>',
		);

		return array_merge( $action_links, $links );
	}
 
	/*
	 * Pega os dados do sistema (produto e configuração PagSeguro)
	 * Faz o Calculo retirando o parcelamento configurado com o valor do produto
	 */
	public static function woo_product_parceled() {
		$product = get_product();
		$pagseguro_settings = get_option( 'woocommerce_pagseguro_settings' );
		$pagseguro_settings_installment = $pagseguro_settings[installment];
		
		if ( $product->get_price_including_tax() ) {
			
			$value =  $product->get_price_including_tax() / $pagseguro_settings_installment;
			
			while(($product->get_price_including_tax() / $pagseguro_settings_installment) < 5 && $pagseguro_settings_installment > 1) {
				$pagseguro_settings_installment--;
			}
			return woocommerce_price($product->get_price_including_tax() / $pagseguro_settings_installment);
		}
	}
	
	/*
	 * Faz o Calculo retirando o parcelamento configurado com o valor do produto.
	 * No caso do valor da parcela for menor que 5 reais referente a configuração do plugin
	 */
	public static function woo_product_parceled_installment($parceled, $get_price) {	
		$pagseguro_settings_installment = $parceled;
		if($pagseguro_settings_installment > 0 && $get_price >= 5 ) {		
			while(($get_price / $pagseguro_settings_installment) < 5 && $pagseguro_settings_installment > 0) {
				$pagseguro_settings_installment--;
			}
		}
		return $pagseguro_settings_installment;	
	}
	
	/*
	 * Exibe na tela o resultado do calculo no loop dos produtos
	 */
	public function woo_product_parceled_loop() {
		
		$product = get_product();
		$pagseguro_settings = get_option( 'woocommerce_pagseguro_settings' );
		if ($pagseguro_settings[installment] > 0 && $product->get_price_including_tax() > 0) { ?>	
        <!--Personalize seu CSS aqui-->	
		<style>
			.p-woo-pagseguro-price { display:block; margin:5px 0; padding:8px 0; border-top:1px solid #b78789; border-radius:0 0 5px 5px; background-color:#f9e6e7; }
			.p-woo-pagseguro-installment { text-align:center; font-size:12px; line-height:13px; }
			.p-woo-pagseguro-installment span { font-size:14px; font-weight:700; color:#4c87c1; }
        </style>		
		<div class="p-woo-pagseguro-price">
			<div class="p-woo-pagseguro-installment">            
			<?php 
			if ($product->get_price_including_tax() > 5) {
				$woo_product_parceled_installment = $this->woo_product_parceled_installment($pagseguro_settings[installment], $product->get_price_including_tax());
			} else {
				$woo_product_parceled_installment = 1;
			}
			printf(__('Ou em até <span>%sx</span> de <span>%s</span>', 'woo-pagseguro-parceled' ),  $woo_product_parceled_installment, $this->woo_product_parceled() ); ?><br/>
			<strong><?php _e('Sem Juros - PagSeguro', 'woo-pagseguro-parceled' ); ?></strong>
			</div>
		</div>
		<?php
		}
	
	}
	
	/*
	 * Exibe na tela o resultado do calculo no loop dos produtos
	 */
	public function woo_product_box_parceled_single_product( $price, $start, $installment) {
		$pagseguro_settings = get_option( 'woocommerce_pagseguro_settings' );
		if ($price > 0) {	
				
			$fator = array( 
				1,
				0.52255,
				0.35347, 
				0.26898,
				0.21830,
				0.18453, 
				0.16044,
				0.14240,
				0.12838, 
				0.11717,
				0.10802,
				0.10040,
			);
			
			if ($installment > 0 )  {
				$j = 0;
				for ($i = 0; $i <= 11; $i++ ) {
					if ($start == 'left') {
						// left
						if (($price / ($i + 1)) >= 5 && ($i % 2) == 0 ) {
							$j++;
							if ($i + 1 > $installment) { $installments = "com juros"; } else { $installments = "<strong>*sem juros</strong>"; }
							if (($j + 1) % 2 ) { $class = ""; } else { $class = "color"; }
							if (($i + 1) <= $installment ) {
								echo '<span class="span-woo-pagseguro-installments '.$class.'">'.($i+1).'x de '.woocommerce_price($price / ($i+1)).' '.$installments.'</span>';
							}else{
								echo '<span class="span-woo-pagseguro-installments '.$class.'">'.($i+1).'x de '.woocommerce_price($price * $fator[$i]).' '.$installments.'</span>';
							}
						} 
						//
					} else if ($start == 'right') {
						// right
						if (($price / ($i + 1)) >= 5 && ($i % 2) != 0 ) {
							$j++;
							if ($i + 1 > $installment) { $installments = "com juros"; } else { $installments = "<strong>*sem juros</strong>"; }
							if (($j + 1) % 2 ) { $class = ""; } else { $class = "color"; }
							if (($i + 1) <= $installment ) {
								echo '<span class="span-woo-pagseguro-installments '.$class.'">'.($i+1).'x de '.woocommerce_price($price / ($i+1)).' '.$installments.'</span>';
							}else{
								echo '<span class="span-woo-pagseguro-installments '.$class.'">'.($i+1).'x de '.woocommerce_price($price * $fator[$i]).' '.$installments.'</span>';
							}
						}
						//
					}
					
				}
			}else{
					echo '<span class="span-woo-pagseguro-installments '.$class.'">1x de '.woocommerce_price($price).' *sem juros</span>';
			}

		}
	}
	
	/*
	 * Exibe na tela o calculo completo com os parcelamentos sem juros e com no página do produto
	 */
	public function woo_product_parceled_single_product() {
		
		$product = get_product();
		$pagseguro_settings = get_option( 'woocommerce_pagseguro_settings' );
		if ($pagseguro_settings[installment] > 0 && $product->get_price_including_tax() > 0) { ?>
        <!--Personalize seu CSS aqui-->	
		<style>
			.price { margin-bottom:0px; }
			.p-woo-pagseguro-price { display:block; margin:0 0 5px; padding:8px 10px; border-top:1px solid #b78789; border-radius:0 0 5px 5px; background-color:#f9e6e7; }
			.p-woo-pagseguro-installment { display:block; padding:0 10px; font-size:12px; line-height:15px; }
			.p-woo-pagseguro-installment span { font-size:14px; font-weight:700; color:#4c87c1; }
			.box-parceled { display:block; text-align:left; margin:0 0 5px; padding:0; border:1px solid #c1c1c1; border-radius:5px; background-color:#f1f1f1; }
			.box-parceled h2 { display:block; background:#dfdfdf; border-bottom:1px solid #c1c1c1; font-size:14px; text-transform:uppercase; text-align:center; padding:5px 0; margin:0; }
			.box-parceled .left { display:block; float: left; width:49.8%; border-right:1px solid #c1c1c1; }
			.box-parceled .right { display:block; float:right; width:49.8%; border-right:0; }
			.box-parceled .span-woo-pagseguro-installments { display: block; padding:5px 10px; font-size:12px; text-align:center; }
			.box-parceled .span-woo-pagseguro-installments.color { background:#FFF; }
			.box-parceled .clear { clear:both; }
        </style>		
		<div class="p-woo-pagseguro-price">
			<div class="p-woo-pagseguro-installment">
			<?php 
			if ($product->get_price_including_tax() > 5) {
				$woo_product_parceled_installment = $this->woo_product_parceled_installment($pagseguro_settings[installment], $product->get_price_including_tax());
			} else {
				$woo_product_parceled_installment = 1;
			}
			printf(__('Ou em até <span>%sx</span> de <span>%s</span>', 'woo-pagseguro-parceled' ),  $woo_product_parceled_installment, $this->woo_product_parceled() ); ?><br/>
			<strong><?php _e('Sem Juros - PagSeguro', 'woo-pagseguro-parceled' ); ?></strong></span>
			</div>
		</div>
		<?php
		}
		if ($pagseguro_settings[installment_single_product] == "yes" && $product->get_price_including_tax() > 5) { ?>
            <div class="box-parceled">
                <h2><?php _e('Parcelamento PagSeguro', 'woo-pagseguro-parceled' ); ?></h2>
                <div class="left">
                <?php $this->woo_product_box_parceled_single_product($product->get_price_including_tax(), 'left',  $this->woo_product_parceled_installment($pagseguro_settings[installment], $product->get_price_including_tax())); ?>
                </div>
                <div class="right">
                <?php if ( $this->woo_product_parceled_installment($pagseguro_settings[installment], $product->get_price_including_tax()) > 0 ) { ?>
                <?php $this->woo_product_box_parceled_single_product($product->get_price_including_tax(), 'right',  $this->woo_product_parceled_installment($pagseguro_settings[installment], $product->get_price_including_tax())); ?>
                <?php } ?>
                </div>
                <div class="clear"></div>
             </div>
			<?php
		}
	}
	
	/*
	 * Exibe na tela uma pequena mensagem de marketing na página do carrinho
	 */
	public static function woo_product_parceled_cart() {		
		$product = get_product();
		$pagseguro_settings = get_option( 'woocommerce_pagseguro_settings' );
		
		if ($pagseguro_settings[installment_single_product] == "yes" && $pagseguro_settings[installment] > 1) { ?>
		<tr>
			<th colspan="2" class="p-woo-pagseguro-price">
        	<!--Personalize seu CSS aqui-->	
			<style>
				.p-woo-pagseguro-price { margin:0; padding:8px 10px; border-top:1px solid #b78789; border-radius:0 0 5px 5px; background-color:#f9e6e7; }
				.p-woo-pagseguro-installment { font-size:12px; line-height:15px; text-align:center; }
            </style>
				<div class="p-woo-pagseguro-installment">
					<?php printf(__('* Pague suas compras em até<br/> %sx sem juros com PagSeguro em nossa loja.', 'woo-pagseguro-parceled'), $pagseguro_settings[installment]); ?>
				</div>
			</th>
		</tr>
		<?php
		}
	}
	
}
/**
 * Ativar a class
 */
new WC_Woo_Product_Parceled();