<?php
/**
 * Custom template tags for this theme
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 */

if ( ! function_exists( 'twentynineteen_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function twentynineteen_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		echo '<span class="posted-on"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a></span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'twentynineteen_posted_by' ) ) :
	/**
	 * Prints HTML with meta information about theme author.
	 */
	function twentynineteen_posted_by() {
		printf( '<span class="byline">%1$s<span class="screen-reader-text">%2$s</span><span class="author vcard"><a class="url fn n" href="%3$s">%4$s</a></span></span>',
			/* translators: 1: SVG icon. 2: post author, only visible to screen readers. 3: author link. */
			twentynineteen_get_icon_svg( 'person', 16 ),
			esc_html__( 'Posted by', 'twentynineteen' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() ) );
	}
endif;

if ( ! function_exists( 'twentynineteen_comment_count' ) ) :
	/**
	 * Prints HTML with the comment count for the current post.
	 */
	function twentynineteen_comment_count() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			echo twentynineteen_get_icon_svg( 'comment', 16 );

			/* translators: %s: Name of current post. Only visible to screen readers. */
			comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'twentynineteen' ), get_the_title() ) );

			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'twentynineteen_estimated_read_time' ) ) :
	/**
	 * Prints HTML with the estimated reading time. Does not display when time to read is zero.
	 */
	function twentynineteen_estimated_read_time() {
		$minutes = twentynineteen_get_estimated_reading_time();
		if ( 0 === $minutes ) return null;
		$datetime_attr = sprintf( '%dm 0s', $minutes );
		$read_time_text = sprintf( _nx( '%s Minute', '%s Minutes', $minutes, 'Time to read', 'twentynineteen' ), $minutes );
		/* translators: 1: SVG icon. 2: Reading time label, only visible to screen readers. 3: The [datetime] attribute for the <time> tag. 4: Estimated reading time text, in minutes. */
		printf ( '<span class="est-reading-time">%1$s<span class="screen-reader-text">%2$s</span><time datetime="%3$s">%4$s</time></span>',
			twentynineteen_get_icon_svg( 'watch', 16 ),
			__( 'Estimated reading time', 'twentynineteen' ),
			$datetime_attr,
			$read_time_text );
	}
endif;

if ( ! function_exists( 'twentynineteen_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function twentynineteen_entry_footer() {

		// Posted by
		twentynineteen_posted_by();

		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma. */
			$categories_list = get_the_category_list( esc_html__( ', ', 'twentynineteen' ) );
			if ( $categories_list ) {
				/* translators: 1: SVG icon. 2: posted in label, only visible to screen readers. 3: list of categories. */
				printf( '<span class="cat-links">%1$s<span class="screen-reader-text">%2$s</span>%3$s</span>',
					twentynineteen_get_icon_svg( 'archive', 16 ),
					esc_html__( 'Posted in', 'twentynineteen' ),
					$categories_list
				); // WPCS: XSS OK.
			}
		}

		// Comment count.
		if ( ! is_singular() ) {
			twentynineteen_comment_count();
		}

		// Edit post link.
		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'twentynineteen' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">' . twentynineteen_get_icon_svg( 'edit', 16 ) ,
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'twentynineteen_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function twentynineteen_post_thumbnail() {
		if ( ! twentynineteen_can_show_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<figure class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</figure><!-- .post-thumbnail -->

		<?php else :
			$post_thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' );
		?>

		<figure class="post-thumbnail">
			<a class="post-thumbnail-inner" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1" style="background-image: url(<?php echo esc_url($post_thumbnail) ?>);">
				<?php
				the_post_thumbnail( 'post-thumbnail', array(
					'alt' => the_title_attribute( array(
						'echo' => false,
					) ),
				) );
				?>
			</a>
		</figure>

		<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'twentynineteen_header_featured_image_css' ) ) :
	/**
	 * Returns the CSS for the header featured image background.
	 */
	function twentynineteen_header_featured_image_css() {
		$img_url = get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' );
		return sprintf( 'body.singular .site-header.featured-image .site-branding-container:before { background-image: url(%s); }', esc_url( $img_url ) );
	}
endif;

