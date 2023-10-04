( function( $ ) {
	"use strict";

	$( document ).ready( function() {

		var _custom_media_logo = true,
			_custom_media = true,
			_orig_send_attachment = wp.media.editor.send.attachment;

		$( '.favethemes-screenshot-upload-button' ).click(function(e) {

			var send_attachment_bkp	= wp.media.editor.send.attachment,
				button = $(this),
				id = button.prev();
				_custom_media = true;

			wp.media.editor.send.attachment = function( props, attachment ) {
				if ( _custom_media ) { 
					$( id ).val( attachment.url );
					var $preview = button.parents('.field-wrap').find( '.favethemes-media-live-preview img' ),
						$remove  = button.parent().find( '.favethemes-media-remove' );
					if ( $remove.length ) {
						$remove.show();
					}
					if ( $preview.length ) { 
						$preview.attr( 'src', attachment.url );
					} else {
						$preview = button.parents('.field-wrap').find('.favethemes-media-live-preview');
						var $imgSize = $preview.data( 'image-size' ) ? $preview.data( 'image-size' ) : 'auto';
						$preview.show().append( '<img src="'+ attachment.url +'" style="height:'+ $imgSize +'px;width:'+ $imgSize +'px;" />' );
					}
				} else {  
					return _orig_send_attachment.apply( this, [props, attachment] );
				};
			}

			wp.media.editor.open( button );
			return false;

		} );

		$( '.add_media').on('click', function() {
			_custom_media = false;
		} );


		$( '.favethemes-media-live-preview' ).each( function( index ) {
			var $this     = $( this ),
				$input    = $this.parent().find( '.favethemes-media-input' ),
				$inputVal = $input.val();
			if ( $inputVal ) {
				$this.show();
			}
		} );

		$( '.favethemes-media-remove' ).each( function( index ) {
			var $this     = $( this ),
				$input    = $this.parent().find( '.favethemes-media-input' ),
				$inputVal = $input.val(),
				$preview  = $this.parents('.field-wrap').find('.favethemes-media-live-preview');
			if ( $inputVal ) {
				$this.show();
			}
			$this.on('click', function() {
				$input.val( '' );
				$preview.find( 'img' ).remove();
				$this.hide();
				return false;
			} );
		} );


		$( '.favethemes-logo-upload-button' ).click(function(e) {

			var send_attachment_bkp	= wp.media.editor.send.attachment,
				button = $(this),
				id = button.prev();
				_custom_media_logo = true;

			wp.media.editor.send.attachment = function( props, attachment ) {
				if ( _custom_media_logo ) {
					$( id ).val( attachment.url );
					var $preview = button.parents('.field-wrap').find( '.favethemes-logo-live-preview img' ),
						$remove  = button.parent().find( '.favethemes-logo-remove' );
					if ( $remove.length ) {
						$remove.show();
					}
					if ( $preview.length ) {
						$preview.attr( 'src', attachment.url );
					} else {
						$preview = button.parents('.field-wrap').find('.favethemes-logo-live-preview');
						var $imgSize = $preview.data( 'image-size' ) ? $preview.data( 'image-size' ) : 'auto';
						$preview.show().append( '<img src="'+ attachment.url +'" style="height:'+ $imgSize +'px;width:'+ $imgSize +'px;" />' );
					}
				} else {
					return _orig_send_attachment.apply( this, [props, attachment] );
				};
			}

			wp.media.editor.open( button );
			return false;

		} );

		$( '.add_media').on('click', function() {
			_custom_media_logo = false;
		} );

		$( '.favethemes-logo-live-preview' ).each( function( index ) {
			var $this     = $( this ),
				$input    = $this.parent().find( '.favethemes-logo-input' ),
				$inputVal = $input.val();
			if ( $inputVal ) {
				$this.show();
			}
		} );

		$( '.favethemes-logo-remove' ).each( function( index ) {
			var $this     = $( this ),
				$input    = $this.parent().find( '.favethemes-logo-input' ),
				$inputVal = $input.val(),
				$preview  = $this.parents('.field-wrap').find('.favethemes-logo-live-preview');
			if ( $inputVal ) {
				$this.show();
			}
			$this.on('click', function() {
				$input.val( '' );
				$preview.find( 'img' ).remove();
				$this.hide();
				return false;
			} );
		} );



	} );

} ) ( jQuery );