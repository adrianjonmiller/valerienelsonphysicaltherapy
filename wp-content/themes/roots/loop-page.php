<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
	<?php roots_post_before(); ?>
		<?php roots_post_inside_before(); ?>
			<h2 class="page-title"><?php the_title(); ?></h2>
			<div id="primary-content" class="span-10">
				<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>' )); ?>
			</div>
			<div id="secondary-content" class="span-8">
				<?php the_secondary_content(); ?>
			</div>
		<?php roots_post_inside_after(); ?>
	<?php roots_post_after(); ?>
<?php endwhile; // End the loop ?>