import { MapPinIcon } from "@heroicons/react/24/outline";
import { NavLink } from "react-router-dom";
import { useTranslation } from "react-i18next";
import AddFeedbackModal from "../modals/AddFeedbackModal";
import { useState } from "react";
import apiService from "../../services/apiService";
import Alert from "../modals/Alert";
import UpdateBookingModal from "../modals/UpdateBookingModal";
import { formatDate } from "../../utils/dateFormatter";
import { useUser } from "../../hooks/useUser";

function ReservationCard({ reservation, reviewCategories }) {
	const { t } = useTranslation();
	const { user } = useUser();

	const [reviewCompany, setReviewCompany] = useState(null);
	const [bookingSelected, setBookingSelected] = useState(null);
	const [showFeedbackModal, setShowFeedbackModal] = useState(false);
	const [showBookingModal, setShowBookingModal] = useState(false);
	const [showAlert, setShowAlert] = useState(false);

	const isDatePassed = new Date(reservation.startDate) < new Date();

	// alert modal
	const handleCloseAlert = () => {
		setShowAlert(false);
	};

	// feedback modal
	const handleOpenFeedbackModal = (company) => () => {
		setReviewCompany(company);
		setShowFeedbackModal(true);
	};

	const handleCloseFeedbackModal = () => {
		setShowFeedbackModal(false);
	};

	const handleFeedbackSubmit = (feedback) => {
		console.log("Feedback submitted:", feedback);
		console.log("Review company:", reviewCompany);

		const ratings = Object.entries(feedback.ratings).map(
			([categoryId, value]) => ({
				category: `/category_reviews/${categoryId}`,
				value: value,
			})
		);

		console.log("Ratings:", ratings);

		apiService
			.createReview({
				company: `${reviewCompany["@id"]}`,
				client: `users/${user.id}`,
				comment: feedback.feedback,
				ratings: ratings,
				date: new Date().toISOString(),
			})
			.then((response) => {
				console.log("Review added:", response.data);
			});
	};

	// report booking modal
	const handleOpenBookingModal = (booking) => () => {
		setBookingSelected(booking);
		setShowBookingModal(true);
	};

	const handleCloseBookingModal = () => {
		setShowBookingModal(false);
	};

	const handleBookingSubmit = (booking) => {
		console.log("Booking submitted:", booking);
		setShowBookingModal(false);
		// TODO: add notification
	};

	return (
		<div
			className="mb-5 bg-white p-4 rounded-lg shadow-md lg:p-6 flex flex-col justify-between"
			key={reservation.id}
		>
			<div className="flex justify-between items-start border-b border-gray-200 pb-5">
				<div className="flex flex-col gap-1">
					<p className="text-gray-800 font-normal">
						{reservation.companyService.company.name}
					</p>
					<p className="text-gray-500 font-light underline text-sm flex items-center gap-2">
						<MapPinIcon className="h-4" />
						{reservation.companyService.company.address}
					</p>
				</div>
				<div className="flex items-center gap-4">
					<p className="text-gray-800 font-light">
						{formatDate(reservation.startDate)}
					</p>
				</div>
			</div>
			<div className="flex items-center gap-4 mt-4">
				<p className="text-gray-800">
					{reservation.companyService.service.name}
				</p>
				<div className="rounded-full bg-gray-300 p-0.5"></div>
				<p className="text-gray-700 font-light text-sm">
					{reservation.companyService.price} €
				</p>
			</div>
			<div className="flex items-center gap-4 justify-end">
				{!isDatePassed ? (
					<>
						{/* <NavLink to={reservation.companyService.company["@id"]}>
							<button className="text-primary-600 font-normal hover:text-primary-800 text-sm">
								{t("edit")}
							</button>
						</NavLink> */}
						<button
							className="text-primary-600 font-normal hover:text-primary-800 text-sm"
							onClick={handleOpenBookingModal(reservation)}
						>
							{t("postpone")}
						</button>
						<button
							className="text-primary-600 font-normal hover:text-primary-800 text-sm"
							onClick={() => setShowAlert(true)}
						>
							{t("cancel")}
						</button>
					</>
				) : (
					<>
						<NavLink to={reservation.companyService.company["@id"]}>
							<button className="text-primary-600 font-normal hover:text-primary-800 text-sm">
								{t("reschedule")}
							</button>
						</NavLink>
						<button
							className="text-primary-600 font-normal hover:text-primary-800 text-sm"
							onClick={handleOpenFeedbackModal(
								reservation.companyService.company
							)}
						>
							{t("add-feedback")}
						</button>
					</>
				)}
			</div>
			<AddFeedbackModal
				isOpen={showFeedbackModal}
				onClose={handleCloseFeedbackModal}
				onSubmit={handleFeedbackSubmit}
				company={reviewCompany}
				reviewCategories={reviewCategories}
			/>
			<UpdateBookingModal
				isOpen={showBookingModal}
				onClose={handleCloseBookingModal}
				onSubmit={handleBookingSubmit}
				booking={bookingSelected}
			/>
			{showAlert && (
				<Alert
					onClose={handleCloseAlert}
					message="cancel-appointment-confirmation"
					type="cancel-appointment"
				/>
			)}
		</div>
	);
}

export default ReservationCard;
