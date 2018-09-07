=== Woo PagSeguro Parceled ===
Contributors: carlosramosweb
Donate link: http://wwww.criacaocriativa.com.br
Tags: woocommerce, pagseguro, payment, parceled, installments, parcela, parcelamento, 
Requires at least: 3.5.0
Tested up to: 4.9.8
Stable tag: 1.6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Github: https://github.com/carlosramosweb/woo-pagseguro-parceled/

Exibir o parcelamento sem juros pré-configurado no Painel Administrativo usando o plugin WooCommerce PagSeguro.

== Description ==

= Em Português Brasil =
Trata-se de um pequeno sistema para exibir o parcelamento sem e com juros pré-configurado no Painel Administrativo WordPress usando o plugin Woocommerce PagSeguro. O plugin foi criado para melhorar a experiência do cliente final exibindo no loop dos produtos o cálculo.

= in English =
It is a small system to display the installment plan without preset interest in WordPress Admin Panel using the plugin WooCommerce PagSeguro.
The plugin is designed to enhance the experience of the end customer displayed on the loop of products calculation.


== Installation ==

= Instalação =
* Fazer upload para o /wp-content/plugins/ diretório.
* Verifique se já tem instalado o outro Plugin WooCommerce PagSeguro fundamental para a instalação deste.
* Ative o plugin através do menu 'Plugins' no WordPress.
* Vá até o painel de configuração do PagSeguro em WooCommerce > Configurações > Finalizar Compras.

> Dúvidas ainda entre em contato [aqui](http://www.criacaocriativa.com.br).

== Frequently Asked Questions ==

> O nosso FAQ é sempre atualizado com as dúvidas mais recebidas. Então fique atendo as nossas atualizações frequentes.

= O que eu preciso para utilizar este plugin? =
Ter instalado os plugins WooCommerce e Woocommerce PagSeguro.

= Qual país o PagSeguro atende? =
Atualmente o PagSeguro recebe pagamento somente e exclusivamente do Brasil.

= Este plugin modifica o outro que tenho instalado? =
Em parte! Este plugin veio para complementar o plugin Woocommerce PagSeguro na falta de configurações de parcelamento e exibição dos mesmo sem juros.

= Tenho que configurar o parcelamento também no website do PagSeguro? =
Sim! Nosso plugin somente apresenta o que você deve configurar no website do PagSeguro.

= Tenho que fazer backup antes? =
Não necessariamente! Como qualquer desenvolvedor ou usuário do wordpress, criamos a mania de segurança e antes de instalar qualquer plugin faça o backup do banco de dados e arquivos.

= Por que este plugin foi criado? =
Houve a necessidade de alguns de nossos clientes na exibição do parcelamento sem juros e com isso estamos disponibilizando gratuitamente em razão de testes de publicação no WordPress Plugins. Uma parte da ideia deste código foi removida do plugin WooCommerce Installments que hoje não é mais atualizado.

= Posso ajudar a melhorar o Plugin? =
Claro! Toda ajuda é bem vinda e estamos a disposição para apreciar as suas modificações. Envie seu código para  o e-mail carlosramosweb@hotmail.com.

= Qual é a licença do plugin? =
Este plugin está licenciado como GPLv2 or later.

= Gostei do plugin e queria ajudar financeiramente? =
Ficamos felizes em saber que lhe ajudamos! Para doar qualquer quantia use este [link](http://donate.criacaocriativa.com.br), será remetido ao sistema do PagSeguro.

== License ==

This file is part of Nome do teu Plugin.
Nome do teu Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Nome do teu Plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Nome do teu Plugin. If not, see <http://www.gnu.org/licenses/>.

== Changelog ==

= 1.6.2 =
* Atualização do arquivo class-wc-pagseguro-gateway.php para a versão atual (2.13.1).

= 1.6.1 =
* Atualização do arquivo class-wc-pagseguro-gateway.php para a versão atual (2.12.5).

= 1.6.0 =
* Correção do erro [Warning: Division by zero] para valores de produtos abaixo de R$ 5 reais.

= 1.5.9 =
* Verificação dos plugin obrigatório WooCommerce e ou WooCommerce Pagseguro.
* Mensagem de erro para as verificações de plugins obrigatório.
* Desativação automática do plugin.
* Ajuste no parcelamento para usar a partir de 1x sem juros.
* Verificação da versão anterior para copiar ou não o arquivo class-wc-pagseguro-gateway.php.

= 1.5.4 =
* Ajuste nos blocos de códigos CSS.
* Ajuste no botão de configurar para facilitar as edições.

= 1.5.2 =
* Mais melhorias no código do arquivo uninstall.php.
* Ajuste de verificação do Plugin se instalado antes WooCommerce Pagseguro.
* Mensagem de erro para a verificação do Plugin se instalado.
* Ajuste da versão da 2.11.3 do plugin Woocommerce PagSeguro.

= 1.5.1 =
* Mais melhorias no código do arquivo woo-pagseguro-parceled.php.
* Melhoria dos comentários no código em Português Brasil.
* Distribuição das parcelas na tabela que fica na página produtos (valores impares e pares) - Crédito de sugestão para brendaferreirap.
* Ajuste da versão da 2.11.0 do plugin Woocommerce PagSeguro. 

= 1.5.0 =
* Cópia automática do arquivo class-wc-pagseguro-gateway.php obrigatória para o bom funcionamento do plugin.
* Mensagem de erro/sucesso de copia do arquivo com link de suporte online.
* Criação do arquivo uninstall.php para limpar os dados criado pelo plugin.

= 1.4.9 =
* Melhorias no código do arquivo woo-pagseguro-parceled.php.
* Adicionamos a mesma mesangem do loop na página do produto.
* Exibição do calculo completo com os parcelamentos sem juros e com na página do produto.
* Nova opção ativar ou desativar o parcelamento completo na página do produto no código do arquivo class-wc-pagseguro-gateway.php.
* Exibição de uma pequena mensagem de marketing na página do carrinho de compras.
* Melhoria do css em todas as aparições.
* Ajuste do limite mínimo em cada parcela simulada no loop dos produtos.
* Ajuste para parcela única no simulador do loop dos produtos e na tabela "parcelamento Pagseguro".
* Ajuste do limite máximo de parcelas simulada na tabela "parcelamento Pagseguro" página do produto.

= 1.4.0 =
* Inserido novo arquivo que class-wc-pagseguro-gateway.php da versão 2.10.3.

== Upgrade Notice ==

= 1.6.0 =
* Correção do erro Division by zero.

= 1.5.7 =
* Ajuste importante para evitar travamento do wordpress - Crédito de identificação de erro para paulo1979.

= 1.5.2 =
* Aviso de erro e ajuste da nova versão do plugin Woocommerce PagSeguro 2.11.3.

= 1.5.1 =
* Questões estéticas e ajuste da nova versão do plugin Woocommerce PagSeguro 2.11.0.

= 1.5.0 =
* Está atualização veio para automatizar a instalação do plugin em respeito a copia do arquivo editado com um simples botão.

= 1.4.9 =
* Questões estéticas e mais recursos disponíveis, além de ajustes importantes no simulador.

= 1.4.0 =
* O ajuste foi feita para ficar de acordo com a nova versão plugin Woocommerce PagSeguro.

= 1.3.0 =
* lançamento inicial.

== Screenshots ==
1. Exibindo parcelamento PagSeguro no loop dos produtos, página do produto e carrinho de compras.
2. Como ativar seu plugin: Na instalação via web ou upload.
3. Links para configuração do sistema.
4. Configurando o sistema para exibir as parcelas e demais recursos.
