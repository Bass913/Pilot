import { StarIcon } from "@heroicons/react/24/solid";
import { StarIcon as StarIconOutline } from "@heroicons/react/24/outline";
import { useTranslation } from "react-i18next";
import { getFormattedDateFromISO } from "../utils/dateFormatter";

function CompanyReviews({ reviews }) {
	const { t } = useTranslation();

	function formatRatingValue(value) {
		return value.toString().replace(".", ",");
	}

	function getAverageRating(review) {
		const sum = review.ratings.reduce((acc, rating) => {
			return acc + rating.value;
		}, 0);

		return sum / review.ratings.length;
	}

	return (
		<ul className="bg-white px-5 py-2 rounded-md shadow-md">
			{reviews.map((review, index) => (
				<li
					key={index}
					className={`flex md:justify-between md:items-center py-4 flex-col md:flex-row ${
						index !== reviews.length - 1 &&
						"border-b border-gray-200"
					}`}
				>
					<div className="w-2/3">
						<p className="text-gray-800 font-medium flex items-center gap-1">
							{formatRatingValue(getAverageRating(review))}
							<StarIcon className="w-4 inline-block text-primary-500" />
						</p>
						<p className="text-gray-600 font-light">
							{review.comment}
						</p>
						<p className="text-gray-800 font-light text-sm">
							{getFormattedDateFromISO(review.date)}
						</p>
					</div>
					<div className="hidden md:block">
						{review.ratings.map((rating, index) => (
							<p
								key={index}
								className="text-gray-800 font-light text-sm flex items-center"
							>
								{t(rating.category.name)}:{" "}
								{formatRatingValue(rating.value)}
								<StarIconOutline className="w-3 inline-block text-gray-600" />
							</p>
						))}
					</div>
				</li>
			))}
		</ul>
	);
}

export default CompanyReviews;
