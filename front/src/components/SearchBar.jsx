import { useEffect, useState } from "react";
import Button from "./Button.jsx";
import { MagnifyingGlassIcon } from "@heroicons/react/24/outline";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import apiService from "../services/apiService";

const SearchBar = ({ initialGarageSearch = "", initialAddressSearch = "" }) => {
	const { t } = useTranslation();
	const [garageSearch, setGarageSearch] = useState(initialGarageSearch);
	const [addressSearch, setAddressSearch] = useState(initialAddressSearch);
	const [services, setServices] = useState([]);
	const [servicesToShow, setServicesToShow] = useState([]);
	const [specialities, setSpecialities] = useState([]);
	const [specialitiesToShow, setSpecialitiesToShow] = useState([]);
	const [showServices, setShowServices] = useState(false);
	const navigate = useNavigate();

	const fetchServices = async () => {
		const response = await apiService.getServices();
		setServices(response.data["hydra:member"]);
		setServicesToShow(response.data["hydra:member"]);
	};

	const fetchSpecialities = async () => {
		const response = await apiService.getSpecialities();
		setSpecialities(response.data["hydra:member"]);
		setSpecialitiesToShow(response.data["hydra:member"]);
	};

	const filterServices = (search) => {
		const servicesFiltered = services.filter((service) =>
			service.name.toLowerCase().includes(search.toLowerCase())
		);
		setServicesToShow(servicesFiltered);
	};

	const filterSpecialities = (search) => {
		const specialitiesFiltered = specialities.filter((speciality) =>
			speciality.name.toLowerCase().includes(search.toLowerCase())
		);
		setSpecialitiesToShow(specialitiesFiltered);
	};

	useEffect(() => {
		fetchServices();
		fetchSpecialities();
	}, []);

	const handleGarageSearchChange = (e) => {
		setGarageSearch(e.target.value);
		filterServices(e.target.value);
		filterSpecialities(e.target.value);
	};
	const handleAddressSearchChange = (e) => {
		setAddressSearch(e.target.value);
	};
	const handleSubmit = (e) => {
		e.preventDefault();
		const url = garageSearch
			? `/companies?search=${garageSearch}`
			: "/companies";
		navigate(url);
	};
	return (
		<div
			onSubmit={handleSubmit}
			className="flex items-center justify-center w-full md:w-150 lg:w-175 bg-white rounded-lg p-2 h-20 relative"
		>
			{showServices && (
				<div className="absolute top-5 left-0 h-52 w-full flex text-gray-600 transform translate-y-16 bg-white rounded-lg overflow-hidden shadow-md">
					{servicesToShow.length > 0 && (
						<div className="w-5/12 h-full py-2">
							<p className="text-sm text-gray-800 font-normal py-2 px-3 underline">
								{t("services")}
							</p>
							<ul className="flex flex-col w-full h-full overflow-y-auto align-start">
								{servicesToShow.map((service) => (
									<li
										key={service.id}
										className="text-sm text-gray-600 font-normal py-2 px-4 hover:bg-gray-100 w-full cursor-pointer"
										onClick={() =>
											setGarageSearch(service.name)
										}
									>
										{service.name}
									</li>
								))}
							</ul>
						</div>
					)}
					{specialitiesToShow.length > 0 && (
						<div className="w-7/12 h-full py-2">
							<p className="text-sm text-gray-800 font-normal py-2 px-3 underline">
								{t("specialities")}
							</p>
							<ul className="flex flex-col w-full h-full overflow-y-auto align-start">
								{specialitiesToShow.map((speciality) => (
									<li
										key={speciality.id}
										className="text-sm text-gray-600 font-normal py-2 px-4 hover:bg-gray-100 w-full cursor-pointer"
										onClick={() =>
											setGarageSearch(speciality.name)
										}
									>
										{speciality.name}
									</li>
								))}
							</ul>
						</div>
					)}
				</div>
			)}
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
					onFocus={() => setShowServices(true)}
					onBlur={() => {
						setTimeout(() => {
							setShowServices(false);
						}, 100);
					}}
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
