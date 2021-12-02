<?php
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$titulo = '';

if(is_tag()) {
	$tag   = get_queried_object();
    $titulo = $tag->name;

	$postsList = new WP_Query( array(
		'post_type'      => 'post',
		'posts_per_page' => 9,
		'post_status' => 'publish',
		'tag_id' => $tag->term_id,
		'paged' => $paged
	) );

} elseif ( is_search() ) {
	$pesquisa = $_GET['s'];
	$titulo = $pesquisa;

	$postsList = new WP_Query( array(
		'post_type'      => array( 'post', 'especiais', 'podcast', 'webstories', 'page' ),
		'post_status'    => 'publish',
		's'              => $pesquisa,
		'posts_per_page' => 9,
		'paged'          => $paged
	) );

} elseif ( is_author() ) {
	$autor_id          = get_user_by( 'slug', get_query_var( 'author_name' ) )->ID;
	$autor_img         = get_field( 'usuario_foto', 'user_' . $autor_id );
	$autor_img         = $autor_img['sizes']['thumbnail'];
	$autor_biografia   = get_field( 'usuario_biografia', 'user_' . $autor_id );
	$autor_facebook    = get_field( 'usuario_facebook', 'user_' . $autor_id );
	$autor_linkedin    = get_field( 'usuario_linkedin', 'user_' . $autor_id );
	$autor_email       = get_field( 'usuario_email', 'user_' . $autor_id );
	$autor_loc = get_field( 'usuario_localizacao', 'user_' . $autor_id );
	$autor_idiomas     = get_field( 'usuario_idiomas', 'user_' . $autor_id );

	$padrao = get_posts(
		array(
			'fields'         => 'ids',
			'post_type'      => 'any',
            'posts_per_page' => -1,
            'author' => $autor_id
		)
	);

	$compartilhados = get_posts(
		array(
			'fields'         => 'ids',
			'post_type'      => 'any',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key'     => 'post_autores',
					'value'   => $autor_id,
					'compare' => 'LIKE',
				),
			),
		)
	);

	$autor_ids = array_merge( $padrao, $compartilhados );

	$postsList = new WP_Query( array(
		'post_type'      => 'any',
		'post_status'    => 'publish',
		'post__in'       => $autor_ids,
		'posts_per_page' => 9,
		'paged'          => $paged,
    ) );
}
?>

<div class="container arquivo mt-4 mt-md-5">
    <header>
	    <?php if ( is_author() ) { ?>
            <div class="arquivo__autor d-flex justify-content-center mb-5 pb-5">
                <div class="imagem me-3">
                    <img data-src="<?php echo $autor_img ?>" class="w-100 lazy">
                </div>

                <div class="texto d-flex flex-column">
                    <h1 class="m-0"><?php echo get_the_author_meta('display_name', $autor_id); ?></h1>

                    <p class="descricao m-0"><?php echo $autor_biografia ?></p>

                    <div class="social-mural d-flex mt-4">
                        <?php

                        if ( ! empty( $autor_linkedin ) ) {
	                        echo '<a href="' . $autor_linkedin . '" class="linkedin"><i></i></a>';
                        }
                        ?>

	                    <?php
	                    if ( ! empty( $autor_loc ) ) {
		                    $quebrada = get_term( $autor_loc, 'quebrada' );
		                    $zona     = get_field( 'quebrada_zona', 'quebrada_' . $quebrada->term_id ); ?>

                            <a href="<?php echo get_bloginfo( 'wpurl' ).'/sua-quebrada/'.$zona['value'].'/'.$quebrada->slug.'/' ?>" class="bairro text-uppercase border px-2 text-center ms-auto"><?php echo $quebrada->name ?></a>

	                    <?php }
	                    ?>

                    </div>
                </div>
            </div>
	    <?php } else { ?>
            <h1 class="m-0 text-center text-uppercase">"<?php echo $titulo ?>"</h1>
	    <?php } ?>
    </header>

	<?php if ( ! empty( $postsList->posts ) ) { ?>
            <div class="arquivo__listagem mt-5">
                <div class="row bloco-card row-cols-1 row-cols-md-3 mt-5 padding-mobile pagination-infi">
                    <?php foreach ( $postsList->posts as $item ) {
	                    $img_desk   = get_image_prod( $item->ID, 'large' );
	                    $img_mobile = get_image_prod( $item->ID, 'medium' );
	                    $linhaFina  = get_field( 'post_linha_fina', $item->ID );
	                    $categoria  = get_the_category( $item->ID )[0];
	                    $bairroZona = mural_quebradaPost( $item->ID );

	                    ?>

                        <div class="bloco col mb-5">
	                        <?php if ( ! empty( $bairroZona ) ) { ?>
                                <div class="d-flex mb-2">
                                    <?php echo $bairroZona; ?>
                                </div>
	                        <?php } ?>


                            <div class="bloco__post">
                                <div class="image">
                                    <a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>">
                                        <picture>
                                            <source media="(max-width: 799px)" data-srcset="<?php echo $img_mobile ?>">
                                            <source media="(min-width: 800px)" data-srcset="<?php echo $img_desk ?>">
                                            <img data-src="<?php echo $img_desk ?>" alt="Image" class="w-100 lazy">
                                        </picture>
                                    </a>
                                </div>

                                <div class="texto mt-1">
                                    <span class="categoria text-uppercase"><?php echo $categoria->name ?></span>

                                    <a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>">
                                        <h2 class="m-0 mt-1"><?php echo $item->post_title; ?></h2>

	                                    <?php if ( ! empty( $linhaFina ) ) { ?>
                                            <p class="linha-fina m-0 mt-1"><?php echo $linhaFina ?></p>
	                                    <?php } ?>
                                    </a>

                                    <span class="detalhes mt-1 d-block">
                                        <?php echo mural_assinaturaData($item->ID); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
	<?php }
	wp_pagenavi(array( 'query' => $postsList ));
    ?>

    <div class="page-load-status more">
        <div class="infinite-scroll-request mt-3 mb-3 spinner">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="infinite-scroll-last"></div>
        <div class="infinite-scroll-error"></div>
    </div>

</div>