<?php

/** ##############################################################################################################################################
 *    AlpineBot Secondary
 * 
 *    Display functions
 *    Contains ONLY UNIVERSAL functions
 * 
 *  ##########################################################################################
 */

class PhotoTileForTumblrBotSecondary extends PhotoTileForTumblrPrimary{     
   
/**
 *  Update global (non-widget) options
 *  
 *  @ Since 1.2.4
 *  @ Updated 1.2.5
 */
  function update_global_options(){
    $options = $this->get_all_options();
    $defaults = $this->option_defaults(); 
    foreach( $defaults as $name=>$info ){
      if( empty($info['widget']) && isset($options[$name])){
        // Update non-widget settings only
        $this->set_active_option($name,$options[$name]);
      }
    }
    // Go ahead and reset info also
    $this->set_private('results', array('photos'=>array(),'feed_found'=>false,'success'=>false,'userlink'=>'','hidden'=>'','message'=>'') );
  }
  
//////////////////////////////////////////////////////////////////////////////////////
///////////////////////      Feed Fetch Functions       //////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

/**
 *  Function for creating cache key
 *  
 *  @ Since 1.2.2
 */
  function key_maker( $array ){
    if( isset($array['name']) && is_array( $array['info'] ) ){
      $return = $array['name'];
      foreach( $array['info'] as $key=>$val ){
        $return = $return."-".(!empty($val)?$val:$key);
      }
      $return = $this->filter_filename( $return );
      return $return;
    }
  }
/**
 *  Filter string and remove specified characters
 *  
 *  @ Since 1.2.2
 */  
  function filter_filename( $name ){
    $name = @ereg_replace('[[:cntrl:]]', '', $name ); // remove ASCII's control characters
    $bad = array_merge(
      array_map('chr', range(0,31)),
      array("<",">",":",'"',"/","\\","|","?","*"," ",",","\'",".")); 
    $return = str_replace($bad, "", $name); // Remove Windows filename prohibited characters
    return $return;
  }
  
//////////////////////////////////////////////////////////////////////////////////////
/////////////////////////      Cache Functions       /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

/**
 * Functions for retrieving results from cache
 *  
 * @ Since 1.2.4
 *
 */
  function retrieve_from_cache( $key ){
    if ( !$this->check_active_option('cache_disable') ) {
      if( $this->cacheExists($key) ) {
        $results = $this->getCache($key);
        $results = @unserialize($results);
        if( count($results) ){
          $results['hidden'] .= '<!-- Retrieved from cache -->';
          $this->set_private('results',$results);
          if( $this->check_active_result('photos') ){
            return true;
          }
        }
      }
    }
    return false;
  }
/**
 * Functions for storing results in cache
 *  
 * @ Since 1.2.4
 *
 */
  function store_in_cache( $key ){
    if( $this->check_active_result('success') && !$this->check_active_option('disable_cache') ){     
      $cache_results = $this->get_private('results');
      if(!is_serialized( $cache_results  )) { $cache_results  = @maybe_serialize( $cache_results ); }
      $this->putCache($key, $cache_results);
      $cachetime = $this->get_option( 'cache_time' );
      if( !empty($cachetime) && is_numeric($cachetime) ){
        $this->setExpiryInterval( $cachetime*60*60 );
      }
    }
  }

/**
 * Functions for caching results and clearing cache
 *  
 * @since 1.1.0
 *
 */
  function setCacheDir($val) {  $this->set_private('cacheDir',$val); }  
  function setExpiryInterval($val) {  $this->set_private('expiryInterval',$val); }  
  function getExpiryInterval($val) {  return (int)$this->get_private('expiryInterval'); }
  
  function cacheExists($key) {  
    $filename_cache = $this->get_private('cacheDir') . '/' . $key . '.cache'; //Cache filename  
    $filename_info = $this->get_private('cacheDir') . '/' . $key . '.info'; //Cache info  
  
    if (file_exists($filename_cache) && file_exists($filename_info)) {  
      $cache_time = file_get_contents ($filename_info) + (int)$this->get_private('expiryInterval'); //Last update time of the cache file  
      $time = time(); //Current Time  
      $expiry_time = (int)$time; //Expiry time for the cache  

      if ((int)$cache_time >= (int)$expiry_time) {//Compare last updated and current time  
        return true;  
      }  
    }
    return false;  
  } 

  function getCache($key)  {  
    $filename_cache = $this->get_private('cacheDir') . '/' . $key . '.cache'; //Cache filename  
    $filename_info = $this->get_private('cacheDir') . '/' . $key . '.info'; //Cache info  
  
    if (file_exists($filename_cache) && file_exists($filename_info))  {  
      $cache_time = file_get_contents ($filename_info) + (int)$this->get_private('expiryInterval'); //Last update time of the cache file  
      $time = time(); //Current Time  

      $expiry_time = (int)$time; //Expiry time for the cache  

      if ((int)$cache_time >= (int)$expiry_time){ //Compare last updated and current time 
        return file_get_contents ($filename_cache);   //Get contents from file  
      }  
    }
    return null;  
  }  

  function putCache($key, $content) {  
    $time = time(); //Current Time  
    $dir = $this->get_private('cacheDir');
    if ( !file_exists($dir) ){  
      @mkdir($dir);  
      $cleaning_info = $dir . '/cleaning.info'; //Cache info 
      @file_put_contents ($cleaning_info , $time); // save the time of last cache update  
    }
    
    if ( file_exists($dir) && is_dir($dir) ){
      $filename_cache = $dir . '/' . $key . '.cache'; //Cache filename  
      $filename_info = $dir . '/' . $key . '.info'; //Cache info  
    
      @file_put_contents($filename_cache ,  $content); // save the content  
      @file_put_contents($filename_info , $time); // save the time of last cache update  
    }
  }
  
  function clearAllCache() {
    $dir = $this->get_private('cacheDir') . '/';
    if(is_dir($dir)){
      $opendir = @opendir($dir);
      while(false !== ($file = readdir($opendir))) {
        if($file != "." && $file != "..") {
          if(file_exists($dir.$file)) {
            $file_array = @explode('.',$file);
            $file_type = @array_pop( $file_array );
            // only remove cache or info files
            if( 'cache' == $file_type || 'info' == $file_type){
              @chmod($dir.$file, 0777);
              @unlink($dir.$file);
            }
          }
          /*elseif(is_dir($dir.$file)) {
            @chmod($dir.$file, 0777);
            @chdir('.');
            @destroy($dir.$file.'/');
            @rmdir($dir.$file);
          }*/
        }
      }
      @closedir($opendir);
    }
  }
  
