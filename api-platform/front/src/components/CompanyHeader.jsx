import { MapPinIcon, StarIcon } from "@heroicons/react/24/outline";
import { useTranslation } from "react-i18next";
import { getAddress } from "../utils/addressFormatter";

function CompanyHeader({ provider }) {
	const { t } = useTranslation();
	return (
		<div>
			<h1 className="text-2xl font-medium">{provider.name}</h1>
			<p className="text-gray-600 mt-2 flex gap-2 items-center font-light">
				<MapPinIcon className="w-4 text-gray-400" />
				<span className="underline">{getAddress(provider)}</span>
			</p>
			<p className="text-gray-600 mt-2 flex gap-2 items-center font-light">
				<StarIcon className="w-4 text-gray-400" />
				{provider.reviewRating} ({provider.reviewCount} {t("reviews")})
			</p>
		</div>
	);
}

export default CompanyHeader;
