(function($) {
    $(document).ready(function(){
        navigation = {
            loadTab: function loadTab(tab) {
                var currentTab = this.getAnchor;
                this.hideTabs();

            },
            switchTabs: function hideTabs(tab) {

                if (!tab) {
                    currentTab = undefined;
                }
                var currentTab = tab,
                    tabs = this.getTabs();

                $('#settings-wrapper').children('div').hide();

                if (!currentTab) {
                    $('a[href="#account-setup"]').addClass('selected-tab');
                    $('#settings-wrapper div:first-child').show();

                    return;
                }

                $('div' + currentTab).show();
                $('a.bpt-admin-tab').removeClass('selected-tab');
                $('a[href="' + currentTab + '"]').addClass('selected-tab');

            },
            getAnchor: function getAnchor() {
                anchor = window.location.hash.substring(1);
                return anchor;
            },
            getTabs: function getTabs() {
                var tabs = [];

                $('#brown_paper_tickets_settings ul li').each(function() {
                   tabs.push($(this).children('a').attr('href')); 
                });

                return tabs;
            }
        };
    });

    $(document).ready(function() {
        navigation.switchTabs(navigation.getAnchor());

        $('a.bpt-admin-tab').click(function(e) {
            e.preventDefault();
            var tab = $(this).attr('href');
            navigation.switchTabs(tab);
        });
    });
})(jQuery);