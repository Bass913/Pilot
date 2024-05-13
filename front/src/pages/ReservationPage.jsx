import { NavLink, useNavigate, useParams } from "react-router-dom";
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
import Alert from "../components/modals/Alert";

function Reservation() {
	const navigate = useNavigate();
	const { id } = useParams();

	const [showAlert, setShowAlert] = useState(false);

	const provider = providers.find((provider) => provider.id === parseInt(id));

	const {
		serviceSelected,
		user,
		employeeSelected,
		setEmployeeSelected,
		timeSlotSelected,
		setTimeSlotSelected,
	} = useUser();

	// employees
	const employees = provider.employees;
	if (!provider.employees.some((employee) => employee === null))
		employees.unshift(null);

	const handleEmployeeSelection = (employee) => {
		setEmployeeSelected(employee);
	};

	// timeSlots

	const initialDate = new Date();
	const [startDate, setStartDate] = useState(
		initialDate.toISOString().substring(0, 10)
	);
	initialDate.setDate(initialDate.getDate() + 6);
	const endDateISOString = initialDate.toISOString().substring(0, 10);
	const [endDate, setEndDate] = useState(endDateISOString);

	const handleSlotSelection = (day, timeSlot) => {
		setTimeSlotSelected({ day, timeSlot });
		setTimeout(() => {
			if (!user) {
				setShowAlert(true);
				return;
			}
			navigate(`/provider/${id}/confirmation`);
		}, 300);
	};

	const handleCloseAlert = () => {
		setShowAlert(false);
		setTimeSlotSelected(null);
	};

	const daysWithTimeSlots = getTimeSlotsFromSchedule(
		getDays(startDate, endDate),
		provider.schedule
	);

	const timeSlotsWithAvailability = getTimeSlotsWithAvailability(
		daysWithTimeSlots,
		employeeSelected ? employeeSelected.unavailabilities : []
	);

	useEffect(() => {
		setTimeSlotSelected(null);
		setEmployeeSelected(employeeSelected);
	}, [employeeSelected]);

	useEffect(() => {
		setTimeSlotSelected(timeSlotSelected);
	}, [timeSlotSelected]);

	return (
		<DefaultLayout>
			{showAlert && <Alert onClose={handleCloseAlert} />}
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
									<NavLink to={`/provider/${id}`}>
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
									Choix de votre préparateur
								</h2>
								<div className="mt-2 bg-white p-6 rounded-lg shadow-md lg:p-8">
									<h3 className="text-base font-light text-gray-800">
										Selectionnez un préparateur
									</h3>
									<EmployeeChooser
										employees={employees}
										selectedEmployee={employeeSelected}
										onEmployeeSelect={
											handleEmployeeSelection
										}
									/>
								</div>
							</div>

							<div className="mt-10">
								<h2 className="text-lg font-normal text-gray-800">
									<span className="font-medium text-primary-600">
										3.
									</span>{" "}
									Choix de l'horaire de la préstation
								</h2>
								<TimeSlotChooser
									timeSlotsWithAvailability={
										timeSlotsWithAvailability
									}
									selectedSlot={timeSlotSelected}
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

export default Reservation;
