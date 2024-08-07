import React, { useState, useEffect } from "react";
import DashboardLayout from "../../layouts/DashboardLayout";
import { useTranslation } from "react-i18next";
import EmployeeChooser from "../../components/EmployeeChooser";
import CompanyChooser from "../../components/admin/CompanyChooser";
import TimeSlotChooser from "../../components/TimeSlotChooser";
import UnavailabilityModal from "../../components/modals/UnavailabilityModal";
import apiService from "../../services/apiService";
import { useUser } from "../../hooks/useUser";
import {
    getTimeSlotsWithAvailability,
    getTimeSlotsFromSchedule,
    getDays,
} from "../../utils/schedule";

function SchedulePage() {
    const { t } = useTranslation();
    const { user } = useUser();
    const [employees, setEmployees] = useState([]);
    const [companies, setCompanies] = useState([]);
    const [selectedEmployee, setSelectedEmployee] = useState(null);
    const [selectedCompany, setSelectedCompany] = useState(null);
    const [selectedSlot, setSelectedSlot] = useState(null);
    const [provider, setProvider] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);

    const isAdmin = user.roles.includes("ROLE_ADMIN");
    const isEmployee = user.roles.includes("ROLE_EMPLOYEE") && !isAdmin;
    const isSuperAdmin = user.roles.includes("ROLE_SUPERADMIN");

    const fetchCompanies = async () => {
        try {
            if (isSuperAdmin) {
                const response = await apiService.getCompanies({
                    search: "",
                    pagination: false,
                });
                setCompanies(response.data["hydra:member"]);
            } else if (isAdmin) {
                const response = await apiService.getAdminCompanies(user.id);
                setCompanies(response.data.companies);
            }
        } catch (error) {
            console.error("Error while fetching companies:", error);
        }
    };

    const fetchEmployees = async () => {
        if (!selectedCompany) return;
        try {
            const response = await apiService.getCompanyEmployeesSchedule(
                selectedCompany["@id"].split("/").pop(),
            );
            setEmployees(response.data["hydra:member"]);
        } catch (error) {
            console.error("Error while fetching employees:", error);
        }
    };

    const fetchProvider = async () => {
        if (isAdmin && !selectedCompany) return;
        try {
            const response = await apiService.getCompany(
                isAdmin
                    ? selectedCompany["@id"].split("/").pop()
                    : user.companyId,
            );
            setProvider(response.data);
        } catch (error) {
            console.error("Error while fetching provider:", error);
        }
    };

    const fetchUserPlanning = async () => {
        try {
            const response = await apiService.getUserSchedule(user.id);
            setSelectedEmployee({
                ...user,
                schedules: response.data.schedules,
                unavailabilities: response.data.unavailabilities,
                bookings: response.data.employeeBookings,
            });
        } catch (error) {
            console.error("Error while fetching user planning:", error);
        }
    };

    useEffect(() => {
        if (isEmployee) setSelectedCompany(provider);
    }, [provider]);

    useEffect(() => {
        if (isAdmin || isSuperAdmin) fetchCompanies();
        fetchProvider();
    }, []);

    useEffect(() => {
        if (isAdmin) {
            fetchEmployees();
            fetchProvider();
        } else {
            fetchUserPlanning();
        }
    }, [selectedCompany]);

    const handleCompanySelect = (company) => {
        setSelectedCompany(company);
        setSelectedEmployee(null);
    };

    const handleEmployeeSelect = (employee) => {
        setSelectedEmployee(employee);
    };

    const handleSlotSelection = (day, timeSlot) => {
        setSelectedSlot({ day, timeSlot });
    };

    const handleOpenModal = () => {
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
    };

    const initialDate = new Date();
    const [startDate, setStartDate] = useState(
        initialDate.toISOString().substring(0, 10),
    );
    const [endDate, setEndDate] = useState(() => {
        const date = new Date(initialDate);
        date.setDate(date.getDate() + 6);
        return date.toISOString().substring(0, 10);
    });

    const [daysWithTimeSlots, setDaysWithTimeSlots] = useState(
        provider
            ? getTimeSlotsFromSchedule(
                  getDays(startDate, endDate),
                  provider.schedules,
              )
            : [],
    );

    useEffect(() => {
        setDaysWithTimeSlots(
            provider
                ? getTimeSlotsFromSchedule(
                      getDays(startDate, endDate),
                      provider.schedules,
                  )
                : [],
        );
    }, [provider, startDate, endDate]);

    const [timeSlotsWithAvailability, setTimeSlotsWithAvailability] =
        useState(null);

    useEffect(() => {
        setTimeSlotsWithAvailability(
            getTimeSlotsWithAvailability({
                timeSlots: daysWithTimeSlots,
                employeeUnavailabilities: selectedEmployee?.unavailabilities,
                companyUnavailabilities: provider?.unavailabilities,
                employeeSchedules: selectedEmployee?.schedules,
                bookings: selectedEmployee?.employeeBookings,
            }),
        );
    }, [selectedEmployee, provider, daysWithTimeSlots]);

    const handleSaveUnavailability = async (unavailability) => {
        if (!selectedEmployee) return;
        try {
            await apiService.createUnavailability({
                startDate: unavailability.startDate,
                endDate: unavailability.endDate,
                user: selectedEmployee["@id"]
                    ? selectedEmployee["@id"]
                    : `/users/${user.id}`,
                company: null,
            });
            fetchEmployees();
            handleCloseModal();
            // refresh the schedule
            setTimeSlotsWithAvailability(
                getTimeSlotsWithAvailability({
                    timeSlots: daysWithTimeSlots,
                    employeeUnavailabilities:
                        selectedEmployee?.unavailabilities,
                    companyUnavailabilities: provider?.unavailabilities,
                    employeeSchedules: selectedEmployee?.schedules,
                    bookings: selectedEmployee?.employeeBookings,
                }),
            );
        } catch (error) {
            console.error("Error while adding unavailability:", error);
        }
    };

    const handleDateChange = (change) => {
        if (change === 1) {
            const newStartDate = new Date(startDate);
            newStartDate.setDate(newStartDate.getDate() + 7);
            setStartDate(newStartDate.toISOString().substring(0, 10));

            const newEndDate = new Date(newStartDate);
            newEndDate.setDate(newEndDate.getDate() + 6);
            setEndDate(newEndDate.toISOString().substring(0, 10));
        } else {
            const newEndDate = new Date(endDate);
            newEndDate.setDate(newEndDate.getDate() - 7);
            setEndDate(newEndDate.toISOString().substring(0, 10));

            const newStartDate = new Date(newEndDate);
            newStartDate.setDate(newStartDate.getDate() - 6);
            setStartDate(newStartDate.toISOString().substring(0, 10));
        }
    };

    return (
        <DashboardLayout>
            <h1 className="text-xl font-medium text-gray-900 mb-4 capitalize">
                {t("schedule")}
            </h1>

            {isAdmin && (
                <>
                    <h2 className="text-lg font-normal text-gray-900 mb-4 mt-10">
                        {t("choose-company")}
                    </h2>
                    <CompanyChooser
                        companies={companies}
                        selectedCompany={selectedCompany}
                        onCompanySelect={handleCompanySelect}
                    />
                </>
            )}
            {selectedCompany && isAdmin && (
                <>
                    <h2 className="text-lg font-normal text-gray-900 mb-4 mt-10">
                        {t("choose-employee")}
                    </h2>
                    <EmployeeChooser
                        employees={employees}
                        selectedEmployee={selectedEmployee}
                        onEmployeeSelect={handleEmployeeSelect}
                        withNoPreference={false}
                    />
                </>
            )}
            {selectedEmployee && (
                <>
                    <div
                        className={`flex justify-${isAdmin ? "between" : "end"} items-center mt-10`}
                    >
                        {isAdmin && (
                            <h2 className="text-lg font-normal text-gray-900">
                                {t("schedule")}
                            </h2>
                        )}
                        <button
                            onClick={handleOpenModal}
                            className="bg-primary-800 text-white px-4 py-2 rounded-sm hover:bg-primary-700 text-sm"
                        >
                            {t("add-unavailability")}
                        </button>
                    </div>
                    <TimeSlotChooser
                        timeSlotsWithAvailability={timeSlotsWithAvailability}
                        selectedSlot={selectedSlot}
                        onSlotSelection={handleSlotSelection}
                        onDateChange={handleDateChange}
                    />
                </>
            )}
            <UnavailabilityModal
                isOpen={isModalOpen}
                onClose={handleCloseModal}
                onSave={handleSaveUnavailability}
            />
        </DashboardLayout>
    );
}

export default SchedulePage;
