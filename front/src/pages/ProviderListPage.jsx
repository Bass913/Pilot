import DefaultLayout from "../layouts/DefaultLayout";
import ProviderCard from "../components/ProviderCard";
import { Loader as MapsLoader } from "@googlemaps/js-api-loader";
import { useEffect, useState } from "react";
import Loader from "../components/Loader";
import { useTranslation } from "react-i18next";
import apiService from "../services/apiService";
import { useLocation } from "react-router-dom";

function ProviderList() {
	const { t } = useTranslation();
	const [location, setLocation] = useState({
		lng: 2.4563571,
		lat: 48.9432991,
	});
	const [isLoading, setIsLoading] = useState(true);
	const locationUrl = useLocation();
	const [providers, setProviders] = useState([]);

	const fetchCompanies = async () => {
		try {
			const searchParams = new URLSearchParams(locationUrl.search);
			const search = searchParams.get("search") || "";

			const response = await apiService.getCompanies({ search });
			setProviders(response.data["hydra:member"]);
		} catch (error) {
			console.error(
				"Erreur lors de la récupération des garages :",
				error
			);
		}
	};

	const fetchData = async () => {
		try {
			const loader = new MapsLoader({
				apiKey: process.env.GOOGLE_MAPS_API_KEY,
				version: "weekly",
			});

			const google = await loader.load();

			navigator.geolocation.getCurrentPosition(
				(position) => {
					setLocation({
						lat: position.coords.latitude,
						lng: position.coords.longitude,
					});
					const map = new google.maps.Map(
						document.getElementById("map"),
						{
							center: location,
							zoom: 10,
						}
					);
					for (const provider of providers) {
						const marker = new google.maps.Marker({
							position: {
								lat: parseFloat(provider.latitude),
								lng: parseFloat(provider.longitude),
							},
							map: map,
							title: provider.name,
						});
					}
				},
				(error) => {
					console.error("Erreur de géolocalisation :", error);
				}
			);
		} catch (error) {
			console.error("Erreur lors du chargement de Google Maps :", error);
		}
	};

	useEffect(() => {
		fetchCompanies();
	}, []);

	useEffect(() => {
		fetchData();
	}, [providers]);

	return (
		<DefaultLayout>
			<div className="flex flex-row">
				<div className="w-7/12 h-screen overflow-y-auto">
					<div className="bg-gray-100 p-7">
						<h1 className="text-md font-semibold text-gray-800">
							{t("select-a-garage")}
						</h1>
						<h2 className="text-sm text-gray-600">
							{t("best-garages-closest-to-you")}
						</h2>
					</div>
					<div>
						{providers.length > 0 &&
							providers.map((provider) => (
								<div key={provider["@id"]}>
									<ProviderCard provider={provider} />
								</div>
							))}
					</div>
				</div>
				<div className="w-5/12 bg-primary-100" id="map">
					{isLoading && <Loader />}
				</div>
			</div>
		</DefaultLayout>
	);
}

export default ProviderList;
