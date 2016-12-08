var dateFormat 		= require('dateformat');

// Vue imports
var Vue 			= require('vue');

var Spinner         = require("spin");
var Gmaps           = require("gmaps.core");
require("gmaps.markers");
var Dropzone 		= require("dropzone");
var swal 			= require("sweetalert");
var bootstrapToggle = require("bootstrap-toggle");
var locationPicker  = require("jquery-locationpicker");
Vue.use(require('vue-resource'));

$(document).ready(function(){
// var dateFormat = require('dateformat');

// set th CSRF_TOKEN for ajax requests
$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});
Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');


 /* ==========================================================================
    Custom functions
    ========================================================================== */

/**
 * Check if variable is instanciated
 * @param  {string} strVariableName name of the variable to pass
 * @return {boolean}
 */
function isset(strVariableName) {
	if(typeof back !== 'undefined'){
        return (typeof back[strVariableName] !== 'undefined');
	}
	return false
 }


/* ==========================================================================
	Billing Stripe
	========================================================================== */




/* ==========================================================================
	Scroll
	========================================================================== */

	if (!("ontouchstart" in document.documentElement)) {

		document.documentElement.className += " no-touch";

		var jScrollOptions = {
			autoReinitialise: true,
			autoReinitialiseDelay: 100
		};

		$('.box-typical-body').jScrollPane(jScrollOptions);
		$('.side-menu').jScrollPane(jScrollOptions);
		//$('.side-menu-addl').jScrollPane(jScrollOptions);
		$('.scrollable-block').jScrollPane(jScrollOptions);
	}

/* ==========================================================================
    Header search
    ========================================================================== */

	$('.site-header .site-header-search').each(function(){
		var parent = $(this),
			overlay = parent.find('.overlay');

		overlay.click(function(){
			parent.removeClass('closed');
		});

		parent.clickoutside(function(){
			if (!parent.hasClass('closed')) {
				parent.addClass('closed');
			}
		});
	});

/* ==========================================================================
    Header mobile menu
    ========================================================================== */

	// Dropdowns
	$('.site-header-collapsed .dropdown').each(function(){
		var parent = $(this),
			btn = parent.find('.dropdown-toggle');

		btn.click(function(){
			if (parent.hasClass('mobile-opened')) {
				parent.removeClass('mobile-opened');
			} else {
				parent.addClass('mobile-opened');
			}
		});
	});

	$('.dropdown-more').each(function(){
		var parent = $(this),
			more = parent.find('.dropdown-more-caption'),
			classOpen = 'opened';

		more.click(function(){
			if (parent.hasClass(classOpen)) {
				parent.removeClass(classOpen);
			} else {
				parent.addClass(classOpen);
			}
		});
	});

	// Left mobile menu
	$('.hamburger').click(function(){
		if ($('body').hasClass('menu-left-opened')) {
			$(this).removeClass('is-active');
			$('body').removeClass('menu-left-opened');
			$('html').css('overflow','auto');
		} else {
			$(this).addClass('is-active');
			$('body').addClass('menu-left-opened');
			$('html').css('overflow','hidden');
		}
	});

	$('.mobile-menu-left-overlay').click(function(){
		$('.hamburger').removeClass('is-active');
		$('body').removeClass('menu-left-opened');
		$('html').css('overflow','auto');
	});

	// Right mobile menu
	$('.site-header .burger-right').click(function(){
		if ($('body').hasClass('menu-right-opened')) {
			$('body').removeClass('menu-right-opened');
			$('html').css('overflow','auto');
		} else {
			$('.hamburger').removeClass('is-active');
			$('body').removeClass('menu-left-opened');
			$('body').addClass('menu-right-opened');
			$('html').css('overflow','hidden');
		}
	});

	$('.mobile-menu-right-overlay').click(function(){
		$('body').removeClass('menu-right-opened');
		$('html').css('overflow','auto');
	});

