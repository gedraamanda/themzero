<?php
$category   = get_queried_object();
$categoryId = $category->term_id;

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$catCortina = new WP_Query( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'cat'            => $categoryId,
) );

if ( empty( $catCortina->posts ) ) {
	return;
}

$posts_not = array();

$corValidate = get_field( 'categoria_cor', 'category_' . $categoryId );
$cor         = ! empty( $corValidate ) ? $corValidate : '#FF5900';
$corFundo    = mural_corOpacidade( $cor, '0.2' );

//categoria patrocinada
$catPatrocinada = get_field( 'patrocinio', 'category_' . $categoryId );
$logoPatro      = get_field( 'patrocinador_logo', 'category_' . $categoryId );
$frasePatro     = get_field( 'patrocinador_frase_editoria', 'category_' . $categoryId );

?>

<div class="categoria-container pt-4 pt-md-5" style="--bgfundo: <?php echo $corFundo ?>">
    <div class="container">
        <header class="categoria-container__header text-center">
            <h1 class="m-0 text-uppercase d-inline-block px-2" style="--bgcolor: <?php echo $cor ?>"><?php echo $category->name ?></h1>

	        <?php if ( ! empty( $category->description ) ) { ?>
                <p class="m-0 descricao mt-3" style="--colorcat: <?php echo $cor ?>"><?php echo $category->description ?></p>
	        <?php } ?>

	        <?php if ( $catPatrocinada ) { ?>
                <div class="patrocinada mt-4 padding-mobile">
                    <div class="d-flex justify-content-center align-items-end chamada">
                        <span class="me-2">APOIO</span>

                        <img src="<?php echo $logoPatro['url'] ?>" alt="<?php echo $logoPatro['title'] ?>" class="lazy" width="40" height="auto">
                    </div>

                    <div class="frase justify-content-center mt-2">
                        <p class="m-0 py-1 px-3"><?php echo $frasePatro ?></p>
                    </div>
                </div>
            <?php } ?>

        </header>

        <div class="categoria-container__cortina cortina mt-4">
            <div class="row cortina-int">
                <?php if ( ! empty( $catCortina->posts[0] ) ) {
                    $destaque = $catCortina->posts[0];

                    array_push( $posts_not, $destaque->ID );

	                $img_desk           = get_image_prod( $destaque->ID, 'large' );
	                $img_mobile         = get_image_prod( $destaque->ID, 'medium' );
	                $destaque_categoria = get_the_category( $destaque->ID )[0];
	                $destaque_linhaFina = get_field( 'post_linha_fina', $destaque->ID );


                ?>

                    <div class="col-12 col-md-6 cortina-int__bloco">
                        <div class="position-sticky-desk bloco-sticky">
                            <div class="image">
                                <a href="<?php echo esc_url( get_permalink( $destaque->ID ) ); ?>">
                                    <picture>
                                        <source media="(max-width: 799px)" data-srcset="<?php echo $img_mobile ?>">
                                        <source media="(min-width: 800px)" data-srcset="<?php echo $img_desk ?>">
                                        <img data-src="<?php echo $img_desk ?>" alt="Image" class="w-100 lazy">
                                    </picture>
                                </a>
                            </div>

                            <div class="texto mt-3 padding-mobile">
<!--                                <span class="categoria text-uppercase">--><?php //echo $destaque_categoria->name ?><!--</span>-->

                                <a href="<?php echo esc_url( get_permalink( $destaque->ID ) ); ?>">
                                    <h2 class="m-0"><?php echo $destaque->post_title; ?></h2>

	                                <?php if ( ! empty( $destaque_linhaFina ) ) { ?>
                                        <p class="linha-fina m-0 mt-2"><?php echo $destaque_linhaFina ?></p>
	                                <?php } ?>
                                </a>

                                <span class="detalhes mt-2 d-block">
                                    <?php echo mural_assinaturaData($destaque->ID); ?>
                                </span>
                            </div>
                        </div>

                    </div>
                <?php }

                //remove destaque
                $primeiro = array_slice( $catCortina->posts, 1, 2, true );
                $segundo  = array_slice( $catCortina->posts, 3, 2, true );

                ?>

                <div class="col-12 col-md-6 cortina-int__bloco menor mt-5 mt-md-0">
                    <div class="row">
                        <div class="col-12 col-md-6 padding-mobile">
                            <?php foreach ( $primeiro as $i => $p ) {
	                            array_push( $posts_not, $p->ID );

	                            $img_desk           = get_image_prod( $p->ID, 'large' );
	                            $img_mobile         = get_image_prod( $p->ID, 'medium' );
	                            $categoria = get_the_category( $p->ID )[0];
	                            $linhaFina = get_field( 'post_linha_fina', $p->ID );
	                        ?>

                                <div class="col-12 menor-bloco <?php echo $i === 1 ? 'mb-5' : '' ?>">
                                    <div class="image text-center text-md-start">
                                        <a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>">
                                            <picture>
                                                <source media="(max-width: 799px)" data-srcset="<?php echo $img_mobile ?>">
                                                <source media="(min-width: 800px)" data-srcset="<?php echo $img_desk ?>">
                                                <img data-src="<?php echo $img_desk ?>" alt="Image" class="lazy">
                                            </picture>
                                        </a>
                                    </div>

                                    <div class="texto mt-2 mt-md-3">
<!--                                        <span class="categoria text-uppercase">--><?php //echo $categoria->name ?><!--</span>-->

                                        <a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>">
                                            <h2 class="m-0 mt-2 mt-md-3"><?php echo $p->post_title; ?></h2>

                                            <?php if ( ! empty( $linhaFina ) ) { ?>
                                                <p class="linha-fina m-0 mt-2"><?php echo $linhaFina ?></p>
                                            <?php } ?>

                                        </a>

                                        <span class="detalhes mt-2 d-block">
                                            <?php echo mural_assinaturaData($p->ID); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-12 col-md-6 padding-mobile mt-5 mt-md-0">
	                        <?php foreach ( $segundo as $i => $s ) {
		                        array_push( $posts_not, $s->ID );

		                        $img_desk           = get_image_prod( $s->ID, 'large' );
		                        $img_mobile         = get_image_prod( $s->ID, 'medium' );
		                        $categoria = get_the_category( $s->ID )[0];
		                        $linhaFina = get_field( 'post_linha_fina', $s->ID );
		                        ?>

                                <div class="col-12 menor-bloco <?php echo $i === 3 ? 'mb-5' : '' ?>">
                                    <div class="image text-center text-md-start">
                                        <a href="<?php echo esc_url( get_permalink( $s->ID ) ); ?>">
                                            <picture>
                                                <source media="(max-width: 799px)" data-srcset="<?php echo $img_mobile ?>">
                                                <source media="(min-width: 800px)" data-srcset="<?php echo $img_desk ?>">
                                                <img data-src="<?php echo $img_desk ?>" alt="Image" class="lazy">
                                            </picture>
                                        </a>
                                    </div>

                                    <div class="texto mt-2 mt-md-3">
<!--                                        <span class="categoria text-uppercase">--><?php //echo $categoria->name ?><!--</span>-->

                                        <a href="<?php echo esc_url( get_permalink( $s->ID ) ); ?>">
                                            <h2 class="m-0 mt-2 mt-md-3"><?php echo $s->post_title; ?></h2>

					                        <?php if ( ! empty( $linhaFina ) ) { ?>
                                                <p class="linha-fina m-0 mt-2"><?php echo $linhaFina ?></p>
					                        <?php } ?>

                                        </a>

                                        <span class="detalhes mt-2 d-block">
                                            <?php echo mural_assinaturaData($s->ID); ?>
                                        </span>
                                    </div>
                                </div>
	                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $catListagem = new WP_Query( array(
	        'post_type'      => 'post',
	        'post_status'    => 'publish',
	        'posts_per_page' => 8,
	        'cat'            => $categoryId,
	        'post__not_in'   => $posts_not,
	        'paged'          => $paged
        ) );

        if ( ! empty( $catListagem->posts ) ) { ?>
            <div class="categoria-container__listagem mt-md-5 pt-5">
                <div class="row bloco-card row-cols-1 row-cols-md-4 mt-md-5 pagination-infi">
			        <?php foreach ( $catListagem->posts as $list ) {
				        $img_desk   = get_image_prod( $list->ID, 'large' );
				        $img_mobile = get_image_prod( $list->ID, 'medium' );
				        $categoria  = get_the_category( $list->ID )[0];
				        $linhaFina  = get_field( 'post_linha_fina', $list->ID );
				        $bairroZona = mural_quebradaPost( $list->ID );
				    ?>

                        <div class="bloco col mb-5">
	                        <?php if ( ! empty( $bairroZona ) ) { ?>
                                <div class="d-flex mb-2">
			                        <?php echo $bairroZona ?>
                                </div>
	                        <?php } ?>

                            <div class="bloco__post">
                                <div class="image">
                                    <picture>
                                        <source media="(max-width: 799px)" data-srcset="<?php echo $img_mobile ?>">
                                        <source media="(min-width: 800px)" data-srcset="<?php echo $img_desk ?>">
                                        <img data-src="<?php echo $img_desk ?>" alt="Image" class="w-100 lazy">
                                    </picture>
                                </div>

                                <div class="texto mt-1">
                                    <a href="<?php echo esc_url( get_permalink( $list->ID ) ); ?>">
                                        <h2 class="m-0 mt-1"><?php echo $list->post_title ?></h2>

	                                    <?php if ( ! empty( $linhaFina ) ) { ?>
                                            <p class="linha-fina m-0 mt-1"><?php echo $linhaFina ?></p>
	                                    <?php } ?>
                                    </a>

                                    <span class="detalhes mt-1 d-block">
                                        <?php echo mural_assinaturaData($list->ID); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
			        <?php } ?>
                </div>
            </div>
        <?php }

        wp_pagenavi(array( 'query' => $catListagem ));
        ?>
    </div>

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


