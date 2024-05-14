import i18n from "i18next";
import { initReactI18next } from "react-i18next";
import fr from "./locales/fr.json";
import en from "./locales/en.json";

const resources = {
	en: {
		translation: en,
	},
	fr: {
		translation: fr,
	},
};

i18n.use(initReactI18next).init({
	resources,
	lng: localStorage.getItem("language"),
	interpolation: {
		escapeValue: false,
	},
	debug: true,
	fallbackLng: "fr",
});

export default i18n;
