module.exports = {
	purge: {
		content: [
			'./public/**/*.html',
			'./src/**/*.{vue,js,ts,jsx,tsx}'
		]
	},
	darkMode: 'class', // or 'media' or 'class'
	theme: {
		extend: {
			colors: {
				'greyple': '#99AAB5',
				'darkbnb': '#2C2F33',
				'nqblack': '#23272A',
				'darkgpl': '#484b51'
			},
		},
	},
	variants: {
		extend: {},
	},
	plugins: [
		require('@tailwindcss/forms'),
		require('@tailwindcss/typography')
	],
}
