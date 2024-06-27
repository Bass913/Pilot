import { defineConfig, loadEnv } from "vite";
import react from "@vitejs/plugin-react";
import path from "path";

// https://vitejs.dev/config/
export default defineConfig(({ command, mode }) => {
	const env = loadEnv(mode, process.cwd(), "");
	return {
		define: {
			'process.env': JSON.stringify(env),
		},
		resolve: {
			alias: {
				'@': path.resolve(__dirname, 'src')
			},
		},
		plugins: [react()],
	};
});
