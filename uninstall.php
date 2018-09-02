<?php
// Se o arquivo uniinstall não for chamado a partir da saída wordpress
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit ();
}
	// Limpando os dados da tabela options
	// Mantendo apenas os dados originais do plugin Woocommerce PagSeguro
	// Isso acontecerá somente se o plugin Woo PagSeguro Parceled for excluido do sistema via WordPress
	$pagseguro_settings = get_option( 'woocommerce_pagseguro_settings' );
	unset($pagseguro_settings[installment]);
	unset($pagseguro_settings[installment_single_product]);
	update_option( 'woocommerce_pagseguro_settings', $pagseguro_settings);
	delete_option( 'woo_pagseguro_parceled', $version_plugin);

?>