if ( ! function_exists( 'twentynineteen_human_time_diff' ) ) :
/**
 * Same as core's human_time_diff(), only in the "ago" context,
 * which is different for some languages.
 *
 * @param int $from Unix timestamp from which the difference begins.
 * @param int $to Optional Unix timestamp to end the time difference. Defaults to time() if not set.
 * @return string Human readable time difference.
 */
	function twentynineteen_human_time_diff( $from, $to = '' ) {
		if ( empty( $to ) ) {
			$to = time();
		}

		$diff = (int) abs( $to - $from );

		if ( $diff < HOUR_IN_SECONDS ) {
			$mins = round( $diff / MINUTE_IN_SECONDS );
			if ( $mins <= 1 ) {
				$mins = 1;
			}
			/* translators: min=minute */
			$since = sprintf( _n( '%s min ago', '%s mins ago', $mins, 'twentynineteen' ), $mins );
		} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
			$hours = round( $diff / HOUR_IN_SECONDS );
			if ( $hours <= 1 ) {
				$hours = 1;
			}
			$since = sprintf( _n( '%s hour ago', '%s hours ago', $hours, 'twentynineteen' ), $hours );
		} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
			$days = round( $diff / DAY_IN_SECONDS );
			if ( $days <= 1 ) {
				$days = 1;
			}
			$since = sprintf( _n( '%s day ago', '%s days ago', $days, 'twentynineteen' ), $days );
		} elseif ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
			$weeks = round( $diff / WEEK_IN_SECONDS );
			if ( $weeks <= 1 ) {
				$weeks = 1;
			}
			$since = sprintf( _n( '%s week ago', '%s weeks ago', $weeks, 'twentynineteen' ), $weeks );
		} elseif ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {
			$months = round( $diff / ( 30 * DAY_IN_SECONDS ) );
			if ( $months <= 1 ) {
				$months = 1;
			}
			$since = sprintf( _n( '%s month ago', '%s months ago', $months, 'twentynineteen' ), $months );
		} elseif ( $diff >= YEAR_IN_SECONDS ) {
			$years = round( $diff / YEAR_IN_SECONDS );
			if ( $years <= 1 ) {
				$years = 1;
			}
			$since = sprintf( _n( '%s year ago', '%s years ago', $years, 'twentynineteen' ), $years );
		}

		return $since;
	}
endif;

if ( ! function_exists( 'twentynineteen_comment_avatar' ) ) :
	/**
	 * Returns the HTML markup to generate a user avatar.
	 */
	function twentynineteen_get_user_avatar_markup( $id_or_email=null ) {
		if ( ! isset( $id_or_email ) ) {
			$id_or_email = get_current_user_id();
		}

		$classes = array( 'comment-author', 'vcard' );

		return sprintf( '<div class="comment-user-avatar comment-author vcard">%s</div>', get_avatar( $id_or_email, twentynineteen_get_avatar_size() ) );
	}
endif;

if ( ! function_exists( 'twentynineteen_discussion_avatars_list' ) ) :
	/**
	 * Displays a list of avatars involved in a discussion for a given post.
	 */
	function twentynineteen_discussion_avatars_list( $comment_authors ) {
		if ( ! empty( $comment_authors ) ) {
			$out = array('<ol class="discussion-avatar-list">');
			foreach( $comment_authors as $id_or_email ) {
				$out[] = sprintf( '<li>%s</li>', twentynineteen_get_user_avatar_markup( $id_or_email ) );
			}
			$out[] = '</ol><!-- .discussion-avatar-list -->';
			echo implode( "\n", $out );
		}
		return null;
	}
endif;

if ( ! function_exists( 'twentynineteen_comment_form' ) ) :
	/**
	 * Documentation for function.
	 */
	function twentynineteen_comment_form( $order ) {
		if ( true === $order || strtolower( $order ) === strtolower( get_option( 'comment_order', 'asc' ) ) ) {
			comment_form( array(
				'title_reply_before' => twentynineteen_get_user_avatar_markup(),
				'logged_in_as'       => null,
				'title_reply'        => null,
			) );
		}
	}
endif;

if ( ! function_exists( 'twentynineteen_the_posts_navigation' ) ) :
	/**
	 * Documentation for function.
	 */
	function twentynineteen_the_posts_navigation() {
		$prev_icon = twentynineteen_get_icon_svg( 'chevron_left',  22 );
		$next_icon = twentynineteen_get_icon_svg( 'chevron_right', 22 );
		the_posts_pagination( array(
			'mid_size'  => 2,
			'prev_text' => sprintf( '%s <span class="nav-prev-text">%s</span>', $prev_icon, __( 'Older posts', 'twentynineteen' ) ),
			'next_text' => sprintf( '<span class="nav-next-text">%s</span> %s', __( 'Newer posts', 'twentynineteen' ), $next_icon ),
		) );
	}
endif;