/* ==========================================================================
    Header help
    ========================================================================== */

	$('.help-dropdown').each(function(){
		var parent = $(this),
			btn = parent.find('>button'),
			popup = parent.find('.help-dropdown-popup'),
			jscroll;

		btn.click(function(){
			if (parent.hasClass('opened')) {
				parent.removeClass('opened');
				jscroll.destroy();
			} else {
				parent.addClass('opened');

				$('.help-dropdown-popup-cont, .help-dropdown-popup-side').matchHeight();

				if (!("ontouchstart" in document.documentElement)) {
					setTimeout(function(){
						jscroll = parent.find('.jscroll').jScrollPane(jScrollOptions).data().jsp;
						//jscroll.reinitialise();
					},0);
				}
			}
		});

		$('html').click(function(event) {
		    if (
		        !$(event.target).closest('.help-dropdown-popup').length
		        &&
		        !$(event.target).closest('.help-dropdown>button').length
		        &&
		        !$(event.target).is('.help-dropdown-popup')
		        &&
		        !$(event.target).is('.help-dropdown>button')
		    ) {
				if (parent.hasClass('opened')) {
					parent.removeClass('opened');
					jscroll.destroy();
		        }
		    }
		});

	});

/* ==========================================================================
    Side menu list
    ========================================================================== */

	$('.side-menu-list li.with-sub').each(function(){
		var parent = $(this),
			clickLink = parent.find('>span'),
			subMenu = parent.find('ul');

		clickLink.click(function(){
			if (parent.hasClass('opened')) {
				parent.removeClass('opened');
				subMenu.slideUp();
			} else {
				$('.side-menu-list li.with-sub').not(this).removeClass('opened').find('ul').slideUp();
				parent.addClass('opened');
				subMenu.slideDown();
			}
		});
	});


/* ==========================================================================
    Dashboard
    ========================================================================== */

	// Calculate height
	function dashboardBoxHeight() {
		$('.box-typical-dashboard').each(function(){
			var parent = $(this),
				header = parent.find('.box-typical-header'),
				body = parent.find('.box-typical-body');
			body.height(parent.outerHeight() - header.outerHeight());
		});
	}

	dashboardBoxHeight();

	$(window).resize(function(){
		dashboardBoxHeight();
	});

	// Collapse box
	$('.box-typical-dashboard').each(function(){
		var parent = $(this),
			btnCollapse = parent.find('.action-btn-collapse');

		btnCollapse.click(function(){
			if (parent.hasClass('box-typical-collapsed')) {
				parent.removeClass('box-typical-collapsed');
			} else {
				parent.addClass('box-typical-collapsed');
			}
		});
	});

	// Full screen box
	$('.box-typical-dashboard').each(function(){
		var parent = $(this),
			btnExpand = parent.find('.action-btn-expand'),
			classExpand = 'box-typical-full-screen';

		btnExpand.click(function(){
			if (parent.hasClass(classExpand)) {
				parent.removeClass(classExpand);
				$('html').css('overflow','auto');
			} else {
				parent.addClass(classExpand);
				$('html').css('overflow','hidden');
			}
			dashboardBoxHeight();
		});
	});



/* ==========================================================================
	Select
	========================================================================== */

	// Bootstrap-select
	$('.bootstrap-select').selectpicker({
		style: '',
		width: '100%',
		size: 8
	});

	// Select2
	$.fn.select2.defaults.set("minimumResultsForSearch", "Infinity");

	$('.select2').select2();

	function select2Icons (state) {
		if (!state.id) { return state.text; }
		var $state = $(
			'<span class="font-icon ' + state.element.getAttribute('data-icon') + '"></span><span>' + state.text + '</span>'
		);
		return $state;
	}

	$(".select2-icon").select2({
		templateSelection: select2Icons,
		templateResult: select2Icons
	});

	$(".select2-arrow").select2({
		theme: "arrow"
	});

	$(".select2-white").select2({
		theme: "white"
	});

	function select2Photos (state) {
		if (!state.id) { return state.text; }
		var $state = $(
			'<span class="user-item"><img src="' + state.element.getAttribute('data-photo') + '"/>' + state.text + '</span>'
		);
		return $state;
	}

	$(".select2-photo").select2({
		templateSelection: select2Photos,
		templateResult: select2Photos
	});