  function cleanCache() {
    $cleaning_info = $this->get_private('cacheDir') . '/cleaning.info'; //Cache info     
    if (file_exists($cleaning_info))  {  
      $cache_time = file_get_contents ($cleaning_info) + (int)$this->cleaningInterval; //Last update time of the cache cleaning  
      $time = time(); //Current Time  
      $expiry_time = (int)$time; //Expiry time for the cache  
      if ((int)$cache_time < (int)$expiry_time){ //Compare last updated and current time     
        // Clean old files
        $dir = $this->get_private('cacheDir') . '/';
        if(is_dir($dir)){
          $opendir = @opendir($dir);
          while(false !== ($file = readdir($opendir))) {                            
            if($file != "." && $file != "..") {
              if(is_dir($dir.$file)) {
                //@chmod($dir.$file, 0777);
                //@chdir('.');
                //@destroy($dir.$file.'/');
                //@rmdir($dir.$file);
              }
              elseif(file_exists($dir.$file)) {
                $file_array = @explode('.',$file);
                $file_type = @array_pop( $file_array );
                $file_key = @implode( $file_array );
                if( $file_type && $file_key && 'info' == $file_type){
                  $filename_cache = $dir . $file_key . '.cache'; //Cache filename  
                  $filename_info = $dir . $file_key . '.info'; //Cache info   
                  if (file_exists($filename_cache) && file_exists($filename_info)) {  
                    $cache_time = file_get_contents ($filename_info) + (int)$this->cleaningInterval; //Last update time of the cache file  
                    $expiry_time = (int)$time; //Expiry time for the cache  
                    if ((int)$cache_time < (int)$expiry_time) {//Compare last updated and current time  
                      @chmod($filename_cache, 0777);
                      @unlink($filename_cache);
                      @chmod($filename_info, 0777);
                      @unlink($filename_info);
                    }  
                  }
                  /*elseif (file_exists($filename_cache) && file_exists($filename_info)) {  
                    $cache_time = file_get_contents ($filename_info) + (int)$this->cleaningInterval; //Last update time of the cache file  
                    $expiry_time = (int)$time; //Expiry time for the cache  
                    if ((int)$cache_time < (int)$expiry_time) {//Compare last updated and current time  
                      @chmod($filename_cache, 0777);
                      @unlink($filename_cache);
                      @chmod($filename_info, 0777);
                      @unlink($filename_info);
                    } 
                  }*/
                }
              }
            }
          }
          @closedir($opendir);
        }
        @file_put_contents ($cleaning_info , $time); // save the time of last cache cleaning        
      }
    }
  } 
  
  /*
  function putCacheImage($image_url){
    $time = time(); //Current Time  
    if ( ! file_exists($this->cacheDir) ){  
      @mkdir($this->cacheDir);  
      $cleaning_info = $this->cacheDir . '/cleaning.info'; //Cache info 
      @file_put_contents ($cleaning_info , $time); // save the time of last cache update  
    }
    
    if ( file_exists($this->cacheDir) && is_dir($this->cacheDir) ){ 
      //replace with your cache directory
      $dir = $this->cacheDir.'/';
      //get the name of the file
      $exploded_image_url = explode("/",$image_url);
      $image_filename = end($exploded_image_url);
      $exploded_image_filename = explode(".",$image_filename);
      $name = current($exploded_image_filename);
      $extension = end($exploded_image_filename);
      //make sure its an image
      if($extension=="gif"||$extension=="jpg"||$extension=="png"){
        //get the remote image
        $image_to_fetch = @file_get_contents($image_url);
        //save it
        $filename_image = $dir . $image_filename;
        $filename_info = $dir . $name . '.info'; //Cache info  
      
        $local_image_file = @fopen($filename_image, 'w+');
        @chmod($dir.$image_filename,0755);
        @fwrite($local_image_file, $image_to_fetch);
        @fclose($local_image_file);
        
        @file_put_contents($filename_info , $time); // save the time of last cache update  
      }
    }
  }
  
  function getImageCache($image_url)  {  
    $dir = $this->cacheDir.'/';
  
    $exploded_image_url = explode("/",$image_url);
    $image_filename = end($exploded_image_url);
    $exploded_image_filename = explode(".",$image_filename);
    $name = current($exploded_image_filename);  
    $filename_image = $dir . $image_filename;
    $filename_info = $dir . $name . '.info'; //Cache info  
  
    if (file_exists($filename_image) && file_exists($filename_info))  {  
      $cache_time = @file_get_contents ($filename_info) + (int)$this->expiryInterval; //Last update time of the cache file  
      $time = time(); //Current Time  

      $expiry_time = (int)$time; //Expiry time for the cache  

      if ((int)$cache_time >= (int)$expiry_time){ //Compare last updated and current time 
        return $this->cacheUrl.'/'.$image_filename;   // Return image URL
      }else{
        $local_image_file = @fopen($filename_image, 'w+');
        @chmod($dir.$image_filename,0755);
        @fwrite($local_image_file, $image_to_fetch);
        @fclose($local_image_file);
        
        @file_put_contents($filename_info , $time); // save the time of last cache update  
      }
    }elseif( $this->cacheAttempts < $this->cacheLimit ){
      $this->putCacheImage($image_url);
      $this->cacheAttempts++;
    }
    return null;  
  }  
  */
}

/** ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *   
 *    AlpineBot Tertiary
 * 
 *    Display functions
 *    Contains ONLY UNIQUE functions
 * 
 *  ##########################################################################################
 */
 
class PhotoTileForTumblrBotTertiary extends PhotoTileForTumblrBotSecondary{ 
 
