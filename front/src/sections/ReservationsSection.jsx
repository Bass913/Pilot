import ReservationCard from "../components/profile/ReservationCard";
import { useEffect, useState } from "react";
import apiService from "../services/apiService";
import { useTranslation } from "react-i18next";
import { useUser } from "../hooks/useUser";
import Loader from "../components/Loader";
import { getISODateFromDate } from "../utils/dateFormatter";

function ReservationsSection() {
	const { t } = useTranslation();
	const { user } = useUser();
	const now = new Date();
	const [reservations, setReservations] = useState([]);
	const [pastReservations, setPastReservations] = useState([]);
	const [futureReservations, setFutureReservations] = useState([]);
	const [loading, setLoading] = useState(true);
	const [reviewCategories, setReviewCategories] = useState([]);

	const fetchReservations = async () => {
		try {
			const response = await apiService.getUserBookings(user.id);
			setReservations(response.data.clientBookings);
		} catch (error) {
			console.error("Error while fetching reservations:", error);
		} finally {
			setLoading(false);
		}
	};

	const fetchReviewCategories = async () => {
		const response = await apiService.getReviewCategories();
		setReviewCategories(response.data["hydra:member"]);
	};

	useEffect(() => {
		fetchReservations();
		fetchReviewCategories();
	}, []);

	useEffect(() => {
		setPastReservations(
			reservations.filter(
				(reservation) =>
					new Date(getISODateFromDate(reservation.startDate)) < now
			)
		);
		setFutureReservations(
			reservations.filter(
				(reservation) =>
					new Date(getISODateFromDate(reservation.startDate)) >= now
			)
		);
	}, [reservations]);

	return (
		<div className="mt-10">
			<div className="space-y-1 mb-5">
				<h3 className="text-lg font-medium leading-6 text-gray-900">
					{t("your-reservations")}
				</h3>
				<p className="max-w-2xl text-sm text-gray-500">
					{t("find-your-reservations")}
				</p>
			</div>
			{loading ? (
				<Loader />
			) : (
				<>
					{reservations.length === 0 && (
						<div className="text-gray-800 text-center p-10 bg-white rounded-lg shadow-md font-light">
							{t("no-appointments")}
						</div>
					)}

					{/* Future Reservations */}
					{futureReservations.length > 0 && (
						<div>
							<h4 className="text-md font-medium leading-6 text-gray-900 mb-1">
								{t("upcoming-reservations")}
							</h4>
							{futureReservations.map((reservation) => (
								<ReservationCard
									reservation={reservation}
									key={reservation["@id"]}
									reviewCategories={reviewCategories}
								/>
							))}
						</div>
					)}

					{/* Past Reservations */}
					{pastReservations.length > 0 && (
						<div className="mt-6">
							<h4 className="text-md font-medium leading-6 text-gray-900 mb-1">
								{t("past-reservations")}
							</h4>
							{pastReservations.map((reservation) => (
								<ReservationCard
									reservation={reservation}
									key={reservation["@id"]}
									reviewCategories={reviewCategories}
								/>
							))}
						</div>
					)}
				</>
			)}
		</div>
	);
}

export default ReservationsSection;
