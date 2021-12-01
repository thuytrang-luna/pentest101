<?php 
$enhanced_tweet = Lastweets\Functions\refactor_tweet_object( $tweet );
?>
<article class="lastweet-tweet">
	<header>
		<a target="_blank" rel="nofollow" href="<?php echo esc_url( $enhanced_tweet->user->url ); ?>">
			<img src="<?php echo esc_url( $enhanced_tweet->user->avatar ); ?>" alt="<?php echo esc_attr( $enhanced_tweet->user->name ); ?>" />
			<p>
				<?php echo esc_html( $enhanced_tweet->user->name ); ?>
				<small>
					<?php echo sprintf( '@%1$s', esc_html( $enhanced_tweet->user->screen_name ) ); ?>
					<time><?php echo esc_html( date_i18n( get_option( 'date_format' ), $enhanced_tweet->date ) ); ?></time>
				</small>
			</p>
		</a>
	</header>

	<blockquote>
		<?php echo wp_kses_post( $enhanced_tweet->text ); ?>
	</blockquote>

	<footer>
		<a target="_blank" rel="nofollow" href="<?php echo esc_url( $enhanced_tweet->url ); ?>">
			<span class="retweets">
				<?php printf(
					__( '%1$d retweets', 'lastweets' ),
					(int) $enhanced_tweet->retweets_count
				); ?>
			</span>
			<span class="favorites">
			<?php printf(
				__( '%1$d favorites', 'lastweets' ),
				(int) $enhanced_tweet->favorites_count
			); ?>
			</span>
		</a>
	</footer>
</article>