  // For Reference:
  // http://www.tumblr.com/services/api/response.json.html
  // sq = thumbnail 75x75
  // t = 100 on longest side
  // s = 240 on longest side
  // n = 320 on longest side
  // m = 500 on longest side
  // z = 640 on longest side
  // c = 800 on longest side
  // b = 1024 on longest side*
  // o = original image, either a jpg, gif or png, depending on source format**
  // *Before May 25th 2010 large photos only exist for very large original images.
  // **Original photos behave a little differently. They have their own secret (called originalsecret in responses) and a variable file extension (called originalformat in responses). These values are returned via the API only when the caller has permission to view the original size (based on a user preference and various other criteria). The values are returned by the tumblr.photos.getInfo method and by any method that returns a list of photos and allows an extras parameter (with a value of original_format), such as tumblr.photos.search. The tumblr.photos.getSizes method, as always, will return the full original URL where permissions allow.

//////////////////////////////////////////////////////////////////////////////////////
//////////////////        Unique Feed Fetch Functions        /////////////////////////
//////////////////////////////////////////////////////////////////////////////////////    

/**
 * Alpine PhotoTile for Tumblr: Photo Retrieval Function.
 * The PHP for retrieving content from Tumblr.
 *
 * @ Since 1.0.0
 * @ Updated 1.2.5
 */  
  function photo_retrieval(){
    $tumblr_options = $this->get_private('options');
    $defaults = $this->option_defaults();

    $key_input = array(
      'name' => 'tumblr',
      'info' => array(
        'vers' => $this->get_private('vers'),
        'src' => (isset($tumblr_options['tumblr_source'])?$tumblr_options['tumblr_source']:''),
        'uid' => (isset($tumblr_options['tumblr_user_id'])?$tumblr_options['tumblr_user_id']:''),
        'curl' => (isset($tumblr_options['tumblr_custom_url'])?$tumblr_options['tumblr_custom_url']:''),
        'num' => (isset($tumblr_options['tumblr_photo_number'])?$tumblr_options['tumblr_photo_number']:''),
        'link' => (isset($tumblr_options['tumblr_display_link'])?$tumblr_options['tumblr_display_link']:''),
        'text' => (isset($tumblr_options['tumblr_display_link_text'])?$tumblr_options['tumblr_display_link_text']:''),
        'size' => (isset($tumblr_options['tumblr_photo_size'])?$tumblr_options['tumblr_photo_size']:'')
        )
      );
    $key = $this->key_maker( $key_input );  // Make Key
    if( $this->retrieve_from_cache( $key ) ){  return; } // Check Cache
    $this->set_size_id(); // Set image size (translate size to Tumblr id)
    
    // Try without API key first
    if( function_exists('simplexml_load_file') ) {
      $this->append_active_result('hidden','<!-- Using AlpinePT for Tumblr v'.$this->get_private('ver').' with Tumblr API V1 XML -->');
      $this->try_simplexml();
    }
    
    if( !$this->check_active_result('success') &&  function_exists('json_decode') ) {
      $this->append_active_result('hidden','<!-- Using AlpinePT for Tumblr v'.$this->get_private('ver').' with Tumblr API V1 JSON -->');
      $this->try_json();
    }

    // Try with API key
    if ( !$this->check_active_result('success') && function_exists('json_decode') ) {
      // Please respect the Tumblr Developer Agreement and do not reuse my api key.
      $this->set_active_option('api_key','GhKB8A19ZFhO3rWpBhjKfJUistNDgQwIYu6tHlzzg4pPT3WZwH');
      $this->append_active_result('hidden','<!-- Using AlpinePT for Tumblr v'.$this->get_private('ver').' with Tumblr API V2 with API KEY -->');
      $this->try_json_v2();
    }

    if( $this->check_active_result('success') ){
      $src = $this->get_private('src');    
      if( $this->check_active_result('userlink') && $this->check_active_option($src.'_display_link') && $this->check_active_option($src.'_display_link_text') && 'community' != $this->get_active_option($src.'_source') ){
        $linkurl = $this->get_active_result('userlink');
        $link = '<div class="AlpinePhotoTiles-display-link" >';
        $link .='<a href="'.$linkurl.'" target="_blank" >';
        $link .= $this->get_active_option($src.'_display_link_text');
        $link .= '</a></div>';
        $this->set_active_result('userlink',$link);
      }else{
        $this->set_active_result('userlink',null);
      }
    }else{
      if( $this->check_active_result('feed_found') ){
        $this->append_active_result('message','- Tumblr feed was successfully retrieved, but no photos found.');
      }else{
        $this->append_active_result('message','- Please recheck your ID(s).');
      }
    }
    
    //$this->results = array('continue'=>$this->success,'message'=>$this->message,'hidden'=>$this->hidden,'photos'=>$this->photos,'user_link'=>$this->userlink);

    $this->store_in_cache( $key );  // Store in cache

  }
/**
 *  Function for forming Tumblr request
 *  
 *  @ Since 1.2.4
 *  @ Updated 1.2.5
 */ 
  function get_tumblr_request($format='xmlv1'){
    $options = $this->get_private('options');
    $num = $options['tumblr_photo_number'];
    if( !empty($options['photo_feed_shuffle']) && function_exists('shuffle') ){ // Shuffle the results
      $num = min(150,$num*4);
    }
    $url = '';
    if( $options['tumblr_source'] == 'custom' || $options['tumblr_source'] == 'custom_tag' ){
      // Check for shortcode mistake (2 curl's)
      $url = (empty($options['tumblr_custom_url']) ? (empty($options['custom_link_url'])?'':$options['custom_link_url']) : $options['tumblr_custom_url']);
      $url = str_replace(array('/',' '),'',$url);
      $url = str_replace('http:','',$url );
      $this->set_active_result('userlink', ('http://'.$url) );
    }else{
      $tumblr_uid = empty($options['tumblr_user_id']) ? 'uid' : $options['tumblr_user_id'];
      $tumblr_uid = str_replace(array('/',' '),'',$tumblr_uid);
      $tumblr_uid = str_replace('http:','',$tumblr_uid );
      $tumblr_uid = str_replace('.tumblr.com','',$tumblr_uid);
      $url = $tumblr_uid.'.tumblr.com';
      $this->set_active_result('userlink', ('http://'.$url) );
    }
    $request = false;

    if( 'jsonv2'==$format && !empty( $options['api_key'] ) ){
      $key = $options['api_key'];
      
      if( $options['tumblr_source'] == 'user' || $options['tumblr_source'] == 'custom' ){
        $request = 'http://api.tumblr.com/v2/blog/'.$url.'/posts/photo?api_key='.$key;
      }elseif( $options['tumblr_source'] == 'user_tag' || $options['tumblr_source'] == 'custom_tag' ){
        if( $this->check_active_option('tumblr_tag') ){
          $tag = $this->get_active_option('tumblr_tag');
          $request = 'http://api.tumblr.com/v2/blog/'.$url.'/posts/photo?api_key='.$key.'&tag='.$tag;
        }else{
          $request = 'http://api.tumblr.com/v2/blog/'.$url.'/posts/photo?api_key='.$key;
        }       
      } 
    }elseif( 'xmlv1'==$format ){
      $num = min($num,50); // Max of 50

      if( $options['tumblr_source'] == 'user' || $options['tumblr_source'] == 'custom' ){
        $request = 'http://' . $url . '/api/read?num=' .$num. '&type=photo&filter=text';
      }elseif( $options['tumblr_source'] == 'user_tag' || $options['tumblr_source'] == 'custom_tag' ){
        if( $this->check_active_option('tumblr_tag') ){
          $tag = $this->get_active_option('tumblr_tag');
          $request = 'http://' . $url . '/api/read?num=' .$num. '&type=photo&filter=text&tagged='.$tag;
        }else{
          $request = 'http://' . $url . '/api/read?num=' .$num. '&type=photo&filter=text';
        }       
      } 
    }elseif( 'jsonv1'==$format ){
      $num = min($num,50); // Max of 50
      
      if( $options['tumblr_source'] == 'user' || $options['tumblr_source'] == 'custom' ){
        $request = 'http://' . $url . '/api/read/json?num=' .$num. '&type=photo&filter=text';
      }elseif( $options['tumblr_source'] == 'user_tag' || $options['tumblr_source'] == 'custom_tag' ){
        if( $this->check_active_option('tumblr_tag') ){
          $tag = $this->get_active_option('tumblr_tag');
          $request = 'http://' . $url . '/api/read/json?num=' .$num. '&type=photo&filter=text&tagged='.$tag;
        }else{
          $request = 'http://' . $url . '/api/read/json?num=' .$num. '&type=photo&filter=text';
        }       
      }
    }
    return $request;
 }
/**
 *  Determine image size id
 *  
 *  @ Since 1.2.4
 *  @ Updated 1.2.5
 */
  function set_size_id(){
    $this->set_active_option('size_id',1); // Default is 500 (also catches 640px)

    switch ($this->get_active_option('tumblr_photo_size')) {
      case 75:
        $this->set_active_option('size_id',5);
      break;
      case 100:
        $this->set_active_option('size_id',4);
      break;
      case 240:
        $this->set_active_option('size_id',3);
      break;  
      case 250:
        $this->set_active_option('size_id',3);
      break;
      case 400: // 400 is not actually given as option
        $this->set_active_option('size_id',2);
      break;
      case 500:
        $this->set_active_option('size_id',1);
      break;
      case 1280:
        $this->set_active_option('size_id',0);
      break;
    }
  }
    
/**
 *  Function for making Tumblr request with API v1
 *  
 *  @ Since 1.2.4
 *  @ Updated 1.2.6
 */
  function try_simplexml(){
  
    $request = $this->get_tumblr_request('xmlv1');
    
    $this->append_active_result('hidden','<!-- Request made -->');
    // XML doesn't seem to care if "www" is present or not
    $_tumblr_request  = @urlencode( $request );	// just for compatibility
    $_tumblr_xml = @simplexml_load_file( $_tumblr_request); // @ is shut-up operator

    if($_tumblr_xml===false){ 
      $this->append_active_result('hidden','<!-- Failed using simplexml_load_file() and XML @ '.$request.' -->');
      $this->set_active_result('success',false);
    }else{
      $num = $this->get_active_option('tumblr_photo_number');
      if( $this->check_active_option('photo_feed_shuffle') && function_exists('shuffle') ){ // Shuffle the results
        $num = min(150,$num*4);
      }
      $repeats = floor($num/50); // Max of 50 retrieved in each call
      $size_id = $this->get_active_option('size_id');    
      $size = $this->get_active_option('tumblr_photo_size');
      $s = 0; // simple counter
      $photos = array();
      for($loop=0;$loop<=$repeats;$loop++){
        if( $s>=$num ){
          break;
        }else{
          if( !empty($_tumblr_xml) && !empty($_tumblr_xml->posts[0]) && !empty($_tumblr_xml->posts[0]->post) ) {
            foreach( $_tumblr_xml->posts[0]->post as $p ) {
              if( $s<$num ){     
                $the_photo = array();
                // list of link urls
                $the_photo['image_link'] = isset($p['url'])?(string) $p['url']:'';
                // list of photo urls
                $the_photo['image_source'] = isset($p->{'photo-url'}[$size_id])?(string) $p->{'photo-url'}[$size_id]:(isset($p->{'photo-url'}[0])?(string) $p->{'photo-url'}[0]:'');

                $the_photo['image_original'] = isset($p->{'photo-url'}[0])?(string) $p->{'photo-url'}[0]:(isset($p->{'photo-url'}[1])?(string) $p->{'photo-url'}[1]:'');
                
                //$the_photo['image_title'] = isset($p['slug'])?(string) $p['slug']:'';
                $the_photo['image_title'] = isset($p->{'photo-caption'})?(string) $p->{'photo-caption'}:'';
                $the_photo['image_title'] = str_replace(array("'","\r\n", "\r","\n"),'',$the_photo['image_title']);
                $the_photo['image_caption'] = '';
                
                // Handle posts with multiple photos (photoset)
                if( isset( $p->{'photoset'} ) ){
                  $photoset = $p->{'photoset'};
                  if( isset( $photoset->{'photo'} ) ){
                    foreach( $photoset->{'photo'} as $each ){
                      $the_photo['image_source'] = isset($each->{'photo-url'}[$size_id])?(string) $each->{'photo-url'}[$size_id]:(isset($each->{'photo-url'}[0])?(string) $each->{'photo-url'}[0]:'');
                      $the_photo['image_original'] = isset($each->{'photo-url'}[0])?(string) $each->{'photo-url'}[0]:(isset($each->{'photo-url'}[1])?(string) $each->{'photo-url'}[1]:$the_photo['image_source']);
                      $this->push_photo( $the_photo );
                      $s++;
                    }
                  }
                }else{
                  $this->push_photo( $the_photo );
                  $s++;
                }
              }else{
                break;
              }
            }
          }
          // Try another request
          if($loop<$repeats && $s<$num){
            $next_request = $request.'&start='.(($loop+1)*50);
            $this->append_active_result('hidden','<!-- Request made -->');
            $_tumblr_request  = @urlencode( $next_request );	// just for compatibility
            $_tumblr_xml = @simplexml_load_file( $_tumblr_request); // @ is shut-up operator
            if($_tumblr_xml===false){ 
              $this->append_active_result('hidden','<!-- Failed on loop '.$loop.' with '.$next_request.' -->');
              $loop = $repeats;
            }          
          }
        }
      }
      if( $this->check_active_result('photos') ){
        $this->set_active_result('success',true);
        $this->set_active_result('feed_found',true);
        $this->append_active_result('hidden','<!-- Success using simplexml_load_file() and XML -->');
      }else{
        $this->set_active_result('success',false);
        $this->set_active_result('feed_found',true);
        $this->append_active_result('hidden','<!-- No photos found using simplexml_load_file() and XML @ '.$request.' -->');
      }
    }
  }
/**
 * Alpine PhotoTile for Tumblr: Photo Retrieval Function
 * The PHP for retrieving content from Tumblr.
 *
 * @ Since 1.0.0
 * @ Updated 1.2.3.1
 */
  function fetch_tumblr_feed($request){
    // No longer write out curl_init and user WP API instead
    $response = wp_remote_get($request,
      array(
        'method' => 'GET',
        'timeout' => 20
      )
    );
    if( is_wp_error( $response ) || !isset($response['body']) ) {
      return false;
    }else{
      return $response['body'];
    }
  }
/**
 *  Function for making Tumblr request with API v1 with JSON
 *  
 *  @ Since 1.2.4
 *  @ Updated 1.2.6
 */
  function try_json(){
    $request = $this->get_tumblr_request('jsonv1');

    $this->append_active_result('hidden','<!-- Request made -->');
    $result = $this->fetch_tumblr_feed($request);
    $result = str_replace('var tumblr_api_read = ','',$result);
    $result = str_replace(';','',$result);
    $_tumblr_json = array();
    if( !empty($result) ){ $_tumblr_json = @json_decode( $result, true ); }
    
    if( empty($_tumblr_json) || empty($_tumblr_json['tumblelog']) || empty($_tumblr_json['posts']) ){
      $this->append_active_result('hidden','<!-- Failed using wp_remote_get() and JSON @ '.$request.' -->');
      $this->set_active_result('success',false);
    }else{
      $num = $this->get_active_option('tumblr_photo_number');
      if( $this->check_active_option('photo_feed_shuffle') && function_exists('shuffle') ){ // Shuffle the results
        $num = min(150,$num*4);
      }
      $repeats = floor($num/50); // Maximum number of repeats
      $size_id = 'photo-url-'.$this->get_active_option('tumblr_photo_size').'';
      $s = 0; // simple counter
      $photos = array();
      for($loop=0;$loop<=$repeats;$loop++){
        if( $s>=$num ){
          break;
        }else{
          if( !empty($_tumblr_json) && !empty($_tumblr_json['tumblelog']) && !empty($_tumblr_json['posts']) ) {
            foreach( $_tumblr_json['posts'] as $p ) {
              if( $s<$num && 'photo' == $p['type'] ){     
                $the_photo = array();
                // list of link urls
                $the_photo['image_link'] = isset($p['url'])?(string) $p['url']:'';
                // list of photo urls
                $the_photo['image_source'] = isset($p[$size_id])?(string) $p[$size_id]:(isset($p['photo-url-500'])?(string) $p['photo-url-500']:(isset($p['photo-url-400'])?(string) $p['photo-url-400']:''));

                $the_photo['image_original'] = isset($p['photo-url-1280'])?(string) $p['photo-url-1280']:(isset($p['photo-url-500'])?(string) $p['photo-url-500']:(isset($p['photo-url-400'])?(string) $p['photo-url-400']:$the_photo['image_source']));

                //$the_photo['image_title'] = isset($p['slug'])?(string) $p['slug']:'';
                $the_photo['image_title'] = isset($p['photo-caption'])?(string) $p['photo-caption']:'';
                $the_photo['image_title'] = str_replace(array("'","\r\n", "\r","\n"),'',$the_photo['image_title']);
                $the_photo['image_caption'] = '';
                
                // Handle posts with multiple photos (photoset)
                if( isset( $p['photos'] ) && count($p['photos'])>1 ){
                  foreach( $p['photos'] as $each ){
                    $the_photo['image_source'] = isset($each[$size_id])?(string) $each[$size_id]:(isset($each['photo-url-500'])?(string) $each['photo-url-500']:(isset($each['photo-url-400'])?(string) $each['photo-url-400']:''));
                    $the_photo['image_original'] = isset($each['photo-url-1280'])?(string) $each['photo-url-1280']:(isset($each['photo-url-500'])?(string) $each['photo-url-500']:(isset($each['photo-url-400'])?(string) $each['photo-url-400']:$the_photo['image_source']));
                    $this->push_photo( $the_photo );
                    $s++;
                  }
                }else{
                  $this->push_photo( $the_photo );
                  $s++;
                }
              }else{
                break;
              }
            }
          }
          // Try another request
          if($loop<$repeats && $s<$num){
            $next_request = $request.'&start='.(($loop+1)*50);
            $this->append_active_result('hidden','<!-- Request made -->');
            $result = $this->fetch_tumblr_feed($next_request);
            $result = str_replace('var tumblr_api_read = ','',$result);
            $result = str_replace(';','',$result);
            $_tumblr_json = array();
            if( !empty($result) ){ $_tumblr_json = @json_decode( $result, true ); }
            if( empty($_tumblr_json) ){ 
              $this->append_active_result('hidden','<!-- Failed on loop '.$loop.' with '.$next_request.' -->');
              $loop = $repeats;
            }          
          }
        }
      }
      if( $this->check_active_result('photos') ){
        $this->set_active_result('success',true);
        $this->set_active_result('feed_found',true);
        $this->append_active_result('hidden','<!-- Success using wp_remote_get() and JSON -->');
      }else{
        $this->set_active_result('success',false);
        $this->set_active_result('feed_found',true);
        $this->append_active_result('hidden','<!-- No photos found using wp_remote_get() and JSON @ '.$request.' -->');
      }
    }
  }
/**
 *  Function for making Tumblr request with API v2 with JSON
 *  
 *  @ Since 1.2.5
 *  @ Updated 1.2.6
 */
  function try_json_v2(){
    $request = $this->get_tumblr_request('jsonv2');
    
    $this->append_active_result('hidden','<!-- Request made -->');
    $result = $this->fetch_tumblr_feed($request);
    $_tumblr_json = array();
    if( $result ){ $_tumblr_json = @json_decode( $result, true ); }

    if( empty($_tumblr_json) || empty($_tumblr_json['response']) || empty($_tumblr_json['response']['posts']) ){
      $this->append_active_result('hidden','<!-- Failed using wp_remote_get() and JSON V2 @ '.$request.' -->');
      $this->set_active_result('success',false);
    }else{
      $num = $this->get_active_option('tumblr_photo_number');
      if( $this->check_active_option('photo_feed_shuffle') && function_exists('shuffle') ){ // Shuffle the results
        $num = min(150,$num*4);
      }
      $repeats = floor($num/20); // Maximum number of repeats
      $size_id = $this->get_active_option('tumblr_photo_size');
      $s = 0; // simple counter
      $photos = array();
      for($loop=0;$loop<=$repeats;$loop++){
        if( $s>=$num ){
          break;
        }else{
          if( !empty($_tumblr_json) && !empty($_tumblr_json['response']) && !empty($_tumblr_json['response']['posts']) ) {
            foreach( $_tumblr_json['response']['posts'] as $p ) {
              if( $s<$num && 'photo' == $p['type'] ){     
                $the_photo = array();

                $the_photo['image_link'] = isset($p['post_url'])?(string) $p['post_url']:'';
                $the_photo['image_title'] = isset($p['slug'])?(string) $p['slug']:'';
                $the_photo['image_title'] = str_replace(array("'","\r\n", "\r","\n"),'',$the_photo['image_title']);
                $the_photo['image_caption'] = '';

                if( !empty($p['photos']) ){
                  foreach( $p['photos'] as $photo ){
                    if( !empty($photo['alt_sizes']) ){
                      $sizes = $photo['alt_sizes'];
                      $the_photo['image_source'] = isset($sizes[0]['url'])?(string) $sizes[0]['url']:'';
                      $the_photo['image_original'] = isset($sizes[0]['url'])?(string) $sizes[0]['url']:(string) $sizes[1]['url'];

                      foreach( $sizes as $currentsize ){
                        if( ($currentsize['width'] >= $size_id || $currentsize['height'] >= $size_id) && !empty($currentsize['url']) ){
                          $the_photo['image_source'] = $currentsize['url'];
                        }
                      }
                      $this->push_photo( $the_photo );
                      $s++;
                    }
                  }
                }
              }else{
                break;
              }
            }
          }
          // Try another request
          if($loop<$repeats && $s<$num){
            $next_request = $request.'&offset='.(($loop+1)*20);
            $this->append_active_result('hidden','<!-- Request made -->');
            $result = $this->fetch_tumblr_feed($next_request);
            $result = str_replace('var tumblr_api_read = ','',$result);
            $result = str_replace(';','',$result);
            $_tumblr_json = array();
            if( !empty($result) ){ $_tumblr_json = @json_decode( $result, true ); }
            if( empty($_tumblr_json) ){ 
              $this->append_active_result('hidden','<!-- Failed on loop '.$loop.' with '.$next_request.' -->');
              $loop = $repeats;
            }          
          }
        }
      }
      if( $this->check_active_result('photos') ){
        $this->set_active_result('success',true);
        $this->set_active_result('feed_found',true);
        $this->append_active_result('hidden','<!-- Success using wp_remote_get() and JSON -->');
      }else{
        $this->set_active_result('success',false);
        $this->set_active_result('feed_found',true);
        $this->append_active_result('hidden','<!-- No photos found using wp_remote_get() and JSON @ '.$request.' -->');
      }      
    }
  }  
}
  
/** ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *  ##############################################################################################################################################
 *   
 *  AlpineBot Display
 * 
 *  Display functions
 *  Try to keep only UNIVERSAL functions
 * 
 */
 
class PhotoTileForTumblrBot extends PhotoTileForTumblrBotTertiary{
/**
 *  Function for printing vertical style
 *  
 *  @ Since 0.0.1
 *  @ Updated 1.2.6.5
 */
  function display_vertical(){
    $this->set_private('out',''); // Clear any output;
    $this->update_count(); // Check number of images found
    $this->randomize_display(); 
    $opts = $this->get_private('options');
    $src = $this->get_private('src');
    $wid = $this->get_private('wid');
                      
    $this->add('<div id="'.$wid.'-AlpinePhotoTiles_container" class="AlpinePhotoTiles_container_class">');     
    
      // Align photos
      $css = $this->get_parent_css();
      $this->add('<div id="'.$wid.'-vertical-parent" class="AlpinePhotoTiles_parent_class" style="'.$css.'">');

        for($i = 0;$i<$opts[$src.'_photo_number'];$i++){
          $css = "margin:1px 0 5px 0;padding:0;max-width:100%;";
          $pin = $this->get_option( 'pinterest_pin_it_button' );
          $this->add_image($i,$css,$pin); // Add image
        }
        
        $this->add_credit_link();
      
      $this->add('</div>'); // Close vertical-parent

      $this->add_user_link();

    $this->add('</div>'); // Close container
    $this->add('<div class="AlpinePhotoTiles_breakline"></div>');
    
    // Add Lightbox call (if necessary)
    $this->add_lightbox_call();
    
    $parentID = $wid."-vertical-parent";
    $borderCall = $this->get_borders_call( $parentID );

    if( !empty($opts['style_shadow']) || !empty($opts['style_border']) || !empty($opts['style_highlight'])  ){
      $this->add("
<script>  
  // Check for on() ( jQuery 1.7+ )
  if( jQuery.isFunction( jQuery(window).on ) ){
    jQuery(window).on('load', function(){".$borderCall."}); // Close on()
  }else{
    // Otherwise, use bind()
    jQuery(window).bind('load', function(){".$borderCall."}); // Close bind()
  }
</script>");  
    }
  }  
/**
 *  Function for printing cascade style
 *  
 *  @ Since 0.0.1
 *  @ Updated 1.2.6.5
 */
  function display_cascade(){
    $this->set_private('out',''); // Clear any output;
    $this->update_count(); // Check number of images found
    $this->randomize_display();
    $opts = $this->get_private('options');
    $wid = $this->get_private('wid');
    $src = $this->get_private('src');
    
    $this->add('<div id="'.$wid.'-AlpinePhotoTiles_container" class="AlpinePhotoTiles_container_class">');     
    
      // Align photos
      $css = $this->get_parent_css();
      $this->add('<div id="'.$wid.'-cascade-parent" class="AlpinePhotoTiles_parent_class" style="'.$css.'">');
      
        for($col = 0; $col<$opts['style_column_number'];$col++){
          $this->add('<div class="AlpinePhotoTiles_cascade_column" style="width:'.(100/$opts['style_column_number']).'%;float:left;margin:0;">');
          $this->add('<div class="AlpinePhotoTiles_cascade_column_inner" style="display:block;margin:0 3px;overflow:hidden;">');
          for($i = $col;$i<$opts[$src.'_photo_number'];$i+=$opts['style_column_number']){
            $css = "margin:1px 0 5px 0;padding:0;max-width:100%;";
            $pin = $this->get_option( 'pinterest_pin_it_button' );
            $this->add_image($i,$css,$pin); // Add image
          }
          $this->add('</div></div>');
        }
        $this->add('<div class="AlpinePhotoTiles_breakline"></div>');
          
        $this->add_credit_link();
      
      $this->add('</div>'); // Close cascade-parent

      $this->add('<div class="AlpinePhotoTiles_breakline"></div>');
      
      $this->add_user_link();

    // Close container
    $this->add('</div>');
    $this->add('<div class="AlpinePhotoTiles_breakline"></div>');
    
    // Add Lightbox call (if necessary)
    $this->add_lightbox_call();
    
    $parentID = $wid."-cascade-parent";
    $borderCall = $this->get_borders_call( $parentID );

    if( !empty($opts['style_shadow']) || !empty($opts['style_border']) || !empty($opts['style_highlight'])  ){
      $this->add("
<script>
  // Check for on() ( jQuery 1.7+ )
  if( jQuery.isFunction( jQuery(window).on ) ){
    jQuery(window).on('load', function(){".$borderCall."}); // Close on()
  }else{
    // Otherwise, use bind()
    jQuery(window).bind('load', function(){".$borderCall."}); // Close bind()
  }
</script>");  
    }
  }
/**
 *  Get jQuery borders plugin string
 *  
 *  @ Since 1.2.6.5
 */
  function get_borders_call( $parentID ){
    $highlight = $this->get_option("general_highlight_color");
    $highlight = (!empty($highlight)?$highlight:'#64a2d8');
    
    $return = "
      if( jQuery().AlpineAdjustBordersPlugin ){
        jQuery('#".$parentID."').AlpineAdjustBordersPlugin({
          highlight:'".$highlight."'
        });
      }else{
        var css = '".($this->get_private('url').'/css/'.$this->get_private('wcss').'.css')."';
        var link = jQuery(document.createElement('link')).attr({'rel':'stylesheet','href':css,'type':'text/css','media':'screen'});
        jQuery.getScript('".($this->get_private('url').'/js/'.$this->get_private('wjs').'.js')."', function(){
          if(document.createStyleSheet){
            document.createStyleSheet(css);
          }else{
            jQuery('head').append(link);
          }
          if( jQuery().AlpineAdjustBordersPlugin ){
            jQuery('#".$parentID."').AlpineAdjustBordersPlugin({
              highlight:'".$highlight."'
            });
          }
        }); // Close getScript
      }
    ";
    return $return;
  }
/**
 *  Function for printing and initializing JS styles
 *  
 *  @ Since 0.0.1
 *  @ Updated 1.2.6.5
 */
  function display_hidden(){
    $this->set_private('out',''); // Clear any output;
    $this->update_count(); // Check number of images found
    $this->randomize_display();
    $opts = $this->get_private('options');
    $wid = $this->get_private('wid');
    $src = $this->get_private('src');
    
    $this->add('<div id="'.$wid.'-AlpinePhotoTiles_container" class="AlpinePhotoTiles_container_class">');     
      // Align photos
      $css = $this->get_parent_css();
      $this->add('<div id="'.$wid.'-hidden-parent" class="AlpinePhotoTiles_parent_class" style="'.$css.'">');
      
        $this->add('<div id="'.$wid.'-image-list" class="AlpinePhotoTiles_image_list_class" style="display:none;visibility:hidden;">'); 
        
          for($i=0;$i<$opts[$src.'_photo_number'];$i++){

            $this->add_image($i); // Add image
            
            // Load original image size
            $original = $this->get_photo_info($i,'image_original');
            if( isset($opts['style_option']) && "gallery" == $opts['style_option'] && !empty( $original ) ){
              $this->add('<img class="AlpinePhotoTiles-original-image" src="' . $original . '" />');
            }
          }
        $this->add('</div>');
        
        $this->add_credit_link();       
      
      $this->add('</div>'); // Close parent  

      $this->add_user_link();
      
    $this->add('</div>'); // Close container
    
    $disable = $this->get_option("general_loader");

    $lightbox = $this->get_option('general_lightbox');
    $prevent = $this->get_option('general_lightbox_no_load');    
    $hasLight = false;
    $lightScript = '';
    $lightStyle = '';
    if( empty($prevent) && isset($opts[$this->get_private('src').'_image_link_option']) && $opts[$src.'_image_link_option'] == 'fancybox' ){
      $lightScript = $this->get_script( $lightbox );
      $lightStyle = $this->get_style( $lightbox );
      if( !empty($lightScript) && !empty($lightStyle) ){
        $hasLight = true;
      }
    }
    
    $this->add('<script>');
      if(!$disable){
        $this->add(
    "
    jQuery(document).ready(function() {
      jQuery('#".$wid."-AlpinePhotoTiles_container').addClass('loading'); 
    });
    ");
    
      }
  
    $pluginCall = $this->get_loading_call($opts,$wid,$src,$lightbox,$hasLight,$lightScript,$lightStyle);
    
    $this->add("
    // Check for on() ( jQuery 1.7+ )
    if( jQuery.isFunction( jQuery(window).on ) ){
      jQuery(window).on('load', function(){".$pluginCall."});
    }else{ 
      // Otherwise, use bind()
      jQuery(window).bind('load', function(){".$pluginCall."});
    }
</script>");    
 
  }
/**
 *  Get jQuery loading string
 *  
 *  @ Since 1.2.6.5
 */
  function get_loading_call($opts,$wid,$src,$lightbox,$hasLight,$lightScript,$lightStyle){
    $return = "
        jQuery('#".$wid."-AlpinePhotoTiles_container').removeClass('loading');
        
        var alpineLoadPlugin = function(){".$this->get_plugin_call($opts,$wid,$src,$hasLight)."}
        
        // Load Alpine Plugin
        if( jQuery().AlpinePhotoTilesPlugin ){
          alpineLoadPlugin();
        }else{ // Load Alpine Script and Style
          var css = '".($this->get_private('url').'/css/'.$this->get_private('wcss').'.css')."';
          var link = jQuery(document.createElement('link')).attr({'rel':'stylesheet','href':css,'type':'text/css','media':'screen'});
          jQuery.getScript('".($this->get_private('url').'/js/'.$this->get_private('wjs').'.js')."', function(){
            if(document.createStyleSheet){
              document.createStyleSheet(css);
            }else{
              jQuery('head').append(link);
            }";
          if( $hasLight ){    
          $check = ($lightbox=='fancybox'?'fancybox':($lightbox=='prettyphoto'?'prettyPhoto':($lightbox=='colorbox'?'colorbox':'fancyboxForAlpine')));    
          $return .="
            if( !jQuery().".$check." ){ // Load Lightbox
              jQuery.getScript('".$lightScript."', function(){
                css = '".$lightStyle."';
                link = jQuery(document.createElement('link')).attr({'rel':'stylesheet','href':css,'type':'text/css','media':'screen'});
                if(document.createStyleSheet){
                  document.createStyleSheet(css);
                }else{
                  jQuery('head').append(link);
                }
                alpineLoadPlugin();
              }); // Close getScript
            }else{
              alpineLoadPlugin();
            }";
          }else{
            $return .= "
            alpineLoadPlugin();";
          }
            $return .= "
          }); // Close getScript
        }
      ";
    return $return;
  }
/**
 *  Get jQuery plugin string
 *  
 *  @ Since 1.2.6.5
 */
  function get_plugin_call($opts,$wid,$src,$hasLight){
    $highlight = $this->get_option("general_highlight_color");
    $highlight = (!empty($highlight)?$highlight:'#64a2d8');
    $return = "
          jQuery('#".$wid."-hidden-parent').AlpinePhotoTilesPlugin({
            id:'".$wid."',
            style:'".(isset($opts['style_option'])?$opts['style_option']:'windows')."',
            shape:'".(isset($opts['style_shape'])?$opts['style_shape']:'square')."',
            perRow:".(isset($opts['style_photo_per_row'])?$opts['style_photo_per_row']:'3').",
            imageBorder:".(!empty($opts['style_border'])?'1':'0').",
            imageShadow:".(!empty($opts['style_shadow'])?'1':'0').",
            imageCurve:".(!empty($opts['style_curve_corners'])?'1':'0').",
            imageHighlight:".(!empty($opts['style_highlight'])?'1':'0').",
            lightbox:".((isset($opts[$src.'_image_link_option']) && $opts[$src.'_image_link_option'] == 'fancybox')?'1':'0').",
            galleryHeight:".(isset($opts['style_gallery_height'])?$opts['style_gallery_height']:'0').", // Keep for Compatibility
            galRatioWidth:".(isset($opts['style_gallery_ratio_width'])?$opts['style_gallery_ratio_width']:'800').",
            galRatioHeight:".(isset($opts['style_gallery_ratio_height'])?$opts['style_gallery_ratio_height']:'600').",
            highlight:'".$highlight."',
            pinIt:".(!empty($opts['pinterest_pin_it_button'])?'1':'0').",
            siteURL:'".get_option( 'siteurl' )."',
            callback: ".(!empty($hasLight)?'function(){'.$this->get_lightbox_call().'}':"''")."
          });
        ";
    return $return;
  }
 
/**
 *  Update photo number count
 *  
 *  @ Since 1.2.2
 */
  function update_count(){
    $src = $this->get_private('src');
    $found = ( $this->check_active_result('photos') && is_array($this->get_active_result('photos') ))?count( $this->get_active_result('photos') ):0;
    $num = $this->get_active_option( $src.'_photo_number' );
    $this->set_active_option( $src.'_photo_number', min( $num, $found ) );
  }  
/**
 *  Function for shuffleing photo feed
 *  
 *  @ Since 1.2.4
 */
  function randomize_display(){
    if( $this->check_active_option('photo_feed_shuffle') && function_exists('shuffle') ){ // Shuffle the results
      $photos = $this->get_active_result('photos');
      @shuffle( $photos );
      $this->set_active_result('photos',$photos);
    }  
  }  
/**
 *  Get Parent CSS
 *  
 *  @ Since 1.2.2
 *  @ Updated 1.2.5
 */
  function get_parent_css(){
    $max = $this->check_active_option('widget_max_width')?$this->get_active_option('widget_max_width'):100;
    $return = 'width:100%;max-width:'.$max.'%;padding:0px;';
    $align = $this->check_active_option('widget_alignment')?$this->get_active_option('widget_alignment'):'';
    if( 'center' == $align ){                          //  Optional: Set text alignment (left/right) or center
      $return .= 'margin:0px auto;text-align:center;';
    }
    elseif( 'right' == $align  || 'left' == $align  ){                          //  Optional: Set text alignment (left/right) or center
      $return .= 'float:' . $align  . ';text-align:' . $align  . ';';
    }
    else{
      $return .= 'margin:0px auto;text-align:center;';
    }
    return $return;
 }
 
/**
 *  Add Image Function
 *  
 *  @ Since 1.2.2
 *  @ Updated 1.2.4
 ** Possible change: place original image as 'alt' and load image as needed
 */
  function add_image($i,$css="",$pin=false){
    $light = $this->get_option( 'general_lightbox' );
    $title = $this->get_photo_info($i,'image_title');
    $src = $this->get_photo_info($i,'image_source');
    $shadow = ($this->check_active_option('style_shadow')?'AlpinePhotoTiles-img-shadow':'AlpinePhotoTiles-img-noshadow');
    $border = ($this->check_active_option('style_border')?'AlpinePhotoTiles-img-border':'AlpinePhotoTiles-img-noborder');
    $curves = ($this->check_active_option('style_curve_corners')?'AlpinePhotoTiles-img-corners':'AlpinePhotoTiles-img-nocorners');
    $highlight = ($this->check_active_option('style_highlight')?'AlpinePhotoTiles-img-highlight':'AlpinePhotoTiles-img-nohighlight');
    $onContextMenu = ($this->check_active_option('general_disable_right_click')?'onContextMenu="return false;"':'');
    
    if( $pin ){ $this->add('<div class="AlpinePhotoTiles-pinterest-container" style="position:relative;display:block;" >'); }
    
    //$src = $this->getImageCache( $this->photos[$i]['image_source'] );
    //$src = ( $src?$src:$this->photos[$i]['image_source']);
    
    $has_link = $this->get_link($i); // Add link
    $this->add('<img id="'.$this->get_private('wid').'-tile-'.$i.'" class="AlpinePhotoTiles-image '.$shadow.' '.$border.' '.$curves.' '.$highlight.'" src="' . $src . '" ');
    $this->add('title='."'". $title ."'".' alt='."'". $title ."' "); // Careful about caps with ""
    $this->add('border="0" hspace="0" vspace="0" style="'.$css.'" '.$onContextMenu.' />'); // Override the max-width set by theme
    if( $has_link ){ $this->add('</a>'); } // Close link
    
    if( $pin ){ 
      $original = $this->get_photo_info($i,'image_original');
      $this->add('<a href="http://pinterest.com/pin/create/button/?media='.$original.'&url='.get_option( 'siteurl' ).'" class="AlpinePhotoTiles-pin-it-button" count-layout="horizontal" target="_blank">');
      $this->add('<div class="AlpinePhotoTiles-pin-it"></div></a>');
      $this->add('</div>'); 
    }
  }
/**
 *  Get Image Link
 *  
 *  @ Since 1.2.2
 *  @ Updated 1.2.6.5
 */
  function get_link($i){
    $src = $this->get_private('src');
    $link = $this->get_active_option($src.'_image_link_option');
    $url = $this->get_active_option('custom_link_url');

    $phototitle = $this->get_photo_info($i,'image_title'); 
    $photourl = $this->get_photo_info($i,'image_source');
    $linkurl = $this->get_photo_info($i,'image_link');
    $originalurl = $this->get_photo_info($i,'image_original');

    if( 'original' == $link && !empty($photourl) ){
      $this->add('<a href="' . $photourl . '" class="AlpinePhotoTiles-link" target="_blank" title=" '. $phototitle .' " alt=" '. $phototitle .' ">');
      return true;
    }elseif( ($src == $link || '1' == $link) && !empty($linkurl) ){
      $this->add('<a href="' . $linkurl . '" class="AlpinePhotoTiles-link" target="_blank" title=" '. $phototitle .' " alt=" '. $phototitle .' ">');
      return true;
    }elseif( 'link' == $link && !empty($url) ){
      $this->add('<a href="' . $url . '" class="AlpinePhotoTiles-link" title=" '. $phototitle .' " alt=" '. $phototitle .' ">'); 
      return true;
    }elseif( 'fancybox' == $link && !empty($originalurl) ){
      $light = $this->get_option( 'general_lightbox' );
      $this->add('<a href="' . $originalurl . '" class="AlpinePhotoTiles-link AlpinePhotoTiles-lightbox" title=" '. $phototitle .' " alt=" '. $phototitle .' ">'); 
      return true;
    }  
    return false;    
  }
/**
 *  Credit Link Function
 *  
 *  @ Since 1.2.2
 */
  function add_credit_link(){
    if( !$this->get_active_option('widget_disable_credit_link') ){
      $this->add('<div id="'.$this->get_private('wid').'-by-link" class="AlpinePhotoTiles-by-link"><a href="http://thealpinepress.com/" style="COLOR:#C0C0C0;text-decoration:none;" title="Widget by The Alpine Press">TAP</a></div>');
    }  
  }
  
/**
 *  User Link Function
 *  
 *  @ Since 1.2.2
 */
  function add_user_link(){
    if( $this->check_active_result('userlink') ){
      $userlink = $this->get_active_result('userlink');
      if($this->get_active_option('widget_alignment') == 'center'){                          //  Optional: Set text alignment (left/right) or center
        $this->add('<div id="'.$this->get_private('wid').'-display-link" class="AlpinePhotoTiles-display-link-container" ');
        $this->add('style="width:100%;margin:0px auto;">'.$userlink.'</div>');
      }
      else{
        $this->add('<div id="'.$this->get_private('wid').'-display-link" class="AlpinePhotoTiles-display-link-container" ');
        $this->add('style="float:'.$this->get_active_option('widget_alignment').';max-width:'.$this->get_active_option('widget_max_width').'%;"><center>'.$userlink.'</center></div>'); 
        $this->add('<div class="AlpinePhotoTiles_breakline"></div>'); // Only breakline if floating
      }
    }
  }
  
/**
 *  Setup Lightbox call
 *  
 *  @ Since 1.2.3
 *  @ Updated 1.2.6.5
 */
  function add_lightbox_call(){
    $src = $this->get_private('src');
    $lightbox = $this->get_option('general_lightbox');
    $prevent = $this->get_option('general_lightbox_no_load');
    $check = ($lightbox=='fancybox'?'fancybox':($lightbox=='prettyphoto'?'prettyPhoto':($lightbox=='colorbox'?'colorbox':'fancyboxForAlpine')));
    if( empty($prevent) && $this->check_active_option($src.'_image_link_option') && $this->get_active_option($src.'_image_link_option') == 'fancybox' ){
      $lightScript = $this->get_script( $lightbox );
      $lightStyle = $this->get_style( $lightbox );
      if( !empty($lightScript) && !empty($lightStyle) ){
        $lightCall = $this->get_lightbox_call();
        $lightboxSetup = "
      if( !jQuery().".$check." ){
        var css = '".$lightStyle."';
        var link = jQuery(document.createElement('link')).attr({'rel':'stylesheet','href':css,'type':'text/css','media':'screen'});
        jQuery.getScript('".($lightScript)."', function(){
          if(document.createStyleSheet){
            document.createStyleSheet(css);
          }else{
            jQuery('head').append(link);
          }
          ".$lightCall."
        }); // Close getScript
      }else{
        ".$lightCall."
      }
    ";
        $this->add("
  <script>
  // Check for on() ( jQuery 1.7+ )
  if( jQuery.isFunction( jQuery(window).on ) ){
    jQuery(window).on('load', function(){".$lightboxSetup."}); // Close on()
  }else{
    // Otherwise, use bind()
    jQuery(window).bind('load', function(){".$lightboxSetup."}); // Close bind()
  }
  </script>"); 
      }
    }
  }
  
/**
 *  Get Lightbox Call
 *  
 *  @ Since 1.2.3
 *  @ Updated 1.2.5
 */
  function get_lightbox_call(){
    $this->set_lightbox_rel();
  
    $lightbox = $this->get_option('general_lightbox');
    $lightbox_style = $this->get_option('general_lightbox_params');
    $lightbox_style = str_replace( array("{","}"), "", $lightbox_style);
    
    $setRel = "jQuery( '#".$this->get_private('wid')."-AlpinePhotoTiles_container a.AlpinePhotoTiles-lightbox' ).attr( 'rel', '".$this->get_active_option('rel')."' );";
    
    if( 'fancybox' == $lightbox ){
      $default = "titleShow: false, overlayOpacity: .8, overlayColor: '#000', titleShow: true, titlePosition: 'inside'";
      $lightbox_style = (!empty($lightbox_style)? $default.','.$lightbox_style : $default );
      return $setRel."if(jQuery().fancybox){jQuery( 'a[rel^=\'".$this->get_active_option('rel')."\']' ).fancybox( { ".$lightbox_style." } );}";  
    }elseif( 'prettyphoto' == $lightbox ){
      //theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook
      $default = "theme:'facebook',social_tools:false, show_title:true";
      $lightbox_style = (!empty($lightbox_style)? $default.','.$lightbox_style : $default );
      return $setRel."if(jQuery().prettyPhoto){jQuery( 'a[rel^=\'".$this->get_active_option('rel')."\']' ).prettyPhoto({ ".$lightbox_style." });}";  
    }elseif( 'colorbox' == $lightbox ){
      $default = "maxHeight:'85%'";
      $lightbox_style = (!empty($lightbox_style)? $default.','.$lightbox_style : $default );
      return $setRel."if(jQuery().colorbox){jQuery( 'a[rel^=\'".$this->get_active_option('rel')."\']' ).colorbox( {".$lightbox_style."} );}";  
    }elseif( 'alpine-fancybox' == $lightbox ){
      $default = "titleShow: false, overlayOpacity: .8, overlayColor: '#000', titleShow: true, titlePosition: 'inside'";
      $lightbox_style = (!empty($lightbox_style)? $default.','.$lightbox_style : $default );
      return $setRel."if(jQuery().fancyboxForAlpine){jQuery( 'a[rel^=\'".$this->get_active_option('rel')."\']' ).fancyboxForAlpine( { ".$lightbox_style." } );}";  
    }
    return "";
  }
  
 /**
  *  Set Lightbox "rel"
  *  
  *  @ Since 1.2.3
  */
  function set_lightbox_rel(){
    $lightbox = $this->get_option('general_lightbox');
    $custom = $this->get_option('hidden_lightbox_custom_rel');
    if( !empty($custom) && $this->check_active_option('custom_lightbox_rel') ){
      $rel = $this->get_active_option('custom_lightbox_rel');
      $rel = str_replace('{rtsq}',']',$rel); // Decode right and left square brackets
      $rel = str_replace('{ltsq}','[',$rel);
    }elseif( 'fancybox' == $lightbox ){
      $rel = 'alpine-fancybox-'.$this->get_private('wid');
    }elseif( 'prettyphoto' == $lightbox ){
      $rel = 'alpine-prettyphoto['.$this->get_private('wid').']';
    }elseif( 'colorbox' == $lightbox ){
      $rel = 'alpine-colorbox['.$this->get_private('wid').']';
    }else{
      $rel = 'alpine-fancybox-safemode-'.$this->get_private('wid');
    }
    $this->set_active_option('rel',$rel);
  }


}

?>
