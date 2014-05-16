<?php

/**
 * Event Partial
 */

?>

<div class="bpt-events">
<?php

    foreach ($events as $event) {
?>
    <div class="single-event event-<?php echo $event['id'];?>">
        <h2 class="event-title"><?php echo $event['title'];?></h2>
        <div class="event-location">
            <span class="address-1"><?php echo $event['address1']; ?></span>
            <span class="address-2"><?php echo $event['address2']; ?></span>
            <span class="city"><?php echo $event['city']; ?>,</span>
            <span class="state"><?php echo $event['state']; ?></span>
            <span class="zip"><?php echo $event['zip']; ?></span>
        </div>
        <div class="event-description">
            <div class="short-description">
                <?php echo htmlspecialchars_decode( $event['shortDescription'] ); ?>
            </div>

            <a href="#" class="show-full-description">Show Full Description</a>
            <div class="full-description">
                <?php echo htmlspecialchars_decode( $event['fullDescription'] ); ?>
            </div>
        </div>

    <form method ="post" class="add-to-cart" action="http://www.brownpapertickets.com/addtocart.html">
        <?php $this->generate_date_html($event['dates'], $event['id']); ?>
    </form>
    </div>
<?php
    }
?>
</div>