<?php
get_header();

if ( is_home() ) {
	get_template_part( 'template_partes/pagina-inicial' );
} elseif ( is_category() ) {
	get_template_part( 'template_partes/categorias' );
} elseif ( is_single() ) {
	get_template_part( 'template_partes/single' );
} elseif ( is_page() ) {
	get_template_part( 'template_partes/paginas' );
} else {
	get_template_part( 'template_partes/archive' );
}
get_footer();