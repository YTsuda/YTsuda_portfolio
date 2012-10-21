<?php
	/**
	 * Starkers functions and definitions
	 *
	 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
	 *
 	 * @package 	WordPress
 	 * @subpackage 	Starkers
 	 * @since 		Starkers 4.0
	 */

	/* ========================================================================================================================
	
	Required external files
	
	======================================================================================================================== */

	require_once( 'external/starkers-utilities.php' );

	/* ========================================================================================================================
	
	Theme specific settings

	Uncomment register_nav_menus to enable a single menu with the title of "Primary Navigation" in your theme
	
	======================================================================================================================== */

	add_theme_support('post-thumbnails');
	
	// register_nav_menus(array('primary' => 'Primary Navigation'));

	/* ========================================================================================================================
	
	Actions and Filters
	
	======================================================================================================================== */

	add_action( 'wp_enqueue_scripts', 'script_enqueuer' );

	add_filter( 'body_class', 'add_slug_to_body_class' );

	/* ========================================================================================================================
	
	Custom Post Types - include custom post types and taxonimies here e.g.

	e.g. require_once( 'custom-post-types/your-custom-post-type.php' );
	
	======================================================================================================================== */



	/* ========================================================================================================================
	
	Scripts
	
	======================================================================================================================== */

	/**
	 * Add scripts via wp_head()
	 *
	 * @return void
	 * @author Keir Whitaker
	 */

	function script_enqueuer() {
        /* script */
        wp_register_script( 'jquery.mousewheel', get_template_directory_uri().'/js/jquery.mousewheel.js', array( 'jquery' ) );
        wp_enqueue_script( 'jquery.mousewheel' );

        wp_register_script( 'jquery.jscrollpane', get_template_directory_uri().'/js/jquery.jscrollpane.min.js', array( 'jquery.mousewheel' ) );
        wp_enqueue_script( 'jquery.jscrollpane' );

		wp_register_script( 'site', get_template_directory_uri().'/js/site.js', array( 'jquery.jscrollpane' ) );
		wp_enqueue_script( 'site' );

        /* style */
        wp_register_style( 'jquery.jscrollpane', get_template_directory_uri().'/css/jquery.jscrollpane.css', '', '', 'screen' );
        wp_enqueue_style( 'jquery.jscrollpane' );

		wp_register_style( 'screen', get_template_directory_uri().'/style.css', '', '', 'screen' );
        wp_enqueue_style( 'screen' );
	}

    function works_archives_script(){
        wp_register_script( 'works-archives', get_template_directory_uri().'/js/works-archives.js', array( 'site' ) );
        wp_enqueue_script( 'works-archives' );
    }
    function works_single_script(){
        wp_register_script( 'works-single', get_template_directory_uri().'/js/works-single.js', array( 'site' ) );
        wp_enqueue_script( 'works-single' );
    }

	/* ========================================================================================================================
	
	Comments
	
	======================================================================================================================== */

	/**
	 * Custom callback for outputting comments 
	 *
	 * @return void
	 * @author Keir Whitaker
	 */
	function starkers_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; 
		?>
		<?php if ( $comment->comment_approved == '1' ): ?>	
		<li>
			<article id="comment-<?php comment_ID() ?>">
				<?php echo get_avatar( $comment ); ?>
				<h4><?php comment_author_link() ?></h4>
				<time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
				<?php comment_text() ?>
			</article>
		<?php endif; ?>
		</li>
		<?php 
	}

    /**
     *  カスタム投稿タイプ、worksを追加する
     */
    add_action('init', 'add_works_post_type');
    function add_works_post_type()
    {
        $labels = array(
            'name' => 'Works',
            'singular_name' => 'Work'
        );
        $args = array(
            'public' => true,
            'labels' => $labels,
            'has_archive' => true,
            'rewrite' => array('slug' => 'works'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
            'taxonomies' => array('category', 'post_tag')
        );
        register_post_type('work',$args);
    }

    function echo_no_image_thumb(){
        echo '<img src="' , get_template_directory_uri() , '/img/noimage.png" alt="no-image" />';
    }


    /**
     *  カテゴリを元にworkに関する各種情報を表示するため、整形して $post 変数に入れておく
     */
    add_action('the_post', 'attach_categories');
    function attach_categories()
    {
        global $post;
        $slugs = array('env', 'part', 'type');
        $catID_slugs = array();
        foreach($slugs as $slug ){
            $catid =  get_cat_ID_by_slug($slug);
            $catID_slugs[$catid] = $slug;
        }
        $catIDs = array_keys($catID_slugs);

        $params = array();
        foreach( get_the_category() as $cat ){
            if(in_array( $cat->category_parent, $catIDs )){
                $params[$catID_slugs[$cat->category_parent]][] = $cat;
            }
        }
        $post->work_params = $params;
    }

    /**
     * カテゴリのスラッグからカテゴリIDを引く
     *
     * @param string $slug   :slug of category
     * @return numeric       :category_id
     */
    function get_cat_ID_by_slug($slug){
        $cat = get_category_by_slug($slug);
        return $cat->cat_ID;
    }

    /**
     * 種類、環境、担当箇所、などworkに関する各種情報を出力する
     *
     * @param string $param_type    : [env,part,type] のいずれか
     */
    function get_the_work_params($param_type){
        global $post;
        $params = $post->work_params[$param_type];
        return $params;
    }

    /**
     * 種類、環境、担当箇所、などworkに関する各種情報を出力する
     *
     * @param string $param_type    : [env,part,type] のいずれか
     */
    function get_the_work_param_string($param_type, $divided = ', '){
        $params = get_the_work_params($param_type);
        $paramArray = array();
        foreach( $params as $param ){
            $paramArray[] = $param->cat_name;
        }
        return implode($divided, $paramArray);
    }

    /**
     * ループ中、現在のポストのカスタムフィールドの値を一つだけ取ってくる
     *
     * @param $key : カスタムフィールドのキー
     */
    function get_the_custom_value($key){
        global $post;
        return array_shift(get_post_custom_values( $key, $post->ID));
    }


