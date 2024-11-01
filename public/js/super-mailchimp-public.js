
(function ($) {
	'use strict';

	const MailChimpNewsletter = {
		isValidEmail: (email) => {
			return /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/i.test(email);
		},

		clearForm: (form) => {
			let $form = $(form).find('.fn-form-wrapper form');
			let $emailInput = $(form).find('.fn-email');
			$emailInput.val('');
			MailChimpNewsletter.setState($form);
		},

		setState: (form, state) => {
			let $formWrapper = $(form).find('.fn-form-wrapper');
			let $confirmWrapper = $(form).find('.fn-confirm-wrapper');
			let $loadingWrapper = $(form).find('.fn-loading-wrapper');
			let $msgInvalid = $formWrapper.find('.fn-invalid');
			let $msgError = $formWrapper.find('.fn-error');
			let $msgSuccess = $formWrapper.find('.fn-success');

			switch (state) {
				case 'invalid':
					$formWrapper.addClass('mailchimp-newsletter__form-wrapper--active');
					$confirmWrapper.removeClass('mailchimp-newsletter__confirm-wrapper--active');
					$loadingWrapper.removeClass('mailchimp-newsletter__loading-wrapper--active');

					$msgInvalid.show();
					$msgError.hide();
					$msgSuccess.hide();
					break;
				case 'error':
					$formWrapper.addClass('mailchimp-newsletter__form-wrapper--active');
					$confirmWrapper.removeClass('mailchimp-newsletter__confirm-wrapper--active');
					$loadingWrapper.removeClass('mailchimp-newsletter__loading-wrapper--active');

					$msgError.show();
					$msgInvalid.hide();
					$msgSuccess.hide();
					break;
				case 'success':
					$formWrapper.addClass('mailchimp-newsletter__form-wrapper--active');
					$confirmWrapper.removeClass('mailchimp-newsletter__confirm-wrapper--active');
					$loadingWrapper.removeClass('mailchimp-newsletter__loading-wrapper--active');

					$msgSuccess.show();
					$msgInvalid.hide();
					$msgError.hide();

					setTimeout(() => {
						MailChimpNewsletter.clearForm(form);
					}, 1500);

					break;
				default:
					$msgInvalid.hide();
					$msgError.hide();
					$msgSuccess.hide();
					break;
			}
		},

		gotoForm: (form) => {
			let $formWrapper = $(form).find('.fn-form-wrapper');
			let $confirmWrapper = $(form).find('.fn-confirm-wrapper');
			let $loadingWrapper = $(form).find('.fn-loading-wrapper');

			$confirmWrapper.removeClass('mailchimp-newsletter__confirm-wrapper--active');
			$loadingWrapper.removeClass('mailchimp-newsletter__loading-wrapper--active');
			$formWrapper.addClass('mailchimp-newsletter__form-wrapper--active');
		},

		gotoLoading: (form) => {
			let $formWrapper = $(form).find('.fn-form-wrapper');
			let $confirmWrapper = $(form).find('.fn-confirm-wrapper');
			let $loadingWrapper = $(form).find('.fn-loading-wrapper');

			$formWrapper.removeClass('mailchimp-newsletter__form-wrapper--active');
			$confirmWrapper.removeClass('mailchimp-newsletter__confirm-wrapper--active');
			$loadingWrapper.addClass('mailchimp-newsletter__loading-wrapper--active');
		},

		handleCancel(form) {
			MailChimpNewsletter.gotoForm(form);
		},

		handleConfirmForm: (form) => {
			let $formWrapper = $(form).find('.fn-form-wrapper');
			let $confirmWrapper = $(form).find('.fn-confirm-wrapper');
			let $emailInput = $(form).find('.fn-email');
			let $emailHolder = $(form).find('.fn-email-holder');

			MailChimpNewsletter.setState(form);

			if (!MailChimpNewsletter.isValidEmail($emailInput.val())) {
				MailChimpNewsletter.setState(form, 'invalid');
				return;
			}

			$formWrapper.removeClass('mailchimp-newsletter__form-wrapper--active');
			$confirmWrapper.addClass('mailchimp-newsletter__confirm-wrapper--active');
			$emailHolder.html($emailInput.val());

			$(form).find('.fn-cancel').on('click', (event) => {
				event.preventDefault();
				MailChimpNewsletter.handleCancel(form);
			});

			if ($confirmWrapper.find('.fn-newsletter-submit')) {
				$confirmWrapper.find('.fn-newsletter-submit').on('click', (event) => {
					event.preventDefault();
					MailChimpNewsletter.handleSubmit(form);
				});
			}
		},

		handleSubmit: (form) => {
			let $loadingStatus = $(form).find('.fn-loading-status');
			let payload = {
				action: 'mailchimp_ajax_callback',
				mailchimp_newsletter_ajax_nonce: $(form).find('input[name="mailchimp_newsletter_ajax_nonce"]').val(),
				email: $(form).find('.fn-email').val()
			};

			$loadingStatus.show('inline-block');
			MailChimpNewsletter.gotoLoading(form);

			$.post(ajax_object.ajax_url, payload).done((response) => {
				response = JSON.parse(response);

				if (response.success === true) {
					$loadingStatus.hide();
					MailChimpNewsletter.setState(form, 'success');

					setTimeout(() => {
						MailChimpNewsletter.clearForm(form);
					}, 1000);
				} else {
					$loadingStatus.hide();
					MailChimpNewsletter.setState(form, 'error');
				}
			}).fail(() => {
				$loadingStatus.hide();
				MailChimpNewsletter.setState(form, 'error');
			});
		},

		init: () => {
			let $newsletters = $('.mailchimp-newsletter');

			if ($newsletters.length > 0) {
				$.each($newsletters, (key, item) => {
					let $btn = $(item).find('.fn-confirm-submit');

					$btn.on('click', (event) => {
						event.preventDefault();
						MailChimpNewsletter.handleConfirmForm(item);
					});
				});
			}
		}
	}

	$(() => {
		// Quick fix to work with modal
		setTimeout(function () {
			MailChimpNewsletter.init();
		}, 500);
	});
})(jQuery);