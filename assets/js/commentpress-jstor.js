/**
 * CommentPress JSTOR Javascript.
 *
 * Implements access to JSTOR Matchmaker tool from The Reader's Thoreau site.
 *
 * @package Commentpress_JSTOR
 */

/**
 * Create CommentPress JSTOR object.
 *
 * This works as a "namespace" of sorts, allowing us to hang properties, methods
 * and "sub-namespaces" from it.
 *
 * @since 0.1
 */
var CommentPressJSTOR = CommentPressJSTOR || {};

/**
 * Set up CommentPress JSTOR when the page is ready.
 *
 * @since 0.1
 */
jQuery(document).ready( function($) {

	/**
	 * Create CommentPress JSTOR Settings Object.
	 *
	 * @since 0.1
	 */
	CommentPressJSTOR.settings = new function() {

		// Store object refs
		var me = this;

		/**
		 * Initialise CommentPress JSTOR Settings.
		 *
		 * This method should only be called once.
		 *
		 * @since 0.1
		 */
		this.init = function() {

			// Init spinner.
			me.init_spinner();

			// Init JSTOR work code.
			me.init_work();

			// Init JSTOR token.
			me.init_token();

			// Init JSTOR fields.
			me.init_fields();

			// Init JSTOR link behaviour.
			me.init_link();

		};

		/**
		 * Do setup when jQuery reports that the DOM is ready.
		 *
		 * This method should only be called once.
		 *
		 * @since 0.1
		 */
		this.dom_ready = function() {

		};

		// Not enabled until CommentPress is ready.
		me.enabled = false;

		/**
		 * Setter for enabled flag.
		 *
		 * @since 0.1
		 *
		 * @param {Boolean} flag Whether or not this functionality works.
		 */
		this.set_enabled = function( flag ) {
			me.enabled = flag;
		};

		/**
		 * Getter for enabled flag.
		 *
		 * @since 0.1
		 *
		 * @return {Boolean} The enabled flag.
		 */
		this.get_enabled = function() {
			return me.enabled;
		};

		// Default spinner.
		me.spinner = '';

		/**
		 * Init spinner from settings object.
		 *
		 * @since 0.1
		 *
		 * By doing this, we can override the default value.
		 */
		this.init_spinner = function() {
			if ( 'undefined' !== typeof CommentPress_JSTOR_Settings.interface.spinner ) {
				me.spinner = CommentPress_JSTOR_Settings.interface.spinner;
			}
		};

		/**
		 * Setter for spinner.
		 *
		 * @since 0.1
		 *
		 * @param {String} val The spinner URL.
		 */
		this.set_spinner = function( val ) {
			me.spinner = val;
		};

		/**
		 * Getter for spinner URL.
		 *
		 * @since 0.1
		 *
		 * @return {String} The spinner URL.
		 */
		this.get_spinner = function() {
			return me.spinner;
		};

		// Init JSTOR work code.
		me.work = '';

		/**
		 * Init JSTOR work code from settings object.
		 *
		 * @since 0.1
		 *
		 * By doing this, we can override the default value.
		 */
		this.init_work = function() {
			if ( 'undefined' !== typeof CommentPress_JSTOR_Settings.jstor.work ) {
				me.work = CommentPress_JSTOR_Settings.jstor.work;
			}
		};

		/**
		 * Setter for JSTOR work code.
		 *
		 * @since 0.1
		 *
		 * @param {String} val The JSTOR work code.
		 */
		this.set_work = function( val ) {
			me.work = val;
		};

		/**
		 * Getter for JSTOR work code.
		 *
		 * @since 0.1
		 *
		 * @return {String} The JSTOR work code.
		 */
		this.get_work = function() {
			return me.work;
		};

		// Init token.
		me.token = '';

		/**
		 * Init JSTOR token from settings object.
		 *
		 * @since 0.1
		 *
		 * By doing this, we can override the default value.
		 */
		this.init_token = function() {
			if ( 'undefined' !== typeof CommentPress_JSTOR_Settings.jstor.token ) {
				me.token = CommentPress_JSTOR_Settings.jstor.token;
			}
		};

		/**
		 * Setter for JSTOR token.
		 *
		 * @since 0.1
		 *
		 * @param {String} val The JSTOR token.
		 */
		this.set_token = function( val ) {
			me.token = val;
		};

		/**
		 * Getter for JSTOR token.
		 *
		 * @since 0.1
		 *
		 * @return {String} The JSTOR token.
		 */
		this.get_token = function() {
			return me.token;
		};

		// Init fields.
		me.fields = '';

		/**
		 * Init JSTOR fields from settings object.
		 *
		 * @since 0.1
		 *
		 * By doing this, we can override the default value.
		 */
		this.init_fields = function() {
			if ( 'undefined' !== typeof CommentPress_JSTOR_Settings.jstor.fields ) {
				me.fields = CommentPress_JSTOR_Settings.jstor.fields;
			}
		};

		/**
		 * Setter for JSTOR fields.
		 *
		 * @since 0.1
		 *
		 * @param {String} val The JSTOR fields.
		 */
		this.set_fields = function( val ) {
			me.fields = val;
		};

		/**
		 * Getter for JSTOR fields.
		 *
		 * @since 0.1
		 *
		 * @return {String} The JSTOR fields.
		 */
		this.get_fields = function() {
			return me.fields;
		};

		// Default link behaviour.
		me.link = '';

		/**
		 * Init link from settings object.
		 *
		 * @since 0.1
		 *
		 * By doing this, we can override the default value.
		 */
		this.init_link = function() {
			if ( 'undefined' !== typeof CommentPress_JSTOR_Settings.jstor.link ) {
				me.link = CommentPress_JSTOR_Settings.jstor.link;
			}
		};

		/**
		 * Setter for link behaviour.
		 *
		 * @since 0.1
		 *
		 * @param {String} val The link markup.
		 */
		this.set_link = function( val ) {
			me.link = val;
		};

		/**
		 * Getter for link behaviour.
		 *
		 * @since 0.1
		 *
		 * @return {String} The link markup.
		 */
		this.get_link = function() {
			return me.link;
		};

	};

	/**
	 * Create CommentPress JSTOR API Object.
	 *
	 * @since 0.1
	 */
	CommentPressJSTOR.API = new function() {

		// Store object refs.
		var me = this;

		/**
		 * Initialise CommentPress JSTOR.
		 *
		 * @since 0.1
		 *
		 * This method should only be called once.
		 */
		this.init = function() {

		};

		/**
		 * Do setup when jQuery reports that the DOM is ready.
		 *
		 * @since 0.1
		 *
		 * This method should only be called once.
		 */
		this.dom_ready = function() {

			// Enable listeners
			me.listeners();

		};

		/**
		 * Initialise listeners.
		 *
		 * @since 0.1
		 *
		 * This method should only be called once.
		 */
		this.listeners = function() {

			/**
			 * Hook into the CommentPress theme "document ready" trigger.
			 *
			 * @since 0.1
			 *
			 * @param {Object} event The clicked object.
			 */
			$(document).on( 'commentpress-document-ready', function( event ) {

				// Enable this plugin.
				CommentPressJSTOR.settings.set_enabled( true );

			});

			/**
			 * Clicks on "Find references in JSTOR articles" text.
			 *
			 * @since 0.1
			 *
			 * @param {Object} event The clicked object.
			 */
			$('#comments_sidebar').on( 'click', '.commentpress_jstor p', function( event ) {

				// Define vars.
				var element = $(this), text_sig;

				// Already triggered?
				if ( element.hasClass( 'commentpress_jstor_triggered' ) ) {
					return;
				}

				// Show spinner.
				$(this).after(
					'<p class="commentpress_jstor_spinner" id="jstor-loading">' +
					'<img src="' + CommentPressJSTOR.settings.get_spinner() + '" alt="' + '" />' +
					'</p>'
				);

				// Get text signature.
				text_sig = element.attr( 'data-jstor-textsig' );

				// Retrieve JSTOR data.
				me.get_data( element, text_sig );

			});

			/**
			 * Rolling onto the JSTOR article snippet.
			 *
			 * @since 0.1
			 *
			 * @param {Object} event The rolled-over object.
			 */
			$('#comments_sidebar').on( 'mouseenter', '.commentpress_jstor_comments .comment-content', function( event ) {

				// Define vars.
				var element = $(this);

				// Call highlight method for this element.
				me.highlight_text( element );

			});

			/**
			 * Rolling off the JSTOR article snippet.
			 *
			 * @since 0.1
			 *
			 * @param {Object} event The rolled-off object.
			 */
			$('#comments_sidebar').on( 'mouseleave', '.commentpress_jstor_comments .comment-content', function( event ) {

				// Clear all highlights.
				me.unhighlight_text();

			});

		};

		/**
		 * Highlight the matched text in the body of the page.
		 *
		 * @since 0.1
		 *
		 * @param {Object} element The element containing the matched text.
		 */
		this.highlight_text = function( element ) {

			// Define vars.
			var match_text, para_text,
				text_sig, textblock_id,
				item, start, end;

			// Get matched text wrapped in <em> tag.
			match_text = element.find( 'em' ).html();

			// Find this in the paragraph's text sig.
			text_sig = element.parents( '.commentpress_jstor_comments' ).attr( 'data-jstor-textsig' );

			// Construct textblock ID.
			textblock_id = 'textblock-' + text_sig;

			// Target para.
			para_text = $('#' + textblock_id).text();

			// Try and find a match.
			start = me.find_start( para_text, match_text );

			// Bail if we can't find the text.
			if ( start === -1 ) {
				return;
			}

			// Find end.
			end = start + match_text.length;

			// Construct item.
			item = { start: start, end: end };

			// Highlight the match using CommentPress.texthighlighter.
			CommentPress.texthighlighter.utilities.selection_restore( document.getElementById( textblock_id ), item );
			$('#' + textblock_id).wrapSelection({fitToWord: false}).addClass( 'inline-highlight-per-comment' );

		};

		/**
		 * Unhighlight all matched text in the body of the page.
		 *
		 * @since 0.1
		 */
		this.unhighlight_text = function( element ) {

			// Clear all highlights.
			CommentPress.texthighlighter.textblocks.highlights_clear_for_comment();

		};

		/**
		 * Parse text to try and find a match.
		 *
		 * @since 0.1
		 *
		 * @param {String} haystack The source text in which to find the needle.
		 * @param {String} needle The snippet to find in the source text.
		 * @return {Integer} start The start of the match (or -1 on failure).
		 */
		this.find_start = function( haystack, needle ) {

			// Declare vars.
			var start, tmp, tmp_length,
			haystack_original = haystack,
			haystack_length = haystack.length,
			needle_length = needle.length;

			// Get start.
			var start = haystack.indexOf( needle );

			// If we find the text, return start.
			if ( start !== -1 ) {
				return start;
			}

			// Trace.
			me.debug( 'Could not find text:', needle );

			// Remove fancy quotes from para text and match text.
			haystack = haystack.replace( /[\u2018\u2019]/g, "'" ).replace( /[\u201C\u201D]/g, '"' );
			needle = needle.replace( /[\u2018\u2019]/g, "'" ).replace( /[\u201C\u201D]/g, '"' );

			// Get start.
			start = haystack.indexOf( needle );

			// If we find the text, return start.
			if ( start !== -1 ) {
				me.debug( 'Found un-smartened text:', needle );
				return start;
			}

			// Trace.
			me.debug( 'Could not find un-smartened text:', needle );

			// Try making para text and match text lowercase.
			haystack = haystack.toLowerCase();
			needle = needle.toLowerCase();

			// Get start.
			start = haystack.indexOf( needle );

			// If we find the text, return start.
			if ( start !== -1 ) {
				me.debug( 'Found lowercase text:', needle );
				return start;
			}

			// Trace.
			me.debug( 'Could not find lowercase text:', needle );

			// Remove the last character, which is often punctuation.
			needle = needle.substr( 0, needle.length - 1 );

			// Get start.
			start = haystack.indexOf( needle );

			// If we find the text, return start.
			if ( start !== -1 ) {
				me.debug( 'Found shortened text:', needle );
				return start;
			}

			// Trace.
			me.debug( 'Could not find shortened text:', needle );

			// Remove all quote marks (already un-smartened) and un-smarten m- and n-dashes.
			haystack = haystack.replace( /[\'\"]/g, '' ).replace( /\u2013|\u2014/g, '-' );
			needle = needle.replace( /[\'\"]/g, '' ).replace( /\u2013|\u2014/g, '-' );

			// Remove all punctuation and concatenate whitespace.
			haystack = haystack.replace( /[.,\/#!$%\^&\*;:{}=\-_`~()]/g, '' ).replace( /\s{2,}/g, ' ' );
			needle = needle.replace( /[.,\/#!$%\^&\*;:{}=\-_`~()]/g, '' ).replace( /\s{2,}/g, ' ' );

			// Get start.
			start = haystack.indexOf( needle );

			// If we find the text, return adjusted start value.
			if ( start !== -1 ) {
				me.debug( 'Found punctuation-free text:', needle );
				diff = me.get_diff( haystack_original, start, needle.length );
				return start + diff;
			}

			// Trace.
			me.debug( 'Could not find punctuation-free text:', needle );

			// Replace various words in para text and match text.
			haystack = haystack.replace( /traveling/g, "travelling" ).replace( /&amp;/g, "&" );
			needle = needle.replace( /traveling/g, "travelling" ).replace( /&amp;/g, "&" );

			// Remove little words (let's start with "i").
			haystack = haystack.replace( / i /g, ' ' );
			needle = needle.replace( / i /g, ' ' );

			// Get start.
			start = haystack.indexOf( needle );

			// If we find the text, return adjusted start value.
			if ( start !== -1 ) {
				me.debug( 'Found word-replaced text:', needle );
				diff = me.get_diff( haystack_original, start, needle.length );
				return start + diff;
			}

			// Trace.
			me.debug( 'Could not find word-replaced text:', needle );

			// --<
			return start;

		};

		/**
		 * Get the rought difference in length between original and parsed string.
		 *
		 * We need to find out how many chars have been removed from the needle
		 * by removing punctuation. This is necessarily going to be an inexact
		 * figure, but should help a bit.
		 *
		 * @since 0.1
		 *
		 * @param {String} original The original string.
		 * @param {Integer} start The position in the original string.
		 * @param {Integer} leeway The length of the match as leeway.
		 * @return {Array} data The data.
		 */
		this.get_diff = function( original, start, leeway ) {

			// Declare vars.
			var tmp, tmp_length;

			// Get preceding text.
			tmp = original.substr( 0, start + leeway );

			// Store initial length of preceding text.
			tmp_length = tmp.length;

			// Perform the same punctuation removal.
			tmp = tmp.replace( /[\'\"]/g, '' ).replace( /\u2013|\u2014/g, '-' );
			tmp = tmp.replace( /[.,\/#!$%\^&\*;:{}=\-_`~()]/g, '' ).replace( /\s{2,}/g, ' ' );

			// --<
			return tmp_length - tmp.length;

		};

		/**
		 * Get some data.
		 *
		 * To retrieve same sample data fro mthe command line:
		 * $ curl -H "Authorization: Token YOUR-TOKEN" https://labs.jstor.org/apis/matchmaker/
		 *
		 * @since 0.1
		 *
		 * @param {Object} element The jQuery paragraph object.
		 * @param {String} text_sig The text signature of the paragraph.
		 * @return {Array} data The data.
		 */
		this.get_data = function( element, text_sig ) {

			var request;

			// Perform AJAX look-up.
			request = $.ajax({

				// Authentication header.
				beforeSend: function( xhr ) {
					xhr.setRequestHeader( 'Authorization', 'Token ' + CommentPressJSTOR.settings.get_token() );
				},

				// API endpoint.
				url: 'https://labs.jstor.org/apis/matchmaker/',
				method: "GET",

				// Data.
				data: {
					work: CommentPressJSTOR.settings.get_work(),
					chunk_ids: 'textblock-' + text_sig,
					similarity: '[0.9 TO *]',
					match_size: '[20 TO *]',
					limit: 1000,
					fields: CommentPressJSTOR.settings.get_fields()
				},

				// Expected data type.
				dataType: 'json'

			})

			// Callback on success.
			request.done( function( data ) {

				var docs, i, doc, stable_url, comment_html, m, match, page_link, item, text_sig_data;

				// Did we get any?
				if ( data.count == 0 ) {

					// Hide the spinner.
					$('#jstor-loading').remove();

					// Costruct markup.
					comment_html = '<p class="comment-not-found">' + CommentPress_JSTOR_Settings.localisation.not_found + '</p>';

					// Convert markup to jQuery object.
					item = $('<div class="commentpress_jstor_comments">').html( comment_html );

					// Append to trigger.
					item.appendTo( element.parent() )
						.hide()
						.slideDown( 'fast', function() {
							// After slide.
							setTimeout(function () {
								item.slideUp( 'fast' );
							}, 2000 );
						});

					// Bail!
					return;

				}

				// Add class to trigger element.
				element.addClass( 'commentpress_jstor_triggered' );

				// Switch out trigger text.
				element.html( CommentPress_JSTOR_Settings.localisation.triggered_text );

				// Aggregate.
				docs = me.aggregate_data( data );

				// Init markup.
				comment_html = '<ol>';

				// Format.
				for( i = 0; i < docs.length; i++ ) {

					// Get document.
					doc = docs[i];

					// Construct permalink.
					stable_url = 'http://www.jstor.org/stable/' + doc.docid;

					// Open list item.
					comment_html += '<li>';

					// Open wrapper.
					comment_html += '<div class="comment-wrapper">';

					// Comment identifier.
					comment_html += '<div class="comment-identifier">';
					comment_html += '<h4 class="comment-title">';
					comment_html += '<a href="' + stable_url + '?labs=matchmaker">' + doc.title + '</a>';
					comment_html += '</h4>';
					if ( doc.authors ) {
						comment_html += '<cite class="fn">' + doc.authors + ', ';
					}
					if ( doc.journal ) {
						comment_html += '<span class="doc_journal">' + doc.journal + '</span>, ';
					}
					if ( doc.pubyear ) {
						comment_html += '<span class="doc_year">' + doc.pubyear + '</span>';
					}
					if ( doc.authors ) {
						comment_html += '</cite>';
					}
					comment_html += '</div><!-- /comment-identifier -->';

					// Collect aggregated comments.
					for( m = 0; m < doc.matches.length; m++ ) {

						// Get match.
						match = doc.matches[m];

						// more granular link.
						page_link = stable_url + '?seq=' + match.snippet.page + '&amp;labs=matchmaker';

						// Comment content.
						comment_html += '<div class="comment-content">';
						comment_html += '&hellip;' + match.snippet.text.replace( /"/g, '&quot;' ) + '&hellip;';
						comment_html += '</div><!-- /comment-content -->';

						// Comment JSTOR link.
						comment_html += '<div class="reply">';
						comment_html += '<a href="' + page_link + '"' + CommentPressJSTOR.settings.get_link() + '>' + CommentPress_JSTOR_Settings.localisation.snippet_link + '</a>';
						comment_html += '</div>';

					}

					// Close wrapper.
					comment_html += '</div><!-- /comment-wrapper -->';

					// Close list item.
					comment_html += '</li>';

				}

				// Close list.
				comment_html += '</ol>';

				// Hide the spinner.
				$('#jstor-loading').remove();

				// Add text signature for access during rollovers.
				text_sig_data = ' data-jstor-textsig="' + text_sig + '"';

				// Convert markup to jQuery object.
				item = $('<div class="commentpress_jstor_comments"' + text_sig_data + '>').html( comment_html );

				// Append to trigger.
				item.appendTo( element.parent() )
					.hide()
					.slideDown( 'fast', function() {
						// After slide
					});

			});

			// Callback on failure.
			request.fail( function( jqXHR, textStatus ) {

				// Hide the spinner.
				$('#jstor-loading').remove();

				me.debug( 'request failed', textStatus );

			});

		};

		/**
		 * Aggregates raw match data by docid for display.
		 *
		 * @since 0.1
		 *
		 * @param {Array} raw_data The raw data.
		 * @return {Array} aggregated The aggregated data.
		 */
		this.aggregate_data = function( raw_data ) {

			// Declare vars.
			var by_docid = {}, match, aggregated = [], docid;

			// Loop through docs in raw data.
			for( var i = 0; i < raw_data.docs.length; i++ ) {

				// Grab the item.
				match = raw_data.docs[i];

				// Have we created the docid key yet?
				if ( ! ( match.docid in by_docid ) ) {

					// Nope - create it.
					by_docid[match.docid] = {
						docid:    match.docid,
						journal:  match.journal,
						title:    match.title,
						authors:  match.authors,
						pubyear:  match.pubyear,
						keyterms: match.keyterms,
						matches:  []
					}

				}

				// Add to matches array, keyed by docid.
				by_docid[match.docid]['matches'].push({
					work:       match.work,
					chunk_ids:  match.chunk_ids,
					similarity: match.similarity,
					match_size: match.match_size,
					work_text:  match.work_text,
					snippet:    {
						page:       match.pages[0],
						text:       match.snippet,
						similarity: match.similarity,
						size:       match.match_size,
						source:     match.source
					}
				});

			}

			// Reassemble into aggregated.
			for ( docid in by_docid ) {
				aggregated.push( by_docid[docid] );
			}

			// Sort.
			aggregated.sort( function( a, b ) {
				return b.matches[0].similarity * b.matches[0].match_size - a.matches[0].similarity * a.matches[0].match_size;
			});

			// --<
			return aggregated;

		};

		/**
		 * Escape a string.
		 *
		 * @see http://shebang.brandonmintern.com/foolproof-html-escaping-in-javascript/
		 *
		 * @since 0.1
		 *
		 * @param {String} str The string to escape.
		 * @return {String} div.innerHTML The escaped string.
		 */
		this.escape_string = function( str ) {
			var div = document.createElement( 'div' );
			div.appendChild( document.createTextNode( str ) );
			return div.innerHTML;
		};

		/**
		 * Debugging.
		 *
		 * @since 0.1
		 *
		 * @param {String} message The message to display.
		 * @param {Mixed} variable The variable to display.
		 */
		this.debug = function( message, variable ) {
			if ( console && console.log ) {
				console.log( message, variable );
			}
		};

	};

	// Init settings.
	CommentPressJSTOR.settings.init();

	// Init API.
	CommentPressJSTOR.API.init();

});

/**
 * Trigger dom_ready methods where necessary.
 *
 * @since 0.1
 */
jQuery(document).ready(function($) {

	// The DOM is loaded now.
	CommentPressJSTOR.settings.dom_ready();

	// The DOM is loaded now.
	CommentPressJSTOR.API.dom_ready();

});
