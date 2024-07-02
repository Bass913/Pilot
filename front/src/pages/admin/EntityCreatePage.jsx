import { useState, useEffect } from "react";
import Select from "react-select";
import apiService from "../../services/apiService";
import DashboardLayout from "../../layouts/DashboardLayout";
import { useTranslation } from "react-i18next";
import entitiesNames from "../../lib/entitiesNames";
import Loader from "../../components/Loader";
import BackButton from "../../components/BackButton";
import { useNavigate } from "react-router-dom";
import { useUser } from "../../hooks/useUser";

function EntityCreatePage({ model }) {
    const { t } = useTranslation();
    const [entityData, setEntityData] = useState({});
    const [loading, setLoading] = useState(false);
    const [companies, setCompanies] = useState([]);
    const navigate = useNavigate();
    const { user } = useUser();

    useEffect(() => {
        const fetchCompanies = async () => {
            if (user) {
                try {
                    const response = await apiService.getAdminCompanies(
                        user.id,
                    );
                    setCompanies(response.data.companies);
                } catch (error) {
                    console.error("Failed to fetch companies:", error);
                }
            }
        };
        fetchCompanies();
    }, [user]);

    const handleSubmit = async (newData) => {
        try {
            setLoading(true);
            let response;
            switch (model) {
                case "provider":
                case "companyProvider":
                case "companiesProvider":
                    response = await apiService.createCompany(newData);
                    navigate("/admin/providers");
                    break;
                case "user":
                case "employee":
                case "companiesEmployee":
                    response = await apiService.adminCreateUser(newData);
                    navigate("/admin/employees");
                    break;
                case "service":
                    response = await apiService.createService(newData);
                    break;
                case "companyService":
                case "companiesService":
                    response = await apiService.createCompanyService(newData);
                    break;
                case "booking":
                    response = await apiService.createBooking(newData);
                    break;
                default:
                    break;
            }
            // TODO: Add notification
        } catch (error) {
            console.error(`Failed to create ${model}:`, error);
        } finally {
            setLoading(false);
        }
    };

    const formFields = [
        // User / Employee
        {
            name: "firstname",
            label: t("firstname"),
            type: "text",
            value: entityData.firstname || "",
            models: [
                "user",
                "employee",
                "companyEmployee",
                "companiesEmployee",
            ],
        },
        {
            name: "lastname",
            label: t("lastname"),
            type: "text",
            value: entityData.lastname || "",
            models: [
                "user",
                "employee",
                "companyEmployee",
                "companiesEmployee",
            ],
        },
        {
            name: "email",
            label: t("email"),
            type: "email",
            value: entityData.email || "",
            models: [
                "user",
                "employee",
                "companyEmployee",
                "companiesEmployee",
            ],
        },
        {
            name: "phone",
            label: t("phone"),
            type: "tel",
            value: entityData.phone || "",
            models: [
                "user",
                "employee",
                "companyEmployee",
                "companiesEmployee",
            ],
        },

        // Company
        {
            name: "name",
            label: t("name"),
            type: "text",
            value: entityData.name || "",
            models: ["provider", "companyProvider", "companiesProvider"],
        },
        {
            name: "description",
            label: t("description"),
            type: "textarea",
            value: entityData.description || "",
            models: ["provider", "companyProvider", "companiesProvider"],
        },
        {
            name: "address",
            label: t("address"),
            type: "text",
            value: entityData.address || "",
            models: ["provider", "companyProvider", "companiesProvider"],
        },
        {
            name: "city",
            label: t("city"),
            type: "text",
            value: entityData.city || "",
            models: ["provider", "companyProvider", "companiesProvider"],
        },
        {
            name: "zipcode",
            label: t("zipcode"),
            type: "text",
            value: entityData.zipcode || "",
            models: ["provider", "companyProvider", "companiesProvider"],
        },

        // Service
        {
            name: "price",
            label: t("price"),
            type: "number",
            value: entityData.price || "",
            step: 0.01,
            models: ["service", "companyService", "companiesService"],
        },
        {
            name: "company",
            label: t("company"),
            type: "multi-select",
            value: entityData.company || [],
            models: ["service", "companyService", "companiesService"],
        },
        {
            name: "duration",
            label: t("duration"),
            type: "number",
            value: entityData.duration || "",
            step: 1,
            models: ["service", "companyService", "companiesService"],
        },
    ];

    const getCommonFields = () => {
        const commonFields = [];
        formFields.forEach((field) => {
            if (field.models?.includes(model)) {
                commonFields.push(field);
            }
        });
        return commonFields;
    };

    const commonFields = getCommonFields();

    const newData = commonFields.reduce((acc, field) => {
        acc[field.name] =
            field.type === "number" && field.step === 1
                ? parseInt(field.value)
                : field.value;
        return acc;
    }, {});

    newData.company = entityData.company || [];

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
                            {field.type === "multi-select" ? (
                                <Select
                                    id={field.name}
                                    name={field.name}
                                    value={entityData.company}
                                    className="mt-1 block w-full rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                    onChange={(selectedOptions) =>
                                        setEntityData({
                                            ...entityData,
                                            [field.name]: selectedOptions.map(
                                                (option) =>
                                                    companies.find(
                                                        (company) =>
                                                            company.id ===
                                                            option.value,
                                                    ),
                                            ),
                                        })
                                    }
                                    options={companies.map((company) => ({
                                        value: company.id,
                                        label: company.name,
                                    }))}
                                    isMulti
                                />
                            ) : (
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
                            )}
                        </div>
                    ))}
                </div>
            )}
            <button
                onClick={() => handleSubmit(newData)}
                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 flex justify-center"
            >
                {t("create")}
            </button>
        </DashboardLayout>
    );
}

export default EntityCreatePage;
