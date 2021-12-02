<?php
$home_blocos = get_field( 'home', 'home' );
$home_marquee = get_field( 'marquee', 'home' );

if ( empty( $home_blocos ) ) {
	return;
}
?>

<div class="home">
	<?php
	if ( ! empty( $home_blocos ) ) {
		foreach ( $home_blocos as $home ) {
			$layout = $home['acf_fc_layout'];

			if ( $layout === 'especial' ) {
				$especialPost      = $home['post'];
				$especialTitulo    = ! empty( $home['titulo'] ) ? $home['titulo'] : get_field( 'especial_titulo_fantasia', $especialPost->ID );
				$especialSubtitulo = ! empty( $home['subtitulo'] ) ? $home['subtitulo'] : $especialPost->post_title;
				$especialImagemAlt = $home['imagem'];
				$especialQuebrada  = mural_quebradaPost( $especialPost->ID );

				if ( ! empty( $especialImagemAlt ) ) { //imagem alternativa
					$espDesk   = $especialImagemAlt['url'];
					$espMobile = $especialImagemAlt['sizes']['medium'];
				} else {
					$espDesk   = get_image_prod( $especialPost->ID, 'large' );
					$espMobile = get_image_prod( $especialPost->ID, 'medium' );
				} ?>

                <section class="home__especial topo-especial pt-0 py-md-5">
                    <div class="container h-100">
                        <div class="row especial__int align-items-end h-100">
                            <div class="int col-12 col-md-6 padding-mobile">
                                <div class="texto mt-4">
                                    <div class="d-flex mb-2">
										<?php
										if ( ! empty( $especialQuebrada ) ) {
											echo $especialQuebrada;
										}
										?>

                                        <a href="<?php echo get_bloginfo( 'wpurl' ) . '/especiais/' ?>"
                                           class="categoria text-uppercase <?php echo ! empty( $especialQuebrada ) ? 'ms-2' : '' ?>">especial</a>
                                    </div>


                                    <a href="<?php echo esc_url( get_permalink( $especialPost->ID ) ) ?>"
                                       class="chamada mt-2 d-block">
                                        <h2 class=mb-1"><?php echo $especialTitulo ?></h2>
                                    </a>

                                    <a href="<?php echo esc_url( get_permalink( $especialPost->ID ) ) ?>">
                                        <h2 class="titulo"><?php echo $especialSubtitulo ?></h2>
                                    </a>

                                    <span class="detalhes mt-3 d-block">Publicação: <?php echo esc_html( get_the_date( 'd/m/Y H:i', $especialPost->ID ) ); ?></span>
                                </div>

                            </div>

                            <div class="int col-12 col-md-5 position-relative order-first order-md-last padding-mobile">
                                <div class="lazy image-absolute"
                                     data-bg="<?php echo wp_is_mobile() ? $espMobile : $espDesk ?>"></div>
                                <!--                    position-absolute       -->
                            </div>
                        </div>
                    </div>
                </section>
			<?php }
			elseif ( $layout === 'destaques' ) { ?>
                <section class="home__cortina mt-5 cortina container">
                    <div class="row cortina-int no-padding-mobile ">
						<?php if ( $home['ultimos'] ) { // nenhum post preenchido, trazer os ultimos posts publicados
							$destaquePosts = new WP_Query( array(
								'post_type'      => 'post',
								'post_status'    => 'publish',
								'posts_per_page' => 5
							) );

							if ( ! empty( $destaquePosts->posts ) ) {
								$cortinaDestaque = $destaquePosts->posts[0];

								array_shift( $destaquePosts->posts );
								$cortinaPosts = $destaquePosts->posts;
							}
						} else { //posts vindos do admin
							$cortinaDestaque = $home['posts'][0];

							array_shift( $home['posts'] );
							$cortinaPosts = $home['posts'];
						}

						if ( ! empty( $cortinaDestaque ) ) {
							if ( ! $home['ultimos'] && $cortinaDestaque['externo'] ) { //link externo
							    $link       = $cortinaDestaque['link'];
								$chapeu     = $cortinaDestaque['chapeu'];
								$titulo     = $cortinaDestaque['titulo'];
								$linha_fina = $cortinaDestaque['linha_fina'];
								$imgDesk    = $cortinaDestaque['imagem']['sizes']['large'];
								$imgMobile  = $cortinaDestaque['imagem']['sizes']['large'];
							} else {
								$postData   = $cortinaDestaque;
								$categoria  = get_the_category( $postData['post']->ID )[0];
								$link       = get_permalink( $postData['post']->ID );
								$chapeu     = ! $home['ultimos'] && ! empty( $cortinaDestaque['chapeu'] ) ? $cortinaDestaque['chapeu'] : $categoria->name;
								$titulo     = ! $home['ultimos'] && ! empty( $cortinaDestaque['titulo'] ) ? $cortinaDestaque['titulo'] : get_field( 'post_titulo', $postData['post']->ID ); //campo de titulo alternativo, fazer segunda verificacao!
								$linha_fina = ! $home['ultimos'] && ! empty( $cortinaDestaque['linha_fina'] ) ? $cortinaDestaque['linha_fina'] : get_field( 'post_linha_fina', $postData['post']->ID );
								$imgDesk    = get_image_prod( $postData['post']->ID, 'large' );
								$imgMobile  = get_image_prod( $postData['post']->ID, 'medium' );
							}

							?>
                            <div class="col-12 col-md-6 cortina-int__bloco mb-5 mb-md-0 no-padding-mobile">
                                <div class="bloco-sticky">
                                    <div class="image image-maior">
                                        <picture>
                                            <source media="(max-width: 799px)"
                                                    data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                            <source media="(min-width: 800px)"
                                                    data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                            <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                                 class="lazy w-100">
                                        </picture>
                                    </div>

                                    <div class="texto mt-2 padding-mobile">
                                        <a href="<?php echo get_category_link( $categoria->term_id ) ?>"
                                           class="categoria text-uppercase"><?php echo $chapeu ?></a>

                                        <a href="<?php echo esc_url( $link ) ?>">
                                            <h2 class="m-0"><?php echo ! empty( $titulo ) ? $titulo : $postData['post']->post_title ?></h2>

											<?php
											if ( ! empty( $linha_fina ) ) {
												echo '<p class="linha-fina m-0 mt-2">' . $linha_fina . '</p>';
											}
											?>
                                        </a>

										<?php if ( ! $home['ultimos'] && ! $cortinaDestaque['externo'] ) { ?>
                                            <span class="detalhes mt-2 d-block">
                                                    <?php echo mural_assinaturaData( $postData->ID ); ?>
                                                </span>
										<?php } ?>

                                    </div>
                                </div>

                            </div>
						<?php }

						if ( ! empty( $cortinaPosts ) ) { ?>
                            <div class="col-12 col-md-6 cortina-int__bloco menor">
                                <div class="row">
									<?php
									$part1 = array_slice( $cortinaPosts, 0, 2 );
									$part2 = array_slice( $cortinaPosts, 2, 2 );

									if ( ! empty( $part1 ) ) { ?>
                                        <div class="col-12 col-md-6 mb-5 mb-md-0 padding-mobile">
											<?php
											foreach ( $part1 as $i => $p1 ) {
												if ( ! $home['ultimos'] && $p1['externo'] ) { //link externo
													$link       = $p1['link'];
													$chapeu     = $p1['chapeu'];
													$titulo     = $p1['titulo'];
													$linha_fina = $p1['linha_fina'];
													$imgDesk    = $p1['imagem']['sizes']['large'];
													$imgMobile  = $p1['imagem']['sizes']['large'];
												} else {
													$postData   = ! $home['ultimos'] ? $p1['post'] : $p1;
													$link       = get_permalink( $postData->ID );
													$categoria  = get_the_category( $postData->ID )[0];
													$chapeu     = ! $home['ultimos'] && ! empty( $p1['chapeu'] ) ? $p1['chapeu'] : $categoria->name;
													$titulo     = ! $home['ultimos'] && ! empty( $p1['titulo'] ) ? $p1['titulo'] : get_field( 'post_titulo', $postData->ID ); //campo de titulo alternativo, fazer segunda verificacao!
													$linha_fina = ! $home['ultimos'] && ! empty( $p1['linha_fina'] ) ? $p1['linha_fina'] : get_field( 'post_linha_fina', $postData->ID );
													$imgDesk    = get_image_prod( $postData->ID, 'large' );
													$imgMobile  = get_image_prod( $postData->ID, 'medium' );
												} ?>

                                                <div class="col-12 <?php echo $i === 0 ? 'mb-5' : '' ?>">
                                                    <div class="image text-center text-md-start">
                                                        <picture>
                                                            <source media="(max-width: 799px)"
                                                                    data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                                            <source media="(min-width: 800px)"
                                                                    data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                                            <img data-src="<?php echo changeurl( $imgDesk ) ?>"
                                                                 alt="Image" class="lazy">
                                                        </picture>
                                                    </div>

                                                    <div class="texto mt-2">
                                                        <a href="<?php echo get_category_link( $categoria->term_id ) ?>"
                                                           class="categoria text-uppercase"><?php echo $chapeu ?></a>

                                                        <a href="<?php echo esc_url( $link ) ?>">
                                                            <h2 class="m-0 mt-md-2 mt-1"><?php echo ! empty( $titulo ) ? $titulo : $postData->post_title ?></h2>

															<?php
															if ( ! empty( $linha_fina ) ) {
																echo '<p class="linha-fina m-0 mt-md-1 mt-2">' . $linha_fina . '</p>';
															}
															?>
                                                        </a>

														<?php if ( ! $home['ultimos'] && ! $p1['externo'] ) { ?>
                                                            <span class="detalhes mt-2 d-block">
                                                                <?php echo mural_assinaturaData( $postData->ID ); ?>
                                                            </span>
														<?php } ?>

                                                    </div>
                                                </div>

											<?php }
											?>
                                        </div>
									<?php }

									if ( ! empty( $part2 ) ) { ?>
                                        <div class="col-12 col-md-6 padding-mobile">
											<?php foreach ( $part2 as $i => $p2 ) {
												if ( ! $home['ultimos'] && $p2['externo'] ) { //link externo
													$link       = $p2['link'];
													$chapeu     = $p2['chapeu'];
													$titulo     = $p2['titulo'];
													$linha_fina = $p2['linha_fina'];
													$imgDesk    = $p2['imagem']['sizes']['large'];
													$imgMobile  = $p2['imagem']['sizes']['medium'];
												} else {
													$postData   = ! $home['ultimos'] ? $p2['post'] : $p2;
													$link       = get_permalink( $postData->ID );
													$categoria  = get_the_category( $postData->ID )[0];
													$chapeu     = ! $home['ultimos'] && ! empty( $p2['chapeu'] ) ? $p2['chapeu'] : $categoria->name;
													$titulo     = ! $home['ultimos'] && ! empty( $p2['titulo'] ) ? $p2['titulo'] : get_field( 'post_titulo', $postData->ID ); //campo de titulo alternativo, fazer segunda verificacao!
													$linha_fina = ! $home['ultimos'] && ! empty( $p2['linha_fina'] ) ? $p2['linha_fina'] : get_field( 'post_linha_fina', $postData->ID );
													$imgDesk    = get_image_prod( $postData->ID, 'large' );
													$imgMobile  = get_image_prod( $postData->ID, 'medium' );
												} ?>

                                                <div class="col-12 <?php echo $i === 0 ? 'mb-5' : '' ?>">
                                                    <div class="image text-center text-md-start">
                                                        <picture>
                                                            <source media="(max-width: 799px)"
                                                                    data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                                            <source media="(min-width: 800px)"
                                                                    data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                                            <img data-src="<?php echo changeurl( $imgDesk ) ?>"
                                                                 alt="Image" class="lazy">
                                                        </picture>
                                                    </div>

                                                    <div class="texto mt-2">
                                                        <a href="<?php echo get_category_link( $categoria->term_id ) ?>"
                                                           class="categoria text-uppercase"><?php echo $chapeu ?></a>

                                                        <a href="<?php echo esc_url( $link ) ?>">
                                                            <h2 class="m-0 mt-md-2 mt-1"><?php echo ! empty( $titulo ) ? $titulo : $postData->post_title ?></h2>

															<?php
															if ( ! empty( $linha_fina ) ) {
																echo '<p class="linha-fina m-0 mt-md-1 mt-2">' . $linha_fina . '</p>';
															}
															?>
                                                        </a>

														<?php if ( ! $home['ultimos'] && ! $p2['externo'] ) { ?>
                                                            <span class="detalhes mt-2 d-block">
                                                                <?php echo mural_assinaturaData( $postData->ID ); ?>
                                                            </span>
														<?php } ?>

                                                    </div>
                                                </div>

											<?php } ?>
                                        </div>
									<?php }

									?>
                                </div>
                            </div>
						<?php }
						?>
                    </div>
                </section>
			<?php }
			elseif ( $layout === 'midia' ) {
				$galeriaEx = $home['externo']; //se true, vai subir imagem e campos de fora / false sao posts internos
				?>
                <section class="home__podcast container mt-5 pt-md-5">
                    <div class="row podcast-int">
                        <div class="col-12 col-md-6 podcast-int__post padding-mobile position-relative">
                            <h2 class="m-0 text-uppercase mb-md-3 mb-2 text-center text-md-start"><?php echo ! empty( $home['titulo'] ) ? $home['titulo'] : 'vídeos' ?></h2>

							<?php if ( $galeriaEx ) { //galeria
								$midiaPosts = $home['galeria']; ?>
                                <div class="video-bloco">
									<?php foreach ( $midiaPosts as $mPost ) {
										$titulo     = ! empty( $mPost['titulo'] ) ? $mPost['titulo'] : '';
										$linha_fina = ! empty( $mPost['linha_fina'] ) ? $mPost['linha_fina'] : '';
										$embed      = ! empty( $mPost['embed'] ) ? $mPost['embed'] : false;
										?>

                                        <div class="bloco">
                                            <div class="image">
												<?php
												if ( ! $embed ) { //nao colocou link do youtube
													$imgDesk   = $mPost['imagem']['sizes']['large'];
													$imgMobile = $mPost['imagem']['sizes']['medium']; ?>

                                                    <picture>
                                                        <source media="(max-width: 799px)"
                                                                data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                                        <source media="(min-width: 800px)"
                                                                data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                                        <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                                             class="lazy w-100">
                                                    </picture>

												<?php } else {
													echo $embed;
												}
												?>
                                            </div>

                                            <div class="texto mt-md-2 pt-2">
                                                <a href="#" class="pe-none">
                                                    <h2 class="m-0 mt-1"><?php echo $titulo ?></h2>

													<?php
													if ( ! empty( $linha_fina ) ) {
														echo '<p class="linha-fina m-0 mt-md-3 mt-2">' . $linha_fina . '</p>';
													}
													?>
                                                </a>
                                            </div>
                                        </div>

									<?php } ?>

                                </div>
							<?php } else { //posts
								$midiaPosts = $home['posts']; ?>

                                <div class="video-bloco">
									<?php foreach ( $midiaPosts as $mPost ) {
										$postData   = $mPost['post'];
										$titulo     = ! empty( $mPost['titulo'] ) ? $mPost['titulo'] : get_field( 'post_titulo', $postData->ID );
										$linha_fina = ! empty( $mPost['linha_fina'] ) ? $mPost['linha_fina'] : get_field( 'post_linha_fina', $postData->ID );
										$embed      = ! empty( $mPost['embed'] ) ? $mPost['embed'] : false; ?>

                                        <div class="bloco">
                                            <div class="image">
												<?php
												if ( ! $embed ) { //nao colocou link do youtube
													$imgDesk   = get_image_prod( $postData->ID, 'large' );
													$imgMobile = get_image_prod( $postData->ID, 'medium' ); ?>

                                                    <picture>
                                                        <source media="(max-width: 799px)"
                                                                data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                                        <source media="(min-width: 800px)"
                                                                data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                                        <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                                             class="lazy w-100">
                                                    </picture>

												<?php } else {
													echo $embed;
												}
												?>
                                            </div>

                                            <div class="texto mt-md-2 pt-2">
                                                <a href="<?php echo esc_url( get_permalink( $postData->ID ) ) ?>">
                                                    <h2 class="m-0 mt-1"><?php echo ! empty( $titulo ) ? $titulo : $postData->post_title ?></h2>

													<?php
													if ( ! empty( $linha_fina ) ) {
														echo '<p class="linha-fina m-0 mt-md-3 mt-2">' . $linha_fina . '</p>';
													}
													?>
                                                </a>

                                                <span class="detalhes mt-2 d-block"><?php echo mural_assinaturaData( $postData->ID ) ?></span>
                                            </div>
                                        </div>

									<?php } ?>
                                </div>
							<?php } ?>

                            <div class="video-controls position-relative">
                                <span class="prev" data-controls="prev" aria-controls="customize" tabindex="-1"><</span>

                                <span class="next" data-controls="next" aria-controls="customize" tabindex="-1">></span>
                            </div>


                            <div class="fale text-center mt-5 pt-2 d-none d-md-block">
                                <a href="https://www.agenciamural.org.br/contato/">
                                    <i class="d-block mx-auto"></i>

                                    <span class="text-decoration-underline mt-3 d-block">FALE COM A GENTE</span>
                                </a>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 podcast-int__ep padding-mobile">
                            <div class="bloco-sticky">
                                <div class="assine mx-auto p-3 mt-5 mt-md-0 border border-dark">
                                    <a href="https://agenciamural.substack.com/" target="_blank">
                                        <h3 class="m-0 text-center text-uppercase">receba o melhor da mural no seu
                                            email</h3>
                                    </a>
                                </div>

                                <div class="bloco mt-5 mt-md-5 pt-md-3">
                                    <h2 class="m-0 text-uppercase">Próxima parada</h2>
                                    <p class="linha-fina m-0">Um podcast jornalístico diário, com histórias, noticias e
                                        relatos mais conectados com a realidade das periferias do Brasil e, em especial,
                                        São Paulo.</p>

                                    <div class="episodio d-flex align-items-center justify-content-center justify-content-md-start mt-2">
										<?php
										$lastPodcast = get_posts( array(
											'post_type'      => 'podcast',
											'posts_per_page' => 1,
											'post_status'    => 'publish'
										) );

										if ( ! empty( $lastPodcast[0] ) ) {
											$episodio = get_field( 'episodio', $lastPodcast[0]->ID ); ?>

                                            <span class="num me-4">#<?php echo $episodio ?></span>
                                            <span class="data me-4"><?php echo esc_html( get_the_date( 'd.m.y', $lastPodcast[0]->ID ) ); ?></span>
										<?php } ?>


                                        <a href="<?php echo get_bloginfo( 'wpurl' ) . '/podcast/' ?>"
                                           class="px-2 py-2 d-none d-md-block ms-auto">
                                            OUÇA OUTROS EPISÓDIOS >
                                        </a>

                                    </div>

                                    <div class="mt-3">
                                        <iframe src="https://open.spotify.com/embed/show/2tg43mihRuOR8ugcfNzEWU?utm_source=generator" width="100%" height="232" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>
                                    </div>

                                    <div class="episodio d-md-none text-center mt-3 ms-auto">
                                        <a href="<?php echo get_bloginfo( 'wpurl' ) . '/podcast/' ?>" class="px-2 py-2">
                                            OUÇA OUTROS EPISÓDIOS >
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
				<?php
			}
			elseif ( $layout === 'quebrada' ) {
				$atomatica = $home['automatica'];

				if ( $atomatica ) { //linha automativa
					$postZn = get_posts( array(
						'post_status'    => 'publish',
						'posts_per_page' => 1,
						'meta_query'     => array(
							array(
								'key'     => 'post_zonas',
								'value'   => 'zona-norte',
								'compare' => 'LIKE'
							)
						)
					) );

					$postZl = get_posts( array(
						'post_status'    => 'publish',
						'posts_per_page' => 1,
						'meta_query'     => array(
							array(
								'key'     => 'post_zonas',
								'value'   => 'zona-leste',
								'compare' => 'LIKE'
							)
						)
					) );

					$postZo = get_posts( array(
						'post_status'    => 'publish',
						'posts_per_page' => 1,
						'meta_query'     => array(
							array(
								'key'     => 'post_zonas',
								'value'   => 'zona-oeste',
								'compare' => 'LIKE'
							)
						)
					) );

					$postZs = get_posts( array(
						'post_status'    => 'publish',
						'posts_per_page' => 1,
						'meta_query'     => array(
							array(
								'key'     => 'post_zonas',
								'value'   => 'zona-sul',
								'compare' => 'LIKE'
							)
						)
					) );

					$postGsp = get_posts( array(
						'post_status'    => 'publish',
						'posts_per_page' => 1,
						'meta_query'     => array(
							array(
								'key'     => 'post_zonas',
								'value'   => 'grande-sp',
								'compare' => 'LIKE'
							)
						)
					) );

					$quebradaPosts = array_merge( $postZs, $postZn, $postZl, $postZo, $postGsp );


				} else {
					$quebradaPosts = $home['posts'];
				}

				//var_dump($quebradaPosts); die;
				?>

                <section class="home__sua-quebrada container mt-5 pt-md-5 pt-3">
                    <div class="d-flex sua-quebrada-topo">
                        <h2 class="m-0 flex-grow-1">SUA QUEBRADA</h2>

                        <a href="<?php echo get_bloginfo( 'wpurl' ) . '/sua-quebrada/' ?>"
                           class="m-0 p-2 border border-dark text-center align-self-end mb-4 d-none d-md-block">VER
                            TODAS AS REGIÕES</a>
                    </div>

                    <div class="guia-mobile position-relative d-md-none container mt-2 mb-2">
                        <div class="quebrada-controls position-relative ">
                            <span class="prev" data-controls="prev" aria-controls="customize" tabindex="-1"><</span>

                            <span class="next" data-controls="next" aria-controls="customize" tabindex="-1">></span>
                        </div>

                        <div class="d-flex zonas-mobile text-uppercase justify-content-center">
							<?php
							if ( ! empty( $quebradaPosts ) ) {
								foreach ( $quebradaPosts as $i => $qPost ) {
									if ( $atomatica ) { //linha automatica
										$quebrada = get_the_terms( $qPost->ID, 'quebrada' );
									} else {
										$quebrada = get_the_terms( $qPost['post']->ID, 'quebrada' );
									}

									$zona      = get_field( 'quebrada_zona', 'quebrada_' . $quebrada[0]->term_id );
									$zona_name = mural_converteZona( $zona['value'] ); ?>

                                    <a href="javascript:;"
                                       class="<?php echo $i == 0 ? 'active' : '' ?>"><?php echo $zona_name ?></a>
								<?php }
							}
							?>
                        </div>
                    </div>

                    <div class="sua-quebrada-lista d-md-flex mt-2 bloco-card">
						<?php
						if ( ! empty( $quebradaPosts ) ) {
							$qtdQuebradaPosts = count( $quebradaPosts );

							foreach ( $quebradaPosts as $i => $qPost ) {
								if ( $atomatica ) { //linha automatica
									$qpostID = $qPost->ID;

									$categoria  = get_the_category( $qpostID )[0];
									$chapeu     = $categoria->name;
									$titulo     = ! empty( get_field( 'post_titulo', $qpostID ) ) ? get_field( 'post_titulo', $qpostID ) : '';
									$linha_fina = get_field( 'post_linha_fina', $qpostID );
								} else {
									$qpostID = $qPost['post']->ID;

									$categoria  = get_the_category( $qpostID )[0];
									$chapeu     = ! empty( $qPost['chapeu'] ) ? $qPost['chapeu'] : $categoria->name;
									$titulo     = ! empty( $qPost['titulo'] ) ? $qPost['titulo'] : get_field( 'post_titulo', $qpostID );
									$linha_fina = ! empty( $qPost['linha_fina'] ) ? $qPost['linha_fina'] : get_field( 'post_linha_fina', $qpostID );
								}

								$quebrada  = get_the_terms( $qpostID, 'quebrada' );
								$zona      = get_field( 'quebrada_zona', 'quebrada_' . $quebrada[0]->term_id );
								$zona_name = mural_converteZona( $zona['value'] );

								$imgDesk   = get_image_prod( $qpostID, 'large' );
								$imgMobile = get_image_prod( $qpostID, 'medium' );
								?>

                                <div class="bloco <?php echo $qtdQuebradaPosts - 1 != $i ? 'me-md-4' : '' ?> <?php echo $i === 0 ? 'active' : '' ?>">
									<?php if ( ! empty( mural_quebradaPost( $qpostID ) ) ) {
									    $letras = strlen($quebrada[0]->name);
									    ?>
                                        <div class="d-flex mb-2 d-none d-md-block <?php echo $letras >= 13 ? 'lines' : '' ?> <?php echo $zona_name == 'GDE SP' ? 'lines-maior' : '' ?>">
											<?php echo mural_quebradaPost( $qpostID ); ?>
                                        </div>
									<?php } ?>

                                    <div class="d-flex mb-2 d-block d-md-none bairro-mobile">
                                        <a href="<?php echo get_bloginfo( 'wpurl' ) . '/' . $zona['value'] . '/' . $quebrada[0]->slug . '/' ?>"
                                           class="bairro text-uppercase border px-2 text-center"><?php echo $quebrada[0]->name ?></a>
                                    </div>

                                    <div class="bloco__post">
                                        <div class="image">
                                            <picture>
                                                <source media="(max-width: 799px)"
                                                        data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                                <source media="(min-width: 800px)"
                                                        data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                                <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                                     class="lazy w-100">
                                            </picture>
                                        </div>

                                        <div class="texto mt-2">
                                            <a href="<?php echo esc_url( get_category_link( $categoria->term_id ) ); ?>"
                                               class="categoria text-uppercase"><?php echo $chapeu ?></a>

                                            <a href="<?php echo esc_url( get_permalink( $qpostID ) ) ?>">
                                                <h2 class="m-0 mt-md-2 mt-1"><?php echo ! empty( $titulo ) ? $titulo : get_the_title( $qpostID ); ?></h2>

												<?php
												if ( ! empty( $linha_fina ) ) {
													echo '<p class="linha-fina m-0 mt-1">' . $linha_fina . '</p>';
												}
												?>
                                            </a>

                                            <span class="detalhes mt-2 d-block"><?php echo mural_assinaturaData( $qpostID ); ?></span>
                                        </div>
                                    </div>
                                </div>

							<?php }
						}
						?>
                    </div>
                </section>
				<?php
			}
			elseif ( $layout === 'slider' ) { ?>
                <section class="home__slider mt-5 pt-md-5">
                    <div data-slide="slide" class="slide">
                        <div class="slide-items">
							<?php
							if ( ! empty( $home['itens'] ) ) {
								foreach ( $home['itens'] as $sItem ) { ?>
                                    <a href="<?php echo esc_url( $sItem['link'] ) ?>">
                                        <picture class="w-100">
                                            <source media="(max-width: 799px)"
                                                    data-srcset="<?php echo $sItem['imagem_mobile']['url'] ?>">
                                            <source media="(min-width: 800px)"
                                                    data-srcset="<?php echo $sItem['imagem_desk']['url'] ?>">
                                            <img data-src="<?php echo $sItem['imagem_desk']['url'] ?>"
                                                 alt="<?php echo $sItem['imagem_desk']['title'] ?>" class="w-100 lazy">
                                        </picture>

                                    </a>
								<?php }
							}
							?>
                        </div>
                        <nav class="slide-nav">
                            <div class="slide-thumb"></div>
                            <a class="slide-prev"><i></i></a>
                            <a class="slide-next"><i></i></a>
                        </nav>
                    </div>
                </section>
			<?php }
			elseif ( $layout === 'webstories' ) { ?>
                <section class="home__listagem-1 container mt-5 pt-md-4">
                    <div class="row align-items-right">
                        <div class="col-12 col-md-7 pe-md-5 padding-mobile">
							<?php
							if ( ! empty( $home['posts'] ) ) { ?>
                                <div class="lista d-flex flex-column">
                                    <span class="titulo text-uppercase pb-2 border-bottom border-dark"><?php echo ! empty( $home['titulo_lista'] ) ? $home['titulo_lista'] : 'conversas que não terminam'; ?></span>

									<?php
									$qtdListaPost = count( $home['posts'] );
									foreach ( $home['posts'] as $i => $postLista ) {
										$externo = $postLista['externo'];
										if ( $externo ) {
											$link       = $postLista['link'];
											$titulo     = $postLista['titulo'];
											$linha_fina = $postLista['linha_fina'];
										} else {
											$link       = get_permalink( $postLista['post']->ID );
											$titulo     = ! empty( $postLista['titulo'] ) ? $postLista['titulo'] : get_field( 'post_titulo', $postLista['post']->ID );
											$linha_fina = ! empty( $postLista['linha_fina'] ) ? $postLista['linha_fina'] : get_field( 'post_linha_fina', $postLista['post']->ID );
										} ?>

                                        <div class="lista__bloco py-3 <?php echo $i === 0 ? 'pt-0 pt-md-3' : ''; ?> <?php echo $i != $qtdListaPost - 1 ? 'border-bottom' : '' ?>">
                                            <a href="<?php echo esc_url( $link ) ?>">
                                                <h2 class="m-0"><?php echo ! empty( $titulo ) ? $titulo : $postLista['post']->post_title ?></h2>

												<?php if ( ! empty( $linha_fina ) ) {
													echo '<p class="linha-fina m-0 mt-2 mt-md-0">' . $linha_fina . '</p>';
												} ?>
                                            </a>
                                        </div>

									<?php } ?>
                                </div>
							<?php } ?>


                            <div class="clearfix"></div>

							<?php
							if ( empty( $home['stories'] ) ) {
								$postsStories = new WP_Query( array(
									'post_type'      => 'webstories',
									'post_status'    => 'publish',
									'posts_per_page' => 2
								) );
								?>

                                <div class="stories <?php echo ! empty( $home['posts'] ) ? 'mt-5 pt-md-5' : '' ?>">
                                    <span class="titulo text-uppercase text-center w-100 d-block">webstories</span>

                                    <div class="stories__block d-md-flex mt-3 d-none">
                                        <?php foreach ( $postsStories->posts as $i => $postStories ) {
	                                        $imgDesk   = get_image_prod( $postStories->ID, 'large' );
	                                        $imgMobile = get_image_prod( $postStories->ID, 'medium' ); ?>

                                            <div class="bloco flex-fill <?php echo $i == 0 ? 'me-5' : '' ?>">
                                                <a href="<?php echo esc_url(get_permalink($postStories->ID)) ?>">
                                                    <picture>
                                                        <source media="(max-width: 799px)"
                                                                data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                                        <source media="(min-width: 800px)"
                                                                data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                                        <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                                             class="lazy w-100">
                                                    </picture>
                                                </a>
                                            </div>

                                        <?php } ?>
                                    </div>

                                    <div class="stories-mobile d-md-none">
                                        <div class="int w-100 h-100">
	                                        <?php
	                                        $qtd_capa = count($postsStories->posts) - 1;
                                            foreach ( $postsStories->posts as $i => $postStories ) {
		                                        $imgDesk   = get_image_prod( $postStories->ID, 'large' );
		                                        $imgMobile = get_image_prod( $postStories->ID, 'medium' ); ?>

                                                <div class="stories-card" data-index="<?php echo $qtd_capa - $i ?>">
                                                    <a href="<?php echo esc_url(get_permalink($postStories->ID)) ?>">
                                                        <picture>
                                                            <source media="(max-width: 799px)"
                                                                    data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                                            <source media="(min-width: 800px)"
                                                                    data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                                            <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                                                 class="lazy w-100">
                                                        </picture>
                                                    </a>
                                                </div>

                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
							<?php } else { ?>
                                <div class="stories <?php echo ! empty( $home['posts'] ) ? 'mt-5 pt-md-5' : '' ?>">
                                    <span class="titulo text-uppercase text-center w-100 d-block">webstories</span>

                                    <div class="stories__block d-md-flex mt-3 d-none">
                                        <?php foreach ( $home['stories'] as $i => $postStories ) {
	                                        $imgDesk   = get_image_prod( $postStories['post']->ID, 'large' );
	                                        $imgMobile = get_image_prod( $postStories['post']->ID, 'medium' ); ?>

                                            <div class="bloco flex-fill <?php echo $i === 0 ? 'me-5' : '' ?>">
                                                <a href="<?php echo esc_url(get_permalink($postStories['post']->ID)) ?>">
                                                    <picture>
                                                        <source media="(max-width: 799px)"
                                                                data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                                        <source media="(min-width: 800px)"
                                                                data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                                        <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                                             class="lazy w-100">
                                                    </picture>
                                                </a>
                                            </div>

                                        <?php } ?>
                                    </div>

                                    <div class="stories-mobile d-md-none">
                                        <div class="int w-100 h-100">
	                                        <?php
	                                        $qtd_capa = count($home['stories']) - 1;
                                            foreach ( $home['stories'] as $i => $postStories ) {
		                                        $imgDesk   = get_image_prod( $postStories['post']->ID, 'large' );
		                                        $imgMobile = get_image_prod( $postStories['post']->ID, 'medium' ); ?>

                                                <div class="stories-card" data-index="<?php echo $qtd_capa - $i ?>">
                                                    <a href="<?php echo esc_url(get_permalink($postStories['post']->ID)) ?>">
                                                        <picture>
                                                            <source media="(max-width: 799px)"
                                                                    data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                                            <source media="(min-width: 800px)"
                                                                    data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                                            <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                                                 class="lazy w-100">
                                                        </picture>
                                                    </a>
                                                </div>

	                                        <?php } ?>
                                        </div>
                                    </div>
                                </div>
							<?php } ?>

                        </div>

                        <div class="col-12 col-md-4 ms-md-2 ps-md-5 padding-mobile mt-5 mt-md-0">
                            <div class="bloco-sticky">
                                <div class="d-flex flex-column">
                                    <?php
                                    if ( $home['externo'] ) {
	                                    $imgDesk    = $home['imagem']['sizes']['large'];
	                                    $imgMobile  = $home['imagem']['sizes']['medium'];
	                                    $chapeu     = $home['chapeu'];
	                                    $titulo     = $home['titulo'];
	                                    $linha_fina = $home['linha_fina'];
	                                    $link       = $home['link'];
                                    } else {
                                        $postData = $home['post'];
	                                    $imgDesk   = get_image_prod( $postData->ID, 'large' );
	                                    $imgMobile = get_image_prod( $postData->ID, 'medium' );
	                                    $chapeu     = !empty($home['chapeu']) ? $home['chapeu'] : get_the_category($postData->ID)[0]->name;
	                                    $titulo     = !empty($home['titulo']) ? $home['titulo'] : get_field('post_titulo', $postData->ID);
	                                    $linha_fina = !empty($home['linha_fina']) ? $home['linha_fina'] : get_field('post_linha_fina', $postData->ID);
	                                    $link       = get_permalink($postData->ID);
                                    }
                                    ?>
                                    <div class="post">
                                        <div class="post__image">
                                            <picture>
                                                <source media="(max-width: 799px)"
                                                        data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                                <source media="(min-width: 800px)"
                                                        data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                                <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                                     class="lazy w-100">
                                            </picture>
                                        </div>

                                        <div class="post__texto mt-2">
                                            <a href="#" class="categoria text-uppercase"><span
                                                        class="titulo"><?php echo $chapeu ?></span></a>

                                            <a href="<?php echo esc_url($link) ?>">
                                                <h2 class="m-0 mt-2"><?php echo !empty($titulo) ? $titulo : $postData->post_title ?></h2>

                                                <?php
                                                if ( ! empty( $linha_fina ) ) {
                                                    echo '<p class="linha-fina m-0 mt-2">'.$linha_fina.'</p>';
                                                }
                                                ?>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="apoie text-center w-50 py-4 mx-auto mt-5 d-none">
                                        <span>APOIE O JORNALISMO LOCAL</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
				<?php
			}
			elseif ( $layout === 'acordeon' ) { ?>
                <section class="home__accordion accordion container mt-5 pt-md-5 padding-mobile">
                    <ul>
                        <?php foreach ( $home['posts'] as $i => $postAc ) {
                            $titulo = !empty($postAc['titulo']) ? $postAc['titulo'] : get_field('post_titulo', $postAc['post']->ID);
                            $imagem = !empty($postAc['imagem']) ? $postAc['imagem']['sizes']['large'] : get_image_prod( $postAc['post']->ID, 'large' );
                            $categoria = get_the_category($postAc['post']->ID)[0];
                            $cor = get_field( 'categoria_cor', 'category_' . $categoria->term_id );
                            ?>

                            <li class="c-<?php echo $i + 1; ?> <?php echo $i == 0 ? 'active' : '' ?>" style="background: <?php echo $cor ?>">
                                <div class="section-title">
                                    <h2 class="m-0 text-uppercase"><?php echo $postAc['chapeu'] ?></h2>
                                </div>
                                <div class="section-content ms-4">
                                    <div class="row h-100">
                                        <div class="col-5 titulo pe-5">
                                            <a href="<?php echo esc_url( get_permalink( $postAc['post']->ID ) ) ?>">
                                                <h2 class="m-0"><?php echo !empty($titulo) ? $titulo : $postAc['post']->post_title ?></h2>
                                            </a>
                                        </div>

                                        <div class="col-7">
                                            <div class="image lazy"
                                                 data-bg="<?php echo $imagem ?>"></div>
                                        </div>
                                    </div>

                                </div>
                            </li>
                        <?php } ?>
                    </ul>

                    <div class="accordion-home-mobile d-md-none">
                        <div class="int w-100 h-100">
	                        <?php
	                        $qtd_ac = count($home['posts']) - 1;
                            foreach ( $home['posts'] as $i => $postAc ) {
		                        $titulo    = ! empty( $postAc['titulo'] ) ? $postAc['titulo'] : get_field( 'post_titulo', $postAc['post']->ID );
		                        $imagem    = ! empty( $postAc['imagem'] ) ? $postAc['imagem']['sizes']['large'] : get_image_prod( $postAc['post']->ID, 'large' );
		                        $categoria = get_the_category( $postAc['post']->ID )[0];
		                        $cor       = get_field( 'categoria_cor', 'category_' . $categoria->term_id ); ?>

                                <div class="c-1 accordion-card px-3 py-2" data-index="<?php echo $qtd_ac - $i ?>" style="background: <?php echo $cor ?>">
                                    <a class="d-flex flex-column">
                                        <span class="m-0 text-uppercase mb-2"><?php echo $postAc['chapeu'] ?></span>

                                        <div class="image mb-2 lazy"
                                             data-bg="<?php echo $imagem ?>"></div>

                                        <h2 class="m-0 pt-2"><?php echo !empty($titulo) ? $titulo : $postAc['post']->post_title ?></h2>
                                    </a>
                                </div>

	                        <?php } ?>
                        </div>
                    </div>
                </section>
			<?php }
			elseif ( $layout === 'lista' ) { ?>
                <section class="home__listagem-2 container mt-5 pt-5 pt-md-0">
                    <div class="row no-padding-mobile">
                        <div class="col-12 col-md-5 pe-md-5 px-3 padding-mobile">
                            <div class="lista d-flex flex-column mt-md-5 padding-mobile">
	                            <?php if ( ! empty( $home['posts'] ) ) {
		                            foreach ( $home['posts'] as $i => $postLista ) {
		                                $externo = $postLista['externo'];
			                            if ( $externo ) {
				                            $chapeu     = $postLista['chapeu'];
				                            $link       = $postLista['link'];
				                            $titulo     = $postLista['titulo'];
				                            $linha_fina = $postLista['linha_fina'];
			                            } else {
				                            $categoria  = get_the_category( $postLista['post']->ID )[0];
				                            $chapeu     = ! empty( $postLista['chapeu'] ) ? $postLista['chapeu'] : $categoria->name;
				                            $link       = get_permalink( $postLista['post']->ID );
				                            $titulo     = ! empty( $postLista['titulo'] ) ? $postLista['titulo'] : get_field( 'post_titulo', $postLista['post']->ID );
				                            $linha_fina = ! empty( $postLista['linha_fina'] ) ? $postLista['linha_fina'] : get_field( 'post_linha_fina', $postLista['post']->ID );
			                            }
		                                ?>
                                        <div class="lista__bloco py-3 <?php echo $i === 0 ? 'border-bottom pt-0' : '' ?>">
                                            <span class="titulo text-uppercase"><?php echo $chapeu ?></span>

                                            <a href="<?php echo esc_url($link) ?>">
                                                <h2 class="m-0 mt-md-3 mt-2"><?php echo !empty($titulo) ? $titulo : $postLista['post']->post_title; ?></h2>

                                                <?php
                                                if ( ! empty( $linha_fina ) ) {
                                                    echo '<p class="linha-fina m-0 mt-2">'.$linha_fina.'</p>';
                                                }
                                                ?>
                                            </a>

	                                        <?php if ( ! $externo ) { ?>
                                                <span class="detalhes mt-2 d-block"><?php echo mural_assinaturaData($postLista['post']->ID) ?></span>
	                                        <?php } ?>

                                        </div>
		                            <?php }
	                            } ?>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 pe-md-0 ps-md-5 no-padding-mobile">
	                        <?php
	                        if ( $home['externo'] ) {
		                        $imgDesk    = $home['imagem']['sizes']['large'];
		                        $imgMobile  = $home['imagem']['sizes']['medium'];
		                        $chapeu     = $home['chapeu'];
		                        $titulo     = $home['titulo'];
		                        $linha_fina = $home['linha_fina'];
		                        $link       = $home['link'];
	                        } else {
		                        $postData   = $home['post'];
		                        $imgDesk    = get_image_prod( $postData->ID, 'large' );
		                        $imgMobile  = get_image_prod( $postData->ID, 'medium' );
		                        $chapeu     = ! empty( $home['chapeu'] ) ? $home['chapeu'] : get_the_category( $postData->ID )[0]->name;
		                        $titulo     = ! empty( $home['titulo'] ) ? $home['titulo'] : get_field( 'post_titulo', $postData->ID );
		                        $linha_fina = ! empty( $home['linha_fina'] ) ? $home['linha_fina'] : get_field( 'post_linha_fina', $postData->ID );
		                        $link       = get_permalink( $postData->ID );
	                        }
	                        ?>

                            <div class="post my-5">
                                <div class="post__image">
                                    <picture>
                                        <source media="(max-width: 799px)"
                                                data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                        <source media="(min-width: 800px)"
                                                data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                        <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                             class="lazy w-100">
                                    </picture>
                                </div>

                                <div class="post__texto mt-2 padding-mobile">
                                    <a href="<?php echo !$home['externo'] ? get_category_link(get_the_category($postData->ID)[0]->term_id) : '#' ?>" class="categoria text-uppercase"><span class="titulo"><?php echo $chapeu ?></span></a>

                                    <a href="<?php echo esc_url($link) ?>">
                                        <h2 class="m-0 mt-2"><?php echo !empty($titulo) ? $titulo : $postData->post_title; ?></h2>

	                                    <?php
	                                    if ( ! empty( $linha_fina ) ) {
		                                    echo '<p class="linha-fina m-0 mt-2">'.$linha_fina.'</p>';
	                                    }
	                                    ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
			<?php }
			elseif ( $layout === 'canais' ) {
			    //var_dump($home); die;
			    ?>
                <section class="home__sua-quebrada quebrada-tooltip container mt-md-5 padding-mobile">
                    <div class="sua-quebrada-lista d-flex flex-column flex-md-row mt-4 bloco-card">
                        <?php
                        $qtdCanais = count( $home['posts'] );

                        foreach ( $home['posts'] as $i => $postCanal ) {
	                        $canalLink = '';

	                        if ( $postCanal['canal']['value'] === 'xsp' ) {
		                        $canalDescricao = 'As desigualdades dos 32 distritos da capital paulista';
		                        $canalLink = 'https://32xsp.org.br/';
	                        } elseif ( $postCanal['canal']['value'] === 'blog-mural' ) {
		                        $canalDescricao = 'Reflexões sobre os bastidores do jornalismo nas periferias';
		                        $canalLink = 'https://folha.com/mural/';
	                        } elseif ( $postCanal['canal']['value'] === 'global-voices' ) {
		                        $canalDescricao = 'Nossas reportagens para o mundo todo';
		                        $canalLink = 'https://pt.globalvoices.org/author/agenciamural/';
	                        } elseif ( $postCanal['canal']['value'] === 'folha' ) {
		                        $canalDescricao = 'Reportagens exclusivas da Mural publicadas ali';
	                        } elseif ( $postCanal['canal']['value'] === 'uol' ) {
		                        $canalDescricao = 'Reportagens exclusivas da Mural publicadas ali';
	                        }

	                        $canal      = $postCanal['canal']['label'];
	                        $imgDesk    = $postCanal['imagem']['sizes']['large'];
	                        $imgMobile  = $postCanal['imagem']['sizes']['medium'];
	                        $chapeu     = $postCanal['chapeu'];
	                        $titulo     = $postCanal['titulo'];
	                        $linha_fina = $postCanal['linha_fina'];
	                        $link       = $postCanal['link'];
                            ?>
                            <div class="bloco <?php echo $i + 1 != $qtdCanais ? 'me-md-4 mb-5 mb-md-0' : '' ?>">
                                <div class="d-flex mb-2">
                                    <a href="<?php echo $canalLink ?>" class="m-tooltip bairro text-uppercase border px-2 text-center position-relative"
                                       draggable="false" data-tooltip="<?php echo $canalDescricao ?>"><?php echo $canal ?></a>
                                </div>

                                <div class="bloco__post d-flex d-md-block">
                                    <div class="image">
                                        <picture>
                                            <source media="(max-width: 799px)"
                                                    data-srcset="<?php echo changeurl( $imgMobile ) ?>">
                                            <source media="(min-width: 800px)"
                                                    data-srcset="<?php echo changeurl( $imgDesk ) ?>">
                                            <img data-src="<?php echo changeurl( $imgDesk ) ?>" alt="Image"
                                                 class="lazy w-100">
                                        </picture>
                                    </div>

                                    <div class="texto mt-md-2 ms-3 ms-md-0">
                                        <a href="#" class="pe-none categoria text-uppercase"><?php echo $chapeu ?></a>

                                        <a href="<?php echo esc_url($link) ?>">
                                            <h2 class="m-0 mt-md-2 mt-1"><?php echo $titulo ?></h2>

	                                        <?php
	                                        if ( ! empty( $linha_fina ) ) {
		                                        echo '<p class="linha-fina m-0 mt-md-1 mt-2 d-none d-md-block">' . $linha_fina . '</p>';
	                                        }
	                                        ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="assine mx-auto p-3 mt-5 border border-dark">
                        <a href="https://agenciamural.substack.com/" target="_blank">
                            <h3 class="m-0 text-center text-uppercase">assine nossa newsletter</h3>
                        </a>
                    </div>
                </section>
			<?php }
		}
	}

	if ( ! empty( $home_marquee['posts'] ) ) { ?>
        <section class="home__marquee d-flex position-relative">

            <marquee behavior="scroll">
                <a href="javacript:;" class="chamada text-uppercase"><?php echo $home_marquee['titulo'] ?> > </a>

	            <?php foreach ( $home_marquee['posts'] as $postMarque ) {
		            $pMarque    = $postMarque['post'];
		            $titulo     = ! empty( $postMarque['titulo'] ) ? $postMarque['titulo'] : $pMarque->post_title;
		            $linha_fina = ! empty( $postMarque['linha_fina'] ) ? $postMarque['linha_fina'] : get_field( 'post_linha_fina', $pMarque->ID );
	                ?>
                    <a href="<?php echo esc_url(get_permalink($pMarque->ID)) ?>" class="post me-2">
                        <span class="titulo text-uppercase me-2"><?php echo $titulo ?></span>
                        <span class="linha-fina text-uppercase me-2"><?php echo $linha_fina ?></span>

                        <span class="leia border rounded-pill">leia mais ></span>
                    </a>
                <?php } ?>

            </marquee>
        </section>
	<?php } ?>
</div>