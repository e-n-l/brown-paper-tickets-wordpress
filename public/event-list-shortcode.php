<?php

/**
 * The ShortCode HTML Container.
 * Contents will be populated by javascript after successfull ajax call.
 */

?>
<div class="bpt-loading hidden">
    Loading Events
    <br />
    <img src="<?php echo plugins_url( '/assets/img/loading.gif', dirname( __FILE__ ) ); ?>">
</div>
<div class="bpt-events">
    <div class="bpt-single-event hidden">
        <h2 class="bpt-event-title"></h2>
        <div class="bpt-event-location">
            <span class="address1"></span>
            <span class="address2"></span>
            <span class="city"></span>
            <span class="state"></span>
            <span class="zip"></span>
        </div>
        <div class="bpt-event-short-description">

        </div>
<?php 
        if ( get_option('show_full_description') === 'false' ) {
?>
            <a href="#" class="bpt-show-full-description">Show Full Description</a>
            <div class="bpt-event-full-description hidden">
            
            </div>

<?php
        } else {
?>
            <div class="bpt-event-full-description">
            
            </div>

<?php
        }
?>
        

        <ul class="bpt-date-list">
            <li class="bpt-single-date">
                
                <ul class="bpt-event-date-price-list">
                    <li class="bpt-single-price">
                    </li>

                </ul>
            </li>
        </ul>

    </div>

</div>

<script type="text/html">
<% 
    _.each(events, function(event) {


%>
    <div class="bpt-event-template">
        <h2 class="bpt-event-title"><%- event.title %></h2>
        <div class="bpt-event-location">
            <span class="address1"><%- event.address1 %></span>
            <span class="address2"><%- event.address2 %></span>
            <span class="city"><%- event.address2 %></span>
            <span class="state"><%- event.state %></span>
            <span class="zip"><%- event.zip %></span>
        </div>
        <div class="bpt-event-short-description">
            <%- event.shortDescription %>
        </div>

            <a href="#" class="bpt-show-full-description">Show Full Description</a>
            <div class="bpt-event-full-description hidden">
                <%- event.fullDescription %>
            </div>

            <div class="bpt-event-full-description">
                <%- event.fullDescription %>
            </div>

        <form method ="post" class="add-to-cart" action="http://www.brownpapertickets.com/addtocart.html">

            <div class="event-dates">
                <label for="dates-<%- event.id %>">Select a Date:</label>
                <select id="dates-<%- event.id %>" name="date_id">
                <%
                    _.each(events.dates, function(date) {

                    });
                %>
                    <option class="date-unavailable" value="<%- date.id %>" data-date-id="<%- date.id %>">
                       <del></del>

                    <option class="event-date" value="<%- date.id %>">
                        
                    </option>
                </select>
            </div>
            <fieldset>
                <legend class="price-legend">Prices for  at </legend>
                <%
    
                });

                %>>
            </fieldset>
        </form>
    </div>

<%
    });
%>
</script>