/* ==========================================================================
	Datepicker
	========================================================================== */

	$('.datetimepicker-1').datetimepicker({
		widgetPositioning: {
			horizontal: 'right'
		},
		debug: false
	});

	$('.datetimepicker-2').datetimepicker({
		widgetPositioning: {
			horizontal: 'right'
		},
		format: 'LT',
		debug: false
	});

/* ==========================================================================
	Tooltips
	========================================================================== */

	// Tooltip
	$('[data-toggle="tooltip"]').tooltip({
		html: true
	});

	// Popovers
	$('[data-toggle="popover"]').popover({
		trigger: 'focus'
	});

/* ==========================================================================
	Validation
	========================================================================== */

	$('#form-signin_v1').validate({
		submit: {
			settings: {
				inputContainer: '.form-group'
			}
		}
	});

	$('#form-signin_v2').validate({
		submit: {
			settings: {
				inputContainer: '.form-group',
				errorListClass: 'form-error-text-block',
				display: 'block',
				insertion: 'prepend'
			}
		}
	});

	$('#form-signup_v1').validate({
		submit: {
			settings: {
				inputContainer: '.form-group',
				errorListClass: 'form-tooltip-error'
			}
		}
	});

	$('#form-signup_v2').validate({
		submit: {
			settings: {
				inputContainer: '.form-group',
				errorListClass: 'form-tooltip-error'
			}
		}
	});

/* ==========================================================================
	Sweet alerts
	========================================================================== */

	$('.swal-btn-basic').click(function(e){
		// e.preventDefault();
		swal("Here's a message!");
	});

	$('.swal-btn-text').click(function(e){
		e.preventDefault();
		swal({
			title: "Here's a message!",
			text: "It's pretty, isn't it?"
		});
	});

	$('.swal-btn-success').click(function(e){
		e.preventDefault();
		swal({
			title: "Good job!",
			text: "You clicked the button!",
			type: "success",
			confirmButtonClass: "btn-success",
			confirmButtonText: "Success"
		});
	});

	$('.swal-btn-warning').click(function(e){
		e.preventDefault();
		swal({
				title: "Are you sure?",
				text: "Your will not be able to recover this imaginary file!",
				type: "warning",
				showCancelButton: true,
				cancelButtonClass: "btn-default",
				confirmButtonClass: "btn-warning",
				confirmButtonText: "Warning",
				closeOnConfirm: false
			},
			function(){
				swal({
					title: "Deleted!",
					text: "Your imaginary file has been deleted.",
					type: "success",
					confirmButtonClass: "btn-success"
				});
			});
	});

	$('.swal-btn-cancel').click(function(e){
		e.preventDefault();
		swal({
				title: "Are you sure?",
				text: "You will not be able to recover this imaginary file!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Yes, delete it!",
				cancelButtonText: "No, cancel plx!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					swal({
						title: "Deleted!",
						text: "Your imaginary file has been deleted.",
						type: "success",
						confirmButtonClass: "btn-success"
					});
				} else {
					swal({
						title: "Cancelled",
						text: "Your imaginary file is safe :)",
						type: "error",
						confirmButtonClass: "btn-danger"
					});
				}
			});
	});

	$('.swal-btn-custom-img').click(function(e){
		e.preventDefault();
		swal({
			title: "Sweet!",
			text: "Here's a custom image.",
			confirmButtonClass: "btn-success",
			imageUrl: 'img/smile.png'
		});
	});

	$('.swal-btn-info').click(function(e){
		e.preventDefault();
		swal({
				title: "Are you sure?",
				text: "Your will not be able to recover this imaginary file!",
				type: "info",
				showCancelButton: true,
				cancelButtonClass: "btn-default",
				confirmButtonText: "Info",
				confirmButtonClass: "btn-primary"
			});
	});

