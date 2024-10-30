'use strict';

( function( window ) {

	window.EB_PATH = jackmail_ajax_object.emailbuilder_path;

} )( window );

document.write( '<script src="' + window.EB_PATH + 'scripts.js"></script>' );
document.write( '<script src="' + window.EB_PATH + 'runtime-es5.js"></script>' );
document.write( '<script src="' + window.EB_PATH + 'polyfills-es5.js"></script>' );
document.write( '<script src="' + window.EB_PATH + 'styles-es5.js"></script>' );
document.write( '<script src="' + window.EB_PATH + 'main-es5.js"></script>' );
