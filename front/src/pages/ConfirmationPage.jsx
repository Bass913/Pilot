import { NavLink, useParams } from "react-router-dom";
import { useUser } from "../hooks/useUser";
import DefaultLayout from "../layouts/DefaultLayout";
import providers from "../data/providers";
import CompanyHeader from "../components/CompanyHeader";
import Loader from "../components/Loader";
import { getFormattedDate } from "../utils/schedule";

function Reservation() {
	const { id } = useParams();

	const provider = providers.find((provider) => provider.id === parseInt(id));

	const { serviceSelected, timeSlotSelected, employeeSelected } = useUser();

	const handleReservation = () => {
		alert("Réservation confirmée");
	};

	return (
		<DefaultLayout>
			<div className="flex justify-center w-full bg-gray-100">
				<div
					className="max-w-5xl w-full flex flex-col py-10 px-6"
					style={{ minHeight: "calc(100vh - 5rem)" }}
				>
					{provider === null ? (
						<Loader />
					) : (
						<div>
							<CompanyHeader provider={provider} />

							<div className="mt-10">
								<h2 className="text-lg font-normal text-gray-800">
									<span className="font-medium text-primary-600">
										1.
									</span>{" "}
									Votre prestation
								</h2>
								<div className="mt-2 bg-white p-6 rounded-lg shadow-md lg:p-8 flex items-center justify-between">
									<div className="flex items-center gap-4">
										<p className="text-gray-800">
											{serviceSelected.name}
										</p>
										<div className="rounded-full bg-gray-300 p-0.5"></div>
										<p className="text-gray-700 font-light text-sm">
											{serviceSelected.price} €
										</p>
									</div>
									<NavLink to={`/companies/${id}`}>
										<button className="text-primary-600 font-normal underline hover:text-primary-800 text-sm">
											Modifier
										</button>
									</NavLink>
								</div>
							</div>

							<div className="mt-10">
								<h2 className="text-lg font-normal text-gray-800">
									<span className="font-medium text-primary-600">
										2.
									</span>{" "}
									Votre horaire
								</h2>
								<div className="mt-2 bg-white p-6 rounded-lg shadow-md lg:p-8 flex items-center justify-between">
									<div className="flex items-center gap-4">
										<p className="text-gray-800">
											{getFormattedDate(
												timeSlotSelected?.day
											)}
										</p>
										<div className="rounded-full bg-gray-300 p-0.5"></div>
										<p className="text-gray-700 font-light text-sm">
											à {timeSlotSelected?.timeSlot}
										</p>
										{employeeSelected && (
											<div className="flex items-center gap-4">
												<div className="rounded-full bg-gray-300 p-0.5"></div>
												<p className="text-gray-800 font-light text-sm">
													avec
													<span className="font-medium ml-1">
														{employeeSelected?.name}
													</span>
												</p>
											</div>
										)}
									</div>
									<NavLink to={`/companies/${id}/reservation`}>
										<button className="text-primary-600 font-normal underline hover:text-primary-800 text-sm">
											Modifier
										</button>
									</NavLink>
								</div>
							</div>

							<small className="text-gray-600 mt-2 font-light">
								Le paiement se fait sur place
							</small>
							<div className="mt-10">
								<button
									className="bg-primary-900 text-white px-6 py-3 rounded-md shadow-md hover:bg-primary-800 w-full"
									onClick={handleReservation}
								>
									Confirmer la réservation
								</button>
							</div>
						</div>
					)}
				</div>
			</div>
		</DefaultLayout>
	);
}

export default Reservation;
