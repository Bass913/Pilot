import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import providers from "../data/providers";
import DefaultLayout from "../layouts/DefaultLayout";
import Loader from "../components/Loader";
import { MapPinIcon, StarIcon } from "@heroicons/react/24/outline";
import ImagesGallery from "../components/ImagesGallery";
import ServicesChooser from "../components/ServicesChooser";
import CompanySchedule from "../components/CompanySchedule";
import CompanyReviews from "../components/CompanyReviews";

function ProviderDetail() {
	const { id } = useParams();
	const [provider, setProvider] = useState(null);

	useEffect(() => {
		const provider = providers.find(
			(provider) => provider.id === parseInt(id)
		);
		setProvider(provider);
	}, []);

	return (
		<DefaultLayout>
			<div className="flex justify-center w-full bg-gray-100">
				<div className="max-w-6xl w-full">
					{provider === null ? (
						<Loader />
					) : (
						<div
							className="bg-gray-100 px-8 py-10 flex flex-col"
							style={{ minHeight: "calc(100vh - 5rem)" }}
						>
							<div>
								<h1 className="text-2xl font-medium">
									{provider.name}
								</h1>
								<p className="text-gray-600 mt-2 flex gap-2 items-center font-light">
									<MapPinIcon className="w-4 text-gray-400" />
									{provider.address}
								</p>
								<p className="text-gray-600 mt-2 flex gap-2 items-center font-light">
									<StarIcon className="w-4 text-gray-400" />
									{provider.reviewRating} (
									{provider.reviewsCount} avis)
								</p>
							</div>

							<ImagesGallery images={provider.images} />

							<div className="mt-10">
								<h2 className="text-xl font-medium text-gray-800">
									Réservez un rendez-vous en ligne avec{" "}
									{provider.name}
								</h2>
								<p className="text-gray-600 mt-2 font-light">
									Le paiement se fait sur place
								</p>
							</div>

							<div className="mt-8 flex flex-col lg:flex-row gap-8 w-full">
								<div className="w-full">
									<div>
										<h3 className="text-xl font-medium text-gray-800 mb-4">
											Choisissez votre prestation
										</h3>
										<ServicesChooser
											services={provider.services}
										/>
										<small className="text-gray-600">
											* Les prix sont donnés à titre
											indicatif et peuvent varier en
											fonction du véhicule
										</small>
									</div>

									<div className="mt-10">
										<h4 className="text-lg font-medium text-gray-800 mb-4">
											À propos de {provider.name}
										</h4>
										<p className="text-gray-600 bg-white p-4 rounded-md shadow-md font-light text-base">
											{provider.description}
										</p>
									</div>

									<div className="mt-10">
										<h4 className="text-lg font-medium text-gray-800 mb-4">
											Avis des clients
										</h4>
										<CompanyReviews
											reviews={provider.reviews}
										/>
									</div>
								</div>

								<div className="w-full lg:w-132">
									<h4 className="text-lg font-medium text-gray-800 mb-4">
										Horaires d'ouverture
									</h4>
									<CompanySchedule
										schedule={provider.schedule}
									/>
								</div>
							</div>
						</div>
					)}
				</div>
			</div>
		</DefaultLayout>
	);
}

export default ProviderDetail;
