/*

Template:  Webmin - Bootstrap 4 & Angular 5 Admin Dashboard Template
Author: potenzaglobalsolutions.com
Design and Developed by: potenzaglobalsolutions.com

NOTE: 

*/

 (function($){
  "use strict";
    $.validator.setDefaults( {
        submitHandler: function (form) {
          form.submit();
        }
      });
     $.validator.setDefaults( {
      submitHandler: function (form) {
          form.submit();
      }
    });

    $( document ).ready( function () {
      $( "#creditResellerForm" ).validate( {
        rules: {
          username: "required",
          credits: "required"
        },
        messages: {
          username: "Please enter reseller username",
          credits: "Please enter number of credits"
        },
        errorPlacement: function ( error, element ) {
          error.addClass( "ui red pointing label transition" );
          error.insertAfter( element.parent() );
        },
        highlight: function ( element, errorClass, validClass ) {
          $( element ).parents( ".row" ).addClass( errorClass );
        },
        unhighlight: function (element, errorClass, validClass) {
          $( element ).parents( ".row" ).removeClass( errorClass );
        }
      } );
        
    $( "#createResellerForm" ).validate( {
        rules: {
          username: {
            required: true,
            minlength: 4
          },
          password: "required"
        },
        messages: {
          username: {
            required: "Please enter a username",
            minlength: "Username must be up to 4 characters long"
          },
          password: "Please enter a password"
        },
        errorPlacement: function ( error, element ) {
          error.addClass( "ui red pointing label transition" );
          error.insertAfter( element.parent() );
        },
        highlight: function ( element, errorClass, validClass ) {
          $( element ).parents( ".row" ).addClass( errorClass );
        },
        unhighlight: function (element, errorClass, validClass) {
          $( element ).parents( ".row" ).removeClass( errorClass );
        }
      } );
        
    $( "#createSingleUserForm" ).validate( {
        rules: {
          username: {
            required: true,
            minlength: 4
          },
          password: "required",
          duration: "required"
        },
        messages: {
          username: {
            required: "Please enter a username",
            minlength: "Username must be up to 4 characters long"
          },
          password: "Please enter a password",
          duration: "Please select a duration"
        },
        errorPlacement: function ( error, element ) {
          error.addClass( "ui red pointing label transition" );
          error.insertAfter( element.parent() );
        },
        highlight: function ( element, errorClass, validClass ) {
          $( element ).parents( ".row" ).addClass( errorClass );
        },
        unhighlight: function (element, errorClass, validClass) {
          $( element ).parents( ".row" ).removeClass( errorClass );
        }
      } );
        
    $( "#createMultiUserForm" ).validate( {
        rules: {
          prefix: {
            required: true,
            minlength: 4
          },
          count: "required",
          duration: "required"
        },
        messages: {
          prefix: {
            required: "Please enter a prefix",
            minlength: "Prefix must be up to 4 characters long"
          },
          count: "Please enter number of users",
          duration: "Please select a duration"
        },
        errorPlacement: function ( error, element ) {
          error.addClass( "ui red pointing label transition" );
          error.insertAfter( element.parent() );
        },
        highlight: function ( element, errorClass, validClass ) {
          $( element ).parents( ".row" ).addClass( errorClass );
        },
        unhighlight: function (element, errorClass, validClass) {
          $( element ).parents( ".row" ).removeClass( errorClass );
        }
      } );
        
    
    $( "#changePasswordForm" ).validate( {
        rules: {
          password: "required",
          password1: {
            required: true,
            minlength: 4
          },
          password2: {
            required: true,
            minlength: 4,
            equalTo: "#password1"
          }
        },
        messages: {
          password: "Please enter old password",
          password1: {
              required: "Please enter new password",
              minlength: "Password must be up to 4 characters long"
          },
          password2: {
              required: "Please enter new password",
              minlength: "Password must be at least 4 characters long",
              equalTo: "New passwords must match"
          }
        },
        errorPlacement: function ( error, element ) {
          error.addClass( "ui red pointing label transition" );
          error.insertAfter( element.parent() );
        },
        highlight: function ( element, errorClass, validClass ) {
          $( element ).parents( ".row" ).addClass( errorClass );
        },
        unhighlight: function (element, errorClass, validClass) {
          $( element ).parents( ".row" ).removeClass( errorClass );
        }
      } );

    });

 })(jQuery);