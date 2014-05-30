(function($) {
    'use strict';
    var navigation = {
        loadTab: function loadTab() {
            var currentTab = this.getAnchor;
            this.switchTabs(currentTab);
        },
        switchTabs: function hideTabs(tab) {
            var currentTab = tab,
                tabs = this.getTabs();

            if (!tab) {
                currentTab = tabs[0];
            }

            this.setAnchor(tab);

            $('#bpt-settings-wrapper').children('div').hide();

            if (!currentTab) {
                $('a[href="#account-setup"]').addClass('selected-tab');
                $('#bpt-settings-wrapper div:first-child').show();
                return;
            }

            $('div' + currentTab).show();
            $('a.bpt-admin-tab').removeClass('selected-tab');
            $('a[href="' + currentTab + '"]').addClass('selected-tab');
        },
        getAnchor: function getAnchor() {
            var anchor = window.location.hash.substring(1);

            if (!anchor) {
                return false;
            }

            anchor = '#' + anchor;

            return anchor;
        },
        getTabs: function getTabs() {
            var tabs = [];

            $('#brown_paper_tickets_settings ul li').each(function() {
               tabs.push($(this).children('a').attr('href')); 
            });

            return tabs;
        },
        setAnchor: function setAnchor(tab) {

            if (tab === this.getAnchor()) {
                return;
            }
            
            document.location.replace(tab);

        }
    };

    var customDateFormat = function() {

        var selectedDateFormat = $('select#date-format option').filter(':selected');

        if (selectedDateFormat.val() === 'custom') {
            $('input#custom-date-format-input').removeClass('hidden');
        } else {
            $('input#custom-date-format-input').addClass('hidden');
        }
    };

    var customTimeFormat = function() {

        var selectedTimeFormat = $('select#time-format option').filter(':selected');

        if (selectedTimeFormat.val() === 'custom') {
            $('input#custom-time-format-input').removeClass('hidden');
        } else {
            $('input#custom-time-format-input').addClass('hidden');
        }
    };


    $(document).ready(function() {

        navigation.switchTabs(navigation.getAnchor());

        $('a.bpt-admin-tab').click(function(e) {
            e.preventDefault();
            var tab = $(this).attr('href');
            navigation.switchTabs(tab);
        });


        customDateFormat();
        customTimeFormat();

        $('select#date-format').change(function() {
            customDateFormat();
        });

        $('select#time-format').change(function() {
            customTimeFormat();
        });
    });
})(jQuery);