import React, { useEffect, useState } from "react";
import { NavLink, useParams } from "react-router-dom";
import DefaultLayout from "../layouts/DefaultLayout";
import Loader from "../components/Loader";
import ImagesGallery from "../components/ImagesGallery";
import ServicesChooser from "../components/ServicesChooser";
import CompanySchedule from "../components/CompanySchedule";
import CompanyReviews from "../components/CompanyReviews";
import CompanyHeader from "../components/CompanyHeader";
import { useTranslation } from "react-i18next";
import apiService from "../services/apiService";

function ProviderDetail() {
	const { t } = useTranslation();
	const { id } = useParams();
	const [provider, setProvider] = useState(null);
	const [services, setServices] = useState([]);
	const [similarServices, setSimilarServices] = useState([]);

	const fetchProvider = async () => {
		try {
			const response = await apiService.getCompany(id);
			setProvider(response.data);
		} catch (error) {
			console.error("Erreur lors de la récupération du garage :", error);
		}
	};

	const fetchServices = async () => {
		const response = await apiService.getServices({ pagination: false });
		setServices(response.data["hydra:member"]);
	};

	const getSimilarServices = () => {
		if (!provider) return;
		const similar = services.filter(
			(service) =>
				!provider.companyServices.some(
					(companyService) =>
						companyService.service["@id"] === service["@id"]
				)
		);
		const randomSimilarServices = similar
			.sort(() => 0.5 - Math.random())
			.slice(0, 5);
		setSimilarServices(randomSimilarServices);
	};

	useEffect(() => {
		fetchProvider();
		fetchServices();
	}, []);

	useEffect(() => {
		if (!services.length) return;
		getSimilarServices();
	}, [services, provider]);

	return (
		<DefaultLayout>
			<div className="flex justify-center w-full bg-gray-100">
				<div
					className="max-w-5xl w-full flex flex-col py-10 px-6"
					style={{ minHeight: "calc(100vh - 5rem)" }}
				>
					{!provider ? (
						<div className="flex justify-center h-screen">
							<Loader />
						</div>
					) : (
						<div>
							<CompanyHeader provider={provider} />
							<ImagesGallery images={provider.images} />

							<div className="mt-10">
								<h2 className="text-xl font-medium text-gray-800">
									{t("make-an-appointment-with")}{" "}
									{provider.name}
								</h2>
								<p className="text-gray-600 mt-2 font-light">
									{t("payment-in-place")}
								</p>
							</div>

							<div className="mt-8 flex flex-col lg:flex-row gap-8 w-full mb-20">
								<div className="w-full">
									<div>
										<h3 className="text-xl font-medium text-gray-800 mb-4">
											{t("select-your-service")}
										</h3>
										<ServicesChooser
											services={provider.companyServices}
										/>
										<small className="text-gray-600">
											* {t("indicative-prices")}
										</small>
									</div>

									<div className="mt-10">
										<h4 className="text-lg font-medium text-gray-800 mb-4">
											{t("about")} {provider.name}
										</h4>
										<p className="text-gray-600 bg-white p-4 rounded-md shadow-md font-light text-base">
											{provider.description}
										</p>
									</div>

									<div className="mt-10">
										<h4 className="text-lg font-medium text-gray-800 mb-4">
											{t("client-reviews")}
										</h4>
										<CompanyReviews
											reviewRating={provider.reviewRating}
											reviews={provider.reviews}
										/>
									</div>
								</div>

								<div className="w-full ">
									<div className="w-full mb-10">
										<h4 className="text-lg font-medium text-gray-800 mb-4">
											{t("opening-hours")}
										</h4>
										<CompanySchedule
											schedules={provider.schedules}
										/>
									</div>

									<div className="w-full mb-10">
										<h4 className="text-lg font-medium text-gray-800 mb-4">
											{t("similar-services")}
										</h4>
										<div className="bg-white rounded-md shadow-md font-light text-base p-6">
											{similarServices?.map(
												(service, index) => (
													<NavLink
														to={`/companies?search=${service.name}`}
													>
														<div
															key={service.id}
															className={`flex items-center justify-between py-4 mx-5 cursor-pointer hover:text-primary-600
															${index !== similarServices.length - 1 && "border-b border-gray-200"}
															${index === 0 && "pt-0"}
															${index === similarServices.length - 1 && "pb-0"}
														 `}
														>
															<p>
																{t(
																	service.name
																)}
															</p>
														</div>
													</NavLink>
												)
											)}
										</div>
									</div>
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
