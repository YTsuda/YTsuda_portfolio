<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Please see /external/starkers-utilities.php for info on get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php
//スクリプトをHEADに読むこむよう登録
works_single_script();
get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="artcle-inner">
    <div class="title">
        <a class="page-title" href="/works">works</a>
        <a class="page-title year" href="/works/#<?php echo get_the_custom_value('year') ?>"><?php echo get_the_custom_value('year') ?></a>
        <div class="post-title"><?php the_title(); ?></div>
    </div>
    <div class="main-content"><div class="main-content-inner">
        <article>
        <h1><?php the_title(); ?></h1>
        <table class="work_param_table">
            <?php
            if( $url = get_the_custom_value('url')):  ?>
            <tr>
                <th>URL</th>
                <td><a href="<?php echo $url ?>" target="_blank"><?php echo $url ?></a></td>
            </tr>
            <?php endif; ?>
            <tr>
                <th>種類</th>
                <td><?php echo get_the_work_param_string('type') ?></td>
            </tr>
            <tr>
                <th>メンバー</th>
                <td><?php echo get_the_custom_value('member') ?>人</td>
            </tr>
            <tr>
                <th>環境</th>
                <td><?php echo get_the_work_param_string('env') ?></td>
            </tr>
            <tr>
                <th>役割</th>
                <td><?php echo get_the_work_param_string('part') ?></td>
            </tr>
        </table>
        <div class="text"><?php the_content(); ?></div>
        </article>
    </div></div>
</div>
<?php endwhile; ?>

<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>