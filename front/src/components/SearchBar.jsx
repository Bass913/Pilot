import { useState } from "react";
import Button from "./Button.jsx";
import { MagnifyingGlassIcon } from "@heroicons/react/24/outline";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";

const SearchBar = ({ initialGarageSearch, initialAddressSearch }) => {
	const { t } = useTranslation();
	const [garageSearch, setGarageSearch] = useState(initialGarageSearch);
	const [addressSearch, setAddressSearch] = useState(initialAddressSearch);
	const navigate = useNavigate();

	const handleGarageSearchChange = (e) => {
		setGarageSearch(e.target.value);
	};
	const handleAddressSearchChange = (e) => {
		setAddressSearch(e.target.value);
	};
	const handleSubmit = (e) => {
		e.preventDefault();
		navigate("/providers");
	};
	return (
		<div
			onSubmit={handleSubmit}
			className="flex items-center justify-center w-full md:w-150 lg:w-175 bg-white rounded-lg p-2 h-20"
		>
			<div className="w-6 ml-2">
				<MagnifyingGlassIcon className="w-full text-gray-600" />
			</div>
			<div className="flex w-full h-full items-center mx-3">
				<input
					type="text"
					aria-label="search provider"
					className="w-1/2 h-full px-3 border border-white rounded focus:border-primary-600 focus:outline-none focus:bg-gray-100 relative hover:bg-gray-100 text-sm text-gray-600 font-normal placeholder-gray-500"
					placeholder={t("search-provider")}
					name="search"
					onChange={handleGarageSearchChange}
					value={garageSearch}
				/>
				<hr className="w-0 h-10 border-r border-gray-400 mx-2" />
				<input
					type="text"
					aria-label="search provider"
					className="w-1/2 h-full px-3 border border-white rounded focus:border-primary-600 focus:outline-none focus:bg-gray-100 relative hover:bg-gray-100 text-sm text-gray-600 font-normal placeholder-gray-500"
					placeholder={t("search-address")}
					name="search"
					onChange={handleAddressSearchChange}
					value={addressSearch}
				/>
			</div>
			<Button
				className="text-white font-normal h-10 rounded p-3 mr-2 z-10 flex items-center text-sm bg-primary-600 hover:bg-primary-700"
				onClick={handleSubmit}
			>
				{t("search")}
			</Button>
		</div>
	);
};

export default SearchBar;
