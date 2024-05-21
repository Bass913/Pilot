import { useState } from "react";
import { MapPinIcon, StarIcon } from "@heroicons/react/24/outline";
import { NavLink } from "react-router-dom";
import { useTranslation } from "react-i18next";

function ProviderCard({ provider }) {
	const { t } = useTranslation();
	const [expanded, setExpanded] = useState(false);

	return (
		<div className="bg-white p-5 flex-col border-b border-gray-200 hover:drop-shadow-xl cursor-pointer transition-all">
			<NavLink to={`/provider/${provider.id}`} className="flex">
				<div className="mr-5">
					<img
						src={provider.images[0]}
						alt="Garage"
						className="w-72 object-cover h-40 rounded-md"
					/>
				</div>
				<div>
					<h2 className="text-lg font-semibold text-gray-800 mb-1">
						{provider.name}
					</h2>
					<p className="text-sm text-gray-600 flex gap-2 items-center">
						<MapPinIcon className="w-4 text-gray-400" />
						{provider.address}
					</p>
					<p className="text-sm text-gray-600 flex gap-2 items-center">
						<StarIcon className="w-4 text-gray-400" />
						{provider.reviewRating.toString().replace(".", ",")} (
						{provider.reviewsCount} {t("reviews")})
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
				<button className="bg-primary-600 text-white px-3 py-1.5 rounded-sm text-sm hover:bg-primary-700">
					{t("make-an-appointment")}
				</button>
			</div>
		</div>
	);
}

export default ProviderCard;
