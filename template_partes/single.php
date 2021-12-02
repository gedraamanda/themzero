<?php
$post_id = get_the_ID();

$modelo = ! empty( get_field( 'post_layout', $post_id ) ) ? get_field( 'post_layout', $post_id ) : 'pequena';

$img_destaqueMobile = get_image_prod( $post_id, 'medium' );
$img_destaqueDesk = get_image_prod( $post_id, 'large' );
$linha_fina       = get_field( 'post_linha_fina', $post_id );
$categoria        = get_the_category( $post_id )[0];
$tipo             = get_field( 'post_tipo', $post_id );
$dataPublicacao   = esc_html( get_the_date( 'd.m.Y', $post_id ) );
$horaPublicacao   = esc_html( get_the_date( 'G:i', $post_id ) );
$dataAlteracao    = esc_html( get_the_modified_date( 'd.m.Y', $post_id ) );
$postData         = get_post( $post_id );

//categoria patrocinada
$catPatrocinada = get_field( 'patrocinio', 'category_' . $categoria->term_id );
$logoPatro      = get_field( 'patrocinador_logo', 'category_' . $categoria->term_id );
$frasePatro     = get_field( 'patrocinador_frase_post', 'category_' . $categoria->term_id );

//para saber mais
$saber_titulo = get_field( 'sabermais_titulo', $post_id );
$saber_itens  = get_field( 'sabermais_itens', $post_id );
$saber_qtd    = get_field( 'sabermais_qtd', $post_id );

$edicao = get_field( 'post_edicao', $post_id );

$maisLidas   = mural_googleSheets();
$comofizemos = get_field( 'post_comofizemos', $post_id );

if ( $modelo == 'grande' ) {
	$bairroZona = mural_quebradaPost( $post_id );
    ?>
    <div class="imagem header-big">
        <picture>
            <source media="(max-width: 799px)" data-srcset="<?php echo $img_destaqueMobile ?>">
            <source media="(min-width: 800px)" data-srcset="<?php echo $img_destaqueDesk ?>">
            <img data-src="<?php echo $img_destaqueDesk ?>" alt="Image" class="w-100 lazy">
        </picture>

        <div class="big-texto position-absolute w-100">
            <div class="container">
                <div class="d-flex mb-0">
                    <?php
                    if ( ! empty( $bairroZona ) ) {
	                    echo $bairroZona;
                    }
                    ?>

                    <a href="<?php echo esc_url( get_category_link( $categoria->term_id ) ); ?>"
                       class="categoria text-uppercase ms-2 border border-dark bg-light text-dark px-1"><?php echo $categoria->name ?></a>
                </div>

                <h1 class="m-0 mt-md-4"><?php echo get_the_title($post_id) ?></h1>
            </div>

        </div>
    </div>
<?php } ?>

