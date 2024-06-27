import { NavLink, useNavigate, useParams } from "react-router-dom";
import { useUser } from "../hooks/useUser";
import DefaultLayout from "../layouts/DefaultLayout";
import { useEffect, useState } from "react";
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
import { useTranslation } from "react-i18next";
import apiService from "../services/apiService";

function Reservation() {
	const { t } = useTranslation();
	const navigate = useNavigate();

	const { id } = useParams();
	const [provider, setProvider] = useState(null);
	const [employees, setEmployees] = useState([]);
	const [showAlert, setShowAlert] = useState(false);
	const {
		serviceSelected,
		user,
		employeeSelected,
		setEmployeeSelected,
		timeSlotSelected,
		setTimeSlotSelected,
	} = useUser();

	const fetchProvider = async () => {
		try {
			const response = await apiService.getCompany(id);
			setProvider(response.data);
			// setEmployees(response.data.users || []);
			// console.log("Provider fetched:", response.data);
		} catch (error) {
			console.error("Error while fetching provider:", error);
		}
	};

	const fetchEmployeesSchedule = async () => {
		try {
			const response = await apiService.getCompanyEmployeesSchedule(id);
			setEmployees(response.data["hydra:member"]);
			// console.log("Employees fetched:", response.data);
			// console.log("Employees fetched:", response.data);
		} catch (error) {
			console.error("Error while fetching employees:", error);
		}
	};

	useEffect(() => {
		fetchProvider();
		fetchEmployeesSchedule();
	}, [id]);

	const handleEmployeeSelection = (employee) => {
		setEmployeeSelected(employee);
	};

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
			navigate(`/companies/${id}/confirmation`);
		}, 300);
	};

	const handleCloseAlert = () => {
		setShowAlert(false);
		setTimeSlotSelected(null);
	};

	const daysWithTimeSlots = provider
		? getTimeSlotsFromSchedule(
				getDays(startDate, endDate),
				provider.schedules,
				serviceSelected.duration
			)
		: [];
	// console.log("Days with time slots:", daysWithTimeSlots);
	const timeSlotsWithAvailability = getTimeSlotsWithAvailability(
		daysWithTimeSlots,
		employeeSelected?.unavailabilities,
		employeeSelected?.schedules
	);

	useEffect(() => {
		setTimeSlotSelected(null);
	}, [employeeSelected]);

	return (
		<DefaultLayout>
			{showAlert && (
				<Alert
					onClose={handleCloseAlert}
					message="not-logged-in-error"
					type="login"
				/>
			)}
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
									{t("your-service")}
								</h2>
								<div className="mt-2 bg-white p-6 rounded-lg shadow-md lg:p-8 flex items-center justify-between">
									<div className="flex items-center gap-4">
										<p className="text-gray-800">
											{t(serviceSelected.service.name)}
										</p>
										<div className="rounded-full bg-gray-300 p-0.5"></div>
										<p className="text-gray-700 font-light text-sm">
											{serviceSelected.price} €
										</p>
									</div>
									<NavLink to={`/companies/${id}`}>
										<button className="text-primary-600 font-normal underline hover:text-primary-800 text-sm">
											{t("edit")}
										</button>
									</NavLink>
								</div>
							</div>

							<div className="mt-10">
								<h2 className="text-lg font-normal text-gray-800">
									<span className="font-medium text-primary-600">
										2.
									</span>{" "}
									{t("your-preparer-choice")}
								</h2>
								<div className="mt-2 bg-white p-6 rounded-lg shadow-md lg:p-8">
									<h3 className="text-base font-light text-gray-800">
										{t("select-your-preparer")}
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
									{t("your-time-slot-choice")}
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
