import React from "react";
import { InformationCircleIcon } from "@heroicons/react/24/outline";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { ToastContainer, toast } from "react-toastify";

import apiService from "../../services/apiService";

function Alert({ onClose, message, type, bookingId, entityId, model }) {
    const navigate = useNavigate();
    const { t } = useTranslation();

    const handleCancel = () => {
        apiService
            .removeBooking(bookingId)
            .then(() => window.location.reload());
    };

    const handleDelete = async () => {
        switch (model) {
            case "provider":
            case "companiesProvider":
                await apiService.removeCompany(entityId);
                break;
            case "service":
                await apiService.removeService(entityId);
                break;
            case "employee":
                await apiService.removeUser(entityId);
                break;
            case "companyService":
                await apiService.removeCompanyService(entityId);
                break;
            case "companyEmployee":
            case "companiesEmployee":
                await apiService.removeUser(entityId);
                break;
            case "booking":
                await apiService.removeBooking(entityId);
                break;
            default:
                break;
        }
        window.location.reload();
    };

    const getButtonLabel = () => {
        switch (type) {
            case "login":
                return t("login");
            case "cancel-appointment":
                return t("yes");
            case "delete-entity":
                return t("delete");
            default:
                return "OK";
        }
    };

    const handleAlertAction = () => {
        switch (type) {
            case "login":
                navigate("/auth/login");
                break;
            case "cancel-appointment":
                handleCancel();
                break;
            case "delete-entity":
                handleDelete();
                break;
            default:
                break;
        }
        onClose();
    };

    return (
        <div className="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
            <div className="bg-white p-6 rounded-lg shadow-md flex flex-col gap-6">
                <div className="text-gray-800 flex items-center gap-2">
                    <InformationCircleIcon className="w-6 h-6 text-orange-600 inline-block" />
                    <h2 className="text-lg font-medium">Information</h2>
                </div>
                <p className="text-gray-600 font-light">{t(message)}</p>
                <div className="flex justify-end mt-5">
                    <button
                        className={`${type === "cancel-appointment" || type === "delete-entity" ? "bg-red-700 hover:bg-red-600" : "bg-primary-700 hover:bg-primary-600"} px-4 py-2 text-white rounded  mr-2 text-sm`}
                        onClick={handleAlertAction}
                    >
                        {getButtonLabel()}
                    </button>
                    <button
                        className="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 text-sm"
                        onClick={onClose}
                    >
                        {t("cancel")}
                    </button>
                </div>
            </div>
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
        </div>
    );
}

export default Alert;
