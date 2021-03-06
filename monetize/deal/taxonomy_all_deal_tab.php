<?php get_header();

global $wp_query, $post;
	
	$current_term = $wp_query->get_queried_object();	
	if( $current_term->name)
	{
		$ptitle = $current_term->name; 
		
	} else {
		$ptitle = DEAL_TITLE;
	} 
if ( get_option('ddbwpthemes_breadcrumbs' )) {  
$sep_array = get_option('yoast_breadcrumbs');
$sep = $sep_array['sep'];
?>
    <div class="breadcrumb clearfix">
         <div class="breadcrumb_in"><a href="<?php echo site_url(); ?>"><?php echo HOME;?></a> <?php echo $sep; ?> <?php _e($ptitle,'ddb_wp'); ?></div>
    </div>
<?php } ?>
<div  class="<?php DDBWP_content_css();?>" >
<?php DDBWP_page_title_above(); //page title above action hook?>
<script type="text/javascript" src="<?php echo DDB_PUGIN_URL;?>/js/timer.js"></script>
  <!--  CONTENT AREA START -->
  <div class="content-title">
   
    <?php echo DDBWP_page_title_filter($ptitle); //page tilte filter?> </div>
	<?php 
	if ( force_ssl_admin() && !is_ssl() ) {
		if ( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ) {
			$main_url = preg_replace('|^http://|', 'https://', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			
		} else { 
			$main_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
		}
	}
	
	if(strstr($main_url,'?')) {
		$main_url = substr($main_url, 0,strrpos($main_url, '&dwtype') );
	} else {
		$main_url = substr($main_url, 0 ,strrpos($main_url, '?dwtype') );
	}
	
	if(strstr($main_url,'?')) {
	
		$all_deal_querystr = "&amp;dwtype=taxonomy_all_deal_tab";
	
		$live_deal_querystr = "&amp;dwtype=taxonomy_live_deal_tab";
	
		$expired_deal_querystr = "&amp;dwtype=taxonomy_expired_deal_tab";
	
	}else	{
		
			$all_deal_querystr = "?dwtype=taxonomy_all_deal_tab";
		
			$live_deal_querystr = "?dwtype=taxonomy_live_deal_tab";
		
			$expired_deal_querystr = "?dwtype=taxonomy_expired_deal_tab";
		
	}
	

?>
  <div id="loop" class="<?php if (get_option('ptttheme_view_opt') == 'Grid View') {  echo 'grid'; }else{ echo 'list clear'; } ?> ">
    <ul class="tabbernav">
		<li class="tabberactive">
			<a href="<?php echo $main_url.$all_deal_querystr ;?>" title="<?php echo ALL_DEAL; ?>"><?php echo ALL_DEAL; ?></a>
		</li>
		<li class="">
			<a href="<?php echo $main_url.$live_deal_querystr ;?>" title="<?php echo LIVE_DEAL; ?>"><?php echo LIVE_DEAL; ?></a>
		</li>
		<li class="">
			<a href="<?php echo $main_url.$expired_deal_querystr ;?>" title="<?php echo EXPIRED_DEAL; ?>"><?php echo EXPIRED_DEAL; ?></a>
		</li>
	</ul>
	<div class="tabbertab">
	<?php include_once('all_deal.php');?>
	</div>
  </div>
  
  <!--  CONTENT AREA END -->
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
