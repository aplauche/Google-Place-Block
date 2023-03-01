<?php

function fsdgpb_render($atts, $content, $block){

  // Get the post ID from block context - this is provided by useContext within block.json
  $postID = $block->context['postId'];

  // Get taxonomy terms
  $postNeighborhoods = get_the_terms( $postID, 'neighborhood' );
  // Force into an array
  $postNeighborhoods = is_array($postNeighborhoods) ? $postNeighborhoods : [];
  
  // Get taxonomy terms
  $postSpecials = get_the_terms( $postID, 'specials' );
  // Force into an array
  $postSpecials = is_array($postSpecials) ? $postSpecials : [];

  // Get our custom meta of happy hour time slots
  $happy_hour_times = get_post_meta( $postID, 'happy_hour_times', true );
  // Again coerce into an array
  $happy_hour_times = is_array($happy_hour_times) ? $happy_hour_times : [];


  ob_start();?>

  <div class="happy-hour">

    <div class="happy-hour-times">
      <?php foreach($happy_hour_times as $day => $times): ?>
        <div>
          <p><strong><?php echo $day ?></strong></p>
          <?php if(
            array_key_exists('start', $times) &&
            array_key_exists('end', $times) &&
            $times["start"] !== "" &&
            $times["end"] !== ""
            ) :
          ?>
            <div>
              <div><?php echo date('g:i a', strtotime($times["start"]))  ?></div> 
              <div>-</div> 
              <div><?php echo date('g:i a', strtotime($times["end"])) ?></div>
            </div>
          <?php else : ?>
            <p> - - </p>
          <?php endif; ?> 
        </div>
      <?php endforeach; ?>
    </div>

    <div class="specials-row">
      <h3>Specials</h3>
      <div class="specials">
        <?php foreach($postSpecials as $idx => $special):?>
          <?php 
            echo $special->name; 
            if($idx != count($postSpecials) - 1){
              echo  " | ";
            }
            //var_dump($special);
          ?>
        <?php endforeach; ?>
      </div>
    </div>
    
  </div>

  <?php return ob_get_clean();

}