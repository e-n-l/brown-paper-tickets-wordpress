<?php
/**
 * Generates the Date HTML.
 *
 */
?>

    <div class="event-dates">
        <label for="dates-<?php echo $event_id;?>">Select a Date:</label>
        <select id="dates-<?php echo $event_id;?>" name="date_id">
<?php 
        foreach ( $dates as $date ) {
            if ( !$this->date_has_past( $date ) ) {
                continue;
            }

            if ( !$this->date_has_past( $date ) && !$this->date_is_sold_out( $date ) or !$date['live'] ) {
?>
            <option class="date-unavailable" value="<?php echo $date['id'];?>" data-date-id="<?php echo $date['id'];?>">
               <del><?php $this->convert_date( $date['dateStart'] );?></del>
            </option>
<?php
                continue;
            }

?>
            <option class="event-date" value="<?php echo $date['id'];?>">
                <?php echo $this->convert_date( $date['dateStart'] ); ?>
            </option>
        </select>
    </div>
    <fieldset>
        <legend class="price-legend">Prices for <?php echo $this->convert_date( $date['dateStart'] );?> at <?php echo $this->convert_time( $date['timeStart'] );?></legend>
<?php
            $this->generate_price_html($date['prices']);
        }

?>
    </fieldset>