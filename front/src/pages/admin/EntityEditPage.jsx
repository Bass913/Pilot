import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

import apiService from "../../services/apiService";
import { useParams } from "react-router-dom";
import DashboardLayout from "../../layouts/DashboardLayout";
import { useTranslation } from "react-i18next";
import entitiesNames from "../../lib/entitiesNames";
import Loader from "../../components/Loader";
import BackButton from "../../components/BackButton";

function EntityEditPage({ model }) {
    const [entityData, setEntityData] = useState({});
    const [loading, setLoading] = useState(true);
    const { id } = useParams();
    const { t } = useTranslation();
    const navigate = useNavigate();
    console.log("model", model);

    useEffect(() => {
        const fetchEntity = async () => {
            try {
                let response;
                switch (model) {
                    case "provider":
                    case "companyProvider":
                    case "companiesProvider":
                        response = await apiService.getCompany(id);
                        break;
                    case "user":
                    case "employee":
                        response = await apiService.getUser(id);
                        break;
                    case "service":
                        response = await apiService.getService(id);
                        break;
                    case "companyService":
                    case "companiesService":
                        response = await apiService.getCompanyService(id);
                        break;
                    case "booking":
                        response = await apiService.getBooking(id);
                        break;
                    default:
                        break;
                }
                setEntityData(response.data);
            } catch (error) {
                console.error(`Error fetching ${model} data:`, error);
            } finally {
                setLoading(false);
            }
        };

        fetchEntity();
    }, [model, id]);

    const handleSubmit = async (updatedData) => {
        try {
            let response;
            switch (model) {
                case "provider":
                case "companyProvider":
                case "companiesProvider":
                    response = await apiService.updateCompany(id, updatedData);
                    navigate("/admin/providers");
                    break;
                case "user":
                case "employee":
                    response = await apiService.updateUser(id, updatedData);
                    break;
                case "service":
                    response = await apiService.updateService(id, updatedData);
                    break;
                case "companyService":
                case "companiesService":
                    response = await apiService.updateCompanyService(
                        id,
                        updatedData,
                    );
                    r;
                    break;
                case "booking":
                    response = await apiService.updateBooking(id, updatedData);
                    break;
                default:
                    break;
            }
            // TODO: Add notification
        } catch (error) {
            console.error(`Failed to update ${model}:`, error);
        }
    };

    const convertToDatetimeLocal = (isoDate) => {
        const date = new Date(isoDate);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        const hours = String(date.getHours()).padStart(2, "0");
        const minutes = String(date.getMinutes()).padStart(2, "0");

        return `${year}-${month}-${day}T${hours}:${minutes}`;
    };

    const formFields = [
        // User
        {
            name: "firstname",
            label: t("firstname"),
            type: "text",
            value: entityData.firstname,
        },
        {
            name: "lastname",
            label: t("lastname"),
            type: "text",
            value: entityData.lastname,
        },
        {
            name: "email",
            label: t("email"),
            type: "email",
            value: entityData.email,
        },
        {
            name: "phone",
            label: t("phone"),
            type: "tel",
            value: entityData.phone,
        },

        // Company
        {
            name: "name",
            label: t("name"),
            type: "text",
            value: entityData.name,
        },
        {
            name: "description",
            label: t("description"),
            type: "textarea",
            value: entityData.description,
        },
        {
            name: "address",
            label: t("address"),
            type: "text",
            value: entityData.address,
        },
        {
            name: "city",
            label: t("city"),
            type: "text",
            value: entityData.city,
        },
        {
            name: "zipcode",
            label: t("zipcode"),
            type: "text",
            value: entityData.zipcode,
        },

        // Service
        {
            name: "price",
            label: t("price"),
            type: "number",
            value: entityData.price,
            step: 0.01,
        },
        {
            name: "duration",
            label: t("duration"),
            type: "number",
            value: entityData.duration,
            step: 1,
        },

        // Booking
        {
            name: "startDate",
            label: t("date"),
            type: "datetime-local",
            value: convertToDatetimeLocal(entityData.startDate),
        },
        {
            name: "status",
            label: t("status"),
            type: "text",
            value: entityData.status,
        },
    ];

    const getCommonFields = () => {
        const commonFields = [];
        formFields.forEach((field) => {
            if (entityData.hasOwnProperty(field.name)) {
                commonFields.push(field);
            }
        });
        return commonFields;
    };

    const commonFields = getCommonFields();

    const updatedData = commonFields.reduce((acc, field) => {
        // change to int if the field is a number and step is 1
        acc[field.name] =
            field.type === "number" && field.step === 1
                ? parseInt(field.value)
                : field.value;
        return acc;
    }, {});

    return (
        <DashboardLayout>
            <div className="flex justify-between">
                <h1 className="text-xl font-medium text-gray-900 mb-6 capitalize">
                    {t(entitiesNames[model].label)}
                </h1>
                <BackButton to={`/admin/${entitiesNames[model].labelPlural}`} />
            </div>
            {loading ? (
                <div className="flex justify-center h-96 items-center">
                    <Loader />
                </div>
            ) : (
                <div className="grid grid-cols-1 gap-y-3 gap-x-4 sm:grid-cols-2">
                    {commonFields.map((field) => (
                        <div key={field.name} className="mb-4">
                            <label
                                htmlFor={field.name}
                                className="block text-sm font-medium text-gray-700"
                            >
                                {field.label}
                            </label>
                            <input
                                type={field.type}
                                id={field.name}
                                name={field.name}
                                value={field.value}
                                className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                onChange={(e) =>
                                    setEntityData({
                                        ...entityData,
                                        [field.name]: e.target.value,
                                    })
                                }
                                step={field.step}
                            />
                        </div>
                    ))}
                </div>
            )}
            <button
                onClick={() => handleSubmit(updatedData)}
                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 flex justify-center"
            >
                {t("save")}
            </button>
        </DashboardLayout>
    );
}

export default EntityEditPage;
