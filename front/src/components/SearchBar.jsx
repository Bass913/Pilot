import { useState, useEffect } from "react";
import Button from "./Button.jsx";
import {
	MagnifyingGlassIcon,
	MicrophoneIcon,
} from "@heroicons/react/24/outline";
// import useSpeechToText from "../hooks/useSpeechToText";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";

const SearchBar = ({ initialValue }) => {
	const { t } = useTranslation();
	const [search, setSearch] = useState(initialValue);
	const navigate = useNavigate();
	// const { isListening, transcript, startListening, stopListening } =
	// 	useSpeechToText({ continuous: true });

	// const startStopListening = () => {
	// 	if (isListening) {
	// 		stopVoiceInput();
	// 	} else {
	// 		setSearch("");
	// 		startListening();
	// 	}
	// };

	// const stopVoiceInput = () => {
	// 	stopListening();
	// };

	// useEffect(() => {
	// 	if (isListening) {
	// 		setSearch(transcript);
	// 	}
	// }, [transcript, isListening]);

	const handleChange = (e) => {
		setSearch(e.target.value);
	};

	const handleSubmit = (e) => {
		e.preventDefault();
		if (!search) {
			navigate("/providers");
			return;
		}
		navigate(`/?search=${search}`);
	};
	return (
		<div
			onSubmit={handleSubmit}
			className="flex items-center justify-center mt-28 relative w-full md:w-128"
		>
			<MagnifyingGlassIcon className="w-5 h-5 text-gray-600 absolute  left-3 z-10" />
			<input
				type="text"
				aria-label="search provider"
				className="w-full h-10 p-7 border border-gray-600 rounded focus:border-primary-600 focus:outline-none relative pl-10 pr-20 hover:border-primary-600 hover:bg-gray-50 text-sm text-gray-600 font-normal placeholder-gray-500 transition-all duration-200"
				placeholder={t("search-provider")}
				name="search"
				onChange={handleChange}
				// disabled={isListening}
				value={search}
			/>
			<Button
				className="text-white font-normal h-10 rounded p-3 ml-2 absolute right-2 z-10 flex items-center text-sm bg-primary-600 hover:bg-primary-700"
				onClick={handleSubmit}
			>
				{t("search")}
			</Button>
			{/* <MicrophoneIcon
				onClick={startStopListening}
				className="w-5 h-5 cursor-pointer text-gray-600 absolute right-28 z-10"
			/>{" "} */}
		</div>
	);
};

export default SearchBar;
