import DefaultLayout from "../layouts/DefaultLayout";
import ProviderCard from "../components/ProviderCard";
import providers from "../data/providers";
import { Loader as MapsLoader } from "@googlemaps/js-api-loader";
import { useEffect, useState } from "react";
import Loader from "../components/Loader";

function ProviderList() {
	const [location, setLocation] = useState({
		lng: 2.4563571,
		lat: 48.9432991,
	});
	const [isLoading, setIsLoading] = useState(true);

	useEffect(() => {
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
								zoom: 12,
							}
						);
						for (const provider of providers) {
							const marker = new google.maps.Marker({
								position: provider.location,
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
				console.error(
					"Erreur lors du chargement de Google Maps :",
					error
				);
			}
		};

		fetchData();
	}, []);

	return (
		<DefaultLayout>
			<div className="flex flex-row">
				<div className="w-7/12 h-screen overflow-y-auto">
					<div className="bg-gray-100 p-7">
						<h1 className="text-md font-semibold text-gray-800">
							Sélectionnez un garage
						</h1>
						<h2 className="text-sm text-gray-600">
							Les meilleurs garages près de chez vous
						</h2>
					</div>
					<div>
						{providers.map((provider) => (
							<div key={provider.id}>
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
