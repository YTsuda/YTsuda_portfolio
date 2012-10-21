<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Please see /external/starkers-utilities.php for info on get_template_parts() 
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php
global $wp_query;
$args = array_merge($wp_query->query, array(
    'meta_key' => 'year',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
));
query_posts($args);

works_archives_script();
?>
<?php get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

<div class="title">
    <?php if ( have_posts() ): ?>
    <?php if ( is_day() ) : ?>
    <h2 class="page-title">works: <?php echo  get_the_date( 'D M Y' ); ?></h2>
    <?php elseif ( is_month() ) : ?>
    <h2 class="page-title">works: <?php echo  get_the_date( 'M Y' ); ?></h2>
    <?php elseif ( is_year() ) : ?>
    <h2 class="page-title">works: <?php echo  get_the_date( 'Y' ); ?></h2>
    <?php else : ?>
    <h2 class="page-title">works</h2>
    <?php endif; ?>
    <!-- メニューである年号の出力。クリックするとコンテンツがスクロール  -->
    <ul class="menu"><?php for($i = 2012; $i > 2008; $i-- ) echo '<li><a id="year-menu-', $i ,'" href="#', $i ,'">', $i, '</a></li>'; ?></ul>
</div>
<div class="main-content"><div class="main-content-inner">
    <ol class="archives">
        <?php
        $current_year = null;
        while ( have_posts() ) : the_post();

            /* 制作年を出力する */
            $the_year = get_the_custom_value('year');
            if($the_year != $current_year):
                $current_year = $the_year; ?>
                <li class="year" id="year-<?php echo $the_year ?>"><?php echo $the_year ?></li><?php
            endif; ?>

        <li>
            <article>
                <div class="thumb"><a href="<?php esc_url( the_permalink() ); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark">
                    <?php
                    if(has_post_thumbnail()){
                        the_post_thumbnail();
                    }else{
                        echo_no_image_thumb();
                    } ?>
                </a></div>
                <div class="data">
                    <h2><a href="<?php esc_url( the_permalink() ); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                    <?php if($urls = get_post_custom_values('url', get_the_ID())){
                    echo '<div class="url"><a href="', $urls[0] , '" target="_blank">', $urls[0], '</a></div>';
                } ?>
                    <dl class="work-params type">
                        <dt>種類</dt>
                        <?php foreach(get_the_work_params('type') as $param): ?>
                        <dd><a href="/category/type/<?php echo $param->slug ?>"><?php echo $param->cat_name ?></a></dd>
                        <?php endforeach; ?>
                    </dl>
                    <dl class="work-params member">
                        <dt>メンバー</dt>
                        <dd><?php echo get_the_custom_value('member'); ?>人</dd>
                    </dl>
                    <dl class="work-params env" style="clear:left;">
                        <dt>環境</dt>
                        <?php foreach(get_the_work_params('env') as $param): ?>
                        <dd><a href="/category/type/<?php echo $param->slug ?>"><?php echo $param->cat_name ?></a></dd>
                        <?php endforeach; ?>
                    </dl>
                    <dl class="work-params part" style="clear:left;">
                        <dt>担当</dt>
                        <?php foreach(get_the_work_params('part') as $param): ?>
                        <dd><a href="/category/type/<?php echo $param->slug ?>"><?php echo $param->cat_name ?></a></dd>
                        <?php endforeach; ?>
                    </dl>
                    <?php the_excerpt(); ?>
                </div>
            </article>
        </li>
        <?php endwhile; ?>
    </ol>
</div></div>
<?php else: ?>
<h2>No posts to display</h2>	
<?php endif; ?>

<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