/* ==========================================================================
	Bar chart
	========================================================================== */

	$(".bar-chart").peity("bar",{
		delimiter: ",",
		fill: ["#919fa9"],
		height: 16,
		max: null,
		min: 0,
		padding: 0.1,
		width: 384
	});

/* ==========================================================================
	Full height box
	========================================================================== */

	function boxFullHeight() {
		var sectionHeader = $('.section-header');
		var sectionHeaderHeight = 0;

		if (sectionHeader.size()) {
			sectionHeaderHeight = parseInt(sectionHeader.height()) + parseInt(sectionHeader.css('padding-bottom'));
		}

		$('.box-typical-full-height').css('min-height',
			$(window).height() -
			parseInt($('.page-content').css('padding-top')) -
			parseInt($('.page-content').css('padding-bottom')) -
			sectionHeaderHeight -
			parseInt($('.box-typical-full-height').css('margin-bottom')) - 2
		);
		$('.box-typical-full-height>.tbl, .box-typical-full-height>.box-typical-center').height(parseInt($('.box-typical-full-height').css('min-height')));
	}

	boxFullHeight();

	$(window).resize(function(){
		boxFullHeight();
	});

/* ==========================================================================
	Chat
	========================================================================== */

	function chatHeights() {
		$('.chat-dialog-area').height(
			$(window).height() -
			parseInt($('.page-content').css('padding-top')) -
			parseInt($('.page-content').css('padding-bottom')) -
			parseInt($('.chat-container').css('margin-bottom')) - 2 -
			$('.chat-area-header').outerHeight() -
			$('.chat-area-bottom').outerHeight()
		);
		$('.chat-list-in')
			.height(
				$(window).height() -
				parseInt($('.page-content').css('padding-top')) -
				parseInt($('.page-content').css('padding-bottom')) -
				parseInt($('.chat-container').css('margin-bottom')) - 2 -
				$('.chat-area-header').outerHeight()
			)
			.css('min-height', parseInt($('.chat-dialog-area').css('min-height')) + $('.chat-area-bottom').outerHeight());
	}

	chatHeights();

	$(window).resize(function(){
		chatHeights();
	});

/* ==========================================================================
	Auto size for textarea
	========================================================================== */

	autosize($('textarea[data-autosize]'));

/* ==========================================================================
	Pages center
	========================================================================== */

	$('.page-center').matchHeight({
		target: $('html')
	});

	$(window).resize(function(){
		setTimeout(function(){
			$('.page-center').matchHeight({ remove: true });
			$('.page-center').matchHeight({
				target: $('html')
			});
		},100);
	});

/* ==========================================================================
	Cards user
	========================================================================== */

	$('.card-user').matchHeight();

/* ==========================================================================
	Fancybox
	========================================================================== */

	$(".fancybox").fancybox({
		padding: 0,
		openEffect	: 'none',
		closeEffect	: 'none'
	});



/* ==========================================================================
	Box typical full height with header
	========================================================================== */

	function boxWithHeaderFullHeight() {
		$('.box-typical-full-height-with-header').each(function(){
			var box = $(this),
				boxHeader = box.find('.box-typical-header'),
				boxBody = box.find('.box-typical-body');

			boxBody.height(
				$(window).height() -
				parseInt($('.page-content').css('padding-top')) -
				parseInt($('.page-content').css('padding-bottom')) -
				parseInt(box.css('margin-bottom')) - 2 -
				boxHeader.outerHeight()
			);
		});
	}

	boxWithHeaderFullHeight();

	$(window).resize(function(){
		boxWithHeaderFullHeight();
	});

/* ==========================================================================
	Gallery
	========================================================================== */

	$('.gallery-item').matchHeight({
		target: $('.gallery-item .gallery-picture')
	});


/* ==========================================================================
	Addl side menu
	========================================================================== */

	setTimeout(function(){
		if (!("ontouchstart" in document.documentElement)) {
			$('.side-menu-addl').jScrollPane(jScrollOptions);
		}
	},1000);



