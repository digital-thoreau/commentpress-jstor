/**
 * CommentPress JSTOR Javascript.
 *
 * Implements access to JSTOR Matchmaker tool from The Reader's Thoreau site.
 *
 * @package WordPress
 * @subpackage Commentpress_JSTOR
 */



/**
 * Create CommentPress JSTOR object.
 *
 * This works as a "namespace" of sorts, allowing us to hang properties, methods
 * and "sub-namespaces" from it.
 */
var CommentPressJSTOR = CommentPressJSTOR || {};



/**
 * Set up CommentPress JSTOR when the page is ready.
 */
jQuery(document).ready( function($) {

	/**
	 * Create CommentPress JSTOR Settings Object.
	 */
	CommentPressJSTOR.settings = new function() {

		// store object refs
		var me = this;

		/**
		 * Initialise CommentPress JSTOR Settings.
		 *
		 * This method should only be called once.
		 *
		 * @return void
		 */
		this.init = function() {

			// init spinner
			me.init_spinner();

			// init JSTOR work code
			me.init_work();

			// init JSTOR token
			me.init_token();

			// init JSTOR fields
			me.init_fields();

		};

		/**
		 * Do setup when jQuery reports that the DOM is ready.
		 *
		 * This method should only be called once.
		 *
		 * @return void
		 */
		this.dom_ready = function() {

		};

		// not enabled until CommentPress is ready
		me.enabled = false;

		/**
		 * Setter for enabled flag.
		 *
		 * @param {Boolean} flag Whether or not this functionality works
		 * @return void
		 */
		this.set_enabled = function( flag ) {
			me.enabled = flag;
		};

		/**
		 * Getter for enabled flag.
		 *
		 * @return {Boolean} The enabled flag
		 */
		this.get_enabled = function() {
			return me.enabled;
		};

		// default spinner
		me.spinner = '';

		/**
		 * Init spinner from settings object.
		 *
		 * By doing this, we can override the default value.
		 *
		 * @return void
		 */
		this.init_spinner = function() {
			if ( 'undefined' !== typeof CommentPress_JSTOR_Settings.interface.spinner ) {
				me.spinner = CommentPress_JSTOR_Settings.interface.spinner;
			}
		};

		/**
		 * Setter for spinner.
		 *
		 * @param {String} val The spinner URL
		 * @return void
		 */
		this.set_spinner = function( val ) {
			me.spinner = val;
		};

		/**
		 * Getter for spinner URL.
		 *
		 * @return {String} The spinner URL
		 */
		this.get_spinner = function() {
			return me.spinner;
		};

		// init JSTOR work code
		me.work = '';

		/**
		 * Init JSTOR work code from settings object.
		 *
		 * By doing this, we can override the default value.
		 *
		 * @return void
		 */
		this.init_work = function() {
			if ( 'undefined' !== typeof CommentPress_JSTOR_Settings.jstor.work ) {
				me.work = CommentPress_JSTOR_Settings.jstor.work;
			}
		};

		/**
		 * Setter for JSTOR work code.
		 *
		 * @param {String} val The JSTOR work code
		 * @return void
		 */
		this.set_work = function( val ) {
			me.work = val;
		};

		/**
		 * Getter for JSTOR work code.
		 *
		 * @return {String} The JSTOR work code
		 */
		this.get_work = function() {
			return me.work;
		};

		// init token
		me.token = '';

		/**
		 * Init JSTOR token from settings object.
		 *
		 * By doing this, we can override the default value.
		 *
		 * @return void
		 */
		this.init_token = function() {
			if ( 'undefined' !== typeof CommentPress_JSTOR_Settings.jstor.token ) {
				me.token = CommentPress_JSTOR_Settings.jstor.token;
			}
		};

		/**
		 * Setter for JSTOR token.
		 *
		 * @param {String} val The JSTOR token
		 * @return void
		 */
		this.set_token = function( val ) {
			me.token = val;
		};

		/**
		 * Getter for JSTOR token.
		 *
		 * @return {String} The JSTOR token
		 */
		this.get_token = function() {
			return me.token;
		};

		// init fields
		me.fields = '';

		/**
		 * Init JSTOR fields from settings object.
		 *
		 * By doing this, we can override the default value.
		 *
		 * @return void
		 */
		this.init_fields = function() {
			if ( 'undefined' !== typeof CommentPress_JSTOR_Settings.jstor.fields ) {
				me.fields = CommentPress_JSTOR_Settings.jstor.fields;
			}
		};

		/**
		 * Setter for JSTOR fields.
		 *
		 * @param {String} val The JSTOR fields
		 * @return void
		 */
		this.set_fields = function( val ) {
			me.fields = val;
		};

		/**
		 * Getter for JSTOR fields.
		 *
		 * @return {String} The JSTOR fields
		 */
		this.get_fields = function() {
			return me.fields;
		};

	};

	/**
	 * Create CommentPress JSTOR API Object.
	 */
	CommentPressJSTOR.API = new function() {

		// store object refs
		var me = this;

		/**
		 * Initialise CommentPress JSTOR.
		 *
		 * This method should only be called once.
		 *
		 * @return void
		 */
		this.init = function() {

		};

		/**
		 * Do setup when jQuery reports that the DOM is ready.
		 *
		 * This method should only be called once.
		 *
		 * @return void
		 */
		this.dom_ready = function() {

			// enable listeners
			me.listeners();

		};

		/**
		 * Initialise listeners.
		 *
		 * This method should only be called once.
		 *
		 * @return void
		 */
		this.listeners = function() {

			/**
			 * Hook into the CommentPress theme "document ready" trigger
			 *
			 * @param {Object} event The clicked object
			 * @return void
			 */
			$(document).on( 'commentpress-document-ready', function( event ) {

				// enable this plugin
				CommentPressJSTOR.settings.set_enabled( true );

			});

			/**
			 * Clicks on "Find references in JSTOR articles" text
			 *
			 * @param {Object} event The clicked object
			 * @return void
			 */
			$('#comments_sidebar').on( 'click', '.commentpress_jstor p', function( event ) {

				// define vars
				var element = $(this), text_sig;

				// show spinner
				$(this).after(
					'<p class="commentpress_jstor_spinner" id="jstor-loading">' +
					'<img src="' + CommentPressJSTOR.settings.get_spinner() + '" alt="' + '" />' +
					'</p>'
				);

				// get text signature
				text_sig = element.attr( 'data-jstor-textsig' );

				// retrieve JSTOR data
				me.get_data( element, text_sig );

			});

			/**
			 * Rolling onto the JSTOR article snippet.
			 *
			 * @param {Object} event The rolled-over object
			 * @return void
			 */
			$('#comments_sidebar').on( 'mouseenter', '.commentpress_jstor_comments .comment-content', function( event ) {

				// define vars
				var element = $(this);

				// call highlight method for this element
				me.highlight_text( element );

			});

			/**
			 * Rolling off the JSTOR article snippet.
			 *
			 * @param {Object} event The rolled-off object
			 * @return void
			 */
			$('#comments_sidebar').on( 'mouseleave', '.commentpress_jstor_comments .comment-content', function( event ) {

				// clear all highlights
				me.unhighlight_text();

			});

		};

		/**
		 * Highlight the matched text in the body of the page.
		 *
		 * @param {Object} element The element containing the matched text
		 * @return void
		 */
		this.highlight_text = function( element ) {

			// define vars
			var match_text, para_text,
				text_sig, textblock_id,
				item, start, end;

			// get matched text wrapped in <em> tag
			match_text = element.find( 'em' ).html();

			// find this in the paragraph's text sig
			text_sig = element.parents( '.commentpress_jstor_comments' ).attr( 'data-jstor-textsig' );

			textblock_id = 'textblock-' + text_sig

			// target para
			para_text = $('#' + textblock_id).text();

			// get start
			start = para_text.indexOf( match_text );

			// if we can't find the text
			if ( start === -1 ) {

				// trace
				me.debug( 'Could not find text:', match_text );

				// try removing fancy quotes from para text and match text
				para_text = para_text.replace(/[\u2018\u2019]/g, "'").replace(/[\u201C\u201D]/g, '"');
				match_text = match_text.replace(/[\u2018\u2019]/g, "'").replace(/[\u201C\u201D]/g, '"');

				// get start
				start = para_text.indexOf( match_text );

				// if we can't find the text
				if ( start === -1 ) {

					// trace
					me.debug( 'Could not find un-smartened text:', match_text );

					// try making para text and match text lowercase
					para_text = para_text.toLowerCase();
					match_text = match_text.toLowerCase();

					// get start
					start = para_text.indexOf( match_text );

					// if we can't find the text
					if ( start === -1 ) {

						// trace
						me.debug( 'Could not find lowercase text:', match_text );

						// try replacing various words in para text and match text
						para_text = para_text.replace(/traveling/g, "travelling");
						match_text = match_text.replace(/traveling/g, "travelling");

						// get start
						start = para_text.indexOf( match_text );

						// if we can't find the text
						if ( start === -1 ) {

							// trace
							me.debug( 'Could not find word-replaced text:', match_text );

							// try removing the last character, which is often punctuation
							match_text = match_text.substr( 0, match_text.length - 1 );

							// get start
							start = para_text.indexOf( match_text );

							// if we can't find the text
							if ( start === -1 ) {

								// trace
								me.debug( 'Could not find shortened text:', match_text );

								// if all of this fails, bail
								return;

							}

						}

					}

				}

			}

			// find end
			end = start + match_text.length;

			// construct item
			item = { start: start, end: end };

			// highlight the match using CommentPress.texthighlighter
			CommentPress.texthighlighter.utilities.selection_restore( document.getElementById( textblock_id ), item );
			$('#' + textblock_id).wrapSelection({fitToWord: false}).addClass( 'inline-highlight-per-comment' );

		};

		/**
		 * Unhighlight all matched text in the body of the page.
		 *
		 * @return void
		 */
		this.unhighlight_text = function( element ) {

			// clear all highlights
			CommentPress.texthighlighter.textblocks.highlights_clear_for_comment();

		};

		/**
		 * Get some data.
		 *
		 * To retrieve same sample data fro mthe command line:
		 * $ curl -H "Authorization: Token YOUR-TOKEN" https://labs.jstor.org/apis/matchmaker/
		 *
		 * @param {Object} element The jQuery paragraph object
		 * @param {String} text_sig The text signature of the paragraph
		 * @return {Array} data The data
		 */
		this.get_data = function( element, text_sig ) {

			var request;

			// perform AJAX look-up
			request = $.ajax({

				// authentication header
				beforeSend: function( xhr ) {
					xhr.setRequestHeader( 'Authorization', 'Token ' + CommentPressJSTOR.settings.get_token() );
				},

				// API endpoint
				url: 'https://labs.jstor.org/apis/matchmaker/',
				method: "GET",

				// data
				data: {
					work: CommentPressJSTOR.settings.get_work(),
					chunk_ids: 'textblock-' + text_sig,
					similarity: '[0.9 TO *]',
					match_size: '[20 TO *]',
					limit: 1000,
					fields: CommentPressJSTOR.settings.get_fields()
				},

				// expected data type
				dataType: 'json'

			})

			// callback on success
			request.done( function( data ) {

				var docs, i, doc, stable_url, comment_html, m, match, page_link, item, text_sig_data;

				// did we get any?
				if ( data.count == 0 ) {

					// hide the spinner
					$('#jstor-loading').remove();

					// costruct markup
					comment_html = '<p class="comment-not-found">' + CommentPress_JSTOR_Settings.localisation.not_found + '</p>';

					// convert markup to jQuery object
					item = $('<div class="commentpress_jstor_comments">').html( comment_html );

					// append to trigger
					item.appendTo( element.parent() )
						.hide()
						.slideDown( 'fast', function() {
							// after slide
							setTimeout(function () {
								item.slideUp( ' fast' );
							}, 2000 );
						});

					// bail!
					return;

				}

				// aggregate
				docs = me.aggregate_data( data );

				// init markup
				comment_html = '<ol>';

				// format
				for( i = 0; i < docs.length; i++ ) {

					// get document
					doc = docs[i];

					// construct permalink
					stable_url = 'http://www.jstor.org/stable/' + doc.docid;

					// open list item
					comment_html += '<li>';

					// open wrapper
					comment_html += '<div class="comment-wrapper">';

					// comment identifier
					comment_html += '<div class="comment-identifier">';
					comment_html += '<h4 class="comment-title">';
					comment_html += '<a href="' + stable_url + '?labs=matchmaker">' + doc.title + '</a>';
					comment_html += '</h4>';
					if ( doc.authors ) { comment_html += '<cite class="fn">' + doc.authors + ', '; }
					if ( doc.journal ) { comment_html += '<span class="doc_journal">' + doc.journal + '</span>, '; }
					if ( doc.pubyear ) { comment_html += '<span class="doc_year">' + doc.pubyear + '</span>'; }
					if ( doc.authors ) { comment_html += '</cite>'; }
					comment_html += '</div><!-- /comment-identifier -->';

					// collect aggregated comments
					for( m = 0; m < doc.matches.length; m++ ) {

						// get match
						match = doc.matches[m];

						// more granular link
						page_link = stable_url + '?seq=' + match.snippet.page + '&amp;labs=matchmaker';

						// comment content
						comment_html += '<div class="comment-content">';
						comment_html += '&hellip;' + match.snippet.text.replace( /"/g, '&quot;' ) + '&hellip;';
						comment_html += '</div><!-- /comment-content -->';

						// comment JSTOR link
						comment_html += '<div class="reply">';
						comment_html += '<a href="' + page_link + '">' + CommentPress_JSTOR_Settings.localisation.snippet_link + '</a>';
						comment_html += '</div>';

					}

					// close wrapper
					comment_html += '</div><!-- /comment-wrapper -->';

					// close list item
					comment_html += '</li>';

				}

				// close list
				comment_html += '</ol>';

				// hide the spinner
				$('#jstor-loading').remove();

				// add text signature for access during rollovers
				text_sig_data = ' data-jstor-textsig="' + text_sig + '"';

				// convert markup to jQuery object
				item = $('<div class="commentpress_jstor_comments"' + text_sig_data + '>').html( comment_html );

				// append to trigger
				item.appendTo( element.parent() )
					.hide()
					.slideDown( 'fast', function() {
						// after slide
					});

			});

			// callback on failure
			request.fail( function( jqXHR, textStatus ) {

				// hide the spinner
				$('#jstor-loading').remove();

				me.debug( 'request failed', textStatus );

			});

		};

		/**
		 * Aggregates raw match data by docid for display.
		 *
		 * @param {Array} raw_data The raw data
		 * @return {Array} aggregated The aggregated data
		 */
		this.aggregate_data = function( raw_data ) {

			// declare vars
			var by_docid = {}, match, aggregated = [], docid;

			// loop through docs in raw data
			for( var i = 0; i < raw_data.docs.length; i++ ) {

				// grab the item
				match = raw_data.docs[i];

				// have we created the docid key yet?
				if ( ! ( match.docid in by_docid ) ) {

					// nope - create it
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

				// add to matches array, keyed by docid
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

			// reassemble into aggregated
			for ( docid in by_docid ) {
				aggregated.push( by_docid[docid] );
			}

			// sort
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
		 * @param {String} str The string to escape
		 * @return {String} div.innerHTML The escaped string
		 */
		this.escape_string = function( str ) {
			var div = document.createElement( 'div' );
			div.appendChild( document.createTextNode( str ) );
			return div.innerHTML;
		};

		/**
		 * Debugging.
		 *
		 * @param {String} message The message to display
		 * @param {Mixed} variable The variable to display
		 * @return void
		 */
		this.debug = function( message, variable ) {
			if ( console && console.log ) {
				//console.log( message, variable );
			}
		};

	};

	// init settings
	CommentPressJSTOR.settings.init();

	// init API
	CommentPressJSTOR.API.init();

}); // end jQuery document ready



/**
 * Trigger dom_ready methods where necessary.
 *
 * @return void
 */
jQuery(document).ready(function($) {

	// The DOM is loaded now
	CommentPressJSTOR.settings.dom_ready();

	// The DOM is loaded now
	CommentPressJSTOR.API.dom_ready();

}); // end document.ready()
