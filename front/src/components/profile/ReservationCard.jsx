import { getFormattedDate } from "../../utils/schedule";
import { MapPinIcon } from "@heroicons/react/24/outline";
import { NavLink } from "react-router-dom";
import { useTranslation } from "react-i18next";
import AddFeedbackModal from "../modals/AddFeedbackModal";
import { useState } from "react";
import apiService from "../../services/apiService";

function ReservationCard({ reservation }) {
	const { t } = useTranslation();
	const [isModalOpen, setIsModalOpen] = useState(false);
	const [reviewCompany, setReviewCompany] = useState(null);

	const isDatePassed = new Date(reservation.date) < new Date();

	const handleOpenModal = (company) => () => {
		setReviewCompany(company);
		setIsModalOpen(true);
	};

	const handleCloseModal = () => {
		setIsModalOpen(false);
	};

	const handleFeedbackSubmit = (feedback) => {
		console.log("Feedback submitted:", feedback);
		// create ratings in the database, then the review with the comment
		// feedback.ratings.forEach((categoryId, value) => {
		//     apiService.addRating(
		//         {
		//             category: `/category_reviews/${categoryId}`,
		//             value: value,
		//         }
		//     );
		// });
		// apiService.addReview(
		//     {
		//         company: `/companies/${reviewCompany.id}`,
		//         user: `/users/${reviewCompany.user.id}`,
		//         comment: feedback.feedback,
		//         ratings: feedback.ratings,
		//     }
		// );
	};

	return (
		<div
			className="mb-5 bg-white p-4 rounded-lg shadow-md lg:p-6 flex flex-col justify-between"
			key={reservation.id}
		>
			<div className="flex justify-between items-start border-b border-gray-200 pb-5">
				<div className="flex flex-col gap-1">
					<p className="text-gray-800 font-normal">
						{reservation.company.name}
					</p>
					<p className="text-gray-500 font-light underline text-sm flex items-center gap-2">
						<MapPinIcon className="h-4" />
						{reservation.company.address}
					</p>
				</div>
				<div className="flex items-center gap-4">
					<p className="text-gray-800 font-light">
						{getFormattedDate(reservation.date)}
					</p>
				</div>
			</div>
			<div className="flex items-center gap-4 mt-4">
				<p className="text-gray-800">{reservation.service}</p>
				<div className="rounded-full bg-gray-300 p-0.5"></div>
				<p className="text-gray-700 font-light text-sm">
					{reservation.price} €
				</p>
			</div>
			<div className="flex items-center gap-4 justify-end">
				{!isDatePassed ? (
					<>
						<NavLink to={`/companies/${reservation.id}`}>
							<button className="text-primary-600 font-normal hover:text-primary-800 text-sm">
								{t("edit")}
							</button>
						</NavLink>
						<NavLink to={`/companies/${reservation.id}`}>
							<button className="text-primary-600 font-normal hover:text-primary-800 text-sm">
								{t("postpone")}
							</button>
						</NavLink>
						<NavLink to={`/companies/${reservation.id}`}>
							<button className="text-primary-600 font-normal hover:text-primary-800 text-sm">
								{t("cancel")}
							</button>
						</NavLink>
					</>
				) : (
					<>
						<NavLink to={`/companies/${reservation.id}`}>
							<button className="text-primary-600 font-normal hover:text-primary-800 text-sm">
								{t("reschedule")}
							</button>
						</NavLink>
						<button
							className="text-primary-600 font-normal hover:text-primary-800 text-sm"
							onClick={handleOpenModal(reservation.company)}
						>
							{t("add-feedback")}
						</button>
					</>
				)}
			</div>
			<AddFeedbackModal
				isOpen={isModalOpen}
				onClose={handleCloseModal}
				onSubmit={handleFeedbackSubmit}
				company={reviewCompany}
			/>
		</div>
	);
}

export default ReservationCard;
