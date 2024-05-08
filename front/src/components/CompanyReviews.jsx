import { StarIcon } from "@heroicons/react/24/solid";
import { StarIcon as StarIconOutline } from "@heroicons/react/24/outline";

function CompanyReviews({ reviews }) {
	// "review": {
	// 		"id": 1,
	// 		"date": "2021-06-01",
	// 		"rating": [
	// 			{
	// 				"category": {
	// 					"name": "Qualité de service"
	// 				},
	// 				"value": 5
	// 			},
	// 			{
	// 				"category": {
	// 					"name": "Prix"
	// 				},
	// 				"value": 4
	// 			},
	// 			{
	// 				"category": {
	// 					"name": "Délai"
	// 				},
	// 				"value": 5
	// 			},
	// 			{
	// 				"category": {
	// 					"name": "Accueil"
	// 				},
	// 				"value": 3
	// 			}
	// 		],
	// 		"comment": "Super garage, je recommande !"
	// 	},

	function calculateReviewAverageRating(review) {
		const total = review.rating.reduce((acc, rating) => {
			return acc + rating.value;
		}, 0);
		return total / review.rating.length;
	}

	function formatDate(date) {
		const [year, month, day] = date.split("-");
		return `${day}/${month}/${year}`;
	}

	function formatRatingValue(value) {
		return value.toString().replace(".", ",");
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
					<div>
						<p className="text-gray-800 font-medium flex items-center gap-1">
							{formatRatingValue(
								calculateReviewAverageRating(review)
							)}
							<StarIcon className="w-4 inline-block text-primary-500" />
						</p>
						<p className="text-gray-600 font-light">
							{review.comment}
						</p>
						<p className="text-gray-800 font-light text-sm">
							{formatDate(review.date)}
						</p>
					</div>
					<div className="hidden md:block">
						{review.rating.map((rating, index) => (
							<p
								key={index}
								className="text-gray-800 font-light text-sm flex items-center"
							>
								{rating.category.name}:{" "}
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
