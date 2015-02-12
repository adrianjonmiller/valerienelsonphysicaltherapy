<?php
/*
Template Name: Staff
*/
?>
<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
	<?php roots_post_before(); ?>
		<?php roots_post_inside_before(); ?>
			<h2 class="page-title"><?php the_title(); ?></h2>
			<div id="primary-content" class="span-10">
				<?php the_content(); ?>
				<?php
				$staff_query = new WP_Query("posts_per_page=-1&post_type=page&post_parent=13&orderby=menu_order&order=ASC");
				while ($staff_query->have_posts()) : $staff_query->the_post(); ?>
				<div class="staff">
					<h3><?php $staff_query->the_title(); ?></h3>
					<img src="<?php echo $staff_query->get_post_meta($post->ID, "main-image", true); ?>" alt="Photo of <?php $staff_query->the_title(); ?>" title="<?php $staff_query->the_title(); ?>" />
					<p><?php $staff_query->the_content(); ?></p>
				</div>
				<?php endwhile; ?>
			<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>' )); ?>
			</div>
			<div id="secondary-content" class="span-8">
				<?php the_secondary_content(); ?>
			</div>
		<?php roots_post_inside_after(); ?>
	<?php roots_post_after(); ?>
<?php endwhile; // End the loop ?>