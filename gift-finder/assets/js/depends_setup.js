function addDependsOn(options) {
    const typeUserAttrs = {
        dependson_type: {
            label: 'Available',
            options: {
                '': 'All the Time',
                'checkbox-group': 'checkbox-group',
                'select': 'select',
                'radio-group': 'radio-group',
            }
        },
        dependson_code: {
            value: '',
        }
    };
    const typeUserEvents = {
        onadd: function (fld) {
            jQuery('.dependson_code-wrap').addClass('collapse');
            const type_selector = jQuery('.fld-dependson_type', fld);
            const code_selector = jQuery('.fld-dependson_code', fld);
            const my_field_name = jQuery('.fld-name', fld).val();
            setTimeout(function () {
                setDependsOptionName(code_selector.val(), type_selector);
            }, 1000);
            type_selector.change(function (e) {
                const selected = jQuery(this).children("option:selected").val();
                if (selected.length > 0) {
                    jQuery('#depends_on_field').empty();
                    jQuery('.form-builder .form-field[type=' + selected + ']').each(function () {
                        let field_value = jQuery('.fld-name', this).val();
                        let field_name = jQuery('.fld-label', this).html();
                        if (field_value != my_field_name) {
                            jQuery('#depends_on_field').append(new Option(field_name, field_value));
                        }
                    });
                    jQuery('#depends_on_checkbox-group').css('display', 'none');
                    jQuery('#depends_on_radio-group').css('display', 'none');
                    jQuery('#depends_on_select').css('display', 'none');
                    jQuery('#depends_on_' + selected).css('display', 'flex');
                    setOptions('#depends_on_field', '#depends_on_' + selected + '_container', selected);
                    jQuery('#depends_on_field').change(function (e) {
                        setOptions('#depends_on_field', '#depends_on_' + selected + '_container', selected);
                    });
                    jQuery('#modal-form-dependson button[name=depends-on-save]').click(function () {
                        jQuery('#modal-form-dependson button[name=depends-on-save]').off('click');
                        DependsOnSave('#depends_on_field', '#depends_on_' + selected + '_container', selected, code_selector, type_selector);
                    });
                    jQuery('#modal-form-dependson').on('hide.bs.modal', function (e) {
                        jQuery('#modal-form-dependson').off('hide.bs.modal');
                        console.log(jQuery('#modal-form-dependson'));
                        setDependsOptionName(code_selector.val(), type_selector);
                    });
                    jQuery('#modal-form-dependson').modal('show');
                } else {
                    code_selector.val("");
                }
            });
        }
    };
    if (!options.typeUserAttrs) options.typeUserAttrs = {};
    if (!options.typeUserEvents) options.typeUserEvents = {};


    getControlList().forEach(function (item, index) {
        if (!options.typeUserAttrs[item]) options.typeUserAttrs[item] = {};
        if (!options.typeUserEvents[item]) options.typeUserEvents[item] = {};
        options.typeUserAttrs[item].dependson_type = typeUserAttrs.dependson_type;
        options.typeUserAttrs[item].dependson_code = typeUserAttrs.dependson_code;
        options.typeUserEvents[item].onadd = typeUserEvents.onadd;
    });
    return options;
}

function getControlList() {
    return ['autocomplete', 'button', 'checkbox-group', 'date', 'file', 'header', 'hidden', 'number', 'paragraph', 'radio-group', 'select', 'starRating', 'text', 'textarea'];
}

function setDisabledDefaults(list) {
    let obj = {};
    list.forEach(function (item, index) {
        obj[item] = ['access'];
    });
    return obj;
}

function DependsOnSave(src_select_field, dst_select_field, option_type, code_selector, type_selector) {
    let field_name = jQuery(src_select_field).children('option:selected').val();
    let option_value = jQuery(dst_select_field).children('option:selected').val();
    let action = "";
    let code = option_type + ":" + field_name + ":" + option_value;
    if (option_type == 'checkbox-group') {
        action = jQuery('#depends_on_checkbox_checkbox').is(":checked") ? 'checked' : 'unchecked';
        code += ':' + action;
    }
    code_selector.val(code);
    code_selector.prop('readonly', true);
    setDependsOptionName(code, type_selector);
    jQuery('#modal-form-dependson').modal('hide');
}

function resetDependsOptionNames(type_selector) {
    jQuery(type_selector).children('option').each(function () {
        if (jQuery(this).val() == 'checkbox-group') {
            jQuery(this).text("when checkbox ....");
        } else if (jQuery(this).val() == 'radio-group') {
            jQuery(this).text("when radio button ....");
        } else if (jQuery(this).val() == 'select') {
            jQuery(this).text("when select option ....");
        }
    });
}

function setDependsOptionName(code, type_selector) {
    let type_selector_option = null;
    resetDependsOptionNames(type_selector);
    if (code.length > 0) {
        let bits = code.split(":");
        let option_type = bits.shift();
        let field_name = bits.shift();
        let option_value = bits.shift();
        let action = "";

        let name = "when ";
        let field_container = jQuery('.fld-name[value=' + field_name + ']').closest('.form-elements');
        let option_container = jQuery('.option-value[value=' + option_value + ']', field_container).closest('li');
        if (option_type == 'checkbox-group') {
            name += " checkbox "
            action = bits.shift();
        } else if (option_type == 'radio-group') {
            name += " radio button ";
            action = 'chosen';
        } else if (option_type == 'select') {
            name += " select option ";
            action = 'selected';
        }
        name += jQuery('.fld-label', field_container).html() + " -> " + jQuery('.option-label', option_container).val() + " is " + action;
        type_selector_option = jQuery(type_selector).children('option[value="' + option_type + '"]');
        type_selector_option.text(name);
    } else {
        type_selector_option = jQuery(type_selector).children('option[value=""]');
    }
    type_selector_option.prop('selected', true);
}

function setOptions(src_select_field, dst_select_field, option_type) {
    let depends_on_field_name = jQuery(src_select_field).children('option:selected').val();
    let select_box = jQuery(dst_select_field);

    const form = JSON.parse(myFormBuilder.actions.getData('json', true));
    const element = form.find(element => element.name === depends_on_field_name);

    select_box.empty();

    element.values.forEach(function (option) {
        select_box.append(new Option(option.label, option.value));
    });
}