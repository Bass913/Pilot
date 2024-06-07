import { useState } from "react";
import { MapPinIcon, StarIcon } from "@heroicons/react/24/outline";
import { NavLink } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { useNavigate } from "react-router-dom";

function ProviderCard({ provider }) {
	const { t } = useTranslation();
	const [expanded, setExpanded] = useState(false);
	const navigate = useNavigate();

	function getAddress(provider) {
		return `${provider.address} ${provider.zipcode} ${provider.city}`;
	}

	return (
		<div className="bg-white p-5 flex-col border-b border-gray-200 hover:drop-shadow-xl cursor-pointer transition-all">
			<NavLink to={provider["@id"]} className="flex">
				<div className="mr-5 w-1/2 min-w-1/2 md:w-72 md:min-w-72">
					<img
						src={
							!provider.images || !provider.images.length
								? "/no-image.jpeg"
								: provider.images[0]
						}
						alt="Garage"
						className="w-full object-cover h-40 rounded-md"
					/>
				</div>
				<div>
					<h2 className="text-lg font-semibold text-gray-800 mb-1">
						{provider.name}
					</h2>
					<p className="text-sm text-gray-600 flex gap-2 items-center">
						{provider.speciality.name}
					</p>
					<p className="text-sm text-gray-600 flex gap-2 items-center underline">
						<MapPinIcon className="w-4 text-gray-400" />
						{getAddress(provider)}
					</p>
					<p className="text-sm text-gray-600 flex gap-2 items-center">
						<StarIcon className="w-4 text-gray-400" />
						{provider.reviewRating.toString().replace(".", ",")} (
						{provider.reviewCount} {t("reviews")})
					</p>
				</div>
			</NavLink>
			{expanded && (
				<div>
					<h3 className="text-sm font-semibold text-gray-800 mt-5">
						{t("more-information-about")} {provider.name}
					</h3>
					<p className="text-sm text-gray-600 mt-2">
						{provider.description}
					</p>
				</div>
			)}
			<div className="flex justify-between">
				<button
					className="text-primary-600 hover:text-primary-700 mt-5 font-semibold text-sm underline"
					onClick={() => setExpanded(!expanded)}
				>
					{expanded ? t("reduce") : t("more-information")}
				</button>
				<button
					className="bg-primary-600 text-white px-3 py-1.5 rounded-sm text-sm hover:bg-primary-700"
					onClick={() => navigate(provider["@id"])}
				>
					{t("make-an-appointment")}
				</button>
			</div>
		</div>
	);
}

export default ProviderCard;
