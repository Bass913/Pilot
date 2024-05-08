import { MapPinIcon, StarIcon } from "@heroicons/react/24/outline";

function CompanyHeader({ provider }) {
	return (
		<div>
			<h1 className="text-2xl font-medium">{provider.name}</h1>
			<p className="text-gray-600 mt-2 flex gap-2 items-center font-light">
				<MapPinIcon className="w-4 text-gray-400" />
				{provider.address}
			</p>
			<p className="text-gray-600 mt-2 flex gap-2 items-center font-light">
				<StarIcon className="w-4 text-gray-400" />
				{provider.reviewRating} ({provider.reviewsCount} avis)
			</p>
		</div>
	);
}

export default CompanyHeader;