/* ==========================================================================
	Tables
	========================================================================== */

	var generic_table = $('.generic_table');


    let tableOptions = {
		iconsPrefix: 'font-icon',
        toggle:'table',
        sidePagination:'client',
        pagination:'true',
		icons: {
			paginationSwitchDown:'font-icon-arrow-square-down',
			paginationSwitchUp: 'font-icon-arrow-square-down up',
			refresh: 'font-icon-refresh',
			toggle: 'font-icon-list-square',
			columns: 'font-icon-list-rotate',
			export: 'font-icon-download'
		},
		paginationPreText: '<i class="font-icon font-icon-arrow-left"></i>',
		paginationNextText: '<i class="font-icon font-icon-arrow-right"></i>',
	}

	generic_table.bootstrapTable(tableOptions);


    $('.generic_table').on( 'click-row.bs.table', function (e, row, $element) {
        if ( $element.hasClass('table_active') ) {
            $element.removeClass('table_active');
        }
        else {
            generic_table.find('tr.table_active').removeClass('table_active');
            $element.addClass('table_active');
        }
        window.location.href = back.click_url+row.id;
    });

/* ==========================================================================
    Side datepicker
    ========================================================================== */
    if(isset('enabledDates')){
	    $('#side-datetimepicker').datetimepicker({
	        inline: true,
            enabledDates: back.enabledDates,
	        format: 'YYYY-MM-DD',
            defaultDate: back.todayDate,
	    });
	}

	if(isset('date_url')){
	   	$("#side-datetimepicker").on("dp.change", function(e) {
	   		var date = new Date(e.date._d);
	   		var date_selected = dateFormat(date, "yyyy-mm-dd");
            var new_url = back.datatable_url+date_selected;
            var new_missingServices_url = back.missingServices_url+date_selected;

            generic_table.bootstrapTable('refresh', {url: new_url});
            reportVue.$broadcast('datePickerClicked', date_selected);
	    });
   }
   	if(isset('defaultDate')){
	    $('#editGenericDatepicker').datetimepicker({
	        widgetPositioning: {
				horizontal: 'right'
			},
			debug: false,
	        defaultDate: back.defaultDate,
	    });
	}

	$('#genericDatepicker').datetimepicker({
	        widgetPositioning: {
				horizontal: 'right'
			},
			debug: false,
	        useCurrent: true,
	    });


/* ==========================================================================
    Custom Slick Carousel
    ========================================================================== */

    $(".reports_slider").slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		dots: true,
		centerMode: true,
		focusOnSelect: true
	});


