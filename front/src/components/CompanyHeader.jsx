import { MapPinIcon, StarIcon } from "@heroicons/react/24/outline";
import { useTranslation } from "react-i18next";

function CompanyHeader({ provider }) {
	const { t } = useTranslation();
	return (
		<div>
			<h1 className="text-2xl font-medium">{provider.name}</h1>
			<p className="text-gray-600 mt-2 flex gap-2 items-center font-light">
				<MapPinIcon className="w-4 text-gray-400" />
				<span className="underline">{provider.address}</span>
			</p>
			<p className="text-gray-600 mt-2 flex gap-2 items-center font-light">
				<StarIcon className="w-4 text-gray-400" />
				{provider.reviewRating} ({provider.reviewsCount} {t('reviews')})
			</p>
		</div>
	);
}

export default CompanyHeader;
