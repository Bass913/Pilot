import React, { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import apiService from "../../services/apiService";
import TimeSlotChooser from "../TimeSlotChooser";
import {
	getTimeSlotsWithAvailability,
	getTimeSlotsFromSchedule,
	getDays,
} from "../../utils/schedule";
import Loader from "../Loader";
import EmployeeChooser from "../EmployeeChooser";
import ServicesChooser from "../ServicesChooser";
import { getISODateFromSlot } from "../../utils/dateFormatter";

const UpdateBookingModal = ({ isOpen, onClose, onSubmit, booking }) => {
	const { t } = useTranslation();

	const [provider, setProvider] = useState(null);
	const [employeeSelected, setEmployeeSelected] = useState(null);
	const [selectedSlot, setSelectedSlot] = useState(null);
	const [loading, setLoading] = useState(true);
	const [bookingDetails, setBookingDetails] = useState(null);
	const [employees, setEmployees] = useState([]);
	const companyId = booking
		? booking.companyService.company["@id"].split("/").pop()
		: null;

	const fetchProvider = async () => {
		if (!companyId) return;
		try {
			const response = await apiService.getCompany(companyId);
			setProvider(response.data);
		} catch (error) {
			console.error("Error while fetching provider:", error);
		} finally {
			setLoading(false);
		}
	};

	const fetchEmployees = async () => {
		if (!companyId) return;
		try {
			const response =
				await apiService.getCompanyEmployeesSchedule(companyId);
			setEmployees(response.data["hydra:member"]);
		} catch (error) {
			console.error("Error while fetching employees:", error);
		}
	};

	const fetchBookingDetails = async () => {
		try {
			const response = await apiService.getBooking(
				booking["@id"].split("/").pop()
			);
			setBookingDetails(response.data);
			setEmployeeSelected(response.data.employee);
		} catch (error) {
			console.error("Error while fetching booking details:", error);
		}
	};

	const initialDate = new Date();
	const [startDate, setStartDate] = useState(
		initialDate.toISOString().substring(0, 10)
	);
	initialDate.setDate(initialDate.getDate() + 6);
	const endDateISOString = initialDate.toISOString().substring(0, 10);
	const [endDate, setEndDate] = useState(endDateISOString);

	useEffect(() => {
		if (!booking) return;
		setSelectedSlot({
			day: booking.startDate.split("T")[0],
			timeSlot: booking.startDate
				.split("T")[1]
				.substring(0, 5)
				.replace(/^0/, ""),
		});
		fetchBookingDetails();
		fetchProvider();
		fetchEmployees();
	}, [booking]);

	const handleSlotSelection = (day, timeSlot) => {
		setSelectedSlot({ day, timeSlot });
	};

	const daysWithTimeSlots = provider
		? getTimeSlotsFromSchedule(
				getDays(startDate, endDate),
				provider.schedules
			)
		: [];

	const timeSlotsWithAvailability = getTimeSlotsWithAvailability(
		daysWithTimeSlots,
		employeeSelected?.unavailabilities,
		employeeSelected?.schedules
	);

	const handleSubmit = () => {
		const bookingId = booking["@id"];
		const bookingBody = {
			employee: employeeSelected["@id"],
			startDate: getISODateFromSlot(selectedSlot),
			status: bookingDetails.status,
			companyService: bookingDetails.companyService["@id"],
		};
		apiService.updateBooking(bookingId, bookingBody).then((response) => {
			onSubmit(response.data);
		});
	};

	return (
		<div
			className={`fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50 ${isOpen ? "" : "hidden"}`}
		>
			<div className="flex items-center justify-center min-h-screen px-4">
				<div className="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-full">
					<div
						className={`bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 w-full ${loading ? "max-h-96" : ""}`}
					>
						<h3 className="text-lg leading-6 font-medium text-gray-900 mb-5">
							{t("edit-booking")}
						</h3>
						{loading ? (
							<div className="flex justify-center h-32 sm:w-96">
								<Loader />
							</div>
						) : (
							<>
								<div className="h-96 overflow-y-auto">
									{/* <p className="text-gray-600 font-light text-sm mb-5">
										{t("select-service")}
									</p>
									<ServicesChooser
										services={provider.companyServices}
										selectedService={booking.companyService}
										onServiceSelection={() => {}}
									/> */}
									<p className="text-gray-600 font-light text-sm mb-5">
										{t("select-employee")}
									</p>
									<EmployeeChooser
										employees={employees}
										selectedEmployee={employeeSelected}
										onEmployeeSelect={setEmployeeSelected}
									/>
									<p className="text-gray-600 font-light text-sm mb-2 mt-10">
										{t("select-time-slot")}
									</p>
									<TimeSlotChooser
										timeSlotsWithAvailability={
											timeSlotsWithAvailability
										}
										selectedSlot={selectedSlot}
										onSlotSelection={handleSlotSelection}
										littleVersion={true}
									/>
								</div>
								<div className="mt-5 sm:mt-6">
									<button
										type="submit"
										className="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm"
										onClick={handleSubmit}
									>
										{t("edit")}
									</button>
								</div>
							</>
						)}
					</div>

					<div className="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
						<button
							type="button"
							className="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
							onClick={onClose}
						>
							{t("cancel")}
						</button>
					</div>
				</div>
			</div>
		</div>
	);
};

export default UpdateBookingModal;
