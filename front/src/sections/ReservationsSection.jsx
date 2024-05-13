import { NavLink } from "react-router-dom";
import reservations from "../data/reservations";
import { getFormattedDate } from "../utils/schedule";
import { MapPinIcon } from "@heroicons/react/24/outline";

function ReservationsSection() {
	return (
		<div className="mt-10">
			{reservations.length === 0 && (
				<div className="text-gray-800 text-center p-10 bg-white rounded-lg shadow-md font-light">
					Vous n'avez pas encore pris de rendez-vous.
				</div>
			)}
			{reservations.map((reservation) => (
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
						<NavLink to={`/provider/${reservation.id}`}>
							<button className="text-primary-600 font-normal hover:text-primary-800 text-sm">
								Modifier
							</button>
						</NavLink>
						<NavLink to={`/provider/${reservation.id}`}>
							<button className="text-primary-600 font-normal hover:text-primary-800 text-sm">
								Reporter
							</button>
						</NavLink>
						<NavLink to={`/provider/${reservation.id}`}>
							<button className="text-primary-600 font-normal hover:text-primary-800 text-sm">
								Annuler
							</button>
						</NavLink>
					</div>
				</div>
			))}
		</div>
	);
}

export default ReservationsSection;
