import ReservationCard from "../components/profile/ReservationCard";
import reservations from "../data/reservations";
import { useTranslation } from "react-i18next";

function ReservationsSection() {
	const { t } = useTranslation();
	const now = new Date();

	const pastReservations = reservations.filter(
		(reservation) => new Date(reservation.date) < now
	);
	const futureReservations = reservations.filter(
		(reservation) => new Date(reservation.date) >= now
	);

	return (
		<div className="mt-10">
			{reservations.length === 0 && (
				<div className="text-gray-800 text-center p-10 bg-white rounded-lg shadow-md font-light">
					{t("no-appointments")}
				</div>
			)}
			<div className="space-y-1 mb-5">
				<h3 className="text-lg font-medium leading-6 text-gray-900">
					{t("your-reservations")}
				</h3>
				<p className="max-w-2xl text-sm text-gray-500">
					{t("find-your-reservations")}
				</p>
			</div>

			{/* Future Reservations */}
			{futureReservations.length > 0 && (
				<div>
					<h4 className="text-md font-medium leading-6 text-gray-900 mb-1">
						{t("upcoming-reservations")}
					</h4>
					{futureReservations.map((reservation) => (
						<ReservationCard
							reservation={reservation}
							key={reservation.id}
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
							key={reservation.id}
						/>
					))}
				</div>
			)}
		</div>
	);
}

export default ReservationsSection;
