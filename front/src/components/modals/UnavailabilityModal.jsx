import React, { useState } from "react";
import { useTranslation } from "react-i18next";

function UnavailabilityModal({ isOpen, onClose, onSave }) {
    const { t } = useTranslation();
    const [unavailability, setUnavailability] = useState({
        startDate: "",
        endDate: "",
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setUnavailability((prev) => ({
            ...prev,
            [name]: value,
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        onSave(unavailability);
        setUnavailability({ startDate: "", endDate: "" });
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
            <div className="bg-white rounded shadow-md w-full sm:w-1/3">
                <h2 className="text-lg font-normal text-gray-900 p-4 border-b mb-4">
                    {t("add-unavailability")}
                </h2>
                <form onSubmit={handleSubmit}>
                    <div className="p-4">
                        <label>
                            {t("start-date")}:
                            <input
                                type="datetime-local"
                                name="startDate"
                                value={unavailability.startDate}
                                onChange={handleChange}
                                required
                                className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                            />
                        </label>
                    </div>
                    <div className="mt-4 p-4">
                        <label>
                            {t("end-date")}:
                            <input
                                type="datetime-local"
                                name="endDate"
                                value={unavailability.endDate}
                                onChange={handleChange}
                                required
                                className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                            />
                        </label>
                    </div>
                    <div className="mt-10 flex justify-end border-t p-4 bg-gray-100">
                        <button
                            type="button"
                            onClick={onClose}
                            className="mr-4 bg-gray-400 text-white px-4 py-2 rounded-sm hover:bg-gray-500 text-sm"
                        >
                            {t("cancel")}
                        </button>
                        <button
                            type="submit"
                            className="bg-primary-600 text-white px-4 py-2 rounded-sm hover:bg-primary-700 text-sm"
                        >
                            {t("add")}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

export default UnavailabilityModal;
