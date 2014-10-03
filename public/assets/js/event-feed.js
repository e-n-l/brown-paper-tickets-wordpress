(function($) {
	'use strict';
	var BptEventList;

	BptEventList = function BptEventList(eventListOptions) {

		var self = this,
			bpt,
			init,
			showOrHidePrice,
			clickHidePrice,
			getEvents,
			postID = eventListOptions.postID,
			allEvents = [],
			eventList;

		eventList = new Ractive({
			el: '#bpt-event-list-' + postID,
			template: '#bpt-event-template',
			data: {

				formatDate: function formatDate(newFormat, date) {
					var singleDate = moment(date, 'YYYY-MM-DD');
					return singleDate.format(newFormat);
				},

				formatTime: function formatTime(newFormat, time) {
					var singleTime = moment(time, 'H:mm');
					return singleTime.format(newFormat);
				},

				unescapeHTML: function unescapeHTML(html) {
					return _.unescape(html);
				},

				formatPrice: function formatPrice(price, currency) {
					var separator = '.',
						priceArr;

					if (currency === '€' ) {
						separator = ',';
					}

					if (price === 0) {
						return 'Free';
					}

					priceArr = price.toString().split('.');

					if (!priceArr[1]) {
						price = priceArr[0] + separator + '00';
					} else {
						price = priceArr[0] + separator + priceArr[1];
					}

					return currency + '' + price;
				},

				isHidden: function isHidden(hidden) {

					if (hidden) {
						return 'bpt-hidden-price';
					}
				}
			}
		});

		getEvents = function(){
			var	bptData = {
					action: 'bpt_get_events',
					bptNonce: eventListOptions.bptNonce,
					postID: eventListOptions.postID
				};

				if ( eventListOptions.clientID ) {

					bptData.clientID = eventListOptions.clientID;
				}

				if ( eventListOptions.eventID ) {

					bptData.eventID = eventListOptions.eventID;
				}

				$('div.bpt-loading-' + postID).fadeIn();

				$.ajax(
					eventListOptions.ajaxurl,
					{
						type: 'POST',
						data: bptData,
						accepts: 'json',
						dataType: 'json'
					}
				)
				.always(function() {
					$('div.bpt-loading-' + postID).hide();
				})
				.fail(function() {

					eventList.set({
						bptError: 'Unknown Error'
					});

				})
				.done(function(data) {
					if (data.error) {

						eventList.set(
							{
								bptError: data
							}
						);

					}

					if ( !data.error ) {

						eventList.set(
							{
								bptEvents: data
							}
						);

						$(document).trigger('bptEventListLoaded');
					}
				})
				.always(function() {

				});
		};


		showOrHidePrice = function(event, showPrice) {
			var priceLink = $(event.original.target),
				price = {
					priceId: priceLink.data('price-id'),
					priceName: priceLink.data('price-name'),
					eventTitle: priceLink.parents('form').data('event-title'),
					eventId: priceLink.parents('form').data('event-id')
				},
				ajaxAction = 'bpt_hide_prices',
				dateKeyPath = event.keypath.replace('.selectedDate', '.dates') + '.hidden',
				selectedKeyPath =  event.keypath + '.hidden';

			event.original.preventDefault();

			if (!showPrice) {
				showPrice = false;
			}

			if (showPrice) {
				ajaxAction = 'bpt_unhide_prices';
			}

			$.ajax(
				eventListOptions.ajaxurl,
				{
					type: 'POST',
					data: {
						action: ajaxAction,
						bptNonce: eventListOptions.bptNonce,
						prices: [price]
					},
					accepts: 'json',
					dataType: 'json'
				}
			).always(function() {

			}).done(function(data) {

				if (data.success) {
					if (showPrice) {
						eventList.set(dateKeyPath, false);
						eventList.set(selectedKeyPath, false);
					} else {
						eventList.set(dateKeyPath, true);
						eventList.set(selectedKeyPath, true);


					}
				}

				if (data.error) {

				}

			}).fail();
		};

		eventList.on({
			showFullDescription: function showFullDescription(event) {
				event.original.preventDefault();
				$(event.node).parent().next('.bpt-event-full-description').toggle('hidden');
			},
			hidePrice: function(event) {
				showOrHidePrice(event);
			},
			unhidePrice: function(event) {
				showOrHidePrice(event, true);
			}
		});

		init = (function() {
			getEvents();
		})();
	};


	$(document).ready(function() {

		var eventListContainers = $('.bpt-event-list'),
			eventLists = [];

		eventListContainers.each(function() {
			var postId = $(this).data('post-id').toString(),
				eventListOptions = window['bptEventFeedAjaxPost' + postId];

			eventLists['post' + postId] = new BptEventList(eventListOptions);

		});
	});

})(jQuery);