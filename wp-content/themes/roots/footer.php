<?php
$street = get_post_meta(33, 'street', true);
$city = get_post_meta(33, 'city', true);
$state = get_post_meta(33, 'state', true);
$zip = get_post_meta(33, 'zip', true);
$phone = get_post_meta(33, 'phone', true);
$email = antispambot(get_post_meta(33, 'email', true));
?>

	<?php roots_footer_before(); ?>
		<footer id="content-info" class="<?php global $roots_options; echo $roots_options['container_class']; ?>" role="contentinfo">
			<?php roots_footer_inside(); ?>
			<div class="container">
				<div id="contact-info">
					<section>
						<address>
							Address:<br />
							<a href="http://maps.google.com/maps?q=2224+5th+Avenue+Oroville,+California+95966&hl=en&sll=37.0625,-95.677068&sspn=48.822589,79.013672&z=16" target="_blank" title="View Google map in new tab or window"><?php echo $street; ?><br />
							<?php echo $city; ?>, <?php echo $state; ?><br />
							<?php echo $zip; ?></a>
						</address>
					</section>
					<section>
						Phone:<br />
						<em><?php echo $phone; ?></em>
					</section>
					<section>
						Email:<br />
						<em><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></em>
					</section>
				</div>
				<div id="ptbc">
					<p><a href="http://www.ptbc.ca.gov/" target="_blank" title="Visit the Physical Therapy Board of California's website"><img src="<?php echo home_url(); ?>/assets/ptb.gif" /></a>All therapists are licensed through the <a href="http://www.ptbc.ca.gov/" target="_blank" title="Visit the Physical Therapy Board of California's website">Physical Therapy Board of California</a></p>
			</div>	
		</footer>
		<?php roots_footer_after(); ?>	
	</div><!-- /#wrap -->

<?php wp_footer(); ?>
<?php roots_footer(); ?>
<!--[if lt IE 7]>
<script src="<?php echo get_template_directory_uri(); ?>/js/libs/dd_belatedpng.min.js"></script>
<script>DD_belatedPNG.fix('.png_bg');</script>
<![endif]-->

</body>
</html>