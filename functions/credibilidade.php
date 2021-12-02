<?php
add_action( 'wp_head', 'mural_credibilidade' );

function mural_credibilidade() { ?>
	<script type="application/ld+json">
        {
            "@context": "http://schema.org",
            <?php if ( ! is_single() && ! is_author() ) { ?>
                "@type": "WebSite",
                "url": "https://www.agenciamural.org.br/",
                "name": "Agência Mural, Jornalismo nas periferias",
            <?php } ?>


            <?php if ( is_single() ) {
			$post_object = get_post( get_the_ID() );

			$tipo_post = get_field('post_tipo', get_the_ID());

			$tipo = array();
			if ( ! empty( $tipo_post ) ) {
				foreach ( $tipo_post as $item_tipo ) {
					if ( $item_tipo['value'] === 'noticia' ) {
						array_push( $tipo, 'ReportageNewsArticle' );
					} elseif ( $item_tipo['value'] === 'opiniao' ) {
						array_push( $tipo, 'OpinionNewsArticle' );
					} else {
						array_push( $tipo, 'AnalysisNewsArticle' );
					}
				}
			} else {
				array_push( $tipo, 'ReportageNewsArticle' );
			}

			/*Errata*/
			$errata = get_field( 'post_errata', get_the_ID() );  ?>

                    "@type":[<?php echo '"' . implode( '","', $tipo ) . '"'; ?>],
                    "url":"<?php echo get_permalink( get_the_ID() ); ?>",
                    "headline":"<?php echo get_the_title( get_the_ID() ) ?>",
                    "description":"<?php echo strip_tags(get_the_excerpt( get_the_ID() )) ?>",
                    "image":["<?php echo get_the_post_thumbnail_url( get_the_ID() ) ?>"],
                    "datePublished":"<?php echo $post_object->post_date ?>",
                    "dateModified":"<?php echo $post_object->post_modified ?>",
                    "dateline": "Brazil",
                    <?php if ( ! empty( $errata ) ) {
				$ultima_errata = array_pop($errata); ?>

                            "correction": {
                              "@type": "CorrectionComment",
                              "text": "<?php echo $ultima_errata['descricao'] ?>",
                              "datePublished": "<?php echo $ultima_errata['data'] ?>"
                            },
                    <?php } else { ?>
                        "correction": {
                              "@type": "CorrectionComment",
                              "text": "",
                              "datePublished": ""
                            },
                    <?php } ?>

                    "author":[
                        <?php
			$autores      = get_field( 'post_assinatura', get_the_ID() );
			$usuario_nome = get_the_author_meta( 'display_name', $post_object->post_author );
			$usuario_link = get_author_posts_url( $post_object->post_author );
			$autor_img         = get_field( 'usuario_foto', 'user_' . $post_object->post_author );
			$autor_localização = get_field( 'usuario_localizacao', 'user_' . $post_object->post_author );
			$autor_email       = get_field( 'usuario_email', 'user_' . $post_object->post_author );
			$autor_telefone     = get_field( 'usuario_telefone', 'user_' . $post_object->post_author );

			if ( empty( $autores ) ) { ?>
                    {
                        "@type":"Person",
                        "name":"<?php echo $usuario_nome ?>",
                        "sameAs": "<?php echo $usuario_link ?>",
                        "image"   : "<?php echo ! empty( $autor_img['sizes']['thumbnail'] ) ? $autor_img['sizes']['thumbnail'] : '' ?>",
                        "jobTitle"	: "Columnist",
                        "workLocation" : {
                            "@type": "Place",
                            "name" : "<?php echo ! empty( $autor_localização ) ? $autor_localização . ' BR' : '' ?>"
                        },
                        "knowsAbout": ["Local News", {
                            "@type": "Place",
                            "name": "São Paulo, SP, BR"
                        }],
                        "contactPoint" : {
                            "@type" : "ContactPoint",
                            "contactType"  : "Journalist",
                            "email" : "<?php echo ! empty( $autor_email ) ? $autor_email : '' ?>",
                            "telephone" : "<?php echo ! empty( $autor_telefone ) ? '+5511'.$autor_telefone : '' ?>",
                            "url" : "<?php echo get_author_posts_url($post_object->post_author); ?>"
                        }
                    }
                            <?php } else {
				if ( $autores === 'compartilhada' ) {
					$usuarios_assinando = get_field( 'post_autores', get_the_ID() );
					$qtd_users          = count( $usuarios_assinando );

					foreach ( $usuarios_assinando as $u => $usuarios ) {
						$usuario_link = get_author_posts_url( $usuarios->ID );
						$usuario_nome = get_the_author_meta( 'display_name', $usuarios->ID );
						$autor_img         = get_field( 'usuario_foto', 'user_' . $usuarios->ID );
						$autor_localização = get_field( 'usuario_localizacao', 'user_' . $usuarios->ID );
						$autor_email       = get_field( 'usuario_email', 'user_' . $usuarios->ID );
						$autor_telefone     = get_field( 'usuario_telefone', 'user_' . $usuarios->ID );?>

                                        {
                                            "@type":"Person",
                                            "name":"<?php echo $usuario_nome ?>",
                                            "sameAs": "<?php echo $usuario_link ?>",
                                            "image"   : "<?php echo ! empty( $autor_img['sizes']['thumbnail'] ) ? $autor_img['sizes']['thumbnail'] : '' ?>",
                                            "jobTitle"	: "Columnist",
                                            "workLocation" : {
                                                "@type": "Place",
                                                "name" : "<?php echo !empty($autor_localização) ? $autor_localização.' BR' : '' ?>"
                                            },
                                            "knowsAbout": ["Local News", {
                                                "@type": "Place",
                                                "name": "São Paulo, SP, BR"
                                            }],
                                            "contactPoint" : {
                                                "@type" : "ContactPoint",
                                                "contactType"  : "Journalist",
                                                "email" : "<?php echo ! empty( $autor_email ) ? $autor_email : '' ?>",
                                                "telephone" : "<?php echo ! empty( $autor_telefone ) ? '+5511'.$autor_telefone : '' ?>",
                                                "url" : "<?php echo get_author_posts_url($usuarios->ID); ?>"
                                            }
                                        }

                                        <?php

						if ( $qtd_users != $u + 1 ) {
							echo ',';
						}
						?>

					<?php }
				} else { ?>
	                                {
                                        "@type":"Person",
                                        "name":"<?php echo $usuario_nome ?>",
                                        "sameAs": "<?php echo $usuario_link ?>",
                                        "image"   : "<?php echo ! empty( $autor_img['sizes']['thumbnail'] ) ? $autor_img['sizes']['thumbnail'] : '' ?>",
                                        "jobTitle"	: "Columnist",
                                        "workLocation" : {
                                            "@type": "Place",
                                            "name" : "<?php echo !empty($autor_localização) ? $autor_localização.' BR' : '' ?>"
                                        },
                                        "knowsAbout": ["Local News", {
                                            "@type": "Place",
                                            "name": "São Paulo, SP, BR"
                                        }],
                                        "contactPoint" : {
                                            "@type" : "ContactPoint",
                                            "contactType"  : "Journalist",
                                            "email" : "<?php echo ! empty( $autor_email ) ? $autor_email : '' ?>",
                                            "telephone" : "<?php echo ! empty( $autor_telefone ) ? '+5511'.$autor_telefone : '' ?>",
                                            "url" : "<?php echo get_author_posts_url($post_object->post_author); ?>"
                                        }
                                    }
                                <?php } ?>

			<?php } ?>
			        ],
            <?php }

		if ( is_author() ) {
			$page_autor        = get_user_by( 'slug', get_query_var( 'author_name' ) );
			$autor_id          = $page_autor->ID;
			$autor_img         = get_field( 'usuario_foto', 'user_' . $autor_id );
			$autor_biografia   = get_field( 'usuario_biografia', 'user_' . $autor_id );
			$biografia         = str_replace( '"', '', $autor_biografia );
			$autor_email       = get_field( 'usuario_email', 'user_' . $autor_id );
			$autor_localização = get_field( 'usuario_localizacao', 'user_' . $autor_id );
			$autor_idiomas     = get_field( 'usuario_idiomas', 'user_' . $autor_id );
			$autor_telefone     = get_field( 'usuario_telefone', 'user_' . $autor_id );

			$autor_facebook    = get_field( 'usuario_facebook', 'user_' . $autor_id );
			$autor_twitter     = get_field( 'usuario_twitter', 'user_' . $autor_id );
			$autor_insta       = get_field( 'usuario_instagram', 'user_' . $autor_id );
			$autor_linkedin    = get_field( 'usuario_linkedin', 'user_' . $autor_id );

			$array_redes = array();
			if ( ! empty( $autor_facebook ) ) {
				array_push($array_redes, $autor_facebook);
			}
			if (! empty( $autor_twitter )){
				array_push($array_redes, $autor_twitter);
			}
			if (! empty( $autor_linkedin )){
				array_push($array_redes, $autor_linkedin);
			}
			if (! empty( $autor_insta )){
				array_push($array_redes, 'https://www.instagram.com/'.$autor_insta);
			} ?>

                    "@type"   : "Person",
                    "name" : "<?php echo $page_autor->display_name; ?>",
                    "image"   : "<?php echo ! empty( $autor_img['sizes']['thumbnail'] ) ? $autor_img['sizes']['thumbnail'] : '' ?>",
                    "description" : "<?php echo ! empty( $biografia ) ? $biografia : '' ?>",
                    "contactPoint" : {
                        "@type" : "ContactPoint",
                        "contactType"  : "Journalist",
                        "email" : "<?php echo ! empty( $autor_email ) ? $autor_email : '' ?>",
                        "telephone" : "<?php echo ! empty( $autor_telefone ) ? $autor_telefone : '' ?>",
                        "url" : "<?php echo get_author_posts_url($autor_id); ?>"
                    },
                    "workLocation" : {
                        "@type": "Place",
                        "name" : "<?php echo !empty($autor_localização) ? $autor_localização.' BR' : '' ?>"
                    },

                    <?php if ( ! empty( $autor_idiomas ) ) { ?>
                            "knowsLanguage": [<?php
				echo implode(',', array_map(function ($entry) {
					return '"'.$entry['value'].'"';
				}, $autor_idiomas));
				?>],
                    <?php } ?>

			<?php if ( ! empty( $array_redes ) ) { ?>

                        "sameAs" : [<?php echo '"' . implode( '","', $array_redes ) . '"'; ?>,"<?php echo get_author_posts_url($autor_id); ?>"],

                    <?php } ?>

                    "knowsAbout": ["Local News", {
                        "@type": "Place",
                        "name": "São Paulo, SP, BR"
                    }],

                    "jobTitle"	: "Columnist",

            <?php } ?>

                "mainEntityOfPage":{
                    "@type":"WebPage",
                    "@id":"<?php echo get_permalink( get_the_ID() ); ?>"
                },

                "publisher": {
                "@type": "NewsMediaOrganization",
                "name": "Agência Mural",
                "ethicsPolicy": "https://www.agenciamural.org.br/credibilidade/politica-de-etica/",
                "masthead": "https://www.agenciamural.org.br/equipe/",
                "missionCoveragePrioritiesPolicy": "https://www.agenciamural.org.br/credibilidade/politica-de-etica/",
                "diversityPolicy": "https://www.agenciamural.org.br/credibilidade/declaracao-de-diversidade-de-vozes/",
                "correctionsPolicy": "https://www.agenciamural.org.br/politica-e-pratica-de-correcoes/",
                "verificationFactCheckingPolicy": "https://www.agenciamural.org.br/credibilidade/politica-e-pratica-de-correcoes/",
                "unnamedSourcesPolicy": "https://www.agenciamural.org.br/credibilidade/politica-de-etica/",
                "actionableFeedbackPolicy": "https://www.agenciamural.org.br/credibilidade/politica-e-pratica-de-correcoes/",
                "foundingDate": "2010-11-24",
                "ownershipFundingInfo": "https://www.agenciamural.org.br/apoie/doadores/",
                "publishingPrinciples": "https://www.agenciamural.org.br/principios/",
                "logo":{
                  "@type":"ImageObject",
                  "url":"https://www.agenciamural.org.br/wp-content/uploads/2018/06/maca-mural-site-org-300x111.png"
                },
                "contactPoint" : [
                {
                    "@type" : "ContactPoint",
                    "contactType" : "Newsroom Contact",
                    "email" : "contato@agenciamural.org.br",
                    "url" : "https://www.agenciamural.org.br/contato/"
                }]
            }
        }
</script>
	<?php
}