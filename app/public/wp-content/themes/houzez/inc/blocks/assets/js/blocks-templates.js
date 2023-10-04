'use strict';

(($) => {
	if (typeof elementor === 'undefined' || typeof elementorCommon === 'undefined') {
		return;
	}

	elementor.on('preview:loaded', () => {
		let dialog = null;
		//Houzez Template button
		let buttons = $('#tmpl-elementor-add-section');

		const text = buttons.text().replace(
			'<div class="elementor-add-section-drag-title',
			'<div class="elementor-add-section-area-button houzez-library-modal-btn" title="Houzez Templates">Houzez Templates</div><div class="elementor-add-section-drag-title'
		);

		buttons.text(text);

		// Call modal.
		$(elementor.$previewContents[0].body).on('click', '.houzez-library-modal-btn', () => {
			if (dialog) {
				dialog.show();
				return;
			}

			var modalOptions = {
				id: 'houzez-library-modal',
				headerMessage: $('#tmpl-elementor-houzez-library-modal-header').html(),
				message: $('#tmpl-elementor-houzez-library-modal').html(),
				className: 'elementor-templates-modal',
				closeButton: true,
				draggable: false,
				hide: {
					onOutsideClick: true,
					onEscKeyPress: true
				},
				position: {
					my: 'center',
					at: 'center'
				}
			};
			dialog = elementorCommon.dialogsManager.createWidget('lightbox', modalOptions);
			dialog.show();

			loadTemplates();
		});

		// Load items.
		function loadTemplates() {
			showLoader();

			$.ajax({
				url: 'https://studio.houzez.co/wp-json/favethemes-blocks/v1/templates',
				method: 'GET',
				dataType: 'json',
				success: function (response) {
					if (response && response.elements) {
						var itemTemplate = wp.template('elementor-houzez-library-modal-item');
						var itemOrderTemplate = wp.template('elementor-houzez-library-modal-order');

						$(itemTemplate(response)).appendTo($('#houzez-library-modal #elementor-template-library-templates-container'));
						$(itemOrderTemplate(response)).appendTo($('#houzez-library-modal #elementor-template-library-filter-toolbar-remote'));

						importTemplate();
						hideLoader();
					} else {
						$('<div class="houzez-notice houzez-error">The library can\'t be loaded from the server.</div>').appendTo($('#houzez-library-modal #elementor-template-library-templates-container'));
						hideLoader();
					}
				},
				error: function () {
					$('<div class="houzez-notice houzez-error">The library can\'t be loaded from the server.</div>').appendTo($('#houzez-library-modal #elementor-template-library-templates-container'));
					hideLoader();
				}
			});
		}

		function showLoader() {
			$('#houzez-library-modal #elementor-template-library-templates').hide();
			$('#houzez-library-modal .elementor-loader-wrapper').show();
		}

		function hideLoader() {
			$('#houzez-library-modal #elementor-template-library-templates').show();
			$('#houzez-library-modal .elementor-loader-wrapper').hide();
		}

		function activateUpdateButton() {
			$('#elementor-panel-saver-button-publish').toggleClass('elementor-disabled');
			$('#elementor-panel-saver-button-save-options').toggleClass('elementor-disabled');
		}

		function importTemplate() {
			$('#houzez-library-modal .elementor-template-library-template-insert').on('click', function () {
				showLoader();

				var config = {
					data: {
						source: 'houzez',
						edit_mode: true,
						display: true,
						template_id: $(this).data('id'),
						with_page_settings: false
					},
					success: function success(data) {
						if (data && data.content) {
							elementor.getPreviewView().addChildModel(data.content);
							dialog.hide();
							setTimeout(function () {
								hideLoader();
							}, 2000);
							activateUpdateButton();
						} else {
							$('<div class="houzez-notice houzez-error">The element can\'t be loaded from the server.</div>').prependTo($('#houzez-library-modal #elementor-template-library-templates-container'));
							hideLoader();
						}
					},
					error: function () {
						$('<div class="houzez-notice houzez-error">The element can\'t be loaded from the server.</div>').prependTo($('#houzez-library-modal #elementor-template-library-templates-container'));
						hideLoader();
					}
				};

				return elementorCommon.ajax.addRequest('get_template_data', config);
			});

			$('#houzez-library-modal .elementor-templates-modal__header__close').on('click', () => {
				dialog.hide();
				hideLoader();
			});

			$('#houzez-library-modal #elementor-template-library-filter-text').on('keyup', function () {
				var search = $(this).val().toLowerCase();
				var activeTab = document.querySelector('#elementor-houzez-library-header-menu .elementor-active').getAttribute('data-tab');

				$('#houzez-library-modal').find('.elementor-template-library-template').each(function () {
					const $this = $(this);
					const slug = $this.data('slug');
					const type = $this.data('type');

					if (slug.includes(search) && type.includes(activeTab)) {
						$this.show();
					} else {
						$this.hide();
					}
				});
			});

			// Filter by tag
			$('#houzez-library-modal #elementor-template-library-filter-subtype').on('change', function () {
				var val = $(this).val();

				$('#houzez-library-modal').find('.elementor-template-library-template-block').each(function () {
					var $this = $(this);
					var slug = $this.data('slug').toLowerCase();

					if ( slug.indexOf(val) > -1 || val == 'all') {
						$this.show();
					} else {
						$this.hide();
					}
				});
			});

			function setActiveTab (tab) {
				$('#houzez-library-modal .elementor-template-library-menu-item').removeClass('elementor-active');
				const activeTab = $('#houzez-tab-' + tab);
				activeTab.addClass('elementor-active');

				document.querySelectorAll('#houzez-library-modal .elementor-template-library-template').forEach(e => {
					const type = e.getAttribute('data-type');
					e.style.display = type === tab ? 'block' : 'none';
					
					if (tab === 'template') {
						$('#elementor-template-library-filter').hide();
					} else {
						$('#elementor-template-library-filter').show();
					}
				});
			}

			setActiveTab('block');

			// Filter by type
			$('#houzez-library-modal .elementor-template-library-menu-item').on('click', function () {
				setActiveTab($(this).data('tab'));
			});
		}
	});
})(jQuery);