<?php 
 	
	$targetpage = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$targetpage = substr($targetpage, 0 ,strrpos($targetpage,'&pagination'));
	if(strstr($targetpage,'?')){
		$target_querystr = "&amp;dwtype=taxonomy_live_deal_tab";
		
	}else	{
		$target_querystr = "?dwtype=taxonomy_live_deal_tab";
	}
	
	$deal_targetpage = $targetpage.$target_querystr;
	$postmeta_db_table_name = $wpdb->prefix . "postmeta";
	$post_db_table_name = $wpdb->prefix . "posts";
	
	if($current_term->term_id != '') {
		$sqlsql = " and p.ID in (select tr.object_id from $wpdb->term_relationships tr join $wpdb->term_taxonomy t on t.term_taxonomy_id=tr.term_taxonomy_id where t.term_id in ($current_term->term_id)  )";
	}
	$all_total_deals = mysql_query("select distinct p.ID,p.* from $post_db_table_name p ,$postmeta_db_table_name pm where (p.post_type = 'seller' and p.post_status = 'publish') and ((pm.meta_key = 'is_expired' and pm.meta_value = '0') or (pm.meta_key = 'is_expired' and  pm.meta_value = '')) and p.ID = pm.post_id  $sqlsql ORDER BY p.ID DESC ");
	$all_total_pages = mysql_num_rows($all_total_deals);
	$recordsperpage = get_option('posts_per_page');
	$recordsperpage=empty($recordsperpage)?5:$recordsperpage;
	$all_pagination = $_REQUEST['pagination'];
	if($all_pagination == '') {
		$all_pagination = 1;
	}
	$strtlimit = ($all_pagination-1)*$recordsperpage;
	$endlimit = $strtlimit+$recordsperpage;
	$sql = "Select distinct p.ID,p.* from $post_db_table_name p ,$postmeta_db_table_name pm where (p.post_type = 'seller' and p.post_status = 'publish') and ((pm.meta_key = 'is_expired' and pm.meta_value = '0') or (pm.meta_key = 'is_expired' and  pm.meta_value = '')) and p.ID = pm.post_id  $sqlsql ORDER BY p.ID DESC  limit $strtlimit,$recordsperpage";
	$all_dealcnt_sql = $wpdb->get_results($sql);
	
	
	$pcount = 0;
	if($all_total_pages > 0 ) {
		foreach( $all_dealcnt_sql as $post ){
			
				$pcount ++;
				if(get_post_meta($post->ID,'is_expired',true) =='0' || get_post_meta($post->ID,'no_of_coupon',true)!= $sellsqlinfo || date("Y-m-d H:i:s") >= date("Y-m-d H:i:s",get_post_meta($post->ID,'coupon_end_date_time',true)))
					{
					if(get_post_meta($post->ID,'enddate',true)!='0')
						deal_expire_process($post->ID); 
					$coupon_website= get_post_meta($post->ID,'coupon_website',true);
					$owner_name= get_post_meta($post->ID,'owner_name',true);
					$our_price= get_post_meta($post->ID,'our_price',true);
					$current_price= get_post_meta($post->ID,'current_price',true);
					$sellsql = "select count(*) from $transection_db_table_name where post_id=".$post->ID." and status=1";
					$totdiff = $current_price - $our_price;
					$percent = $totdiff * 100;
					$percentsave = $percent/$current_price;
					$sellsqlinfo = $wpdb->get_var($sellsql);
					
					if(get_post_meta($post->ID,'coupon_end_date_time',true)){
						$date = get_post_meta($post->ID,'coupon_end_date_time',true);	
						$tardate= date("F d, Y H:i:s",get_post_meta($post->ID,'coupon_end_date_time',true));
						$tardate1= date("F d, Y",get_post_meta($post->ID,'coupon_end_date_time',true));
					}
					$stdate= date("F d, Y H:i:s",get_post_meta($post->ID,'coupon_start_date_time',true));
					
					
					$no_of_coupon = get_post_meta($post->ID,'no_of_coupon',true);
					if(get_post_meta($post->ID,'coupon_end_date_time',true) != "") { //Donothing
					}
					else {
				// Do nothing
			}
			if(date("Y-m-d H:i:s") >= $tardate1 && get_post_meta($post->ID,'enddate',true) != '0') {
				
				if(get_post_meta($post->ID,'is_expired',true)== '0' || get_post_meta($post->ID,'is_expired',true)== '')	{
					update_post_meta($post->ID,'is_expired','1');
				}
			}
					
					?> 
               

<div <?php post_class('post posts_deals'); ?> id="livedealm_<?php the_ID(); ?>" > 			   
            
                <div class="product_image">
               <a href="<?php the_permalink(); ?>">
					<?php 
							if(get_post_meta($post->ID,'file_name',true) != "") {?>
							<img src="<?php echo DDBWP_thumbimage_filter(get_post_meta($post->ID,'file_name',true),'&amp;w=165&amp;h=180&amp;zc=1&amp;q=80');?>" alt="" />
						<?php }else { ?>
							<img src="<?php echo DDBWP_thumbimage_filter(DDB_PUGIN_URL."/images/no-image.png",'&amp;w=165&amp;h=180&amp;zc=1&amp;q=80');?>" alt="" />
						<?php } ?>
					<?php }?>	
					
				</a>
                </div>
				<div class="product_image grid_img">
				<a href="<?php the_permalink(); ?>">
					<?php if(get_post_meta($post->ID,'file_name',true) != "") { ?>
							<img src="<?php echo DDBWP_thumbimage_filter(get_post_meta($post->ID,'file_name',true),'&amp;w=280&amp;h=180&amp;zc=1&amp;q=80');?>" alt="" />
						<?php } else {	?>
							 <div class="noimg"><?php _e('Image  <br /> not available','ddb_wp');?></div>
						<?php } ?>
												
				</a>
                </div>
		         <div class="content_right content_right_inner">
				    <?php if(get_option('ddbwpthemes_listing_author') != 'No') {?>        
                   <span class="title_grey"><?php echo PROVIDE_BY;?> </span>
					<?php
					$user_db_table_name = get_user_table();
					$user_data = $wpdb->get_row("select * from $user_db_table_name where ID = '".$post->post_author."'");
					 ?>
					
					<a href="<?php echo get_author_posts_url($post->post_author);?>" class="top_lnk" title="<?php echo $user_data->display_name;?>"><?php echo get_post_meta($post->ID,'owner_name',true);?></a>
					<?php } ?>
					<h3><a href="<?php the_permalink(); ?>"><?php echo $post->post_title; ?></a></h3>
                    
                     <div class="grid_price"><span class="strike_rate"><?php _e(OUR_PRICES,'ddb_wp');?> <s><?php echo get_post_currency_sym($post->ID);?><?php echo $current_price;?></s></span> <span class="rate"><?php _e(OFFER_PRICE,'ddb_wp');?> <?php echo get_post_currency_sym($post->ID);?><?php echo $our_price;?></span> </div>
                    
	                 <?php 
					if(date("Y-m-d H:i:s")>= $tardate1 && get_post_meta($post->ID,'enddate',true) == '') {
						// Donothing
                    }
					else
				    {	
						if(get_post_meta($post->ID,'coupon_end_date_time',true) && date("Y-m-d H:i:s") <= $tardate1){ ?> 
							<div class="deal_time_box">
								<div class="time_line"></div>
								<div id="countdowncontainerlive_<?php _e($post->ID,'ddb_wp'); ?>"></div>
								<script type="text/javascript">
								var dealexpire=new cdtime("countdowncontainerlive_<?php _e($post->ID,'ddb_wp'); ?>", "<?php echo $tardate; ?>")
								dealexpire.displaycountdown("days", formatresults)
								</script>
								<div class="fr">
								   <div class="price_main">
								   <span class="strike_rate"><?php echo get_post_currency_sym($post->ID);?><?php echo $current_price;?></span> 
								   <span class="rate"><?php echo get_post_currency_sym($post->ID);?><?php echo $our_price;?></span> 
								   </div>
			 <?php 				if(get_post_meta($post->ID,'coupon_type',true) == 1) { ?>
									<a href="<?php echo get_post_meta($post->ID,'coupon_link',true); ?>" title="<?php _e(BUY_NOW,'ddb_wp');?>" class="btn_buy" target="_blank"><?php _e(BUY_NOW,'ddb_wp');?></a>
			<?php 				} else { ?>
								<a href="#" title="<?php _e(BUY_NOW,'ddb_wp');?>" class="btn_buy contact"><?php _e(BUY_NOW,'ddb_wp');?></a>
			<?php 				}?>
							   </div>
                            </div>
            <?php 		} ?>
			<?php	}?>
                    
                    <ul class="rate_summery">
                        <li class="rate_current_price"><span><?php echo CURRENT_PRICE;?></span> 
                        <strong><small><?php echo get_post_currency_sym($post->ID);?></small><?php echo $current_price;?></strong></li>
                        <li class="rate_our_price"><span><?php echo OUR_PRICE;?></span> <strong><small><?php echo get_post_currency_sym($post->ID);?></small><?php echo $our_price;?></strong></li>
                        <li class="rate_percentage"><span><?php echo YOU_SAVE;?></span> <strong><?php echo @number_format($percentsave,2);?>%</strong></li>
						<?php if(get_post_meta($post->ID,'shipping_cost',true) > 0 ) { ?>
						<li class="rate_our_price"><span>
						<?php echo SHIPPING_COST;?>
						</span> <strong><small><?php echo get_post_currency_sym($post->ID);?></small><?php echo get_post_meta($post->ID,'shipping_cost',true);?></strong></li>
						<?php } ?>
                        <li class="bdr_none rate_item_sold"><span><?php echo ITEMS_SOLD;?></span> <strong><?php echo $sellsqlinfo;?></strong>
						
						</li>
                    </ul>
					
                    
                  	<?php 
					$post_categories = wp_get_post_terms($post->ID,'seller_category');
					if($post_categories[0]  != ""){					
					?>
					  <div class="post_cats clearfix"> 
                       <?php the_taxonomies(array('before'=>'<span class="categories">','sep'=>'</span><span class="tags">','after'=>'</span>')); ?> 
                       </div>
                     <?php } ?>
					
					 <?php 	if(get_post_meta($post->ID,'enddate',true) == '0' && get_option('ptttheme_view_opt') != 'Grid View' && (get_post_meta($post->ID,'status',true) == '1' || get_post_meta($post->ID,'status',true) == '2') ) { ?>
		<?php 		if(get_post_meta($post->ID,'coupon_type',true) == 1) {?>
						<a href="<?php echo get_post_meta($post->ID,'coupon_link',true); ?>" title="<?php echo BUY_NOW;?>" class="btn_buy_deal" target="_blank"><?php echo BUY_NOW;?></a>
		<?php 		} else { ?>
						<a href="#" title="<?php echo BUY_NOW;?>" class="btn_buy_deal contact"><?php echo BUY_NOW;?></a>
		<?php 		}
				}?>
					 <?php if(get_post_meta($post->ID,'is_expired',true) != 1) { 
						 if(get_post_meta($post->ID,'coupon_type',true) == 1) { ?>
								<a href="<?php echo get_post_meta($post->ID,'coupon_link',true); ?>" title="<?php echo BUY_NOW;?>" class="btn_buy_grid" target="_blank"><?php echo BUY_NOW;?></a>
							<?php } else { ?>
								<a href="#" title="<?php echo BUY_NOW;?>" class="btn_buy_grid contact"><?php echo BUY_NOW;?></a>
							<?php }
						} ?>
					
					
					<div class="text_content" id="livecontent_<?php _e($post->ID,'ddb_wp');?>">
                   <?php echo "".$post->post_excerpt."";  ?><a href="<?php the_permalink(); ?>" class="readmore_link"><?php _e(get_option('ddbwpthemes_content_excerpt_readmore'));
						?> </a>
					</div>
				</div> 
            </div> 
  <?php  
		$page_layout = DDBWP_get_page_layout();
			if ($pcount == 2){
				$pcount=0; ?>
                <div class="hr clear"></div>
 <?php 		}
		}
		if($all_total_pages>$recordsperpage){
			echo '<div style="text-align:right" >'.get_pagination($deal_targetpage,$all_total_pages,$recordsperpage,$all_pagination).'</div>';
		}
	} 
		
	 else {
		echo '<h3>'.NO_LIVE_DEAL.'</h3>';
	}?>   		 