/* ==========================================================================
    Clockpicker
    ========================================================================== */

	$(document).ready(function() {
	    $('.clockpicker').clockpicker({
	        autoclose: true,
	        donetext: 'Done',
	        'default': 'now'
	    });
	});



 /* ==========================================================================
    VueJs code
    ========================================================================== */

    let Permissions 	 = require('./components/Permissions.vue');
    let PhotoList 	     = require('./components/photoList.vue');
    let emailPreference  = require('./components/email.vue');
    let FormToAjax   	= require('./directives/FormToAjax.vue');
    let countries       = require('./components/countries.vue');
    let dropdown       = require('./components/dropdown.vue');
    let alert       = require('./components/alert.vue');
    let billing       = require('./components/billing.vue');
    let contract       = require('./components/contract.vue');
    let chemical       = require('./components/chemical.vue');
    let equipment       = require('./components/equipment.vue');
    let works       = require('./components/works.vue');
    let payments       = require('./components/payments.vue');
    let routeTable     = require('./components/routeTable.vue');
    let notificationsWidget     = require('./components/notificationsWidget.vue');
    let AllNotificationsAsReadButton = require('./components/AllNotificationsAsReadButton.vue');
    let workOrderPhotosShow = require('./components/workOrderPhotosShow.vue');
    let workOrderPhotosEdit = require('./components/workOrderPhotosEdit.vue');
    let finishWorkOrderButton = require('./components/finishWorkOrderButton.vue');
    let deleteButton = require('./components/deleteButton.vue');
    let addressFields = require('./components/addressFields.vue');
    let missingServices = require('./components/missingServices.vue');
    let settings = require('./components/settings.vue');


    let mainVue = new Vue({
        el: '.site-header',
        components: {
            notificationsWidget
        }
    });

    let notificationsVue = new Vue({
        el: '.notificationsVue',
        components: {
            AllNotificationsAsReadButton
        }
    });

    // workOrders Vue instance
    let workOrderVue = new Vue({
        el:'.workOrderVue',
        components: {
            PhotoList,
            dropdown,
            deleteButton,
            workOrderPhotosShow,
            workOrderPhotosEdit,
            finishWorkOrderButton,
            works
        },
        data:{
            // index
            finishedSwitch: false,
            // create edit
            supervisorId: (isset('supervisorId')) ? back.supervisorId : 0,
            serviceId: (isset('serviceId')) ? back.serviceId : 0,
        },
        methods:{
            changeWorkOrderListFinished(finished){
                var intFinished = (!finished) ? 1 : 0;
                if(isset('workOrderTableUrl')){
                    let new_url = back.workOrderTableUrl+intFinished;
                    generic_table.bootstrapTable('refresh', {url:new_url});
            	}
            }
        },
    });

    // report Vue instance
    let reportVue = new Vue({
        el:'.reportVue',
        components: {
            dropdown,
            missingServices,
            deleteButton,
         },
        directives: { FormToAjax },
        data:{
            reportEmailPreview: (isset('emailPreviewNoImage')) ? back.emailPreviewNoImage : '',
            serviceKey:         (isset('serviceKey')) ? Number(back.serviceKey) : 0,
            technicianKey:      (isset('technicianKey')) ? Number(back.technicianKey) : 0,
        }
    });

    let settingsVue = new Vue({
        el: '.settingsVue',
        components:{
            Permissions,
            emailPreference,
            alert,
            billing,
            settings,
        },
        directives: { FormToAjax },
    });

    let serviceVue = new Vue({
        el: '.serviceVue',
        components: {
            PhotoList,
            countries,
            contract,
            chemical,
            equipment,
            routeTable,
            deleteButton,
            addressFields,
        },
        directives: {
            FormToAjax
        },
        data: {
            statusSwitch: true,
            serviceId: (isset('serviceId')) ? Number(back.serviceId) : 0,
        },
        methods: {
            // Index
            changeServiceListStatus(status){
                var intStatus = (!status) ? 1 : 0;
                if(isset('serviceTableUrl')){
                    let new_url = back.serviceTableUrl+intStatus;
                    generic_table.bootstrapTable('refresh', {url:new_url});
            	}
            },
        },

    });

    let supervisorVue = new Vue({
        el: '.supervisorVue',
        components: {
            deleteButton,
        },
        data:{
            statusSwitch: true,
        },
        methods:{
            changeSupervisorListStatus(status){
                var intStatus = (!status) ? 1 : 0;
                if(isset('supervisorTableUrl')){
                    let new_url = back.supervisorTableUrl+intStatus;
                    generic_table.bootstrapTable('refresh', {url:new_url});
            	}
            }
        }
    });


    let technicianVue = new Vue({
        el: '.technicianVue',
        components: {
            dropdown,
            deleteButton,
        },
        data:{
            statusSwitch: true,
            dropdownKey: (isset('dropdownKey')) ? Number(back.dropdownKey) : 0,
        },
        methods:{
            changeTechnicianListStatus(status){
                var intStatus = (!status) ? 1 : 0;
                if(isset('techniciansTableUrl')){
                    let new_url = back.techniciansTableUrl+intStatus;
                    generic_table.bootstrapTable('refresh', {url:new_url});
            	}
            }
        }
    });

    let invoiceVue = new Vue({
        el: '.invoiceVue',
        components: {
            payments,
            deleteButton,
        },
        data:{
            statusSwitch: false,
        },
        methods:{
            changeStatus(status){
                var intStatus = (!status) ? 1 : 0;
                if(isset('invoicesTableUrl')){
                    let new_url = back.invoicesTableUrl+intStatus;
                    generic_table.bootstrapTable('refresh', {url:new_url});
            	}
            }
        }
    });


