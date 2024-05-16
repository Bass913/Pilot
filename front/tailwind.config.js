/** @type {import('tailwindcss').Config} */
export default {
	content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
	theme: {
		extend: {
			colors: {
				"primary-50": "#f0fdf4",
				"primary-100": "#d1fae5",
				"primary-200": "#a7f3d0",
				"primary-300": "#6ee7b7",
				"primary-400": "#34d399",
				"primary-500": "#10b981",
				"primary-600": "#059669",
				"primary-700": "#047857",
				"primary-800": "#065f46",
				"primary-900": "#064e3b",
				"primary-950": "#022c22",
			},
			width: {
				132: "33rem",
			},
			minWidth: {
				120: "30rem",
			},
		},
	},
	plugins: [],
};
