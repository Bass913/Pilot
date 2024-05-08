import { NavLink, useParams } from "react-router-dom";
import { useUser } from "../hooks/useUser";
import DefaultLayout from "../layouts/DefaultLayout";
import { useEffect, useState } from "react";
import providers from "../data/providers";
import CompanyHeader from "../components/CompanyHeader";
import Loader from "../components/Loader";
import EmployeeChooser from "../components/EmployeeChooser";
import {
	getTimeSlotsWithAvailability,
	getTimeSlotsFromSchedule,
	getDays,
} from "../utils/schedule";
import TimeSlotChooser from "../components/TimeSlotChooser";

function ProviderReservation() {
	const { id } = useParams();

	const provider = providers.find((provider) => provider.id === parseInt(id));

	const { serviceSelected } = useUser();

	// employees
	const employees = provider.employees;
	if (!provider.employees.some((employee) => employee === null))
		employees.unshift(null);

	const [selectedEmployee, setSelectedEmployee] = useState(null);

	const handleEmployeeSelection = (employee) => {
		setSelectedEmployee(employee);
	};

	// timeSlots
	const [selectedSlot, setSelectedSlot] = useState(null);

	const initialDate = new Date();
	const [startDate, setStartDate] = useState(
		initialDate.toISOString().substring(0, 10)
	);
	initialDate.setDate(initialDate.getDate() + 6);
	const endDateISOString = initialDate.toISOString().substring(0, 10);
	const [endDate, setEndDate] = useState(endDateISOString);

	const handleSlotSelection = (day, timeSlot) => {
		setSelectedSlot({ day, timeSlot });
	};

	const daysWithTimeSlots = getTimeSlotsFromSchedule(
		getDays(startDate, endDate),
		provider.schedule
	);

	const timeSlotsWithAvailability = getTimeSlotsWithAvailability(
		daysWithTimeSlots,
		selectedEmployee ? selectedEmployee.unavailabilities : []
	);

	useEffect(() => {
		setSelectedSlot(null);
	}, [selectedEmployee]);

	return (
		<DefaultLayout>
			<div className="flex justify-center w-full bg-gray-100">
				<div className="max-w-6xl w-full">
					{provider === null ? (
						<Loader />
					) : (
						<div
							className="bg-gray-100 px-8 py-10 flex flex-col"
							style={{ minHeight: "calc(100vh - 5rem)" }}
						>
							<CompanyHeader provider={provider} />
							<div className="mt-10">
								<h2 className="text-lg font-normal text-gray-800">
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
									<NavLink to={`/provider/${id}`}>
										<button className="text-primary-600 font-normal underline hover:text-primary-800">
											Modifier
										</button>
									</NavLink>
								</div>
							</div>

							<div className="mt-10">
								<h2 className="text-lg font-normal text-gray-800">
									Choix de votre préparateur
								</h2>
								<div className="mt-2 bg-white p-6 rounded-lg shadow-md lg:p-8">
									<h3 className="text-base font-light text-gray-800">
										Selectionnez un préparateur
									</h3>
									<EmployeeChooser
										employees={employees}
										onEmployeeSelect={
											handleEmployeeSelection
										}
									/>
								</div>
							</div>

							<div className="mt-10">
								<h2 className="text-lg font-normal text-gray-800">
									Choix de l'horaire de la préstation
								</h2>
								<TimeSlotChooser
									timeSlotsWithAvailability={
										timeSlotsWithAvailability
									}
									selectedSlot={selectedSlot}
									onSlotSelection={handleSlotSelection}
								/>
							</div>
						</div>
					)}
				</div>
			</div>
		</DefaultLayout>
	);
}

export default ProviderReservation;
