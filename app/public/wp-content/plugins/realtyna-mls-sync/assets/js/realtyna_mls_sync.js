jQuery(document).on('submit', '#fourth_step_form', function(event) {

    event.preventDefault();

    var current_btn = jQuery("#btn_finalize");
    var selected_mls = jQuery('input[name=mls_id]:checked');
    var mls_id = selected_mls.val();
    var result = jQuery("#import_result");
    var nonce = jQuery("#realtyna_houzez_nonce").val();

    if (mls_id < 0)
        return false;

    if (mls_id > 0) {

        current_btn.addClass("updating-message");

        var mls_name = selected_mls.data("name");
        var mls_slug = selected_mls.data("slug");

        jQuery.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'realtynaidx',
                mls_id: mls_id,
                mls_name: mls_name,
                mls_slug: mls_slug,
                nonce: nonce,
                method: 'select-mls'
            },
            success: function(data) {
                current_btn.removeClass("updating-message");

                if (Realtyna_isJson(data)) {

                    var response = JSON.parse(data);

                    if (response.status == 'OK') {

                        result.removeClass("error");
                        result.removeClass("updated");
                        result.html(response.message);

                        if (response.payment_link != false) {
                            window.location.replace(response.payment_link);
                        }

                    } else if (response.status == 'ERROR') {

                        result.removeClass("updated");
                        result.addClass("error");
                        result.html(response.message);

                    } else {
                        alert('Invalid Response Status');
                        console.log("response status:" + response.status);
                        console.log(data);
                    }

                } else {
                    alert('Invalid JSON!');
                    console.log(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                current_btn.removeClass("updating-message");
                alert(errorThrown);
            }
        });



    } else {

        current_btn.addClass("updating-message");

        var provider = jQuery("#realtyna_request_provider").val();
        var state = jQuery("#realtyna_request_state").val();

        jQuery.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'realtynaidx',
                provider: provider,
                state: state,
                nonce: nonce,
                method: 'request-mls'
            },
            success: function(data) {
                current_btn.removeClass("updating-message");

                if (Realtyna_isJson(data)) {

                    var response = JSON.parse(data);

                    if (response.status == 'OK') {

                        window.location.reload();

                    } else if (response.status == 'ERROR') {

                        result.removeClass("updated");
                        result.addClass("error");
                        result.html(response.message);

                    } else {
                        alert('Invalid Response Status');
                        console.log("response status:" + response.status);
                        console.log(data);
                    }

                } else {
                    alert('Invalid JSON!');
                    console.log(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                current_btn.removeClass("updating-message");
                alert(errorThrown);
            }
        });

    }

    return false;

});

jQuery(document).on('click', 'input[name=mls_id]', function(event) {

    var current_mls = jQuery(this);
    var request_for_mls_box = jQuery('#request_for_mls_box');
    var btn_finalize = jQuery('#btn_finalize');
    var payment_details = jQuery('#payment_details');

    if (current_mls.val() == 0) {

        payment_details.html("");
        payment_details.hide();

        btn_finalize.html(btn_finalize.data("request"));
        request_for_mls_box.show('slow');

        jQuery("#realtyna_request_provider").prop("required", true);
        jQuery("#realtyna_request_state").prop("required", true);

    } else {

        var monthlyFree = current_mls.data("price");
        var setupFee = current_mls.data("setup");
        var currency = current_mls.data("currency");

        payment_details.hide();
        payment_details.html("Your Subscription includes :<br>" + currency + " " + setupFee + " ( one-time Setup fee ) + " + currency + " " + monthlyFree + " Monthly");
        payment_details.show('slow');

        btn_finalize.html(btn_finalize.data("payment"));
        request_for_mls_box.hide('slow');

        jQuery("#realtyna_request_provider").prop("required", false);
        jQuery("#realtyna_request_state").prop("required", false);

    }

    btn_finalize.prop('disabled', false);

});

