module.exports = {
	proxy: 'global4.local/',
	host: 'global4.local',
	open: 'external',
	notify: false,
	files: ['./css/*.min.css', './js/*.min.js', './**/*.php'],
};
