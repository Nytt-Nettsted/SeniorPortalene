/* Norwegian translation for the jQuery Datepicker and Timepicker Addon */
/* Written by Morten Hauan (http://hauan.me) and Knut Sparhell */
(function($){
	$.timepicker.regional['no'] = {
		timeOnlyTitle: 'Velg tid',
		timeText: 'Tid',
		hourText: 'Time',
		minuteText: 'Minutt',
		secondText: 'Sekund',
		millisecText: 'Millisekund',
		timezoneText: 'Tidssone',
		currentText: 'Nå',
		closeText: 'Lukk',
		timeFormat: 'HH:mm',
		amNames: ['am', 'AM', 'A'],
		pmNames: ['pm', 'PM', 'P'],
		ampm: false
	};
	$.timepicker.setDefaults($.timepicker.regional['no']);
})(jQuery);

(function($){
	$.datepicker.regional['no'] = {
		closeText: 'Lukk',
		prevText: '&laquo;Forrige',
		nextText: 'Neste&raquo;',
		currentText: 'Idag',
		monthNames: ['Januar','Februar','Mars','April','Mai','Juni','Juli','August','September','Oktober','November','Desember'],
		monthNamesShort: ['Jan','Feb','Mar','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Des'],
		dayNames: ['Søndag', 'Mandag','Tirsdag','Onsdag','Torsdag','Fredag','Lørdag'],
		dayNamesShort: ['Søn','Man','Tir','Ons','Tor','Fre','Lør'],
		dayNamesMin: ['Sø','Ma','Ti','On','To','Fr','Lø'],
		weekHeader: 'Uke',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};
	$.datepicker.setDefaults($.datepicker.regional['no']);
})(jQuery);