jQuery(document).one('submit', '#second_step_form', function(event) {

    var current_btn = jQuery("#realtya_go_to_third_step");
    current_btn.addClass("updating-message");

    var form = jQuery(this);
    var name = jQuery("#realtyna_idx_client_name").val();
    var email = jQuery("#realtyna_idx_client_email").val();
    var phone = jQuery("#realtyna_idx_client_phone").val();
    var role = jQuery("#realtyna_idx_client_role").val();
    var nonce = jQuery("#realtyna_houzez_nonce").val();

    jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data: {
            action: 'realtynaidx',
            client_name: name,
            client_email: email,
            client_phone: phone,
            client_role: role,
            nonce: nonce,
            method: 'client-info'
        },
        success: function(data) {
            current_btn.removeClass("updating-message");

            if (Realtyna_isJson(data)) {

                var response = JSON.parse(data);

                if (response.status == 'OK') {

                    form.submit();
                    return true;

                } else if (response.status == 'ERROR') {

                    alert(response.message);

                } else {
                    alert('Invalid Response Status');
                    console.log("response status:" + response.status);
                    console.log(data);
                }

            } else {
                alert('Invalid JSON!');
                console.log(data);
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            current_btn.removeClass("updating-message");
            alert(errorThrown);
        }
    });

    return false;

});

jQuery(document).on('click', '#realtyna_idx_demo_import', function() {

    var current_btn = jQuery(this);

    if (current_btn.hasClass("updating-message"))
        return;

    var agent = jQuery("#realtyna_idx_selected_agent").val();
    var agency = jQuery("#realtyna_idx_selected_agency").val();
    var agent_option = jQuery("#realtyna_idx_selected_agent_option").val();
    var image_option = jQuery("#realtyna_idx_images_option").val();
    var nonce = jQuery("#realtyna_houzez_nonce").val();
    var next_button = jQuery("#realtya_go_to_fourth_step");
    var result = jQuery("#import_result");

    if (!agent) {

        result.removeClass("updated");
        result.addClass("error");
        result.html('Select Required Field: Agent');

        return false;

    }

    if (!agency) {

        result.removeClass("updated");
        result.addClass("error");
        result.html('Select Required Field: Agency');

        return false;

    }

    current_btn.addClass("updating-message");

    jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data: {
            action: 'realtynaidx',
            agent: agent,
            agency: agency,
            agent_option: agent_option,
            image_option: image_option,
            nonce: nonce,
            method: 'demo'
        },
        timeout: 60000,
        success: function(data) {

            current_btn.removeClass("updating-message");

            if (Realtyna_isJson(data)) {

                var response = JSON.parse(data);

                if (response.status == 'OK') {

                    result.removeClass("error");
                    result.addClass("updated");
                    result.html(response.message);
                    current_btn.hide();
                    next_button.prop('disabled', false);

                } else if (response.status == 'ERROR') {

                    result.removeClass("updated");
                    result.addClass("error");
                    result.html(response.message);

                } else {

                    result.removeClass("updated");
                    result.addClass("error");
                    result.html('Invalid Response Status');

                    console.log("response status:" + response.status);
                    console.log(data);
                }

            } else {
                result.removeClass("updated");
                result.addClass("error");
                result.html('Invalid Response!');

                console.log(data);
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {

            if (textStatus == 'timeout') {

                setTimeout(function() {

                    jQuery.ajax({
                        url: ajax_object.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'realtynaidx',
                            nonce: nonce,
                            method: 'demo-progress'
                        },
                        timeout: 60000,
                        success: function(data) {

                            current_btn.removeClass("updating-message");

                            if (Realtyna_isJson(data)) {

                                var response = JSON.parse(data);

                                if (response.status == 'OK') {

                                    result.removeClass("error");
                                    result.addClass("updated");
                                    result.html(response.message);
                                    current_btn.hide();
                                    next_button.prop('disabled', false);

                                } else if (response.status == 'ERROR') {

                                    result.removeClass("updated");
                                    result.addClass("error");
                                    result.html(response.message);

                                } else {

                                    result.removeClass("updated");
                                    result.addClass("error");
                                    result.html('Invalid Response Status');

                                    console.log("response status:" + response.status);
                                    console.log(data);
                                }

                            } else {
                                result.removeClass("updated");
                                result.addClass("error");
                                result.html('Invalid Response!');

                                console.log(data);
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            console.log("textStatus = " + textStatus);
                            current_btn.removeClass("updating-message");

                            result.removeClass("updated");
                            result.addClass("error");
                            result.html(errorThrown);

                        }

                    });

                }, 35000);

            } else {
                console.log("textStatus(not timeout) = " + textStatus);
                current_btn.removeClass("updating-message");

                result.removeClass("updated");
                result.addClass("error");
                result.html(errorThrown);

            }

        }
    });

});

