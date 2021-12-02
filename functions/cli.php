<?php
class Mural_Command extends WP_CLI_Command {
	function default() {
		echo ':D';
	}

	function convert($args) {
		$tag = $args[0];
		$quebrada = $args[1];

		$postsList = new WP_Query( array(
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'tag_id' => $tag,
			'fields' => 'ids'
		) );

		//set_post_type
		if(!empty($postsList)) {
			foreach ( $postsList->posts as $i => $item ) {
				$tagAdd = array();
				$zonas = array();

				$quebradas = get_the_terms($item, 'quebrada');

				if ( ! empty( $quebradas ) ) {
					foreach ( $quebradas as $q ) {
						array_push( $tagAdd, $q->term_id );
					}
				}

				array_push($tagAdd, intval($quebrada));
				$result = array_unique($tagAdd);

				wp_set_post_terms($item,$result, 'quebrada');

				foreach ( $result as $i => $z ) {
					$zona = get_field( 'quebrada_zona', 'quebrada_' . $z );
					$zonas[$i] = $zona['value'];
				}

				if(!empty($zonas)) {
					$array = array_unique($zonas);

					delete_post_meta($item, 'post_zonas');

					update_post_meta($item, 'post_zonas', $array);
				}
			}
		}
	}

}

WP_CLI::add_command( 'mural', 'Mural_Command' );
