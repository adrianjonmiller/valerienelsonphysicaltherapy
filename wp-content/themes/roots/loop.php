<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if (!have_posts()) : ?>
	<div class="notice">
		<p class="bottom"><?php _e('Sorry, no results were found.', 'roots'); ?></p>
	</div>
	<?php get_search_form(); ?>	
<?php endif; ?>
	<div id="primary-content" class="span-10">
<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
	<?php roots_post_before(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php roots_post_inside_before(); ?>

			<header>
				<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="Read the entire article: <?php the_title(); ?>"><?php the_title(); ?></a></h3>
				<time pubdate datetime="<?php the_time('c'); ?>"><?php the_time('F j, Y') ?></time> - <span class="byline author vcard"><?php the_author_posts_link(); ?></span>
			</header>
			<div class="entry-content">
		<?php if (is_archive() || is_search()) : // Only display excerpts for archives and search ?>
			<?php the_excerpt(); ?>
		<?php else : ?>
			<?php the_content(); ?>
		<?php endif; ?>
			</div>
			<footer>
				<?php $tag = get_the_tags(); if (!$tag) { } else { ?><p><?php the_tags(); ?></p><?php } ?>
			</footer>
		<?php roots_post_inside_after(); ?>
		</article>
	<?php roots_post_after(); ?>		
<?php endwhile; // End the loop ?>


<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ($wp_query->max_num_pages > 1) : ?>
	<nav id="post-nav">
		<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'roots' ) ); ?></div>
		<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'roots' ) ); ?></div>
	</nav>
<?php endif; ?>
	</div>
	<div id="secondary-content" class="span-8">
		<?php dynamic_sidebar("Sidebar"); ?>
		<?php the_secondary_content(); ?>
	</div>