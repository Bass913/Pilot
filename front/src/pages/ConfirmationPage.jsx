import { useEffect, useState } from "react";
import { NavLink, useParams, useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { ToastContainer, toast } from "react-toastify";

import { useUser } from "../hooks/useUser";
import DefaultLayout from "../layouts/DefaultLayout";
import CompanyHeader from "../components/CompanyHeader";
import Loader from "../components/Loader";
import apiService from "../services/apiService";
import { getFormattedDate } from "../utils/schedule";
import { formatPrice } from "../utils/priceFormatter";
import { getISODateFromSlot } from "../utils/dateFormatter";

function Reservation() {
    const { id } = useParams();
    const [provider, setProvider] = useState(null);
    const { user } = useUser();
    const { t } = useTranslation();
    const navigate = useNavigate();

    const fetchProvider = async () => {
        try {
            const response = await apiService.getCompany(id);
            setProvider(response.data);
        } catch (error) {
            console.error("Error while fetching provider:", error);
        }
    };

    useEffect(() => {
        fetchProvider();
    }, [id]);

    const { serviceSelected, timeSlotSelected, employeeSelected } = useUser();

    const handleReservation = async () => {
        const bookingData = {
            startDate: getISODateFromSlot(timeSlotSelected),
            companyService: serviceSelected[`@id`],
            client: `/users/${user.id}`,
            status: "pending",
            employee: employeeSelected["@id"],
            company: provider["@id"],
        };

        try {
            const response = await apiService.createBooking(bookingData, {
                headers: {
                    "Content-Type": "application/ld+json",
                },
            });

            if (response.status === 201) {
                navigate(`/profile`, {
                    state: {
                        successMessage:
                            "Votre réservation a bien été prise en compte!",
                    },
                });
            } else {
                toast.error(
                    "Une erreur est survenue lors de votre demande de réservation.",
                );
            }
        } catch (error) {
            toast.success("Un erreur est survenue lors de votre réservation!");
            console.error("Error creating booking:", error);
        }
    };

    return (
        <DefaultLayout>
            <ToastContainer
                position="bottom-center"
                autoClose={5000}
                hideProgressBar={false}
                newestOnTop={false}
                closeOnClick
                rtl={false}
                pauseOnFocusLoss
                draggable
                pauseOnHover
                theme="light"
                transition:Bounce
            />
            <div className="flex justify-center w-full bg-gray-100">
                <div
                    className="max-w-5xl w-full flex flex-col py-10 px-6"
                    style={{ minHeight: "calc(100vh - 5rem)" }}
                >
                    {provider === null ? (
                        <div className="flex justify-center h-screen">
                            <Loader />
                        </div>
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
                                            {formatPrice(serviceSelected.price)}
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
                                    {t("your-time-slot-choice")}
                                </h2>
                                <div className="mt-2 bg-white p-6 rounded-lg shadow-md lg:p-8 flex items-center justify-between">
                                    <div className="flex items-center gap-4">
                                        <p className="text-gray-800">
                                            {getFormattedDate(
                                                timeSlotSelected?.day,
                                            )}
                                        </p>
                                        <div className="rounded-full bg-gray-300 p-0.5"></div>
                                        <p className="text-gray-700 font-light text-sm">
                                            {t("to")}{" "}
                                            {timeSlotSelected?.timeSlot}
                                        </p>
                                        {employeeSelected && (
                                            <div className="flex items-center gap-4">
                                                <div className="rounded-full bg-gray-300 p-0.5"></div>
                                                <p className="text-gray-800 font-light text-sm">
                                                    {t("with")}
                                                    <span className="font-medium ml-1">
                                                        {`${employeeSelected?.firstname} ${employeeSelected?.lastname}`}
                                                    </span>
                                                </p>
                                            </div>
                                        )}
                                    </div>
                                    <NavLink
                                        to={`/companies/${id}/reservation`}
                                    >
                                        <button className="text-primary-600 font-normal underline hover:text-primary-800 text-sm">
                                            {t("edit")}
                                        </button>
                                    </NavLink>
                                </div>
                            </div>

                            <small className="text-gray-600 mt-2 font-light">
                                {t("payment-in-place")}
                            </small>
                            <div className="mt-10">
                                <button
                                    className="bg-primary-900 text-white px-6 py-3 rounded-md shadow-md hover:bg-primary-800 w-full"
                                    onClick={handleReservation}
                                >
                                    {t("confirm-booking")}
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