/* ==========================================================================
    GMaps
    ========================================================================== */
    $('#mapModal').on('shown.bs.modal', function (e) {
        if(isset('showLatitude') && isset('showLongitude')){
            let map = new Gmaps({
                el: '#serviceMap',
                lat: back.showLatitude,
                lng: back.showLongitude,
            });

            map.addMarker({
                lat: back.showLatitude,
                lng: back.showLongitude
            });
        }
    });


/* ==========================================================================
    Dropzone
    ========================================================================== */

    // Dropzone.autoDiscover = false;
    // Dropzone.options.genericDropzone = {
    //     workOrderVue: workOrderVue,
    //     paramName: 'photo',
    // 	maxFilesize: 50,
    // 	acceptedFiles: '.jpg, .jpeg, .png',
    //     init: function() {
    //         this.on("success", function(file) {
    //             this.options.workOrderVue.$broadcast('photoUploaded');
    //         });
    //     }
    // }

/* ==========================================================================
    Location Picker
    ========================================================================== */


    let locPicker = $('#locationPicker').locationpicker({
        vue: serviceVue,
        location: {latitude: 23.04457265331633, longitude: -109.70587883663177},
        radius: 0,
        inputBinding: {
        	latitudeInput: $('#serviceLatitude'),
        	longitudeInput: $('#serviceLongitude'),
        	locationNameInput: $('#serviceAddress')
        },
        enableAutocomplete: true,
        onchanged: function (currentLocation, radius, isMarkerDropped) {
            let addressComponents = $(this).locationpicker('map').location.addressComponents;
            let vue = $(this).data("locationpicker").settings.vue;

            vue.pickerServiceAddressLine1 = addressComponents.addressLine1;
            vue.pickerServiceCity         = addressComponents.city;
            vue.pickerServiceState        = addressComponents.stateOrProvince;
            vue.pickerServicePostalCode   = addressComponents.postalCode;
            vue.pickerServiceCountry      = addressComponents.country;
            vue.pickerServiceLongitude      = currentLocation.longitude;
            vue.pickerServiceLatitude      = currentLocation.latitude;
        },
        oninitialized: function(component) {
            let addressComponents = $(component).locationpicker('map').location.addressComponents;
            let startLocation = $(component).data("locationpicker").settings.location;
            let vue = $(component).data("locationpicker").settings.vue;

            vue.pickerServiceAddressLine1 = addressComponents.addressLine1;
            vue.pickerServiceCity         = addressComponents.city;
            vue.pickerServiceState        = addressComponents.stateOrProvince;
            vue.pickerServicePostalCode   = addressComponents.postalCode;
            vue.pickerServiceCountry      = addressComponents.country;
            vue.pickerServiceLongitude      = startLocation.longitude;
            vue.pickerServiceLatitude      = startLocation.latitude;
        }
    });

    $('#locationPickerModal').on('shown.bs.modal', function () {
        $('#locationPicker').locationpicker('autosize');
    });


/* ==========================================================================
    Maxlenght and Hide Show Password
    ========================================================================== */

	$('input.maxlength-simple').maxlength();

    $('input.maxlength-custom-message').maxlength({
        threshold: 10,
        warningClass: "label label-success",
        limitReachedClass: "label label-danger",
        separator: ' of ',
        preText: 'You have ',
        postText: ' chars remaining.',
        validate: true
    });

    $('input.maxlength-always-show').maxlength({
        alwaysShow: true
    });

    $('textarea.maxlength-simple').maxlength({
        alwaysShow: true
    });

    $('.hide-show-password').password();

/* ========================================================================== */
});