<div class="single cortina-efeito <?php echo $modelo == 'grande' ? 'single-hero single-hero-active' : '' ?> ">
	<?php
	if ( $modelo === 'media' ) {
		get_template_part( 'componentes/header-med' );
	} elseif($modelo != 'grande') {
		get_template_part( 'componentes/header-peq' );
	} ?>

    <div class="single__int container mt-4">
        <div class="row">
            <div class="col-12 col-md-9 padding-mobile">

                <?php
                if ( $modelo === 'grande' ) {
	                get_template_part( 'componentes/header-gde' );
                }
                ?>

                <div class="detalhes d-flex flex-column px-4 px-md-0">
                    <?php
                    if ( empty( $edicao ) ) { ?>
                        <p class="m-0 mb-1"><span><?php echo !empty($tipo[0]['label']) ? $tipo[0]['label'] : '' ?></p>
                    <?php } else {
	                    foreach ( $edicao as $i => $edi ) {
                            if($i === 0) { ?>
                                <p class="m-0 mb-1"><span><?php echo !empty($tipo[0]['label']) ? $tipo[0]['label'].' - ' : '' ?><?php echo $edi['tipo'] ?>: </span> <?php echo $edi['nomes'] ?></p>
                            <?php } else { ?>
                                <p class="m-0 mb-1"><span><?php echo $edi['tipo'] ?>: </span> <?php echo $edi['nomes'] ?></p>
                            <?php }
                        }
                    }
                    ?>
                    <p class="m-0 dates">Publicado em <?php echo $dataPublicacao ?> | <?php echo $horaPublicacao ?> <?php echo !empty($dataAlteracao) ? '| Alterado em '.$dataAlteracao : '' ?> </p>
                </div>

                <div class="resumo d-flex flex-column px-4 px-md-0 mt-5">
                    <?php if(!empty($postData->post_excerpt)) { ?>
                        <span class="titulo">RESUMO</span>

                        <p class="m-0 mt-2"><?php echo $postData->post_excerpt ?></p>
                    <?php } ?>

                    <span class="m-0 mt-2 t-leitura">Tempo de leitura: <?php echo get_time_in_minutes($post_id) ?> minuto<?php echo get_time_in_minutes($post_id) != 1 ? 's' : '' ?></span>

                    <?php if ( $catPatrocinada ) { ?>
                        <div class="patrocinada chamada">
                            <div class="d-flex flex-md-row flex-column align-items-md-center mt-3 mt-md-0">
                                <span class="me-3"><?php echo $frasePatro ?></span>

                                <img src="<?php echo $logoPatro['url'] ?>" alt="<?php echo $logoPatro['title'] ?>" class="lazy" width="40" height="auto">
                            </div>

                        </div>

                    <?php } ?>
                </div>

                <div class="row conteudo mt-md-5 pt-5">
                    <div class="col-1 d-none d-md-flex">
                        <div class="position-sticky sticky-menu">
                            <div class="share-post social-mural position-relative">
                                <i class="social-check"></i>
                                <input type="checkbox" class="position-absolute top-0">

                                <div class="share-post__list d-flex flex-column mt-3">
                                    <a href="javascript:;" class="link compartilhar-link" data-link="<?php echo get_permalink($post_id) ?>"><i></i></a>
                                    <a href="javascript" addthis:url="<?php echo get_permalink($post_id) ?>" class=" addthis_button_twitter twitter"><i></i></a>
                                    <a href="#" class="instagram d-none"><i></i></a>
                                    <a class="addthis_button_facebook facebook" addthis:url="<?php echo get_permalink($post_id) ?>" title="Facebook" href="javascript:;">
                                        <i></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-12 col-md-11 conteudo__texto">
                        <?php

                        if ( has_blocks( $postData->post_content ) ) {
	                        $blocks = parse_blocks( $postData->post_content );

	                        foreach ( $blocks as $block ) {
		                        echo apply_filters( 'the_content', render_block( $block ) );
	                        }
                        } else { //legado
                            echo apply_filters( 'the_content', $postData->post_content );
                        }
                        ?>

	                    <?php if ( empty( $saber_itens ) ) { ?>
                            <div class="row after mt-5">
                                <div class="col-md-9 offset-md-3">
			                        <?php
			                        $tags = get_the_tags( $post_id );
			                        if ( ! empty( $tags ) ) {
				                        echo '<div class="after__tags d-flex flex-wrap text-center justify-content-center">';
				                        foreach ( $tags as $tag ) { ?>
                                            <a href="<?php echo get_tag_link($tag->term_id) ?>" class="text-uppercase"><?php echo $tag->name ?></a>
				                        <?php }
				                        echo '</div>';
			                        } ?>

                                    <div class="after__comentarios text-center mt-5 d-none" id="comentarios">
                                        <a href="#" class="text-uppercase border border-dark open-comentarios">
                                            3 comentários
                                        </a>
                                    </div>
			                        <?php
			                        $tipo_assinatura = get_field('post_assinatura', $post_id);

			                        ?>
                                    <div class="after__assinaturas border-top border-bottom border-dark mt-5 py-4">
				                        <?php
				                        if ( $tipo_assinatura === 'padrao' ) {
					                        $usuario_nome     = get_the_author_meta( 'display_name', $postData->post_author );
					                        $usuario_link     = get_author_posts_url( $postData->post_author );
					                        $usuario_img      = get_field( 'usuario_foto', 'user_' . $postData->post_author );
					                        $usuario_bio      = get_field( 'usuario_biografia', 'user_' . $postData->post_author );
					                        $usuario_local    = get_field( 'usuario_localizacao', 'user_' . $postData->post_author );
                                            $usuario_linkedin = get_field( 'usuario_linkedin', 'user_' . $postData->post_author ); ?>

                                            <div class="assinaturas-bloco d-flex">
						                        <?php if ( ! empty( $usuario_img ) ) { ?>
                                                    <div class="imagem me-3">
                                                        <a href="<?php echo $usuario_link ?>">
                                                            <img data-src="<?php echo $usuario_img['url'] ?>" alt="<?php echo $usuario_img['title'] ?>" class="w-100 lazy">
                                                        </a>
                                                    </div>
						                        <?php } ?>

                                                <div class="texto">
                                                    <a href="<?php echo $usuario_link ?>">
                                                        <span><?php echo $usuario_nome ?></span>
                                                    </a>

							                        <?php if ( ! empty( $usuario_bio ) ) { ?>
                                                        <p class="m-0"><?php echo $usuario_bio ?></p>
							                        <?php } ?>

                                                    <div class="d-flex align-items-center mt-2">
								                        <?php if ( ! empty( $usuario_local ) ) {
									                        $quebrada = get_term( $usuario_local, 'quebrada' );
									                        $zona     = get_field( 'quebrada_zona', 'quebrada_' . $quebrada->term_id );
								                            ?>
                                                            <a href="<?php echo get_bloginfo( 'wpurl' ).'/sua-quebrada/'.$zona['value'].'/'.$quebrada->slug.'/' ?>" class="bairro text-uppercase border px-2 text-center me-3"><?php echo $quebrada->name ?></a>
								                        <?php } ?>

                                                        <div class="social d-flex">
									                        <?php
									                        if ( ! empty( $usuario_linkedin ) ) {
										                        echo '<a href="'.$usuario_linkedin.'" target="_blank" class="linkedin"><i></i></a>';
									                        }
									                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
				                        <?php } else {
					                        $autores = get_field( 'post_autores', $post_id );

					                        if ( ! empty( $autores ) ) {
						                        foreach ( $autores as $i => $autor ) {
							                        $autor_id = $autor->data->ID;
							                        $usuario_nome     = get_the_author_meta( 'display_name', $autor_id );
							                        $usuario_link     = get_author_posts_url( $autor_id );
							                        $usuario_img      = get_field( 'usuario_foto', 'user_' . $autor_id );
							                        $usuario_bio      = get_field( 'usuario_biografia', 'user_' . $autor_id );
							                        $usuario_local    = get_field( 'usuario_localizacao', 'user_' . $autor_id );
							                        $usuario_linkedin = get_field( 'usuario_linkedin', 'user_' . $autor_id ); ?>

                                                    <div class="assinaturas-bloco d-flex mb-4">
								                        <?php if ( ! empty( $usuario_img ) ) { ?>
                                                            <div class="imagem me-3">
                                                                <a href="<?php echo $usuario_link ?>">
                                                                    <img data-src="<?php echo changeurl($usuario_img['url']) ?>" alt="<?php echo $usuario_img['title'] ?>" class="w-100 lazy">
                                                                </a>
                                                            </div>
								                        <?php } ?>

                                                        <div class="texto">
                                                            <a href="<?php echo $usuario_link ?>">
                                                                <span><?php echo $usuario_nome ?></span>
                                                            </a>

									                        <?php if ( ! empty( $usuario_bio ) ) { ?>
                                                                <p class="m-0"><?php echo $usuario_bio ?></p>
									                        <?php } ?>

                                                            <div class="d-flex align-items-center mt-2">
										                        <?php if ( ! empty( $usuario_local ) ) {
											                        $quebrada = get_term( $usuario_local, 'quebrada' );
											                        $zona     = get_field( 'quebrada_zona', 'quebrada_' . $quebrada->term_id );
										                            ?>
                                                                    <a href="<?php echo get_bloginfo( 'wpurl' ).'/sua-quebrada/'.$zona['value'].'/'.$quebrada->slug.'/' ?>" class="bairro text-uppercase border px-2 text-center me-3"><?php echo $quebrada->name ?></a>
                                                                <?php } ?>

                                                                <div class="social d-flex">
											                        <?php
											                        if ( ! empty( $usuario_linkedin ) ) {
												                        echo '<a href="'.$usuario_linkedin.'" target="_blank" class="linkedin"><i></i></a>';
											                        }
											                        ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
						                        <?php }
					                        }
				                        }
				                        ?>
                                    </div>

                                    <div class="after__botoes border-bottom border-dark py-4">
                                        <div class="row row-cols-2 row-cols-md-4">
                                            <?php if ( ! empty( $comofizemos ) ) { ?>
                                                <div class="botoes-bloco fizemos col text-center mb-4 mb-md-0">
                                                    <a href="javascript:;" data-href="fizemos">
                                                        <i class="mb-2"></i>

                                                        Como fizemos?
                                                    </a>

                                                </div>
                                            <?php }?>

                                            <div class="botoes-bloco pdf col text-center mb-4 mb-md-0">
                                                <a href="<?php echo get_permalink($post_id).'?format=pdf' ?>">
                                                    <i class="mb-2"></i>

                                                    Baixe PDF
                                                </a>

                                            </div>

                                            <div class="botoes-bloco republique col text-center">
                                                <a href="javascript:;" data-href="republique">
                                                    <i class="mb-2"></i>

                                                    Republique
                                                </a>

                                            </div>

                                            <div class="botoes-bloco erro col text-center">
                                                <a href="javascript:;" data-href="reportar">
                                                    <i class="mb-2"></i>

                                                    Reportar erro
                                                </a>

                                            </div>
                                        </div>

                                        <div class="botoes-descricao">
	                                        <?php if ( ! empty( $comofizemos ) ) { ?>
                                                <div class="desc-fizemos desc border-top border-dark mt-4 pt-4">
                                                    <div class="d-flex mb-3">
                                                        <span class="titulo d-block">Como fizemos?</span>

                                                        <a href="javascript:;" class="close ms-auto"><i></i></a>
                                                    </div>

                                                    <?php echo $comofizemos; ?>
                                                </div>
	                                        <?php } ?>

                                            <div class="desc-republique desc border-top border-dark mt-4 pt-4">
                                                <div class="titulo-republique">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <span class="titulo me-3">Republique</span>

                                                        <i class="cc me-1"></i>
                                                        <i class="att me-1"></i>
                                                        <i class="not-com me-1"></i>
                                                        <i class="sem-deriv"></i>

                                                        <a href="javascript:;" class="close ms-auto"><i></i></a>
                                                    </div>

                                                    <p class="m-0">Faça com que essa história chegue para mais pessoas.</p>
                                                    <p class="m-0">Republique o nosso conteúdo gratuitamente.</p>
                                                </div>

                                                <div class="regras d-flex flex-column mt-4">
                                                    <p class="mb-0"><strong>Regras:</strong></p>

                                                    <p class="border-bottom border-dark py-2 m-0">Os títulos podem ser modificados desde que não mude o contexto;</p>
                                                    <p class="border-bottom border-dark py-2 m-0">Os textos devem ser publicados como estão na versão original, sem edição ou cortes;</p>
                                                    <p class="border-bottom border-dark py-2 m-0">Todas as republicações devem dar crédito para a Agência Mural e também os créditos dos profissionais envolvidos em sua produção, conforme aparece na publicação original;</p>
                                                    <p class="border-bottom border-dark py-2 m-0">As fotografias e outras imagens/artes podem ser republicadas com os devidos créditos;</p>
                                                    <p class="border-bottom border-dark py-2 m-0">Os vídeos republicados não devem passar por nenhum tipo de edição, devem conter os créditos da Agência Mural; para transmissão na TV, é preciso enviar um pedido para contato@agenciamural.org.br.</p>
                                                    <p class="border-bottom border-dark py-2 m-0">Reportagens publicadas no site www.agenciamural.org.br não podem ser revendidas.</p>
                                                    <p class="py-2 m-0">Se possível, os materiais republicados devem mencionar o perfil da Mural nas redes sociais.</p>
                                                </div>
                                            </div>

                                            <div class="desc-reportar desc border-top border-dark mt-4 pt-4">
                                                <div class="d-flex">
                                                    <span class="titulo d-block">Reportar erro</span>

                                                    <a href="javascript:;" class="close ms-auto"><i></i></a>
                                                </div>

                                                <p class="m-0 mb-3">Quer informar a nossa redação sobre algum erro nesta matéria?
                                                    Preencha o formulário abaixo.</p>

                                                <div class="reportar-form">
                                                    <?php echo do_shortcode('[ninja_form id=4]'); ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

			                        <?php
			                        $posts_escolhidas = get_field('escolhidas_posts', 'escolhidas');

			                        if ( ! empty( $posts_escolhidas ) ) { ?>
                                        <div class="after__outras-materias mt-5 position-relative">
                                            <span class="titulo d-block text-center">ESCOLHIDAS PELA REDAÇÃO</span>

                                            <div class="outras-materias-int mt-2">
						                        <?php
						                        foreach ( $posts_escolhidas as $escolhida ) {
							                        $esc_post = $escolhida['post'];
							                        $imagemMobile  = get_image_prod( $esc_post->ID, 'medium' );
							                        $imagemDesk  = get_image_prod( $esc_post->ID, 'large' );
							                        $categoria = get_the_category($esc_post->ID)[0];
							                        ?>

                                                    <div class="outras-bloco">
                                                        <div class="imagem">
                                                            <picture>
                                                                <source media="(max-width: 799px)" data-srcset="<?php echo changeurl($imagemMobile)?>">
                                                                <source media="(min-width: 800px)" data-srcset="<?php echo changeurl($imagemDesk) ?>">
                                                                <img data-src="<?php echo changeurl($imagemDesk) ?>" alt="yrdyr" class="w-100 lazy">
                                                            </picture>
                                                        </div>

                                                        <div class="texto mt-2 mx-auto">
                                                            <a href="<?php echo get_category_link($categoria->term_id) ?>" class="categoria text-uppercase"><?php echo $categoria->name ?></a>

                                                            <a href="<?php echo get_permalink($esc_post->ID) ?>">
                                                                <h2 class="m-0 mt-2"><?php echo $esc_post->post_title ?></h2>
                                                            </a>

                                                            <span class="detalhes mt-2 d-block">
                                            <?php echo mural_assinaturaData($esc_post->ID) ?>
                                        </span>
                                                        </div>
                                                    </div>

						                        <?php } ?>
                                            </div>

                                            <div class="outras-controls position-relative">
                                                <span class="prev" data-controls="prev" aria-controls="customize" tabindex="-1"><</span>

                                                <span class="next" data-controls="next" aria-controls="customize" tabindex="-1">></span>
                                            </div>
                                        </div>
			                        <?php } ?>

                                    <div class="after__news mt-5">
                                        <div class="assine mx-auto p-3 w-100 assine-post">
                                            <a href="https://agenciamural.substack.com/" target="_blank">
                                                <h3 class="m-0 text-center text-uppercase">receba o melhor da mural no seu email</h3>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>


                    </div>
                </div>
            </div>

            <div class="col-3 d-none d-md-flex">
                <div class="sidebar">
                    <div class="sidebar__comentario d-none">
                        <a href="#comentarios"><i></i></a>
                    </div>

                    <div class="assine mx-auto p-3 w-100 mt-5">
                        <a href="https://agenciamural.substack.com/" target="_blank">
                            <h3 class="m-0 text-center text-uppercase">assine nossa newsletter</h3>
                        </a>
                    </div>

                    <div class="sidebar__ads ad-half mt-5 pt-5">
                        <picture>
                            <img data-src="<?php echo get_template_directory_uri() ?>/assets/images/sidebar-ads.jpg" class="w-100 lazy">
                        </picture>
                    </div>

	                <?php if ( ! empty( $maisLidas ) ) {
		                $maisLidas = array_slice( $maisLidas, 0, 5 );

	                    ?>
                        <div class="sidebar__post-lista mt-5 pt-5">
                            <span class="titulo text-uppercase">todo mundo tá lendo</span>

                            <div class="d-flex flex-column">
                                <?php foreach ( $maisLidas as $i => $ml ) {
	                                $mlData = get_page_by_path( $ml, OBJECT, 'post' ); ?>

                                    <a href="<?php echo esc_url(get_permalink($mlData->ID)) ?>" class="d-flex border-top border-dark py-3">
                                        <span><?php echo $i + 1 ?>.</span>

                                        <?php echo $mlData->post_title ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>


                    <div class="sidebar__ads ad-arroba mt-5 pt-5">
	                    <?php if ( ! wp_is_mobile() ) { ?>

                            <!-- /130882887/mural_arroba_sidebar -->
                            <div id='div-gpt-ad-1584544963046-0' style='width: 300px !important; height: 250px;margin: 0 auto'>
                                <script>
                                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1584544963046-0'); });
                                </script>
                            </div>
	                    <?php } ?>
                    </div>

                    <div class="sidebar__podcast p-3 mt-5">
                        <?php
                        $lastPodcast = get_posts( array(
	                        'post_type'      => 'podcast',
	                        'posts_per_page' => 1,
	                        'post_status'    => 'publish'
                        ) );
                        $episodio = get_field( 'episodio', $lastPodcast[0]->ID );
                        $linha_finaPod = get_field( 'post_linha_fina', $lastPodcast[0]->ID );
                        ?>
                        <div class="podcast-int">
                            <span class="titulo">PODCAST</span>

                            <iframe src="https://open.spotify.com/embed/show/2tg43mihRuOR8ugcfNzEWU?utm_source=generator" width="100%" height="160" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>

                            <div class="episodio mt-2">
                                <a href="">
                                    <p class="m-0">
                                        <span class="num me-3">#<?php echo $episodio ?></span>
                                        <span class="data"><?php echo esc_html( get_the_date( 'd.m.y', $lastPodcast[0]->ID ) ); ?></span>
                                    </p>

                                    <h2 class="n-0"><?php echo $lastPodcast[0]->post_title ?></h2>

                                    <?php
                                    if(!empty($linha_finaPod)) {
                                        echo '<p class="linha-fina">'.$linha_finaPod.'</p>';
                                    }
                                    ?>

                                </a>

                                <a href="<?php echo get_bloginfo( 'wpurl' ) . '/podcast/' ?>" class="outros bg-dark text-white p-1 px-3 d-table mx-auto">OUÇA OUTROS EPISÓDIOS</a>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar__fale fale text-center mt-5 position-sticky top-0">
                        <a href="https://www.agenciamural.org.br/contato/">
                            <i class="d-block mx-auto"></i>

                            <span class="text-decoration-underline mt-3 d-block">FALE COM A GENTE</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

	    <?php if ( !empty( $saber_itens ) ) { ?>
        <div class="row after mt-5">
            <?php
            if ( ! empty( $saber_itens ) ) {?>
                <div class="col-12">
                <div class="accordion accordion-single mt-3 mb-5">
                    <span class="titulo text-uppercase"><?php echo !empty($saber_titulo) ? $saber_titulo : 'PARA SABER MAIS'; ?></span>

	                <?php
		                echo '<ul class="mt-md-3">';
		                foreach ( $saber_itens as $i => $saber ) { ?>
                            <li class="c-<?php echo $i + 1 ?> <?php echo $i === 0 ? 'active' : '' ?>">
                                <div class="section-title">
                                    <h2 class="m-0 text-uppercase"><?php echo $saber['titulo'] ?></h2>
                                </div>
                                <div class="section-content ms-4">
	                                <?php
	                                $saber_posts = $saber[ 'posts_' . $saber['quantidade']['label'] ];

                                    if ( $saber['quantidade']['value'] === 'tres' ) { ?>
                                        <div class="row h-100 row-cols-3">
                                        <?php
                                        if ( ! empty( $saber_posts ) ) {
	                                        foreach ( $saber_posts as $item ) {
		                                        if ( $item['externo'] ) { // true
			                                        $imagemDesk     = $item['imagem']['sizes']['large'];
			                                        $imagemMobile     = $item['imagem']['sizes']['medium'];
			                                        $titulo     = $item['titulo'];
			                                        $linha_fina = $item['linha_fina'];
			                                        $link       = $item['link'];
		                                        } else {
			                                        $saberDataPost = $item['post'];
			                                        $imagemDesk        = get_image_prod( $saberDataPost->ID, 'large' );
			                                        $imagemMobile        = get_image_prod( $saberDataPost->ID, 'medium' );
			                                        $titulo        = $saberDataPost->post_title;
			                                        $linha_fina    = get_field( 'post_linha_fina', $saberDataPost->ID);
			                                        $link          = get_permalink( $saberDataPost->ID );
		                                        } ?>

                                                    <div class="col bloco">
                                                        <a href="<?php echo $link ?>" target="_blank">
                                                            <div class="bloco__imagem">
                                                                <picture>
                                                                    <source media="(max-width: 799px)" data-srcset="<?php echo changeurl($imagemMobile)?>">
                                                                    <source media="(min-width: 800px)" data-srcset="<?php echo changeurl($imagemDesk) ?>">
                                                                    <img data-src="<?php echo changeurl($imagemDesk) ?>" alt="" class="w-100 lazy">
                                                                </picture>
                                                            </div>

                                                            <div class="bloco__texto mt-2">
                                                                <h3 class="m-0"><?php echo $titulo ?></h3>

	                                                            <?php if ( ! empty( $linha_fina ) ) { ?>
                                                                    <p class="m-0 linha-fina mt-2"><?php echo $linha_fina ?></p>
                                                                <?php } ?>

                                                            </div>
                                                        </a>
                                                    </div>
                                            <?php }
                                            } ?>
                                        </div>

	                                <?php } elseif($saber['quantidade']['value'] === 'dois') { ?>
                                        <div class="d-flex dois-cards">
	                                        <?php
	                                        if ( ! empty( $saber_posts ) ) {
		                                        foreach ( $saber_posts as $item ) {
			                                        if ( $item['externo'] ) { // true
				                                        $imagemDesk     = $item['imagem']['sizes']['large'];
				                                        $imagemMobile     = $item['imagem']['sizes']['medium'];
				                                        $titulo     = $item['titulo'];
				                                        $linha_fina = $item['linha_fina'];
				                                        $link       = $item['link'];
			                                        } else {
				                                        $saberDataPost = $item['post'];
				                                        $imagemDesk        = get_image_prod( $saberDataPost->ID, 'large' );
				                                        $imagemMobile        = get_image_prod( $saberDataPost->ID, 'medium' );
				                                        $titulo        = $saberDataPost->post_title;
				                                        $linha_fina    = get_field( 'post_linha_fina', $saberDataPost->ID );
				                                        $link          = get_permalink( $saberDataPost->ID );
			                                        } ?>
                                                    <div class="bloco me-4">
                                                        <a href="<?php echo $link ?>">
                                                            <div class="bloco__imagem">
                                                                <picture>
                                                                    <source media="(max-width: 799px)" data-srcset="<?php echo changeurl($imagemMobile)?>">
                                                                    <source media="(min-width: 800px)" data-srcset="<?php echo changeurl($imagemDesk) ?>">
                                                                    <img data-src="<?php echo changeurl($imagemDesk) ?>" alt="" class="w-100 lazy">
                                                                </picture>
                                                            </div>

                                                            <div class="bloco__texto mt-2">
                                                                <h3 class="m-0"><?php echo $titulo ?></h3>

			                                                    <?php if ( ! empty( $linha_fina ) ) { ?>
                                                                    <p class="m-0 linha-fina mt-1"><?php echo $linha_fina ?></p>
                                                                <?php } ?>
                                                            </div>
                                                        </a>
                                                    </div>
		                                        <?php }
	                                        } ?>
                                        </div>
                                    <?php } elseif ( $saber['quantidade']['value'] === 'um' ) {
                                        if ( ! empty( $saber_posts ) ) {
		                                    foreach ( $saber_posts as $item ) {
			                                    if ( $item['externo'] ) { // true
				                                    $imagemDesk     = $item['imagem']['sizes']['large'];
				                                    $imagemMobile     = $item['imagem']['sizes']['medium'];
				                                    $titulo     = $item['titulo'];
				                                    $linha_fina = $item['linha_fina'];
				                                    $link       = $item['link'];
			                                    } else {
				                                    $saberDataPost = $item['post'];
				                                    $imagemDesk        = get_image_prod( $saberDataPost->ID, 'large' );
				                                    $imagemMobile        = get_image_prod( $saberDataPost->ID, 'medium' );
				                                    $titulo        = $saberDataPost->post_title;
				                                    $linha_fina    = get_field( 'post_linha_fina', $saberDataPost->ID );
				                                    $link          = get_permalink( $saberDataPost->ID );
			                                    } ?>
                                                <div class="bloco row h-100">
                                                    <div class="col-6">
                                                        <div class="image lazy" data-bg="<?php echo changeurl($imagemDesk) ?>"></div>
                                                    </div>

                                                    <div class="col-4 bloco__texto align-self-end">
                                                        <h3 class="m-0"><?php echo $titulo ?></h3>

	                                                    <?php if ( ! empty( $linha_fina ) ) { ?>
                                                            <p class="m-0 linha-fina mt-1"><?php echo $linha_fina ?></p>
	                                                    <?php } ?>

                                                    </div>
                                                </div>
		                                    <?php }
	                                    }
                                    } ?>


                                </div>
                            </li>

		                <?php }
		                echo '</div>';
	                ?>

	                <?php if ( ! empty( $saber_itens ) ) {
		                echo '<div class="accordion-home-mobile d-md-none">
                        <div class="int w-100 h-100">';

		                $quantidade = count($saber_itens);
		                foreach ( $saber_itens as $i => $saber ) {
			                $index = $quantidade - ( $i + 1 );

			                $saber_posts = $saber[ 'posts_' . $saber['quantidade']['label'] ];

			                if ( ! empty( $saber_posts ) ) { ?>
                                <div class="c-<?php echo $i + 1 ?> accordion-card px-3 py-2" data-index="<?php echo $index ?>">
                                    <div class="d-flex flex-column">
                                        <span class="m-0 text-uppercase mb-2"><?php echo $saber['titulo'] ?></span>
						                <?php foreach ( $saber_posts as $item ) {
							                if ( $item['externo'] ) { // true
								                $imagem     = $item['imagem'];
								                $titulo     = $item['titulo'];
								                $linha_fina = $item['linha_fina'];
								                $link       = $item['link'];
							                } else {
								                $saberDataPost = $item['post'];
								                $imagem        = get_image_prod( $saberDataPost->ID, 'medium' );
								                $titulo        = $saberDataPost->post_title;
								                $linha_fina    = get_field( 'post_linha_fina', $saberDataPost->ID );
								                $link          = get_permalink( $saberDataPost->ID );
							                } ?>

                                            <a class="d-flex flex-column" href="<?php echo $link ?>">
                                                <div class="image mb-2 lazy" data-bg="<?php echo changeurl($imagem['sizes']['large']) ?>"></div>

                                                <h2 class="m-0"><?php echo $titulo ?></h2>
                                            </a>


						                <?php } ?>
                                    </div>
                                </div>
			                <?php }
		                }

		                echo '</div></div>';
	                } ?>

                </div>
            </div>
            <?php } ?>

            <div class="col-12 col-md-6 offset-md-3 padding-mobile">
	            <?php
	            $tags = get_the_tags( $post_id );
	            if ( ! empty( $tags ) ) {
	                echo '<div class="after__tags d-flex flex-wrap text-center justify-content-center">';
		            foreach ( $tags as $tag ) { ?>
                        <a href="<?php echo get_tag_link($tag->term_id) ?>" class="text-uppercase"><?php echo $tag->name ?></a>
                <?php }
		            echo '</div>';
	            } ?>


                <div class="after__comentarios text-center mt-5 d-none" id="comentarios">
                    <a href="#" class="text-uppercase border border-dark open-comentarios">
                        3 comentários
                    </a>
                </div>

                <?php
                $tipo_assinatura = get_field('post_assinatura', $post_id);

                ?>
                <div class="after__assinaturas border-top border-bottom border-dark mt-5 py-4">
                    <?php
                    if ( $tipo_assinatura === 'padrao' ) {
	                    $usuario_nome     = get_the_author_meta( 'display_name', $postData->post_author );
	                    $usuario_link     = get_author_posts_url( $postData->post_author );
	                    $usuario_img      = get_field( 'usuario_foto', 'user_' . $postData->post_author );
	                    $usuario_bio      = get_field( 'usuario_biografia', 'user_' . $postData->post_author );
	                    $usuario_local    = get_field( 'usuario_localizacao', 'user_' . $postData->post_author );
	                    $usuario_linkedin = get_field( 'usuario_linkedin', 'user_' . $postData->post_author ); ?>

                        <div class="assinaturas-bloco d-flex">
	                        <?php if ( ! empty( $usuario_img ) ) { ?>
                                <div class="imagem me-3">
                                    <a href="<?php echo $usuario_link ?>">
                                        <img data-src="<?php echo $usuario_img['url'] ?>" alt="<?php echo $usuario_img['title'] ?>" class="w-100 lazy">
                                    </a>
                                </div>
                            <?php } ?>

                            <div class="texto">
                                <a href="<?php echo $usuario_link ?>">
                                    <span><?php echo $usuario_nome ?></span>
                                </a>

	                            <?php if ( ! empty( $usuario_bio ) ) { ?>
                                    <p class="m-0"><?php echo $usuario_bio ?></p>
	                            <?php } ?>

                                <div class="d-flex align-items-center mt-2">
	                                <?php if ( ! empty( $usuario_local ) ) { ?>
                                        <a href="#" class="bairro text-uppercase border px-2 text-center me-3"><?php echo $usuario_local ?></a>
                                    <?php } ?>

                                    <div class="social d-flex">
                                        <?php
                                        if ( ! empty( $usuario_linkedin ) ) {
	                                        echo '<a href="'.$usuario_linkedin.'" target="_blank" class="linkedin"><i></i></a>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else {
	                    $autores = get_field( 'post_autores', $post_id );

	                    if ( ! empty( $autores ) ) {
		                    foreach ( $autores as $i => $autor ) {
                                $autor_id = $autor->data->ID;
			                    $usuario_nome     = get_the_author_meta( 'display_name', $autor_id );
			                    $usuario_link     = get_author_posts_url( $autor_id );
			                    $usuario_img      = get_field( 'usuario_foto', 'user_' . $autor_id );
			                    $usuario_bio      = get_field( 'usuario_biografia', 'user_' . $autor_id );
			                    $usuario_local    = get_field( 'usuario_localizacao', 'user_' . $autor_id );
			                    $usuario_linkedin = get_field( 'usuario_linkedin', 'user_' . $autor_id ); ?>

                                <div class="assinaturas-bloco d-flex mb-4">
				                    <?php if ( ! empty( $usuario_img ) ) { ?>
                                        <div class="imagem me-3">
                                            <a href="<?php echo $usuario_link ?>">
                                                <img data-src="<?php echo changeurl($usuario_img['url']) ?>" alt="<?php echo $usuario_img['title'] ?>" class="w-100 lazy">
                                            </a>
                                        </div>
				                    <?php } ?>

                                    <div class="texto">
                                        <a href="<?php echo $usuario_link ?>">
                                            <span><?php echo $usuario_nome ?></span>
                                        </a>

					                    <?php if ( ! empty( $usuario_bio ) ) { ?>
                                            <p class="m-0"><?php echo $usuario_bio ?></p>
					                    <?php } ?>

                                        <div class="d-flex align-items-center mt-2">
						                    <?php if ( ! empty( $usuario_local ) ) { ?>
                                                <a href="#" class="bairro text-uppercase border px-2 text-center me-3"><?php echo $usuario_local ?></a>
						                    <?php } ?>

                                            <div class="social d-flex">
							                    <?php
							                    if ( ! empty( $usuario_linkedin ) ) {
								                    echo '<a href="'.$usuario_linkedin.'" target="_blank" class="linkedin"><i></i></a>';
							                    }
							                    ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
		                    <?php }
	                    }
                    }
                    ?>
                </div>

                <div class="after__botoes border-bottom border-dark py-4">
                    <div class="row row-cols-2 row-cols-md-4">
	                    <?php if ( ! empty( $comofizemos ) ) { ?>
                            <div class="botoes-bloco fizemos col text-center mb-4 mb-md-0">
                                <a href="javascript:;" data-href="fizemos">
                                    <i class="mb-2"></i>

                                    Como fizemos?
                                </a>
                            </div>
                        <?php } ?>

                        <div class="botoes-bloco pdf col text-center mb-4 mb-md-0">
                            <a href="<?php echo get_permalink($post_id).'?format=pdf' ?>">
                                <i class="mb-2"></i>

                                Baixe PDF
                            </a>

                        </div>

                        <div class="botoes-bloco republique col text-center">
                            <a href="javascript:;" data-href="republique">
                                <i class="mb-2"></i>

                                Republique
                            </a>

                        </div>

                        <div class="botoes-bloco erro col text-center">
                            <a href="javascript:;" data-href="reportar">
                                <i class="mb-2"></i>

                                Reportar erro
                            </a>

                        </div>
                    </div>

                    <div class="botoes-descricao">
	                    <?php if ( ! empty( $comofizemos ) ) { ?>
                            <div class="desc-fizemos desc border-top border-dark mt-4 pt-4">
                                <div class="d-flex mb-3">
                                    <span class="titulo d-block">Como fizemos?</span>

                                    <a href="javascript:;" class="close ms-auto"><i></i></a>
                                </div>

			                    <?php echo $comofizemos; ?>
                            </div>
	                    <?php } ?>

                        <div class="desc-republique desc border-top border-dark mt-4 pt-4">
                            <div class="titulo-republique">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="titulo me-3">Republique</span>

                                    <i class="cc me-1"></i>
                                    <i class="att me-1"></i>
                                    <i class="not-com me-1"></i>
                                    <i class="sem-deriv"></i>

                                    <a href="javascript:;" class="close ms-auto"><i></i></a>
                                </div>

                                <p class="m-0">Faça com que essa história chegue para mais pessoas.</p>
                                <p class="m-0">Republique o nosso conteúdo gratuitamente.</p>
                            </div>

                            <div class="regras d-flex flex-column mt-4">
                                <p class="mb-0"><strong>Regras:</strong></p>

                                <p class="border-bottom border-dark py-2 m-0">Os títulos podem ser modificados desde que não mude o contexto;</p>
                                <p class="border-bottom border-dark py-2 m-0">Os textos devem ser publicados como estão na versão original, sem edição ou cortes;</p>
                                <p class="border-bottom border-dark py-2 m-0">Todas as republicações devem dar crédito para a Agência Mural e também os créditos dos profissionais envolvidos em sua produção, conforme aparece na publicação original;</p>
                                <p class="border-bottom border-dark py-2 m-0">As fotografias e outras imagens/artes podem ser republicadas com os devidos créditos;</p>
                                <p class="border-bottom border-dark py-2 m-0">Os vídeos republicados não devem passar por nenhum tipo de edição, devem conter os créditos da Agência Mural; para transmissão na TV, é preciso enviar um pedido para contato@agenciamural.org.br.</p>
                                <p class="border-bottom border-dark py-2 m-0">Reportagens publicadas no site www.agenciamural.org.br não podem ser revendidas.</p>
                                <p class="py-2 m-0">Se possível, os materiais republicados devem mencionar o perfil da Mural nas redes sociais.</p>
                            </div>
                        </div>

                        <div class="desc-reportar desc border-top border-dark mt-4 pt-4">
                            <div class="d-flex">
                                <span class="titulo d-block">Reportar erro</span>

                                <a href="javascript:;" class="close ms-auto"><i></i></a>
                            </div>

                            <p class="m-0 mb-3">Quer informar a nossa redação sobre algum erro nesta matéria?
                                Preencha o formulário abaixo.</p>

                            <div class="reportar-form">
			                    <?php echo do_shortcode('[ninja_form id=4]'); ?>
                            </div>
                        </div>
                    </div>

                </div>

                <?php
                $posts_escolhidas = get_field('escolhidas_posts', 'escolhidas');

                if ( ! empty( $posts_escolhidas ) ) { ?>
                    <div class="after__outras-materias mt-5 position-relative">
                        <span class="titulo d-block text-center">ESCOLHIDAS PELA REDAÇÃO</span>

                        <div class="outras-materias-int mt-2">
                            <?php
                            foreach ( $posts_escolhidas as $escolhida ) {
                                $esc_post = $escolhida['post'];
	                            $imagemMobile  = get_image_prod( $esc_post->ID, 'medium' );
	                            $imagemDesk  = get_image_prod( $esc_post->ID, 'large' );
	                            $categoria = get_the_category($esc_post->ID)[0];
	                            ?>

                                <div class="outras-bloco">
                                    <div class="imagem">
                                        <picture>
                                            <source media="(max-width: 799px)" data-srcset="<?php echo changeurl($imagemMobile)?>">
                                            <source media="(min-width: 800px)" data-srcset="<?php echo changeurl($imagemDesk) ?>">
                                            <img data-src="<?php echo changeurl($imagemDesk) ?>" alt="yrdyr" class="w-100 lazy">
                                        </picture>
                                    </div>

                                    <div class="texto mt-2 mx-auto">
                                        <a href="<?php echo get_category_link($categoria->term_id) ?>" class="categoria text-uppercase"><?php echo $categoria->name ?></a>

                                        <a href="<?php echo get_permalink($esc_post->ID) ?>">
                                            <h2 class="m-0 mt-2"><?php echo $esc_post->post_title ?></h2>
                                        </a>

                                        <span class="detalhes mt-2 d-block">
                                            <?php echo mural_assinaturaData($esc_post->ID) ?>
                                        </span>
                                    </div>
                                </div>

                            <?php } ?>
                        </div>

                        <div class="outras-controls position-relative">
                            <span class="prev" data-controls="prev" aria-controls="customize" tabindex="-1"><</span>

                            <span class="next" data-controls="next" aria-controls="customize" tabindex="-1">></span>
                        </div>
                    </div>
                <?php } ?>


                <div class="after__news mt-5">
                    <div class="assine mx-auto p-3 assine-post">
                        <a href="https://agenciamural.substack.com/" target="_blank">
                            <h3 class="m-0 text-center text-uppercase">receba o melhor da mural no seu email</h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