jQuery(document).on('click', '#realtyna_submit_settings', function() {

    var current_btn = jQuery(this);

    var agency = jQuery("#realtyna_idx_selected_agency_settings").val();
    var apply_agency_to_all = jQuery("#apply_agency_to_all").prop("checked");
    var agent = jQuery("#realtyna_idx_selected_agent_settings").val();
    var apply_agent_to_all = jQuery("#apply_agent_to_all").prop("checked");
    var agent_option = jQuery("#realtyna_idx_selected_agent_option_settings").val();
    var apply_agent_display_option_to_all = jQuery("#apply_agent_display_option_to_all").prop("checked");
    var image_option = jQuery("#realtyna_idx_images_option_settings").val();
    var nonce = jQuery("#realtyna_houzez_nonce").val();
    var result = jQuery("#import_result");

    if (!agent) {

        result.removeClass("updated");
        result.addClass("error");
        result.html('Select Required Field: Agent');

        return false;

    }

    if (!agency) {

        result.removeClass("updated");
        result.addClass("error");
        result.html('Select Required Field: Agency');

        return false;

    }

    current_btn.addClass("updating-message");

    jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data: {
            action: 'realtynaidx',
            agency: agency,
            apply_agency_to_all: apply_agency_to_all,
            agent: agent,
            apply_agent_to_all: apply_agent_to_all,
            agent_option: agent_option,
            apply_agent_display_option_to_all: apply_agent_display_option_to_all,
            image_option: image_option,
            nonce: nonce,
            method: 'settings'
        },
        success: function(data) {

            current_btn.removeClass("updating-message");

            if (Realtyna_isJson(data)) {

                var response = JSON.parse(data);

                if (response.status == 'OK') {

                    result.removeClass("error");
                    result.addClass("updated");
                    result.html(response.message);

                } else if (response.status == 'ERROR') {

                    result.removeClass("updated");
                    result.addClass("error");
                    result.html(response.message);

                } else {

                    result.removeClass("updated");
                    result.addClass("error");
                    result.html('Invalid Response Status');

                    console.log("response status:" + response.status);
                    console.log(data);
                }

            } else {
                result.removeClass("updated");
                result.addClass("error");
                result.html('Invalid Response!');

                console.log(data);
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            current_btn.removeClass("updating-message");

            result.removeClass("updated");
            result.addClass("error");
            result.html(errorThrown);

        }
    });

});

jQuery(document).on('click', '#realtyna_remove_demo_properties', function() {

    var current_btn = jQuery(this);

    var nonce = jQuery("#realtyna_houzez_nonce").val();
    var result = jQuery("#import_result");

    current_btn.addClass("updating-message");

    jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data: {
            action: 'realtynaidx',
            nonce: nonce,
            method: 'remove-demo'
        },
        success: function(data) {

            current_btn.removeClass("updating-message");

            if (Realtyna_isJson(data)) {

                var response = JSON.parse(data);

                if (response.status == 'OK') {

                    result.removeClass("error");
                    result.addClass("updated");
                    result.html(response.message);
                    current_btn.hide();

                } else if (response.status == 'ERROR') {

                    result.removeClass("updated");
                    result.addClass("error");
                    result.html(response.message);

                } else {

                    result.removeClass("updated");
                    result.addClass("error");
                    result.html('Invalid Response Status');

                    console.log("response status:" + response.status);
                    console.log(data);
                }

            } else {
                result.removeClass("updated");
                result.addClass("error");
                result.html('Invalid Response!');

                console.log(data);
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            current_btn.removeClass("updating-message");

            result.removeClass("updated");
            result.addClass("error");
            result.html(errorThrown);

        }
    });

});

jQuery(document).on('click', '#btnRealtynaUpdater', function() {

    var current_btn = jQuery(this);

    var nonce = jQuery("#realtyna_houzez_nonce").val();
    var result = jQuery(this).parent();

    current_btn.addClass("updating-message");

    jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data: {
            action: 'realtynaidx',
            nonce: nonce,
            method: 'update-plugin'
        },
        success: function(data) {

            current_btn.removeClass("updating-message");

            if (Realtyna_isJson(data)) {

                var response = JSON.parse(data);

                if (response.status == 'OK') {

                    current_btn.hide();
                    alert(response.message);
                    location.reload();

                } else if (response.status == 'ERROR') {

                    alert(response.message);

                } else {

                    alert('Invalid Response Status');

                }

            } else {

                alert('Invalid Response!');

            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            current_btn.removeClass("updating-message");

            alert(errorThrown);

        }
    });

});

function Realtyna_isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}