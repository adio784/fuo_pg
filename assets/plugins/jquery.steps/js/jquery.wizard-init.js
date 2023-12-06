/**
* Theme: Montran Admin Template
* Author: Coderthemes
* Form wizard page
*/

!function($) {
    "use strict";

    var FormWizard = function() {};

    FormWizard.prototype.createBasic = function($form_container) {
        $form_container.children("div").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft"
        });
        return $form_container;
    },
    //creates form with validation
    FormWizard.prototype.createValidatorForm = function($form_container) {
        $form_container.validate({
            errorPlacement: function errorPlacement(error, element) {
                element.after(error);
            }
        });
        $form_container.children("div").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                $form_container.validate().settings.ignore = ":disabled,:hidden";
                return $form_container.valid();
            },
            onFinishing: function (event, currentIndex) {
                $form_container.validate().settings.ignore = ":disabled";
                return $form_container.valid();
            },
            onFinished: function (event, currentIndex) {
                // alert("Submitted!");
                $("#previewFormBtn").click();
                $('#fn').val( $('#firstName').val() );
                $('#mn').val( $('#middleName').val() );
                $('#ln').val( $('#lastName').val() );
                $('#gn').val( $('#gender').val() );
                $('#rl').val( $('#religion').val() );
                $('#db').val( $('#birthDate').val() );
                $('#ad').val( $('#address').val() );
                $('#cr').val( $('#country option:selected').text() );
                $('#st').val( $('#state option:selected').text() );
                $('#co').val( $('#countryOrigin option:selected').text() );
                $('#so').val( $('#stateOrigin option:selected').text() );
                $('#lo').val( $('#lgaOrigin option:selected').text() );
                $('#ct').val( $('#city option:selected').text() );
                $('#em').val( $('#emailAddress').val() );
                $('#pn').val( $('#phoneNumber').val() );
                $('#uc').val( $('#undergraduateCourse').val() );
                $('#cd').val( $('#classDegree').val() );
                $('#ia').val( $('#instituteAtt').val() );
                // $('#rm').val( $('#entryMode').val() ); 
                $('#cs').val( $('#courseOfStudy option:selected').text() );
                $('#csed').val( $('#courseOfStudy').val() );

                const olevelInput = $('#oLevel')[0];
                const ungradInput = $('#undergCert')[0];
                const transcInput = $('#tranScripts')[0];
                const masterInput = $('#masterCerts')[0];
                const passpoInput = $('#passPorts')[0];
                var isPhd = $('#isPhd').val();

                if (olevelInput.files && olevelInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#ol').attr('src', e.target.result);
                        $('#olu')[0].files = olevelInput.files;
                    };
                    reader.readAsDataURL(olevelInput.files[0]);
                }

                if (ungradInput.files && ungradInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#up').attr('src', e.target.result);
                        $('#upu')[0].files = ungradInput.files;
                    };
                    reader.readAsDataURL(ungradInput.files[0]);
                }

                if (transcInput.files && transcInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#cpt').attr('src', e.target.result);
                        $('#tsu')[0].files = transcInput.files;
                    };
                    reader.readAsDataURL(transcInput.files[0]);
                }

                if (passpoInput.files && passpoInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#prr').attr('src', e.target.result);
                        $('#psu')[0].files = passpoInput.files;
                    };
                    reader.readAsDataURL(passpoInput.files[0]);
                }

                if( isPhd == 1 ) {
                    if (masterInput.files && masterInput.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            $('#ms').attr('src', e.target.result);
                            $('#msu')[0].files = masterInput.files;
                        };
                        reader.readAsDataURL(masterInput.files[0]);
                    }
                }
            }
        });

        return $form_container;
    },
    //creates vertical form
    FormWizard.prototype.createVertical = function($form_container) {
        $form_container.steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "fade",
            stepsOrientation: "vertical"
        });
        return $form_container;
    },
    FormWizard.prototype.init = function() {
        //initialzing various forms

        //basic form
        this.createBasic($("#basic-form"));

        //form with validation
        this.createValidatorForm($("#wizard-validation-form"));

        //vertical form
        this.createVertical($("#wizard-vertical"));
    },
    //init
    $.FormWizard = new FormWizard, $.FormWizard.Constructor = FormWizard
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.FormWizard.init()
}(window.jQuery);